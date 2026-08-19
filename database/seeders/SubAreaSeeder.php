<?php

namespace Database\Seeders;

use App\Models\SubArea;
use App\Models\Area;
use Illuminate\Database\Seeder;

class SubAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cairoId = Area::where('name->en', 'Cairo')->value('id');
        $gizaId = Area::where('name->en', 'Giza')->value('id');
        $alexandriaId = Area::where('name->en', 'Alexandria')->value('id');
        $qalyubiaId = Area::where('name->en', 'Qalyubia')->value('id');
        $dakahliaId = Area::where('name->en', 'Dakahlia')->value('id');
        $sharqiaId = Area::where('name->en', 'Sharqia')->value('id');
        $gharbiaId = Area::where('name->en', 'Gharbia')->value('id');
        $menofiaId = Area::where('name->en', 'Menofia')->value('id');
        $kafrElSheikhId = Area::where('name->en', 'Kafr El Sheikh')->value('id');
        $damiettaId = Area::where('name->en', 'Damietta')->value('id');
        $portSaidId = Area::where('name->en', 'Port Said')->value('id');
        $ismailiaId = Area::where('name->en', 'Ismailia')->value('id');
        $suezId = Area::where('name->en', 'Suez')->value('id');
        $beheiraId = Area::where('name->en', 'Beheira')->value('id');
        $matrouhId = Area::where('name->en', 'Matrouh')->value('id');
        $northSinaiId = Area::where('name->en', 'North Sinai')->value('id');
        $southSinaiId = Area::where('name->en', 'South Sinai')->value('id');
        $redSeaId = Area::where('name->en', 'Red Sea')->value('id');
        $fayoumId = Area::where('name->en', 'Fayoum')->value('id');
        $beniSuefId = Area::where('name->en', 'Beni Suef')->value('id');
        $minyaId = Area::where('name->en', 'Minya')->value('id');
        $assiutId = Area::where('name->en', 'Assiut')->value('id');
        $sohagId = Area::where('name->en', 'Sohag')->value('id');
        $qenaId = Area::where('name->en', 'Qena')->value('id');
        $luxorId = Area::where('name->en', 'Luxor')->value('id');
        $aswanId = Area::where('name->en', 'Aswan')->value('id');
        $newValleyId = Area::where('name->en', 'New Valley')->value('id');

        $riyadhId = Area::where('name->en', 'Riyadh')->value('id');
        $jeddahId = Area::where('name->en', 'Jeddah')->value('id');
        $dubaiId = Area::where('name->en', 'Dubai')->value('id');
        $abudhabiId = Area::where('name->en', 'Abu Dhabi')->value('id');

        $subAreas = [
            // القاهرة
            ['name' => ['en' => 'Nasr City', 'ar' => 'مدينة نصر'], 'area_id' => $cairoId],
            ['name' => ['en' => 'Maadi', 'ar' => 'المعادي'], 'area_id' => $cairoId],
            ['name' => ['en' => 'Heliopolis', 'ar' => 'مصر الجديدة'], 'area_id' => $cairoId],
            ['name' => ['en' => 'Zamalek', 'ar' => 'الزمالك'], 'area_id' => $cairoId],
            ['name' => ['en' => 'New Cairo', 'ar' => 'القاهرة الجديدة'], 'area_id' => $cairoId],
            ['name' => ['en' => 'Shubra', 'ar' => 'شبرا'], 'area_id' => $cairoId],
            ['name' => ['en' => 'Ain Shams', 'ar' => 'عين شمس'], 'area_id' => $cairoId],
            ['name' => ['en' => 'El Marg', 'ar' => 'المرج'], 'area_id' => $cairoId],
            ['name' => ['en' => 'El Matariya', 'ar' => 'المطرية'], 'area_id' => $cairoId],
            ['name' => ['en' => 'El Basatin', 'ar' => 'البساتين'], 'area_id' => $cairoId],
            ['name' => ['en' => 'El Sayeda Zeinab', 'ar' => 'السيدة زينب'], 'area_id' => $cairoId],
            ['name' => ['en' => 'Downtown Cairo', 'ar' => 'وسط البلد'], 'area_id' => $cairoId],
            ['name' => ['en' => 'El Nozha', 'ar' => 'النزهة'], 'area_id' => $cairoId],
            ['name' => ['en' => 'El Salam', 'ar' => 'السلام'], 'area_id' => $cairoId],
            ['name' => ['en' => 'El Tebbin', 'ar' => 'التبين'], 'area_id' => $cairoId],
            ['name' => ['en' => '15th of May', 'ar' => '15 مايو'], 'area_id' => $cairoId],
            ['name' => ['en' => 'El Khalifa', 'ar' => 'الخليفة'], 'area_id' => $cairoId],
            ['name' => ['en' => 'El Shorouk', 'ar' => 'الشروق'], 'area_id' => $cairoId],
            ['name' => ['en' => 'Badr City', 'ar' => 'مدينة بدر'], 'area_id' => $cairoId],
            ['name' => ['en' => 'El Obour', 'ar' => 'العبور'], 'area_id' => $cairoId],

            // الجيزة
            ['name' => ['en' => '6th of October', 'ar' => 'السادس من أكتوبر'], 'area_id' => $gizaId],
            ['name' => ['en' => 'Sheikh Zayed', 'ar' => 'الشيخ زايد'], 'area_id' => $gizaId],
            ['name' => ['en' => 'Haram', 'ar' => 'الهرم'], 'area_id' => $gizaId],
            ['name' => ['en' => 'Dokki', 'ar' => 'الدقي'], 'area_id' => $gizaId],
            ['name' => ['en' => 'Mohandessin', 'ar' => 'المهندسين'], 'area_id' => $gizaId],
            ['name' => ['en' => 'Agouza', 'ar' => 'العجوزة'], 'area_id' => $gizaId],
            ['name' => ['en' => 'Imbaba', 'ar' => 'إمبابة'], 'area_id' => $gizaId],
            ['name' => ['en' => 'Faisal', 'ar' => 'فيصل'], 'area_id' => $gizaId],
            ['name' => ['en' => 'El Warraq', 'ar' => 'الوراق'], 'area_id' => $gizaId],
            ['name' => ['en' => 'El Omraniya', 'ar' => 'العمرانية'], 'area_id' => $gizaId],
            ['name' => ['en' => 'Kerdasa', 'ar' => 'كرداسة'], 'area_id' => $gizaId],
            ['name' => ['en' => 'Abu Rawash', 'ar' => 'أبو رواش'], 'area_id' => $gizaId],
            ['name' => ['en' => 'El Badrashein', 'ar' => 'البدرشين'], 'area_id' => $gizaId],
            ['name' => ['en' => 'El Saff', 'ar' => 'الصف'], 'area_id' => $gizaId],
            ['name' => ['en' => 'El Ayyat', 'ar' => 'العياط'], 'area_id' => $gizaId],
            ['name' => ['en' => 'Atfih', 'ar' => 'أطفيح'], 'area_id' => $gizaId],
            ['name' => ['en' => 'El Hawamdia', 'ar' => 'الحوامدية'], 'area_id' => $gizaId],
            ['name' => ['en' => 'Hadayek El Ahram', 'ar' => 'حدائق الأهرام'], 'area_id' => $gizaId],

            // الإسكندرية
            ['name' => ['en' => 'Montaza', 'ar' => 'المنتزه'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'El Raml', 'ar' => 'الرمل'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'El Gomrok', 'ar' => 'الجمرك'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'El Attarin', 'ar' => 'العطارين'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'Sidi Gaber', 'ar' => 'سيدي جابر'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'Smouha', 'ar' => 'سموحة'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'Miami', 'ar' => 'ميامي'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'Mandara', 'ar' => 'المندرة'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'Agami', 'ar' => 'العجمي'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'Borg El Arab', 'ar' => 'برج العرب'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'El Amriya', 'ar' => 'العامرية'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'Abu Qir', 'ar' => 'أبو قير'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'Bahary', 'ar' => 'بحري'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'Karmouz', 'ar' => 'كرموز'], 'area_id' => $alexandriaId],
            ['name' => ['en' => 'Moharam Bey', 'ar' => 'محرم بك'], 'area_id' => $alexandriaId],

            // القليوبية
            ['name' => ['en' => 'Banha', 'ar' => 'بنها'], 'area_id' => $qalyubiaId],
            ['name' => ['en' => 'Qalyub', 'ar' => 'قليوب'], 'area_id' => $qalyubiaId],
            ['name' => ['en' => 'Shubra El Kheima', 'ar' => 'شبرا الخيمة'], 'area_id' => $qalyubiaId],
            ['name' => ['en' => 'El Khanka', 'ar' => 'الخانكة'], 'area_id' => $qalyubiaId],
            ['name' => ['en' => 'El Qanater El Khairiya', 'ar' => 'القناطر الخيرية'], 'area_id' => $qalyubiaId],
            ['name' => ['en' => 'Toukh', 'ar' => 'طوخ'], 'area_id' => $qalyubiaId],
            ['name' => ['en' => 'Kafr Shukr', 'ar' => 'كفر شكر'], 'area_id' => $qalyubiaId],
            ['name' => ['en' => 'Shibin El Qanater', 'ar' => 'شبين القناطر'], 'area_id' => $qalyubiaId],

            // الدقهلية
            ['name' => ['en' => 'Mansoura', 'ar' => 'المنصورة'], 'area_id' => $dakahliaId],
            ['name' => ['en' => 'Talkha', 'ar' => 'طلخا'], 'area_id' => $dakahliaId],
            ['name' => ['en' => 'Mit Ghamr', 'ar' => 'ميت غمر'], 'area_id' => $dakahliaId],
            ['name' => ['en' => 'Dekernes', 'ar' => 'دكرنس'], 'area_id' => $dakahliaId],
            ['name' => ['en' => 'Aga', 'ar' => 'أجا'], 'area_id' => $dakahliaId],
            ['name' => ['en' => 'Belqas', 'ar' => 'بلقاس'], 'area_id' => $dakahliaId],
            ['name' => ['en' => 'Shirbin', 'ar' => 'شربين'], 'area_id' => $dakahliaId],
            ['name' => ['en' => 'Manzala', 'ar' => 'المنزلة'], 'area_id' => $dakahliaId],
            ['name' => ['en' => 'El Senbellawein', 'ar' => 'السنبلاوين'], 'area_id' => $dakahliaId],
            ['name' => ['en' => 'Bani Ebeid', 'ar' => 'بني عبيد'], 'area_id' => $dakahliaId],
            ['name' => ['en' => 'El Gamaliya', 'ar' => 'الجمالية'], 'area_id' => $dakahliaId],
            ['name' => ['en' => 'Temay El Amdid', 'ar' => 'تمي الأمديد'], 'area_id' => $dakahliaId],
            ['name' => ['en' => 'Gamasa', 'ar' => 'جمصة'], 'area_id' => $dakahliaId],

            // الشرقية
            ['name' => ['en' => 'Zagazig', 'ar' => 'الزقازيق'], 'area_id' => $sharqiaId],
            ['name' => ['en' => 'Belbeis', 'ar' => 'بلبيس'], 'area_id' => $sharqiaId],
            ['name' => ['en' => '10th of Ramadan', 'ar' => 'العاشر من رمضان'], 'area_id' => $sharqiaId],
            ['name' => ['en' => 'Minya El Qamh', 'ar' => 'منيا القمح'], 'area_id' => $sharqiaId],
            ['name' => ['en' => 'Abu Hammad', 'ar' => 'أبو حماد'], 'area_id' => $sharqiaId],
            ['name' => ['en' => 'Faqous', 'ar' => 'فاقوس'], 'area_id' => $sharqiaId],
            ['name' => ['en' => 'Hihya', 'ar' => 'ههيا'], 'area_id' => $sharqiaId],
            ['name' => ['en' => 'Abu Kebir', 'ar' => 'أبو كبير'], 'area_id' => $sharqiaId],
            ['name' => ['en' => 'Kafr Saqr', 'ar' => 'كفر صقر'], 'area_id' => $sharqiaId],
            ['name' => ['en' => 'Awlad Saqr', 'ar' => 'أولاد صقر'], 'area_id' => $sharqiaId],
            ['name' => ['en' => 'El Husseiniya', 'ar' => 'الحسينية'], 'area_id' => $sharqiaId],
            ['name' => ['en' => 'Diarb Negm', 'ar' => 'ديرب نجم'], 'area_id' => $sharqiaId],
            ['name' => ['en' => 'Mashtool El Souk', 'ar' => 'مشتول السوق'], 'area_id' => $sharqiaId],
            ['name' => ['en' => 'El Ibrahimiya', 'ar' => 'الإبراهيمية'], 'area_id' => $sharqiaId],

            // الغربية
            ['name' => ['en' => 'Tanta', 'ar' => 'طنطا'], 'area_id' => $gharbiaId],
            ['name' => ['en' => 'El Mahalla El Kubra', 'ar' => 'المحلة الكبرى'], 'area_id' => $gharbiaId],
            ['name' => ['en' => 'Kafr El Zayat', 'ar' => 'كفر الزيات'], 'area_id' => $gharbiaId],
            ['name' => ['en' => 'Zefta', 'ar' => 'زفتى'], 'area_id' => $gharbiaId],
            ['name' => ['en' => 'Samanoud', 'ar' => 'سمنود'], 'area_id' => $gharbiaId],
            ['name' => ['en' => 'Basyoun', 'ar' => 'بسيون'], 'area_id' => $gharbiaId],
            ['name' => ['en' => 'El Santa', 'ar' => 'السنطة'], 'area_id' => $gharbiaId],
            ['name' => ['en' => 'Kotoor', 'ar' => 'قطور'], 'area_id' => $gharbiaId],

            // المنوفية
            ['name' => ['en' => 'Shibin El Kom', 'ar' => 'شبين الكوم'], 'area_id' => $menofiaId],
            ['name' => ['en' => 'Menouf', 'ar' => 'منوف'], 'area_id' => $menofiaId],
            ['name' => ['en' => 'Ashmoun', 'ar' => 'أشمون'], 'area_id' => $menofiaId],
            ['name' => ['en' => 'El Bagour', 'ar' => 'الباجور'], 'area_id' => $menofiaId],
            ['name' => ['en' => 'Quesna', 'ar' => 'قويسنا'], 'area_id' => $menofiaId],
            ['name' => ['en' => 'Berket El Sabaa', 'ar' => 'بركة السبع'], 'area_id' => $menofiaId],
            ['name' => ['en' => 'Tala', 'ar' => 'تلا'], 'area_id' => $menofiaId],
            ['name' => ['en' => 'El Shohada', 'ar' => 'الشهداء'], 'area_id' => $menofiaId],
            ['name' => ['en' => 'Sers El Layan', 'ar' => 'سرس الليان'], 'area_id' => $menofiaId],
            ['name' => ['en' => 'Sadat City', 'ar' => 'مدينة السادات'], 'area_id' => $menofiaId],

            // كفر الشيخ
            ['name' => ['en' => 'Kafr El Sheikh City', 'ar' => 'مدينة كفر الشيخ'], 'area_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Desouk', 'ar' => 'دسوق'], 'area_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Fuwwah', 'ar' => 'فوه'], 'area_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Baltim', 'ar' => 'بلطيم'], 'area_id' => $kafrElSheikhId],
            ['name' => ['en' => 'El Burullus', 'ar' => 'البرلس'], 'area_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Metobas', 'ar' => 'مطوبس'], 'area_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Sidi Salem', 'ar' => 'سيدي سالم'], 'area_id' => $kafrElSheikhId],
            ['name' => ['en' => 'El Hamoul', 'ar' => 'الحامول'], 'area_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Biyala', 'ar' => 'بيلا'], 'area_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Qallin', 'ar' => 'قلين'], 'area_id' => $kafrElSheikhId],
            ['name' => ['en' => 'El Riyad', 'ar' => 'الرياض'], 'area_id' => $kafrElSheikhId],

            // دمياط
            ['name' => ['en' => 'Damietta City', 'ar' => 'مدينة دمياط'], 'area_id' => $damiettaId],
            ['name' => ['en' => 'New Damietta', 'ar' => 'دمياط الجديدة'], 'area_id' => $damiettaId],
            ['name' => ['en' => 'Ras El Bar', 'ar' => 'رأس البر'], 'area_id' => $damiettaId],
            ['name' => ['en' => 'Fareskour', 'ar' => 'فارسكور'], 'area_id' => $damiettaId],
            ['name' => ['en' => 'El Zarqa', 'ar' => 'الزرقا'], 'area_id' => $damiettaId],
            ['name' => ['en' => 'Kafr Saad', 'ar' => 'كفر سعد'], 'area_id' => $damiettaId],
            ['name' => ['en' => 'Kafr El Battikh', 'ar' => 'كفر البطيخ'], 'area_id' => $damiettaId],

            // بورسعيد
            ['name' => ['en' => 'Port Fouad', 'ar' => 'بور فؤاد'], 'area_id' => $portSaidId],
            ['name' => ['en' => 'El Arab', 'ar' => 'العرب'], 'area_id' => $portSaidId],
            ['name' => ['en' => 'El Manakh', 'ar' => 'المناخ'], 'area_id' => $portSaidId],
            ['name' => ['en' => 'El Dawahi', 'ar' => 'الضواحي'], 'area_id' => $portSaidId],
            ['name' => ['en' => 'El Zohour', 'ar' => 'الزهور'], 'area_id' => $portSaidId],
            ['name' => ['en' => 'El Ganoub', 'ar' => 'الجنوب'], 'area_id' => $portSaidId],

            // الإسماعيلية
            ['name' => ['en' => 'Ismailia City', 'ar' => 'مدينة الإسماعيلية'], 'area_id' => $ismailiaId],
            ['name' => ['en' => 'Fayed', 'ar' => 'فايد'], 'area_id' => $ismailiaId],
            ['name' => ['en' => 'El Qantara East', 'ar' => 'القنطرة شرق'], 'area_id' => $ismailiaId],
            ['name' => ['en' => 'El Qantara West', 'ar' => 'القنطرة غرب'], 'area_id' => $ismailiaId],
            ['name' => ['en' => 'Abu Sultan', 'ar' => 'أبو سلطان'], 'area_id' => $ismailiaId],
            ['name' => ['en' => 'El Tal El Kebir', 'ar' => 'التل الكبير'], 'area_id' => $ismailiaId],
            ['name' => ['en' => 'El Qassassin', 'ar' => 'القصاصين'], 'area_id' => $ismailiaId],

            // السويس
            ['name' => ['en' => 'Suez City', 'ar' => 'مدينة السويس'], 'area_id' => $suezId],
            ['name' => ['en' => 'El Arbaeen', 'ar' => 'الأربعين'], 'area_id' => $suezId],
            ['name' => ['en' => 'Ataka', 'ar' => 'عتاقة'], 'area_id' => $suezId],
            ['name' => ['en' => 'El Ganayen', 'ar' => 'الجناين'], 'area_id' => $suezId],
            ['name' => ['en' => 'Faisal', 'ar' => 'فيصل'], 'area_id' => $suezId],

            // البحيرة
            ['name' => ['en' => 'Damanhour', 'ar' => 'دمنهور'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'Kafr El Dawwar', 'ar' => 'كفر الدوار'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'Rashid', 'ar' => 'رشيد'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'Edku', 'ar' => 'إدكو'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'Abu Hummus', 'ar' => 'أبو حمص'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'El Mahmoudiya', 'ar' => 'المحمودية'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'Itay El Barud', 'ar' => 'إيتاي البارود'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'Hosh Eissa', 'ar' => 'حوش عيسى'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'Shubrakhit', 'ar' => 'شبراخيت'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'Kom Hamada', 'ar' => 'كوم حمادة'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'El Delengat', 'ar' => 'الدلنجات'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'El Rahmaniya', 'ar' => 'الرحمانية'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'Abu El Matamir', 'ar' => 'أبو المطامير'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'Wadi El Natrun', 'ar' => 'وادي النطرون'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'El Nubariya', 'ar' => 'النوبارية'], 'area_id' => $beheiraId],
            ['name' => ['en' => 'Badr', 'ar' => 'بدر'], 'area_id' => $beheiraId],

            // مطروح
            ['name' => ['en' => 'Marsa Matrouh', 'ar' => 'مرسى مطروح'], 'area_id' => $matrouhId],
            ['name' => ['en' => 'El Alamein', 'ar' => 'العلمين'], 'area_id' => $matrouhId],
            ['name' => ['en' => 'Siwa', 'ar' => 'سيوة'], 'area_id' => $matrouhId],
            ['name' => ['en' => 'El Dabaa', 'ar' => 'الضبعة'], 'area_id' => $matrouhId],
            ['name' => ['en' => 'El Hamam', 'ar' => 'الحمام'], 'area_id' => $matrouhId],
            ['name' => ['en' => 'El Negaila', 'ar' => 'النجيلة'], 'area_id' => $matrouhId],
            ['name' => ['en' => 'Sidi Barrani', 'ar' => 'سيدي براني'], 'area_id' => $matrouhId],
            ['name' => ['en' => 'El Salloum', 'ar' => 'السلوم'], 'area_id' => $matrouhId],

            // شمال سيناء
            ['name' => ['en' => 'El Arish', 'ar' => 'العريش'], 'area_id' => $northSinaiId],
            ['name' => ['en' => 'Sheikh Zuweid', 'ar' => 'الشيخ زويد'], 'area_id' => $northSinaiId],
            ['name' => ['en' => 'Rafah', 'ar' => 'رفح'], 'area_id' => $northSinaiId],
            ['name' => ['en' => 'Bir El Abd', 'ar' => 'بئر العبد'], 'area_id' => $northSinaiId],
            ['name' => ['en' => 'El Hassana', 'ar' => 'الحسنة'], 'area_id' => $northSinaiId],
            ['name' => ['en' => 'Nakhl', 'ar' => 'نخل'], 'area_id' => $northSinaiId],

            // جنوب سيناء
            ['name' => ['en' => 'Sharm El Sheikh', 'ar' => 'شرم الشيخ'], 'area_id' => $southSinaiId],
            ['name' => ['en' => 'Dahab', 'ar' => 'دهب'], 'area_id' => $southSinaiId],
            ['name' => ['en' => 'Nuweiba', 'ar' => 'نويبع'], 'area_id' => $southSinaiId],
            ['name' => ['en' => 'Taba', 'ar' => 'طابا'], 'area_id' => $southSinaiId],
            ['name' => ['en' => 'Saint Catherine', 'ar' => 'سانت كاترين'], 'area_id' => $southSinaiId],
            ['name' => ['en' => 'El Tor', 'ar' => 'الطور'], 'area_id' => $southSinaiId],
            ['name' => ['en' => 'Abu Redeis', 'ar' => 'أبو رديس'], 'area_id' => $southSinaiId],
            ['name' => ['en' => 'Abu Zenima', 'ar' => 'أبو زنيمة'], 'area_id' => $southSinaiId],
            ['name' => ['en' => 'Ras Sidr', 'ar' => 'رأس سدر'], 'area_id' => $southSinaiId],

            // البحر الأحمر
            ['name' => ['en' => 'Hurghada', 'ar' => 'الغردقة'], 'area_id' => $redSeaId],
            ['name' => ['en' => 'Safaga', 'ar' => 'سفاجا'], 'area_id' => $redSeaId],
            ['name' => ['en' => 'El Quseir', 'ar' => 'القصير'], 'area_id' => $redSeaId],
            ['name' => ['en' => 'Marsa Alam', 'ar' => 'مرسى علم'], 'area_id' => $redSeaId],
            ['name' => ['en' => 'Ras Ghareb', 'ar' => 'رأس غارب'], 'area_id' => $redSeaId],
            ['name' => ['en' => 'Shalateen', 'ar' => 'شلاتين'], 'area_id' => $redSeaId],
            ['name' => ['en' => 'Halayeb', 'ar' => 'حلايب'], 'area_id' => $redSeaId],
            ['name' => ['en' => 'El Gouna', 'ar' => 'الجونة'], 'area_id' => $redSeaId],

            // الفيوم
            ['name' => ['en' => 'Fayoum City', 'ar' => 'مدينة الفيوم'], 'area_id' => $fayoumId],
            ['name' => ['en' => 'Ibsheway', 'ar' => 'إبشواي'], 'area_id' => $fayoumId],
            ['name' => ['en' => 'Itsa', 'ar' => 'إطسا'], 'area_id' => $fayoumId],
            ['name' => ['en' => 'Tamiya', 'ar' => 'طامية'], 'area_id' => $fayoumId],
            ['name' => ['en' => 'Sinnuris', 'ar' => 'سنورس'], 'area_id' => $fayoumId],
            ['name' => ['en' => 'Yousef El Seddik', 'ar' => 'يوسف الصديق'], 'area_id' => $fayoumId],

            // بني سويف
            ['name' => ['en' => 'Beni Suef City', 'ar' => 'مدينة بني سويف'], 'area_id' => $beniSuefId],
            ['name' => ['en' => 'El Wasta', 'ar' => 'الواسطى'], 'area_id' => $beniSuefId],
            ['name' => ['en' => 'Nasser', 'ar' => 'ناصر'], 'area_id' => $beniSuefId],
            ['name' => ['en' => 'Ehnasia', 'ar' => 'إهناسيا'], 'area_id' => $beniSuefId],
            ['name' => ['en' => 'Beba', 'ar' => 'ببا'], 'area_id' => $beniSuefId],
            ['name' => ['en' => 'Somosta', 'ar' => 'سمسطا'], 'area_id' => $beniSuefId],
            ['name' => ['en' => 'El Fashn', 'ar' => 'الفشن'], 'area_id' => $beniSuefId],

            // المنيا
            ['name' => ['en' => 'Minya City', 'ar' => 'مدينة المنيا'], 'area_id' => $minyaId],
            ['name' => ['en' => 'Mallawi', 'ar' => 'ملوي'], 'area_id' => $minyaId],
            ['name' => ['en' => 'Abu Qurqas', 'ar' => 'أبو قرقاص'], 'area_id' => $minyaId],
            ['name' => ['en' => 'Samalut', 'ar' => 'سمالوط'], 'area_id' => $minyaId],
            ['name' => ['en' => 'El Edwa', 'ar' => 'العدوة'], 'area_id' => $minyaId],
            ['name' => ['en' => 'Matai', 'ar' => 'مطاي'], 'area_id' => $minyaId],
            ['name' => ['en' => 'Beni Mazar', 'ar' => 'بني مزار'], 'area_id' => $minyaId],
            ['name' => ['en' => 'Maghagha', 'ar' => 'مغاغة'], 'area_id' => $minyaId],
            ['name' => ['en' => 'Deir Mawas', 'ar' => 'دير مواس'], 'area_id' => $minyaId],

            // أسيوط
            ['name' => ['en' => 'Assiut City', 'ar' => 'مدينة أسيوط'], 'area_id' => $assiutId],
            ['name' => ['en' => 'Manfalut', 'ar' => 'منفلوط'], 'area_id' => $assiutId],
            ['name' => ['en' => 'Abu Tig', 'ar' => 'أبو تيج'], 'area_id' => $assiutId],
            ['name' => ['en' => 'El Qusiya', 'ar' => 'القوصية'], 'area_id' => $assiutId],
            ['name' => ['en' => 'Dayrut', 'ar' => 'ديروط'], 'area_id' => $assiutId],
            ['name' => ['en' => 'Abnob', 'ar' => 'أبنوب'], 'area_id' => $assiutId],
            ['name' => ['en' => 'Sahel Selim', 'ar' => 'ساحل سليم'], 'area_id' => $assiutId],
            ['name' => ['en' => 'El Badari', 'ar' => 'البداري'], 'area_id' => $assiutId],
            ['name' => ['en' => 'El Ghanayem', 'ar' => 'الغنايم'], 'area_id' => $assiutId],
            ['name' => ['en' => 'El Fath', 'ar' => 'الفتح'], 'area_id' => $assiutId],
            ['name' => ['en' => 'Sadfa', 'ar' => 'صدفا'], 'area_id' => $assiutId],

            // سوهاج
            ['name' => ['en' => 'Sohag City', 'ar' => 'مدينة سوهاج'], 'area_id' => $sohagId],
            ['name' => ['en' => 'Akhmim', 'ar' => 'أخميم'], 'area_id' => $sohagId],
            ['name' => ['en' => 'Girga', 'ar' => 'جرجا'], 'area_id' => $sohagId],
            ['name' => ['en' => 'Tahta', 'ar' => 'طهطا'], 'area_id' => $sohagId],
            ['name' => ['en' => 'El Maragha', 'ar' => 'المراغة'], 'area_id' => $sohagId],
            ['name' => ['en' => 'El Balyana', 'ar' => 'البلينا'], 'area_id' => $sohagId],
            ['name' => ['en' => 'Dar El Salam', 'ar' => 'دار السلام'], 'area_id' => $sohagId],
            ['name' => ['en' => 'Tima', 'ar' => 'طما'], 'area_id' => $sohagId],
            ['name' => ['en' => 'Sakolta', 'ar' => 'ساقلتة'], 'area_id' => $sohagId],
            ['name' => ['en' => 'El Monsha', 'ar' => 'المنشاة'], 'area_id' => $sohagId],
            ['name' => ['en' => 'Gaheina', 'ar' => 'جهينة'], 'area_id' => $sohagId],

            // قنا
            ['name' => ['en' => 'Qena City', 'ar' => 'مدينة قنا'], 'area_id' => $qenaId],
            ['name' => ['en' => 'Nag Hammadi', 'ar' => 'نجع حمادي'], 'area_id' => $qenaId],
            ['name' => ['en' => 'Qus', 'ar' => 'قوص'], 'area_id' => $qenaId],
            ['name' => ['en' => 'Dishna', 'ar' => 'دشنا'], 'area_id' => $qenaId],
            ['name' => ['en' => 'Abu Tisht', 'ar' => 'أبو تشت'], 'area_id' => $qenaId],
            ['name' => ['en' => 'Farshut', 'ar' => 'فرشوط'], 'area_id' => $qenaId],
            ['name' => ['en' => 'Naqada', 'ar' => 'نقادة'], 'area_id' => $qenaId],
            ['name' => ['en' => 'El Waqf', 'ar' => 'الوقف'], 'area_id' => $qenaId],
            ['name' => ['en' => 'Qeft', 'ar' => 'قفط'], 'area_id' => $qenaId],

            // الأقصر
            ['name' => ['en' => 'Luxor City', 'ar' => 'مدينة الأقصر'], 'area_id' => $luxorId],
            ['name' => ['en' => 'Armant', 'ar' => 'أرمنت'], 'area_id' => $luxorId],
            ['name' => ['en' => 'Esna', 'ar' => 'إسنا'], 'area_id' => $luxorId],
            ['name' => ['en' => 'El Qurna', 'ar' => 'القرنة'], 'area_id' => $luxorId],
            ['name' => ['en' => 'El Bayadiya', 'ar' => 'البياضية'], 'area_id' => $luxorId],
            ['name' => ['en' => 'El Tod', 'ar' => 'الطود'], 'area_id' => $luxorId],
            ['name' => ['en' => 'El Zeiniya', 'ar' => 'الزينية'], 'area_id' => $luxorId],

            // أسوان
            ['name' => ['en' => 'Aswan City', 'ar' => 'مدينة أسوان'], 'area_id' => $aswanId],
            ['name' => ['en' => 'Edfu', 'ar' => 'إدفو'], 'area_id' => $aswanId],
            ['name' => ['en' => 'Kom Ombo', 'ar' => 'كوم أمبو'], 'area_id' => $aswanId],
            ['name' => ['en' => 'Daraw', 'ar' => 'دراو'], 'area_id' => $aswanId],
            ['name' => ['en' => 'Nasr El Nuba', 'ar' => 'نصر النوبة'], 'area_id' => $aswanId],
            ['name' => ['en' => 'Abu Simbel', 'ar' => 'أبو سمبل'], 'area_id' => $aswanId],

            // الوادي الجديد
            ['name' => ['en' => 'El Kharga', 'ar' => 'الخارجة'], 'area_id' => $newValleyId],
            ['name' => ['en' => 'El Dakhla', 'ar' => 'الداخلة'], 'area_id' => $newValleyId],
            ['name' => ['en' => 'Farafra', 'ar' => 'الفرافرة'], 'area_id' => $newValleyId],
            ['name' => ['en' => 'Baris', 'ar' => 'باريس'], 'area_id' => $newValleyId],
            ['name' => ['en' => 'Balat', 'ar' => 'بلاط'], 'area_id' => $newValleyId],

            // السعودية
            ['name' => ['en' => 'Al Olaya', 'ar' => 'العليا'], 'area_id' => $riyadhId],
            ['name' => ['en' => 'Al Balad', 'ar' => 'البلد'], 'area_id' => $jeddahId],

            // الإمارات
            ['name' => ['en' => 'Business Bay', 'ar' => 'الخليج التجاري'], 'area_id' => $dubaiId],
            ['name' => ['en' => 'Corniche', 'ar' => 'الكورنيش'], 'area_id' => $abudhabiId],
        ];

        foreach ($subAreas as $subArea) {
            SubArea::create($subArea);
        }
    }
}
