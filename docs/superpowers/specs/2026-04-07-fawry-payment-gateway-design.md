# Fawry Payment Gateway Integration

**Date:** 2026-04-07
**Status:** Approved
**Project:** A97Infinity (Laravel 12 Real Estate Marketplace API)

---

## Overview

Integrate Fawry as the payment gateway for full property purchases. Users can pay via cards, Fawry reference numbers (kiosk), or mobile wallets. Two payment flows are supported: hosted checkout (Fawry widget) and direct server-to-server card charges.

## Decisions

- **Package:** Nafezly/payments (v2.7.4, 475 stars, actively maintained) for hosted checkout
- **S2S cards:** Custom `FawryDirectApiService` extending beyond the package for direct card flow
- **Refunds:** Not supported. All sales final.
- **Failed payments:** Simple retry. No reservation hold or attempt limits.
- **Invoice:** JSON response with snapshots of buyer/property data. No PDF generation in scope.

## Architecture

Follows the existing Action-based service layer pattern.

### New Files

```
app/
├── Actions/Payment/
│   ├── InitiatePaymentAction.php
│   ├── VerifyPaymentAction.php
│   ├── HandlePaymentCallbackAction.php
│   └── GenerateInvoiceAction.php
├── Models/
│   ├── Payment.php
│   └── Invoice.php
├── Http/
│   ├── Controllers/API/V1/
│   │   ├── EndUser/PaymentController.php
│   │   └── Admin/PaymentController.php
│   ├── Requests/API/V1/Payment/
│   │   └── InitiatePaymentRequest.php
│   └── Resources/API/V1/
│       ├── PaymentResource.php
│       └── InvoiceResource.php
├── Enums/
│   ├── PaymentStatusEnum.php
│   └── PaymentMethodEnum.php
├── Services/
│   └── FawryDirectApiService.php
└── Observers/
    └── PaymentObserver.php

config/
└── fawry.php

database/migrations/
├── xxxx_create_payments_table.php
└── xxxx_create_invoices_table.php
```

## Database Schema

### payments

| Column | Type | Description |
|--------|------|-------------|
| id | bigint (PK) | Auto-increment |
| user_id | foreignId | Buyer (references users) |
| property_id | foreignId | Property being purchased |
| fawry_reference | string (unique, nullable) | Fawry merchant reference number |
| fawry_order_id | string (nullable) | Fawry's own order/transaction ID |
| amount | decimal(12,2) | Payment amount in EGP |
| payment_method | enum (nullable) | card, reference, wallet |
| status | enum | pending, completed, failed, expired |
| checkout_url | text (nullable) | Fawry hosted checkout URL |
| paid_at | timestamp (nullable) | When payment was confirmed |
| fawry_response | json (nullable) | Raw Fawry callback response for audit |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:** user_id, property_id, fawry_reference, status

### invoices

| Column | Type | Description |
|--------|------|-------------|
| id | bigint (PK) | Auto-increment |
| payment_id | foreignId | References payments |
| invoice_number | string (unique) | Auto-generated (INV-2026-00001) |
| buyer_name | string | Snapshot of user name at purchase time |
| buyer_email | string | Snapshot of user email |
| buyer_phone | string (nullable) | Snapshot of user phone |
| property_title | string | Snapshot of property title |
| amount | decimal(12,2) | Total paid |
| issued_at | timestamp | |
| created_at | timestamp | |
| updated_at | timestamp | |

### Relationships

- User hasMany Payment
- Property hasMany Payment
- Payment hasOne Invoice
- Payment belongsTo User, Property
- Invoice belongsTo Payment

## API Endpoints

### EndUser (auth:sanctum)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/V1/payments/initiate | Start payment for a property |
| GET | /api/V1/payments/{payment}/status | Check payment status |
| GET | /api/V1/payments/my-payments | List user's payment history |
| GET | /api/V1/payments/{payment}/invoice | Get invoice data |

### Admin (auth:sanctum)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/admin/V1/payments | List all payments (filterable) |
| GET | /api/admin/V1/payments/{payment} | View payment details |

### Webhook (public, signature-verified)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/V1/payments/callback | Fawry server callback |

### Request: POST /api/V1/payments/initiate

**Hosted flow:**
```json
{
  "property_id": 5,
  "payment_flow": "hosted"
}
```

**Direct flow:**
```json
{
  "property_id": 5,
  "payment_flow": "direct",
  "card_number": "...",
  "expiry_year": "...",
  "expiry_month": "...",
  "cvv": "..."
}
```

### Response: POST /api/V1/payments/initiate (hosted)

```json
{
  "success": true,
  "data": {
    "payment_id": 12,
    "fawry_reference": "PAY-12-1712500000",
    "checkout_url": "https://atfawry.fawrystaging.com/...",
    "status": "pending"
  }
}
```

### Validation Rules

- Property must exist and have status `available`
- Property must not have a completed payment
- User must be authenticated
- For direct flow: card_number, expiry_year, expiry_month, cvv required

## Payment Processing Logic

### Initiation (InitiatePaymentAction)

1. Validate property is available (not sold, no active completed payment)
2. Create Payment record with status `pending`
3. Generate unique merchant reference: `PAY-{paymentId}-{timestamp}`
4. Hosted: call Nafezly/payments to generate Fawry checkout URL, return to user
5. Direct: call FawryDirectApiService with card details, process response immediately
6. Return payment record

### Callback (HandlePaymentCallbackAction)

1. Receive Fawry webhook POST
2. Validate signature (SHA-256: merchantCode + fawryRef + amount + orderStatus + merchantSecret)
3. Find Payment by fawry_reference
4. If PAID: update to completed, set paid_at, store payment_method and fawry_response, mark property sold, generate invoice
5. If FAILED/EXPIRED: update status, property stays available
6. Return 200 to Fawry

### Status Check (VerifyPaymentAction)

- Calls Fawry status API v2 for real-time status
- Updates local record if Fawry status differs
- Safety net for missed callbacks

### Invoice Generation (GenerateInvoiceAction)

- Creates Invoice record with buyer/property snapshots
- Sequential number: INV-{YEAR}-{5-digit zero-padded sequence} (e.g., INV-2026-00001)
- Returns JSON data

### Concurrency Protection

- DB transaction + `where('status', 'available')` check on property during initiation
- Only marks property as sold on completed payment, not on pending

## Configuration

### Environment Variables (.env)

```
FAWRY_MERCHANT_CODE=your_merchant_code
FAWRY_SECURITY_KEY=your_security_key
FAWRY_MODE=staging
```

### Config File (config/fawry.php)

- Merchant code, security key, mode from env
- Staging and live base URLs
- Callback URL path

## Security

- **Signature verification:** Every callback validated by recalculating SHA-256 hash
- **No card storage:** Card details passed through to Fawry, never persisted
- **Sanctum auth:** All user/admin endpoints require auth:sanctum
- **Callback protection:** Public endpoint verified by Fawry signature, not auth middleware
- **Rate limiting:** Applied to initiation endpoint
- **Input validation:** FormRequest classes validate all inputs
- **Audit trail:** fawry_response JSON stores callback data (excluding sensitive card info)

## Enums

### PaymentStatusEnum

- `pending` - Payment initiated, awaiting Fawry
- `completed` - Payment confirmed by Fawry
- `failed` - Payment failed
- `expired` - Payment expired (Fawry timeout)

### PaymentMethodEnum

- `card` - Credit/debit card
- `reference` - Fawry reference number (kiosk)
- `wallet` - Mobile wallet (Vodafone Cash, Orange Money, etc.)

## Out of Scope

- Refunds (all sales final)
- PDF invoice generation (JSON only for now)
- Reservation holds / payment timeouts
- Fraud detection / attempt limits
- Email/SMS notifications
- Partial payments or installments
