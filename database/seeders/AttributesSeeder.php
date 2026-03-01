<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributesSeeder extends Seeder
{
    public function run(): void
    {
        $units = $this->resolveUnits();

        $attributes = [
            // ===========================
            // DIMENSIONS & SIZE (number)
            // ===========================
            [
                'name' => ['en' => 'Area', 'ar' => 'المساحة'],
                'slug' => 'area',
                'type' => 'number',
                'unit_id' => $units['area'],
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Land Area', 'ar' => 'مساحة الأرض'],
                'slug' => 'land-area',
                'type' => 'number',
                'unit_id' => $units['area'],
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Built-up Area', 'ar' => 'المساحة المبنية'],
                'slug' => 'built-up-area',
                'type' => 'number',
                'unit_id' => $units['area'],
                'is_filterable' => false,
            ],
            [
                'name' => ['en' => 'Garden Area', 'ar' => 'مساحة الحديقة'],
                'slug' => 'garden-area',
                'type' => 'number',
                'unit_id' => $units['area'],
                'is_filterable' => false,
            ],
            [
                'name' => ['en' => 'Roof Area', 'ar' => 'مساحة السطح'],
                'slug' => 'roof-area',
                'type' => 'number',
                'unit_id' => $units['area'],
                'is_filterable' => false,
            ],
            [
                'name' => ['en' => 'Street Width', 'ar' => 'عرض الشارع'],
                'slug' => 'street-width',
                'type' => 'number',
                'unit_id' => $units['length'],
                'is_filterable' => false,
            ],
            [
                'name' => ['en' => 'Ceiling Height', 'ar' => 'ارتفاع السقف'],
                'slug' => 'ceiling-height',
                'type' => 'number',
                'unit_id' => $units['length'],
                'is_filterable' => false,
            ],

            // ===========================
            // ROOM COUNTS (number)
            // ===========================
            [
                'name' => ['en' => 'Number of Rooms', 'ar' => 'عدد الغرف'],
                'slug' => 'number-of-rooms',
                'type' => 'number',
                'unit_id' => $units['count'],
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Number of Bedrooms', 'ar' => 'عدد غرف النوم'],
                'slug' => 'number-of-bedrooms',
                'type' => 'number',
                'unit_id' => $units['count'],
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Number of Bathrooms', 'ar' => 'عدد الحمامات'],
                'slug' => 'number-of-bathrooms',
                'type' => 'number',
                'unit_id' => $units['bath'],
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Number of Living Rooms', 'ar' => 'عدد غرف المعيشة'],
                'slug' => 'number-of-living-rooms',
                'type' => 'number',
                'unit_id' => $units['count'],
                'is_filterable' => false,
            ],
            [
                'name' => ['en' => 'Number of Kitchens', 'ar' => 'عدد المطابخ'],
                'slug' => 'number-of-kitchens',
                'type' => 'number',
                'unit_id' => $units['count'],
                'is_filterable' => false,
            ],

            // ===========================
            // BUILDING INFO (number)
            // ===========================
            [
                'name' => ['en' => 'Floor Number', 'ar' => 'رقم الدور'],
                'slug' => 'floor-number',
                'type' => 'number',
                'unit_id' => $units['floor'],
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Number of Floors', 'ar' => 'عدد الطوابق'],
                'slug' => 'number-of-floors',
                'type' => 'number',
                'unit_id' => $units['floor'],
                'is_filterable' => false,
            ],
            [
                'name' => ['en' => 'Building Age', 'ar' => 'عمر المبنى'],
                'slug' => 'building-age',
                'type' => 'number',
                'unit_id' => $units['timeYear'],
                'is_filterable' => false,
            ],
            [
                'name' => ['en' => 'Number of Units', 'ar' => 'عدد الوحدات'],
                'slug' => 'number-of-units',
                'type' => 'number',
                'unit_id' => null,
                'is_filterable' => false,
            ],
            [
                'name' => ['en' => 'Number of Parking Spaces', 'ar' => 'عدد مواقف السيارات'],
                'slug' => 'number-of-parking-spaces',
                'type' => 'number',
                'unit_id' => $units['parking'],
                'is_filterable' => false,
            ],

            // ===========================
            // PRICING (number)
            // ===========================
            [
                'name' => ['en' => 'Total Price', 'ar' => 'السعر الإجمالي'],
                'slug' => 'total-price',
                'type' => 'number',
                'unit_id' => $units['price'],
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Price per m²', 'ar' => 'السعر لكل متر مربع'],
                'slug' => 'price-per-m2',
                'type' => 'number',
                'unit_id' => $units['pricePerMeter'],
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Down Payment', 'ar' => 'المقدم'],
                'slug' => 'down-payment',
                'type' => 'number',
                'unit_id' => $units['percent'],
                'is_filterable' => false,
            ],
            [
                'name' => ['en' => 'Monthly Installment', 'ar' => 'القسط الشهري'],
                'slug' => 'monthly-installment',
                'type' => 'number',
                'unit_id' => $units['price'],
                'is_filterable' => false,
            ],
            [
                'name' => ['en' => 'Installment Period', 'ar' => 'فترة التقسيط'],
                'slug' => 'installment-period',
                'type' => 'number',
                'unit_id' => $units['timeMonth'],
                'is_filterable' => false,
            ],

            // ===========================
            // SELECT ATTRIBUTES
            // ===========================
            [
                'name' => ['en' => 'Finishing Type', 'ar' => 'نوع التشطيب'],
                'slug' => 'finishing-type',
                'type' => 'select',
                'unit_id' => null,
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'View', 'ar' => 'الإطلالة'],
                'slug' => 'view',
                'type' => 'select',
                'unit_id' => null,
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Direction', 'ar' => 'الاتجاه'],
                'slug' => 'direction',
                'type' => 'select',
                'unit_id' => null,
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Furnishing Status', 'ar' => 'حالة الفرش'],
                'slug' => 'furnishing-status',
                'type' => 'select',
                'unit_id' => null,
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Payment Method', 'ar' => 'طريقة الدفع'],
                'slug' => 'payment-method',
                'type' => 'select',
                'unit_id' => null,
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Ownership Type', 'ar' => 'نوع الملكية'],
                'slug' => 'ownership-type',
                'type' => 'select',
                'unit_id' => null,
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Property Condition', 'ar' => 'حالة العقار'],
                'slug' => 'property-condition',
                'type' => 'select',
                'unit_id' => null,
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Flooring Type', 'ar' => 'نوع الأرضيات'],
                'slug' => 'flooring-type',
                'type' => 'select',
                'unit_id' => null,
                'is_filterable' => false,
            ],
            [
                'name' => ['en' => 'Heating Type', 'ar' => 'نوع التدفئة'],
                'slug' => 'heating-type',
                'type' => 'select',
                'unit_id' => null,
                'is_filterable' => false,
            ],
            [
                'name' => ['en' => 'Cooling Type', 'ar' => 'نوع التبريد'],
                'slug' => 'cooling-type',
                'type' => 'select',
                'unit_id' => null,
                'is_filterable' => false,
            ],

            // ===========================
            // DATE ATTRIBUTES
            // ===========================
            [
                'name' => ['en' => 'Delivery Date', 'ar' => 'تاريخ التسليم'],
                'slug' => 'delivery-date',
                'type' => 'date',
                'unit_id' => null,
                'is_filterable' => true,
            ],
            [
                'name' => ['en' => 'Construction Date', 'ar' => 'تاريخ البناء'],
                'slug' => 'construction-date',
                'type' => 'date',
                'unit_id' => null,
                'is_filterable' => false,
            ],

            // ===========================
            // BOOLEAN - PROPERTY FEATURES
            // ===========================
            ...collect([
                ['en' => 'Balcony', 'ar' => 'شرفة'],
                ['en' => 'Parking', 'ar' => 'موقف سيارات'],
                ['en' => 'Elevator', 'ar' => 'مصعد'],
                ['en' => 'Security', 'ar' => 'أمن'],
                ['en' => 'Swimming Pool', 'ar' => 'حمام سباحة'],
                ['en' => 'Gym', 'ar' => 'صالة رياضية'],
                ['en' => 'Garden', 'ar' => 'حديقة'],
                ['en' => 'Air Conditioning', 'ar' => 'تكييف'],
                ['en' => 'Central Gas', 'ar' => 'غاز مركزي'],
                ['en' => 'Internet', 'ar' => 'إنترنت'],
                ['en' => 'Satellite/Cable TV', 'ar' => 'دش/تلفزيون كابل'],
                ['en' => 'Intercom', 'ar' => 'إنتركوم'],
                ['en' => 'Fire System', 'ar' => 'نظام إطفاء حريق'],
                ['en' => 'CCTV', 'ar' => 'كاميرات مراقبة'],
                ['en' => 'Smart Home', 'ar' => 'منزل ذكي'],
                ['en' => 'Solar Energy', 'ar' => 'طاقة شمسية'],
                ['en' => 'Pets Allowed', 'ar' => 'يسمح بالحيوانات الأليفة'],
                ['en' => 'Doorman', 'ar' => 'بواب'],
            ])->map(fn (array $name) => [
                'name' => $name,
                'slug' => Str::slug($name['en']),
                'type' => 'boolean',
                'unit_id' => null,
                'is_filterable' => true,
            ])->all(),

            // ===========================
            // BOOLEAN - ROOM FEATURES
            // ===========================
            ...collect([
                ['en' => "Maid's Room", 'ar' => 'غرفة خادمة'],
                ['en' => "Driver's Room", 'ar' => 'غرفة سائق'],
                ['en' => 'Storage Room', 'ar' => 'غرفة تخزين'],
                ['en' => 'Laundry Room', 'ar' => 'غرفة غسيل'],
            ])->map(fn (array $name) => [
                'name' => $name,
                'slug' => Str::slug($name['en']),
                'type' => 'boolean',
                'unit_id' => null,
                'is_filterable' => true,
            ])->all(),

            // ===========================
            // BOOLEAN - PRIVATE AMENITIES
            // ===========================
            ...collect([
                ['en' => 'Private Garden', 'ar' => 'حديقة خاصة'],
                ['en' => 'Private Pool', 'ar' => 'حمام سباحة خاص'],
                ['en' => 'Jacuzzi', 'ar' => 'جاكوزي'],
                ['en' => 'Roof Access', 'ar' => 'وصول للسطح'],
                ['en' => 'Terrace', 'ar' => 'تراس'],
            ])->map(fn (array $name) => [
                'name' => $name,
                'slug' => Str::slug($name['en']),
                'type' => 'boolean',
                'unit_id' => null,
                'is_filterable' => true,
            ])->all(),

            // ===========================
            // BOOLEAN - UTILITIES
            // ===========================
            ...collect([
                ['en' => 'Electricity', 'ar' => 'كهرباء'],
                ['en' => 'Water Supply', 'ar' => 'مياه'],
                ['en' => 'Natural Gas', 'ar' => 'غاز طبيعي'],
                ['en' => 'Sewage System', 'ar' => 'شبكة صرف صحي'],
                ['en' => 'Landline Phone', 'ar' => 'هاتف أرضي'],
            ])->map(fn (array $name) => [
                'name' => $name,
                'slug' => Str::slug($name['en']),
                'type' => 'boolean',
                'unit_id' => null,
                'is_filterable' => false,
            ])->all(),

            // ===========================
            // BOOLEAN - COMPOUND/COMMUNITY
            // ===========================
            ...collect([
                ['en' => 'Clubhouse', 'ar' => 'نادي'],
                ['en' => 'Kids Area', 'ar' => 'منطقة أطفال'],
                ['en' => 'Commercial Area', 'ar' => 'منطقة تجارية'],
                ['en' => 'Mosque', 'ar' => 'مسجد'],
                ['en' => 'Jogging Track', 'ar' => 'مسار للجري'],
                ['en' => 'Sports Courts', 'ar' => 'ملاعب رياضية'],
                ['en' => 'Spa', 'ar' => 'سبا'],
                ['en' => 'Business Center', 'ar' => 'مركز أعمال'],
                ['en' => 'Medical Center', 'ar' => 'مركز طبي'],
            ])->map(fn (array $name) => [
                'name' => $name,
                'slug' => Str::slug($name['en']),
                'type' => 'boolean',
                'unit_id' => null,
                'is_filterable' => false,
            ])->all(),

            // ===========================
            // BOOLEAN - NEARBY LANDMARKS
            // ===========================
            ...collect([
                ['en' => 'School Nearby', 'ar' => 'قريب من مدرسة'],
                ['en' => 'Hospital Nearby', 'ar' => 'قريب من مستشفى'],
                ['en' => 'Mall Nearby', 'ar' => 'قريب من مول'],
                ['en' => 'Metro Nearby', 'ar' => 'قريب من المترو'],
                ['en' => 'Highway Access', 'ar' => 'وصول للطريق السريع'],
                ['en' => 'Public Transport Nearby', 'ar' => 'قريب من المواصلات'],
            ])->map(fn (array $name) => [
                'name' => $name,
                'slug' => Str::slug($name['en']),
                'type' => 'boolean',
                'unit_id' => null,
                'is_filterable' => false,
            ])->all(),
        ];

        foreach ($attributes as $attribute) {
            Attribute::updateOrCreate(
                ['slug' => $attribute['slug']],
                $attribute,
            );
        }
    }

    /**
     * @return array<string, int|null>
     */
    private function resolveUnits(): array
    {
        return [
            'area' => Unit::where('type', 'area')->where('name->en', 'Square Meter')->value('id'),
            'length' => Unit::where('type', 'length')->value('id'),
            'count' => Unit::where('type', 'count')->where('name->en', 'Room')->value('id'),
            'bath' => Unit::where('type', 'count')->where('name->en', 'Bathroom')->value('id'),
            'timeYear' => Unit::where('type', 'time')->where('name->en', 'Year')->value('id'),
            'timeMonth' => Unit::where('type', 'time')->where('name->en', 'Month')->value('id'),
            'pricePerMeter' => Unit::where('type', 'price')->where('name->en', 'Price per Square Meter')->value('id'),
            'price' => Unit::where('type', 'price')->where('name->en', 'Egyptian Pound')->value('id'),
            'percent' => Unit::where('type', 'count')->where('name->en', 'Percentage')->value('id'),
            'floor' => Unit::where('type', 'count')->where('name->en', 'Floor')->value('id'),
            'parking' => Unit::where('type', 'count')->where('name->en', 'Parking Space')->value('id'),
        ];
    }
}
