<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class AttributesSeeder extends Seeder
{
    public function run(): void
    {
        // Units lookup
        $areaUnit = Unit::where('type', 'area')->where('name->en', 'Square Meter')->first();
        $lengthUnit = Unit::where('type', 'length')->first();
        $countUnit = Unit::where('type', 'count')->where('name->en', 'Room')->first();
        $bathUnit = Unit::where('type', 'count')->where('name->en', 'Bathroom')->first();
        $timeYearUnit = Unit::where('type', 'time')->where('name->en', 'Year')->first();
        $timeMonthUnit = Unit::where('type', 'time')->where('name->en', 'Month')->first();
        $pricePerMeterUnit = Unit::where('type', 'price')->where('name->en', 'Price per Square Meter')->first();
        $priceUnit = Unit::where('type', 'price')->where('name->en', 'Egyptian Pound')->first();
        $percentUnit = Unit::where('type', 'count')->where('name->en', 'Percentage')->first();
        $floorUnit = Unit::where('type', 'count')->where('name->en', 'Floor')->first();
        $parkingUnit = Unit::where('type', 'count')->where('name->en', 'Parking Space')->first();

        $attributes = [
            // ===========================
            // DIMENSIONS & SIZE (number)
            // ===========================
            [
                'name' => ['en' => 'Area', 'ar' => 'المساحة'],
                'type' => 'number',
                'unit_id' => $areaUnit?->id,
            ],
            [
                'name' => ['en' => 'Land Area', 'ar' => 'مساحة الأرض'],
                'type' => 'number',
                'unit_id' => $areaUnit?->id,
            ],
            [
                'name' => ['en' => 'Built-up Area', 'ar' => 'المساحة المبنية'],
                'type' => 'number',
                'unit_id' => $areaUnit?->id,
            ],
            [
                'name' => ['en' => 'Garden Area', 'ar' => 'مساحة الحديقة'],
                'type' => 'number',
                'unit_id' => $areaUnit?->id,
            ],
            [
                'name' => ['en' => 'Roof Area', 'ar' => 'مساحة السطح'],
                'type' => 'number',
                'unit_id' => $areaUnit?->id,
            ],
            [
                'name' => ['en' => 'Street Width', 'ar' => 'عرض الشارع'],
                'type' => 'number',
                'unit_id' => $lengthUnit?->id,
            ],
            [
                'name' => ['en' => 'Ceiling Height', 'ar' => 'ارتفاع السقف'],
                'type' => 'number',
                'unit_id' => $lengthUnit?->id,
            ],

            // ===========================
            // ROOM COUNTS (number)
            // ===========================
            [
                'name' => ['en' => 'Number of Rooms', 'ar' => 'عدد الغرف'],
                'type' => 'number',
                'unit_id' => $countUnit?->id,
            ],
            [
                'name' => ['en' => 'Number of Bedrooms', 'ar' => 'عدد غرف النوم'],
                'type' => 'number',
                'unit_id' => $countUnit?->id,
            ],
            [
                'name' => ['en' => 'Number of Bathrooms', 'ar' => 'عدد الحمامات'],
                'type' => 'number',
                'unit_id' => $bathUnit?->id,
            ],
            [
                'name' => ['en' => 'Number of Living Rooms', 'ar' => 'عدد غرف المعيشة'],
                'type' => 'number',
                'unit_id' => $countUnit?->id,
            ],
            [
                'name' => ['en' => 'Number of Kitchens', 'ar' => 'عدد المطابخ'],
                'type' => 'number',
                'unit_id' => $countUnit?->id,
            ],

            // ===========================
            // BUILDING INFO (number)
            // ===========================
            [
                'name' => ['en' => 'Floor Number', 'ar' => 'رقم الدور'],
                'type' => 'number',
                'unit_id' => $floorUnit?->id,
            ],
            [
                'name' => ['en' => 'Number of Floors', 'ar' => 'عدد الطوابق'],
                'type' => 'number',
                'unit_id' => $floorUnit?->id,
            ],
            [
                'name' => ['en' => 'Building Age', 'ar' => 'عمر المبنى'],
                'type' => 'number',
                'unit_id' => $timeYearUnit?->id,
            ],
            [
                'name' => ['en' => 'Number of Units', 'ar' => 'عدد الوحدات'],
                'type' => 'number',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Number of Parking Spaces', 'ar' => 'عدد مواقف السيارات'],
                'type' => 'number',
                'unit_id' => $parkingUnit?->id,
            ],

            // ===========================
            // PRICING (number)
            // ===========================
            [
                'name' => ['en' => 'Total Price', 'ar' => 'السعر الإجمالي'],
                'type' => 'number',
                'unit_id' => $priceUnit?->id,
            ],
            [
                'name' => ['en' => 'Price per m²', 'ar' => 'السعر لكل متر مربع'],
                'type' => 'number',
                'unit_id' => $pricePerMeterUnit?->id,
            ],
            [
                'name' => ['en' => 'Down Payment', 'ar' => 'المقدم'],
                'type' => 'number',
                'unit_id' => $percentUnit?->id,
            ],
            [
                'name' => ['en' => 'Monthly Installment', 'ar' => 'القسط الشهري'],
                'type' => 'number',
                'unit_id' => $priceUnit?->id,
            ],
            [
                'name' => ['en' => 'Installment Period', 'ar' => 'فترة التقسيط'],
                'type' => 'number',
                'unit_id' => $timeMonthUnit?->id,
            ],

            // ===========================
            // SELECT ATTRIBUTES (with options)
            // ===========================
            [
                'name' => ['en' => 'Finishing Type', 'ar' => 'نوع التشطيب'],
                'type' => 'select',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'View', 'ar' => 'الإطلالة'],
                'type' => 'select',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Direction', 'ar' => 'الاتجاه'],
                'type' => 'select',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Furnishing Status', 'ar' => 'حالة الفرش'],
                'type' => 'select',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Payment Method', 'ar' => 'طريقة الدفع'],
                'type' => 'select',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Ownership Type', 'ar' => 'نوع الملكية'],
                'type' => 'select',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Property Condition', 'ar' => 'حالة العقار'],
                'type' => 'select',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Flooring Type', 'ar' => 'نوع الأرضيات'],
                'type' => 'select',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Heating Type', 'ar' => 'نوع التدفئة'],
                'type' => 'select',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Cooling Type', 'ar' => 'نوع التبريد'],
                'type' => 'select',
                'unit_id' => null,
            ],

            // ===========================
            // DATE ATTRIBUTES
            // ===========================
            [
                'name' => ['en' => 'Delivery Date', 'ar' => 'تاريخ التسليم'],
                'type' => 'date',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Construction Date', 'ar' => 'تاريخ البناء'],
                'type' => 'date',
                'unit_id' => null,
            ],

            // ===========================
            // BOOLEAN - PROPERTY FEATURES
            // ===========================
            [
                'name' => ['en' => 'Balcony', 'ar' => 'شرفة'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Parking', 'ar' => 'موقف سيارات'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Elevator', 'ar' => 'مصعد'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Security', 'ar' => 'أمن'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Swimming Pool', 'ar' => 'حمام سباحة'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Gym', 'ar' => 'صالة رياضية'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Garden', 'ar' => 'حديقة'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Air Conditioning', 'ar' => 'تكييف'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Central Gas', 'ar' => 'غاز مركزي'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Internet', 'ar' => 'إنترنت'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Satellite/Cable TV', 'ar' => 'دش/تلفزيون كابل'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Intercom', 'ar' => 'إنتركوم'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Fire System', 'ar' => 'نظام إطفاء حريق'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'CCTV', 'ar' => 'كاميرات مراقبة'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Smart Home', 'ar' => 'منزل ذكي'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Solar Energy', 'ar' => 'طاقة شمسية'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Pets Allowed', 'ar' => 'يسمح بالحيوانات الأليفة'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Doorman', 'ar' => 'بواب'],
                'type' => 'boolean',
                'unit_id' => null,
            ],

            // ===========================
            // BOOLEAN - ROOM FEATURES
            // ===========================
            [
                'name' => ['en' => "Maid's Room", 'ar' => 'غرفة خادمة'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => "Driver's Room", 'ar' => 'غرفة سائق'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Storage Room', 'ar' => 'غرفة تخزين'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Laundry Room', 'ar' => 'غرفة غسيل'],
                'type' => 'boolean',
                'unit_id' => null,
            ],

            // ===========================
            // BOOLEAN - PRIVATE AMENITIES
            // ===========================
            [
                'name' => ['en' => 'Private Garden', 'ar' => 'حديقة خاصة'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Private Pool', 'ar' => 'حمام سباحة خاص'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Jacuzzi', 'ar' => 'جاكوزي'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Roof Access', 'ar' => 'وصول للسطح'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Terrace', 'ar' => 'تراس'],
                'type' => 'boolean',
                'unit_id' => null,
            ],

            // ===========================
            // BOOLEAN - UTILITIES
            // ===========================
            [
                'name' => ['en' => 'Electricity', 'ar' => 'كهرباء'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Water Supply', 'ar' => 'مياه'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Natural Gas', 'ar' => 'غاز طبيعي'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Sewage System', 'ar' => 'شبكة صرف صحي'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Landline Phone', 'ar' => 'هاتف أرضي'],
                'type' => 'boolean',
                'unit_id' => null,
            ],

            // ===========================
            // BOOLEAN - COMPOUND/COMMUNITY
            // ===========================
            [
                'name' => ['en' => 'Clubhouse', 'ar' => 'نادي'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Kids Area', 'ar' => 'منطقة أطفال'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Commercial Area', 'ar' => 'منطقة تجارية'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Mosque', 'ar' => 'مسجد'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Jogging Track', 'ar' => 'مسار للجري'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Sports Courts', 'ar' => 'ملاعب رياضية'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Spa', 'ar' => 'سبا'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Business Center', 'ar' => 'مركز أعمال'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Medical Center', 'ar' => 'مركز طبي'],
                'type' => 'boolean',
                'unit_id' => null,
            ],

            // ===========================
            // BOOLEAN - NEARBY LANDMARKS
            // ===========================
            [
                'name' => ['en' => 'School Nearby', 'ar' => 'قريب من مدرسة'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Hospital Nearby', 'ar' => 'قريب من مستشفى'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Mall Nearby', 'ar' => 'قريب من مول'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Metro Nearby', 'ar' => 'قريب من المترو'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Highway Access', 'ar' => 'وصول للطريق السريع'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Public Transport Nearby', 'ar' => 'قريب من المواصلات'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::updateOrCreate(
                ['name->en' => $attribute['name']['en']],
                $attribute,
            );
        }
    }
}
