<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cairoId = State::where('name->en', 'Cairo')->value('id');
        $gizaId = State::where('name->en', 'Giza')->value('id');
        $alexandriaId = State::where('name->en', 'Alexandria')->value('id');
        $qalyubiaId = State::where('name->en', 'Qalyubia')->value('id');
        $dakahliaId = State::where('name->en', 'Dakahlia')->value('id');
        $sharqiaId = State::where('name->en', 'Sharqia')->value('id');
        $gharbiaId = State::where('name->en', 'Gharbia')->value('id');
        $menofiaId = State::where('name->en', 'Menofia')->value('id');
        $kafrElSheikhId = State::where('name->en', 'Kafr El Sheikh')->value('id');
        $damiettaId = State::where('name->en', 'Damietta')->value('id');
        $portSaidId = State::where('name->en', 'Port Said')->value('id');
        $ismailiaId = State::where('name->en', 'Ismailia')->value('id');
        $suezId = State::where('name->en', 'Suez')->value('id');
        $beheiraId = State::where('name->en', 'Beheira')->value('id');
        $matrouhId = State::where('name->en', 'Matrouh')->value('id');
        $northSinaiId = State::where('name->en', 'North Sinai')->value('id');
        $southSinaiId = State::where('name->en', 'South Sinai')->value('id');
        $redSeaId = State::where('name->en', 'Red Sea')->value('id');
        $fayoumId = State::where('name->en', 'Fayoum')->value('id');
        $beniSuefId = State::where('name->en', 'Beni Suef')->value('id');
        $minyaId = State::where('name->en', 'Minya')->value('id');
        $assiutId = State::where('name->en', 'Assiut')->value('id');
        $sohagId = State::where('name->en', 'Sohag')->value('id');
        $qenaId = State::where('name->en', 'Qena')->value('id');
        $luxorId = State::where('name->en', 'Luxor')->value('id');
        $aswanId = State::where('name->en', 'Aswan')->value('id');
        $newValleyId = State::where('name->en', 'New Valley')->value('id');

        $riyadhId = State::where('name->en', 'Riyadh')->value('id');
        $jeddahId = State::where('name->en', 'Jeddah')->value('id');
        $dubaiId = State::where('name->en', 'Dubai')->value('id');
        $abudhabiId = State::where('name->en', 'Abu Dhabi')->value('id');

        $cities = [
            // القاهرة
            ['name' => ['en' => 'Nasr City', 'ar' => 'مدينة نصر'], 'state_id' => $cairoId],
            ['name' => ['en' => 'Maadi', 'ar' => 'المعادي'], 'state_id' => $cairoId],
            ['name' => ['en' => 'Heliopolis', 'ar' => 'مصر الجديدة'], 'state_id' => $cairoId],
            ['name' => ['en' => 'Zamalek', 'ar' => 'الزمالك'], 'state_id' => $cairoId],
            ['name' => ['en' => 'New Cairo', 'ar' => 'القاهرة الجديدة'], 'state_id' => $cairoId],
            ['name' => ['en' => 'Shubra', 'ar' => 'شبرا'], 'state_id' => $cairoId],
            ['name' => ['en' => 'Ain Shams', 'ar' => 'عين شمس'], 'state_id' => $cairoId],
            ['name' => ['en' => 'El Marg', 'ar' => 'المرج'], 'state_id' => $cairoId],
            ['name' => ['en' => 'El Matariya', 'ar' => 'المطرية'], 'state_id' => $cairoId],
            ['name' => ['en' => 'El Basatin', 'ar' => 'البساتين'], 'state_id' => $cairoId],
            ['name' => ['en' => 'El Sayeda Zeinab', 'ar' => 'السيدة زينب'], 'state_id' => $cairoId],
            ['name' => ['en' => 'Downtown Cairo', 'ar' => 'وسط البلد'], 'state_id' => $cairoId],
            ['name' => ['en' => 'El Nozha', 'ar' => 'النزهة'], 'state_id' => $cairoId],
            ['name' => ['en' => 'El Salam', 'ar' => 'السلام'], 'state_id' => $cairoId],
            ['name' => ['en' => 'El Tebbin', 'ar' => 'التبين'], 'state_id' => $cairoId],
            ['name' => ['en' => '15th of May', 'ar' => '15 مايو'], 'state_id' => $cairoId],
            ['name' => ['en' => 'El Khalifa', 'ar' => 'الخليفة'], 'state_id' => $cairoId],
            ['name' => ['en' => 'El Shorouk', 'ar' => 'الشروق'], 'state_id' => $cairoId],
            ['name' => ['en' => 'Badr City', 'ar' => 'مدينة بدر'], 'state_id' => $cairoId],
            ['name' => ['en' => 'El Obour', 'ar' => 'العبور'], 'state_id' => $cairoId],

            // الجيزة
            ['name' => ['en' => '6th of October', 'ar' => 'السادس من أكتوبر'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Sheikh Zayed', 'ar' => 'الشيخ زايد'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Haram', 'ar' => 'الهرم'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Dokki', 'ar' => 'الدقي'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Mohandessin', 'ar' => 'المهندسين'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Agouza', 'ar' => 'العجوزة'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Imbaba', 'ar' => 'إمبابة'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Faisal', 'ar' => 'فيصل'], 'state_id' => $gizaId],
            ['name' => ['en' => 'El Warraq', 'ar' => 'الوراق'], 'state_id' => $gizaId],
            ['name' => ['en' => 'El Omraniya', 'ar' => 'العمرانية'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Kerdasa', 'ar' => 'كرداسة'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Abu Rawash', 'ar' => 'أبو رواش'], 'state_id' => $gizaId],
            ['name' => ['en' => 'El Badrashein', 'ar' => 'البدرشين'], 'state_id' => $gizaId],
            ['name' => ['en' => 'El Saff', 'ar' => 'الصف'], 'state_id' => $gizaId],
            ['name' => ['en' => 'El Ayyat', 'ar' => 'العياط'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Atfih', 'ar' => 'أطفيح'], 'state_id' => $gizaId],
            ['name' => ['en' => 'El Hawamdia', 'ar' => 'الحوامدية'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Hadayek El Ahram', 'ar' => 'حدائق الأهرام'], 'state_id' => $gizaId],

            // الإسكندرية
            ['name' => ['en' => 'Montaza', 'ar' => 'المنتزه'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'El Raml', 'ar' => 'الرمل'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'El Gomrok', 'ar' => 'الجمرك'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'El Attarin', 'ar' => 'العطارين'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'Sidi Gaber', 'ar' => 'سيدي جابر'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'Smouha', 'ar' => 'سموحة'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'Miami', 'ar' => 'ميامي'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'Mandara', 'ar' => 'المندرة'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'Agami', 'ar' => 'العجمي'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'Borg El Arab', 'ar' => 'برج العرب'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'El Amriya', 'ar' => 'العامرية'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'Abu Qir', 'ar' => 'أبو قير'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'Bahary', 'ar' => 'بحري'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'Karmouz', 'ar' => 'كرموز'], 'state_id' => $alexandriaId],
            ['name' => ['en' => 'Moharam Bey', 'ar' => 'محرم بك'], 'state_id' => $alexandriaId],

            // القليوبية
            ['name' => ['en' => 'Banha', 'ar' => 'بنها'], 'state_id' => $qalyubiaId],
            ['name' => ['en' => 'Qalyub', 'ar' => 'قليوب'], 'state_id' => $qalyubiaId],
            ['name' => ['en' => 'Shubra El Kheima', 'ar' => 'شبرا الخيمة'], 'state_id' => $qalyubiaId],
            ['name' => ['en' => 'El Khanka', 'ar' => 'الخانكة'], 'state_id' => $qalyubiaId],
            ['name' => ['en' => 'El Qanater El Khairiya', 'ar' => 'القناطر الخيرية'], 'state_id' => $qalyubiaId],
            ['name' => ['en' => 'Toukh', 'ar' => 'طوخ'], 'state_id' => $qalyubiaId],
            ['name' => ['en' => 'Kafr Shukr', 'ar' => 'كفر شكر'], 'state_id' => $qalyubiaId],
            ['name' => ['en' => 'Shibin El Qanater', 'ar' => 'شبين القناطر'], 'state_id' => $qalyubiaId],

            // الدقهلية
            ['name' => ['en' => 'Mansoura', 'ar' => 'المنصورة'], 'state_id' => $dakahliaId],
            ['name' => ['en' => 'Talkha', 'ar' => 'طلخا'], 'state_id' => $dakahliaId],
            ['name' => ['en' => 'Mit Ghamr', 'ar' => 'ميت غمر'], 'state_id' => $dakahliaId],
            ['name' => ['en' => 'Dekernes', 'ar' => 'دكرنس'], 'state_id' => $dakahliaId],
            ['name' => ['en' => 'Aga', 'ar' => 'أجا'], 'state_id' => $dakahliaId],
            ['name' => ['en' => 'Belqas', 'ar' => 'بلقاس'], 'state_id' => $dakahliaId],
            ['name' => ['en' => 'Shirbin', 'ar' => 'شربين'], 'state_id' => $dakahliaId],
            ['name' => ['en' => 'Manzala', 'ar' => 'المنزلة'], 'state_id' => $dakahliaId],
            ['name' => ['en' => 'El Senbellawein', 'ar' => 'السنبلاوين'], 'state_id' => $dakahliaId],
            ['name' => ['en' => 'Bani Ebeid', 'ar' => 'بني عبيد'], 'state_id' => $dakahliaId],
            ['name' => ['en' => 'El Gamaliya', 'ar' => 'الجمالية'], 'state_id' => $dakahliaId],
            ['name' => ['en' => 'Temay El Amdid', 'ar' => 'تمي الأمديد'], 'state_id' => $dakahliaId],
            ['name' => ['en' => 'Gamasa', 'ar' => 'جمصة'], 'state_id' => $dakahliaId],

            // الشرقية
            ['name' => ['en' => 'Zagazig', 'ar' => 'الزقازيق'], 'state_id' => $sharqiaId],
            ['name' => ['en' => 'Belbeis', 'ar' => 'بلبيس'], 'state_id' => $sharqiaId],
            ['name' => ['en' => '10th of Ramadan', 'ar' => 'العاشر من رمضان'], 'state_id' => $sharqiaId],
            ['name' => ['en' => 'Minya El Qamh', 'ar' => 'منيا القمح'], 'state_id' => $sharqiaId],
            ['name' => ['en' => 'Abu Hammad', 'ar' => 'أبو حماد'], 'state_id' => $sharqiaId],
            ['name' => ['en' => 'Faqous', 'ar' => 'فاقوس'], 'state_id' => $sharqiaId],
            ['name' => ['en' => 'Hihya', 'ar' => 'ههيا'], 'state_id' => $sharqiaId],
            ['name' => ['en' => 'Abu Kebir', 'ar' => 'أبو كبير'], 'state_id' => $sharqiaId],
            ['name' => ['en' => 'Kafr Saqr', 'ar' => 'كفر صقر'], 'state_id' => $sharqiaId],
            ['name' => ['en' => 'Awlad Saqr', 'ar' => 'أولاد صقر'], 'state_id' => $sharqiaId],
            ['name' => ['en' => 'El Husseiniya', 'ar' => 'الحسينية'], 'state_id' => $sharqiaId],
            ['name' => ['en' => 'Diarb Negm', 'ar' => 'ديرب نجم'], 'state_id' => $sharqiaId],
            ['name' => ['en' => 'Mashtool El Souk', 'ar' => 'مشتول السوق'], 'state_id' => $sharqiaId],
            ['name' => ['en' => 'El Ibrahimiya', 'ar' => 'الإبراهيمية'], 'state_id' => $sharqiaId],

            // الغربية
            ['name' => ['en' => 'Tanta', 'ar' => 'طنطا'], 'state_id' => $gharbiaId],
            ['name' => ['en' => 'El Mahalla El Kubra', 'ar' => 'المحلة الكبرى'], 'state_id' => $gharbiaId],
            ['name' => ['en' => 'Kafr El Zayat', 'ar' => 'كفر الزيات'], 'state_id' => $gharbiaId],
            ['name' => ['en' => 'Zefta', 'ar' => 'زفتى'], 'state_id' => $gharbiaId],
            ['name' => ['en' => 'Samanoud', 'ar' => 'سمنود'], 'state_id' => $gharbiaId],
            ['name' => ['en' => 'Basyoun', 'ar' => 'بسيون'], 'state_id' => $gharbiaId],
            ['name' => ['en' => 'El Santa', 'ar' => 'السنطة'], 'state_id' => $gharbiaId],
            ['name' => ['en' => 'Kotoor', 'ar' => 'قطور'], 'state_id' => $gharbiaId],

            // المنوفية
            ['name' => ['en' => 'Shibin El Kom', 'ar' => 'شبين الكوم'], 'state_id' => $menofiaId],
            ['name' => ['en' => 'Menouf', 'ar' => 'منوف'], 'state_id' => $menofiaId],
            ['name' => ['en' => 'Ashmoun', 'ar' => 'أشمون'], 'state_id' => $menofiaId],
            ['name' => ['en' => 'El Bagour', 'ar' => 'الباجور'], 'state_id' => $menofiaId],
            ['name' => ['en' => 'Quesna', 'ar' => 'قويسنا'], 'state_id' => $menofiaId],
            ['name' => ['en' => 'Berket El Sabaa', 'ar' => 'بركة السبع'], 'state_id' => $menofiaId],
            ['name' => ['en' => 'Tala', 'ar' => 'تلا'], 'state_id' => $menofiaId],
            ['name' => ['en' => 'El Shohada', 'ar' => 'الشهداء'], 'state_id' => $menofiaId],
            ['name' => ['en' => 'Sers El Layan', 'ar' => 'سرس الليان'], 'state_id' => $menofiaId],
            ['name' => ['en' => 'Sadat City', 'ar' => 'مدينة السادات'], 'state_id' => $menofiaId],

            // كفر الشيخ
            ['name' => ['en' => 'Kafr El Sheikh City', 'ar' => 'مدينة كفر الشيخ'], 'state_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Desouk', 'ar' => 'دسوق'], 'state_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Fuwwah', 'ar' => 'فوه'], 'state_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Baltim', 'ar' => 'بلطيم'], 'state_id' => $kafrElSheikhId],
            ['name' => ['en' => 'El Burullus', 'ar' => 'البرلس'], 'state_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Metobas', 'ar' => 'مطوبس'], 'state_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Sidi Salem', 'ar' => 'سيدي سالم'], 'state_id' => $kafrElSheikhId],
            ['name' => ['en' => 'El Hamoul', 'ar' => 'الحامول'], 'state_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Biyala', 'ar' => 'بيلا'], 'state_id' => $kafrElSheikhId],
            ['name' => ['en' => 'Qallin', 'ar' => 'قلين'], 'state_id' => $kafrElSheikhId],
            ['name' => ['en' => 'El Riyad', 'ar' => 'الرياض'], 'state_id' => $kafrElSheikhId],

            // دمياط
            ['name' => ['en' => 'Damietta City', 'ar' => 'مدينة دمياط'], 'state_id' => $damiettaId],
            ['name' => ['en' => 'New Damietta', 'ar' => 'دمياط الجديدة'], 'state_id' => $damiettaId],
            ['name' => ['en' => 'Ras El Bar', 'ar' => 'رأس البر'], 'state_id' => $damiettaId],
            ['name' => ['en' => 'Fareskour', 'ar' => 'فارسكور'], 'state_id' => $damiettaId],
            ['name' => ['en' => 'El Zarqa', 'ar' => 'الزرقا'], 'state_id' => $damiettaId],
            ['name' => ['en' => 'Kafr Saad', 'ar' => 'كفر سعد'], 'state_id' => $damiettaId],
            ['name' => ['en' => 'Kafr El Battikh', 'ar' => 'كفر البطيخ'], 'state_id' => $damiettaId],

            // بورسعيد
            ['name' => ['en' => 'Port Fouad', 'ar' => 'بور فؤاد'], 'state_id' => $portSaidId],
            ['name' => ['en' => 'El Arab', 'ar' => 'العرب'], 'state_id' => $portSaidId],
            ['name' => ['en' => 'El Manakh', 'ar' => 'المناخ'], 'state_id' => $portSaidId],
            ['name' => ['en' => 'El Dawahi', 'ar' => 'الضواحي'], 'state_id' => $portSaidId],
            ['name' => ['en' => 'El Zohour', 'ar' => 'الزهور'], 'state_id' => $portSaidId],
            ['name' => ['en' => 'El Ganoub', 'ar' => 'الجنوب'], 'state_id' => $portSaidId],

            // الإسماعيلية
            ['name' => ['en' => 'Ismailia City', 'ar' => 'مدينة الإسماعيلية'], 'state_id' => $ismailiaId],
            ['name' => ['en' => 'Fayed', 'ar' => 'فايد'], 'state_id' => $ismailiaId],
            ['name' => ['en' => 'El Qantara East', 'ar' => 'القنطرة شرق'], 'state_id' => $ismailiaId],
            ['name' => ['en' => 'El Qantara West', 'ar' => 'القنطرة غرب'], 'state_id' => $ismailiaId],
            ['name' => ['en' => 'Abu Sultan', 'ar' => 'أبو سلطان'], 'state_id' => $ismailiaId],
            ['name' => ['en' => 'El Tal El Kebir', 'ar' => 'التل الكبير'], 'state_id' => $ismailiaId],
            ['name' => ['en' => 'El Qassassin', 'ar' => 'القصاصين'], 'state_id' => $ismailiaId],

            // السويس
            ['name' => ['en' => 'Suez City', 'ar' => 'مدينة السويس'], 'state_id' => $suezId],
            ['name' => ['en' => 'El Arbaeen', 'ar' => 'الأربعين'], 'state_id' => $suezId],
            ['name' => ['en' => 'Ataka', 'ar' => 'عتاقة'], 'state_id' => $suezId],
            ['name' => ['en' => 'El Ganayen', 'ar' => 'الجناين'], 'state_id' => $suezId],
            ['name' => ['en' => 'Faisal', 'ar' => 'فيصل'], 'state_id' => $suezId],

            // البحيرة
            ['name' => ['en' => 'Damanhour', 'ar' => 'دمنهور'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'Kafr El Dawwar', 'ar' => 'كفر الدوار'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'Rashid', 'ar' => 'رشيد'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'Edku', 'ar' => 'إدكو'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'Abu Hummus', 'ar' => 'أبو حمص'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'El Mahmoudiya', 'ar' => 'المحمودية'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'Itay El Barud', 'ar' => 'إيتاي البارود'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'Hosh Eissa', 'ar' => 'حوش عيسى'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'Shubrakhit', 'ar' => 'شبراخيت'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'Kom Hamada', 'ar' => 'كوم حمادة'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'El Delengat', 'ar' => 'الدلنجات'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'El Rahmaniya', 'ar' => 'الرحمانية'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'Abu El Matamir', 'ar' => 'أبو المطامير'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'Wadi El Natrun', 'ar' => 'وادي النطرون'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'El Nubariya', 'ar' => 'النوبارية'], 'state_id' => $beheiraId],
            ['name' => ['en' => 'Badr', 'ar' => 'بدر'], 'state_id' => $beheiraId],

            // مطروح
            ['name' => ['en' => 'Marsa Matrouh', 'ar' => 'مرسى مطروح'], 'state_id' => $matrouhId],
            ['name' => ['en' => 'El Alamein', 'ar' => 'العلمين'], 'state_id' => $matrouhId],
            ['name' => ['en' => 'Siwa', 'ar' => 'سيوة'], 'state_id' => $matrouhId],
            ['name' => ['en' => 'El Dabaa', 'ar' => 'الضبعة'], 'state_id' => $matrouhId],
            ['name' => ['en' => 'El Hamam', 'ar' => 'الحمام'], 'state_id' => $matrouhId],
            ['name' => ['en' => 'El Negaila', 'ar' => 'النجيلة'], 'state_id' => $matrouhId],
            ['name' => ['en' => 'Sidi Barrani', 'ar' => 'سيدي براني'], 'state_id' => $matrouhId],
            ['name' => ['en' => 'El Salloum', 'ar' => 'السلوم'], 'state_id' => $matrouhId],

            // شمال سيناء
            ['name' => ['en' => 'El Arish', 'ar' => 'العريش'], 'state_id' => $northSinaiId],
            ['name' => ['en' => 'Sheikh Zuweid', 'ar' => 'الشيخ زويد'], 'state_id' => $northSinaiId],
            ['name' => ['en' => 'Rafah', 'ar' => 'رفح'], 'state_id' => $northSinaiId],
            ['name' => ['en' => 'Bir El Abd', 'ar' => 'بئر العبد'], 'state_id' => $northSinaiId],
            ['name' => ['en' => 'El Hassana', 'ar' => 'الحسنة'], 'state_id' => $northSinaiId],
            ['name' => ['en' => 'Nakhl', 'ar' => 'نخل'], 'state_id' => $northSinaiId],

            // جنوب سيناء
            ['name' => ['en' => 'Sharm El Sheikh', 'ar' => 'شرم الشيخ'], 'state_id' => $southSinaiId],
            ['name' => ['en' => 'Dahab', 'ar' => 'دهب'], 'state_id' => $southSinaiId],
            ['name' => ['en' => 'Nuweiba', 'ar' => 'نويبع'], 'state_id' => $southSinaiId],
            ['name' => ['en' => 'Taba', 'ar' => 'طابا'], 'state_id' => $southSinaiId],
            ['name' => ['en' => 'Saint Catherine', 'ar' => 'سانت كاترين'], 'state_id' => $southSinaiId],
            ['name' => ['en' => 'El Tor', 'ar' => 'الطور'], 'state_id' => $southSinaiId],
            ['name' => ['en' => 'Abu Redeis', 'ar' => 'أبو رديس'], 'state_id' => $southSinaiId],
            ['name' => ['en' => 'Abu Zenima', 'ar' => 'أبو زنيمة'], 'state_id' => $southSinaiId],
            ['name' => ['en' => 'Ras Sidr', 'ar' => 'رأس سدر'], 'state_id' => $southSinaiId],

            // البحر الأحمر
            ['name' => ['en' => 'Hurghada', 'ar' => 'الغردقة'], 'state_id' => $redSeaId],
            ['name' => ['en' => 'Safaga', 'ar' => 'سفاجا'], 'state_id' => $redSeaId],
            ['name' => ['en' => 'El Quseir', 'ar' => 'القصير'], 'state_id' => $redSeaId],
            ['name' => ['en' => 'Marsa Alam', 'ar' => 'مرسى علم'], 'state_id' => $redSeaId],
            ['name' => ['en' => 'Ras Ghareb', 'ar' => 'رأس غارب'], 'state_id' => $redSeaId],
            ['name' => ['en' => 'Shalateen', 'ar' => 'شلاتين'], 'state_id' => $redSeaId],
            ['name' => ['en' => 'Halayeb', 'ar' => 'حلايب'], 'state_id' => $redSeaId],
            ['name' => ['en' => 'El Gouna', 'ar' => 'الجونة'], 'state_id' => $redSeaId],

            // الفيوم
            ['name' => ['en' => 'Fayoum City', 'ar' => 'مدينة الفيوم'], 'state_id' => $fayoumId],
            ['name' => ['en' => 'Ibsheway', 'ar' => 'إبشواي'], 'state_id' => $fayoumId],
            ['name' => ['en' => 'Itsa', 'ar' => 'إطسا'], 'state_id' => $fayoumId],
            ['name' => ['en' => 'Tamiya', 'ar' => 'طامية'], 'state_id' => $fayoumId],
            ['name' => ['en' => 'Sinnuris', 'ar' => 'سنورس'], 'state_id' => $fayoumId],
            ['name' => ['en' => 'Yousef El Seddik', 'ar' => 'يوسف الصديق'], 'state_id' => $fayoumId],

            // بني سويف
            ['name' => ['en' => 'Beni Suef City', 'ar' => 'مدينة بني سويف'], 'state_id' => $beniSuefId],
            ['name' => ['en' => 'El Wasta', 'ar' => 'الواسطى'], 'state_id' => $beniSuefId],
            ['name' => ['en' => 'Nasser', 'ar' => 'ناصر'], 'state_id' => $beniSuefId],
            ['name' => ['en' => 'Ehnasia', 'ar' => 'إهناسيا'], 'state_id' => $beniSuefId],
            ['name' => ['en' => 'Beba', 'ar' => 'ببا'], 'state_id' => $beniSuefId],
            ['name' => ['en' => 'Somosta', 'ar' => 'سمسطا'], 'state_id' => $beniSuefId],
            ['name' => ['en' => 'El Fashn', 'ar' => 'الفشن'], 'state_id' => $beniSuefId],

            // المنيا
            ['name' => ['en' => 'Minya City', 'ar' => 'مدينة المنيا'], 'state_id' => $minyaId],
            ['name' => ['en' => 'Mallawi', 'ar' => 'ملوي'], 'state_id' => $minyaId],
            ['name' => ['en' => 'Abu Qurqas', 'ar' => 'أبو قرقاص'], 'state_id' => $minyaId],
            ['name' => ['en' => 'Samalut', 'ar' => 'سمالوط'], 'state_id' => $minyaId],
            ['name' => ['en' => 'El Edwa', 'ar' => 'العدوة'], 'state_id' => $minyaId],
            ['name' => ['en' => 'Matai', 'ar' => 'مطاي'], 'state_id' => $minyaId],
            ['name' => ['en' => 'Beni Mazar', 'ar' => 'بني مزار'], 'state_id' => $minyaId],
            ['name' => ['en' => 'Maghagha', 'ar' => 'مغاغة'], 'state_id' => $minyaId],
            ['name' => ['en' => 'Deir Mawas', 'ar' => 'دير مواس'], 'state_id' => $minyaId],

            // أسيوط
            ['name' => ['en' => 'Assiut City', 'ar' => 'مدينة أسيوط'], 'state_id' => $assiutId],
            ['name' => ['en' => 'Manfalut', 'ar' => 'منفلوط'], 'state_id' => $assiutId],
            ['name' => ['en' => 'Abu Tig', 'ar' => 'أبو تيج'], 'state_id' => $assiutId],
            ['name' => ['en' => 'El Qusiya', 'ar' => 'القوصية'], 'state_id' => $assiutId],
            ['name' => ['en' => 'Dayrut', 'ar' => 'ديروط'], 'state_id' => $assiutId],
            ['name' => ['en' => 'Abnob', 'ar' => 'أبنوب'], 'state_id' => $assiutId],
            ['name' => ['en' => 'Sahel Selim', 'ar' => 'ساحل سليم'], 'state_id' => $assiutId],
            ['name' => ['en' => 'El Badari', 'ar' => 'البداري'], 'state_id' => $assiutId],
            ['name' => ['en' => 'El Ghanayem', 'ar' => 'الغنايم'], 'state_id' => $assiutId],
            ['name' => ['en' => 'El Fath', 'ar' => 'الفتح'], 'state_id' => $assiutId],
            ['name' => ['en' => 'Sadfa', 'ar' => 'صدفا'], 'state_id' => $assiutId],

            // سوهاج
            ['name' => ['en' => 'Sohag City', 'ar' => 'مدينة سوهاج'], 'state_id' => $sohagId],
            ['name' => ['en' => 'Akhmim', 'ar' => 'أخميم'], 'state_id' => $sohagId],
            ['name' => ['en' => 'Girga', 'ar' => 'جرجا'], 'state_id' => $sohagId],
            ['name' => ['en' => 'Tahta', 'ar' => 'طهطا'], 'state_id' => $sohagId],
            ['name' => ['en' => 'El Maragha', 'ar' => 'المراغة'], 'state_id' => $sohagId],
            ['name' => ['en' => 'El Balyana', 'ar' => 'البلينا'], 'state_id' => $sohagId],
            ['name' => ['en' => 'Dar El Salam', 'ar' => 'دار السلام'], 'state_id' => $sohagId],
            ['name' => ['en' => 'Tima', 'ar' => 'طما'], 'state_id' => $sohagId],
            ['name' => ['en' => 'Sakolta', 'ar' => 'ساقلتة'], 'state_id' => $sohagId],
            ['name' => ['en' => 'El Monsha', 'ar' => 'المنشاة'], 'state_id' => $sohagId],
            ['name' => ['en' => 'Gaheina', 'ar' => 'جهينة'], 'state_id' => $sohagId],

            // قنا
            ['name' => ['en' => 'Qena City', 'ar' => 'مدينة قنا'], 'state_id' => $qenaId],
            ['name' => ['en' => 'Nag Hammadi', 'ar' => 'نجع حمادي'], 'state_id' => $qenaId],
            ['name' => ['en' => 'Qus', 'ar' => 'قوص'], 'state_id' => $qenaId],
            ['name' => ['en' => 'Dishna', 'ar' => 'دشنا'], 'state_id' => $qenaId],
            ['name' => ['en' => 'Abu Tisht', 'ar' => 'أبو تشت'], 'state_id' => $qenaId],
            ['name' => ['en' => 'Farshut', 'ar' => 'فرشوط'], 'state_id' => $qenaId],
            ['name' => ['en' => 'Naqada', 'ar' => 'نقادة'], 'state_id' => $qenaId],
            ['name' => ['en' => 'El Waqf', 'ar' => 'الوقف'], 'state_id' => $qenaId],
            ['name' => ['en' => 'Qeft', 'ar' => 'قفط'], 'state_id' => $qenaId],

            // الأقصر
            ['name' => ['en' => 'Luxor City', 'ar' => 'مدينة الأقصر'], 'state_id' => $luxorId],
            ['name' => ['en' => 'Armant', 'ar' => 'أرمنت'], 'state_id' => $luxorId],
            ['name' => ['en' => 'Esna', 'ar' => 'إسنا'], 'state_id' => $luxorId],
            ['name' => ['en' => 'El Qurna', 'ar' => 'القرنة'], 'state_id' => $luxorId],
            ['name' => ['en' => 'El Bayadiya', 'ar' => 'البياضية'], 'state_id' => $luxorId],
            ['name' => ['en' => 'El Tod', 'ar' => 'الطود'], 'state_id' => $luxorId],
            ['name' => ['en' => 'El Zeiniya', 'ar' => 'الزينية'], 'state_id' => $luxorId],

            // أسوان
            ['name' => ['en' => 'Aswan City', 'ar' => 'مدينة أسوان'], 'state_id' => $aswanId],
            ['name' => ['en' => 'Edfu', 'ar' => 'إدفو'], 'state_id' => $aswanId],
            ['name' => ['en' => 'Kom Ombo', 'ar' => 'كوم أمبو'], 'state_id' => $aswanId],
            ['name' => ['en' => 'Daraw', 'ar' => 'دراو'], 'state_id' => $aswanId],
            ['name' => ['en' => 'Nasr El Nuba', 'ar' => 'نصر النوبة'], 'state_id' => $aswanId],
            ['name' => ['en' => 'Abu Simbel', 'ar' => 'أبو سمبل'], 'state_id' => $aswanId],

            // الوادي الجديد
            ['name' => ['en' => 'El Kharga', 'ar' => 'الخارجة'], 'state_id' => $newValleyId],
            ['name' => ['en' => 'El Dakhla', 'ar' => 'الداخلة'], 'state_id' => $newValleyId],
            ['name' => ['en' => 'Farafra', 'ar' => 'الفرافرة'], 'state_id' => $newValleyId],
            ['name' => ['en' => 'Baris', 'ar' => 'باريس'], 'state_id' => $newValleyId],
            ['name' => ['en' => 'Balat', 'ar' => 'بلاط'], 'state_id' => $newValleyId],

            // السعودية
            ['name' => ['en' => 'Al Olaya', 'ar' => 'العليا'], 'state_id' => $riyadhId],
            ['name' => ['en' => 'Al Balad', 'ar' => 'البلد'], 'state_id' => $jeddahId],

            // الإمارات
            ['name' => ['en' => 'Business Bay', 'ar' => 'الخليج التجاري'], 'state_id' => $dubaiId],
            ['name' => ['en' => 'Corniche', 'ar' => 'الكورنيش'], 'state_id' => $abudhabiId],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
