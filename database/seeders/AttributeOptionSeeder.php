<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeOption;
use Illuminate\Database\Seeder;

class AttributeOptionSeeder extends Seeder
{
    public function run(): void
    {
        $optionsMap = $this->getOptionsMap();

        foreach ($optionsMap as $attributeName => $options) {
            $attribute = Attribute::where('name->en', $attributeName)->first();

            if (! $attribute) {
                continue;
            }

            foreach ($options as $sortOrder => $option) {
                AttributeOption::updateOrCreate(
                    [
                        'attribute_id' => $attribute->id,
                        'value->en' => $option['en'],
                    ],
                    [
                        'value' => $option,
                        'sort_order' => $sortOrder,
                        'is_active' => true,
                    ],
                );
            }
        }
    }

    /**
     * @return array<string, array<int, array{en: string, ar: string}>>
     */
    private function getOptionsMap(): array
    {
        return [
            'Finishing Type' => [
                ['en' => 'Super Lux', 'ar' => 'سوبر لوكس'],
                ['en' => 'Lux', 'ar' => 'لوكس'],
                ['en' => 'Semi-Finished', 'ar' => 'نصف تشطيب'],
                ['en' => 'Not Finished', 'ar' => 'بدون تشطيب'],
                ['en' => 'Core & Shell', 'ar' => 'على الطوب الأحمر'],
                ['en' => 'Fully Finished', 'ar' => 'تشطيب كامل'],
                ['en' => 'Extra Super Lux', 'ar' => 'إكسترا سوبر لوكس'],
            ],
            'View' => [
                ['en' => 'Sea View', 'ar' => 'إطلالة بحرية'],
                ['en' => 'Garden View', 'ar' => 'إطلالة حديقة'],
                ['en' => 'City View', 'ar' => 'إطلالة على المدينة'],
                ['en' => 'Pool View', 'ar' => 'إطلالة على حمام السباحة'],
                ['en' => 'Street View', 'ar' => 'إطلالة على الشارع'],
                ['en' => 'Main Road View', 'ar' => 'إطلالة على الطريق الرئيسي'],
                ['en' => 'Nile View', 'ar' => 'إطلالة على النيل'],
                ['en' => 'Desert View', 'ar' => 'إطلالة صحراوية'],
                ['en' => 'Park View', 'ar' => 'إطلالة على الحديقة العامة'],
                ['en' => 'Lake View', 'ar' => 'إطلالة على البحيرة'],
                ['en' => 'Golf View', 'ar' => 'إطلالة على الجولف'],
                ['en' => 'Mountain View', 'ar' => 'إطلالة على الجبل'],
                ['en' => 'Landscape View', 'ar' => 'إطلالة على المناظر الطبيعية'],
                ['en' => 'Courtyard View', 'ar' => 'إطلالة على الفناء'],
            ],
            'Direction' => [
                ['en' => 'North', 'ar' => 'شمال'],
                ['en' => 'South', 'ar' => 'جنوب'],
                ['en' => 'East', 'ar' => 'شرق'],
                ['en' => 'West', 'ar' => 'غرب'],
                ['en' => 'Northeast', 'ar' => 'شمال شرق'],
                ['en' => 'Northwest', 'ar' => 'شمال غرب'],
                ['en' => 'Southeast', 'ar' => 'جنوب شرق'],
                ['en' => 'Southwest', 'ar' => 'جنوب غرب'],
            ],
            'Furnishing Status' => [
                ['en' => 'Furnished', 'ar' => 'مفروش'],
                ['en' => 'Semi-Furnished', 'ar' => 'نصف مفروش'],
                ['en' => 'Unfurnished', 'ar' => 'بدون فرش'],
            ],
            'Payment Method' => [
                ['en' => 'Cash', 'ar' => 'كاش'],
                ['en' => 'Installment', 'ar' => 'تقسيط'],
                ['en' => 'Cash & Installment', 'ar' => 'كاش وتقسيط'],
                ['en' => 'Bank Mortgage', 'ar' => 'تمويل بنكي'],
            ],
            'Ownership Type' => [
                ['en' => 'Freehold', 'ar' => 'تمليك'],
                ['en' => 'Leasehold', 'ar' => 'إيجار'],
                ['en' => 'Usufruct', 'ar' => 'حق الانتفاع'],
            ],
            'Property Condition' => [
                ['en' => 'New', 'ar' => 'جديد'],
                ['en' => 'Resale', 'ar' => 'إعادة بيع'],
                ['en' => 'Under Construction', 'ar' => 'تحت الإنشاء'],
                ['en' => 'Ready to Move', 'ar' => 'جاهز للسكن'],
                ['en' => 'Renovated', 'ar' => 'مجدد'],
            ],
            'Flooring Type' => [
                ['en' => 'Ceramic', 'ar' => 'سيراميك'],
                ['en' => 'Marble', 'ar' => 'رخام'],
                ['en' => 'Porcelain', 'ar' => 'بورسلين'],
                ['en' => 'Wood', 'ar' => 'خشب'],
                ['en' => 'HDF', 'ar' => 'أرضيات HDF'],
                ['en' => 'Parquet', 'ar' => 'باركيه'],
                ['en' => 'Granite', 'ar' => 'جرانيت'],
                ['en' => 'Epoxy', 'ar' => 'إيبوكسي'],
            ],
            'Heating Type' => [
                ['en' => 'Central Heating', 'ar' => 'تدفئة مركزية'],
                ['en' => 'Individual Heating', 'ar' => 'تدفئة فردية'],
                ['en' => 'Underfloor Heating', 'ar' => 'تدفئة أرضية'],
                ['en' => 'No Heating', 'ar' => 'بدون تدفئة'],
            ],
            'Cooling Type' => [
                ['en' => 'Central AC', 'ar' => 'تكييف مركزي'],
                ['en' => 'Split AC', 'ar' => 'تكييف سبليت'],
                ['en' => 'Window AC', 'ar' => 'تكييف شباك'],
                ['en' => 'No AC', 'ar' => 'بدون تكييف'],
            ],
        ];
    }
}
