<?php

namespace Database\Seeders;

use App\Models\Consultant;
use App\Models\ConsultantReview;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class ConsultantReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            ['title' => 'تجربة ممتازة في شراء شقتي', 'comment' => 'ساعدني في إيجاد الشقة المثالية في التجمع الخامس. كان صبوراً جداً وأجاب على كل أسئلتي بوضوح. أنصح الجميع بالتعامل معه.'],
            ['title' => 'محترف وسريع الاستجابة', 'comment' => 'تواصل معي خلال دقائق من إرسال استفساري. زرنا عدة مشاريع في يوم واحد وساعدني في اختيار الأنسب لميزانيتي.'],
            ['title' => 'خبرة واسعة في السوق العقاري', 'comment' => 'يعرف كل تفاصيل المنطقة والأسعار الحقيقية. لم يحاول أن يبيعني شيئاً لا أحتاجه. شخص أمين وموثوق.'],
            ['title' => 'أفضل وكيل عقاري تعاملت معه', 'comment' => 'من أول اتصال وحتى استلام الشقة، كان معي خطوة بخطوة. ساعدني في التفاوض وحصلت على سعر أقل بكثير من المعروض.'],
            ['title' => 'خدمة عملاء لا مثيل لها', 'comment' => 'حتى بعد إتمام عملية الشراء، استمر في متابعة ملفي والتأكد من أن كل شيء يسير بشكل صحيح. خدمة ما بعد البيع ممتازة.'],
            ['title' => 'معرفة محلية عميقة', 'comment' => 'يعرف كل كمبوند ومشروع في المنطقة. أعطاني مقارنة تفصيلية بين عدة مشاريع مما ساعدني في اتخاذ القرار الصحيح.'],
            ['title' => 'تفاوض محترف', 'comment' => 'استطاع أن يحصل لي على خصم 15% من السعر الأصلي مع خطة تقسيط مريحة. مهارات تفاوض استثنائية.'],
            ['title' => 'جولة ممتازة ومنظمة', 'comment' => 'نظم لي جولة في أربعة مشاريع مختلفة في يوم واحد. كل شيء كان مرتباً ومنظماً. وفر علي الكثير من الوقت والجهد.'],
            ['title' => 'نصائح قيمة للاستثمار', 'comment' => 'لم يكن مجرد بائع بل كان مستشاراً حقيقياً. نصحني بالمنطقة الأفضل للاستثمار وكان على حق - قيمة العقار ارتفعت بشكل كبير.'],
            ['title' => 'سرعة في إنجاز الإجراءات', 'comment' => 'أنهى كل الأوراق والإجراءات القانونية في وقت قياسي. لم أضطر للانتظار طويلاً وكل شيء تم بسلاسة.'],
            ['title' => 'Good experience overall', 'comment' => 'Very professional and knowledgeable about the real estate market in New Cairo. Helped me find exactly what I was looking for within my budget.'],
            ['title' => 'Highly recommended agent', 'comment' => 'From the first meeting to the final signing, everything was smooth and professional. Great attention to detail and always available for questions.'],
            ['title' => 'تعامل راقي ومحترم', 'comment' => 'من أكثر الأشخاص احتراماً في التعامل. يستمع لمتطلباتك بعناية ويقدم لك الخيارات المناسبة بدون ضغط.'],
            ['title' => 'ساعدني في إيجاد فيلا أحلامي', 'comment' => 'كنت أبحث عن فيلا مستقلة في مدينتي وبعد أسبوع واحد فقط وجد لي الفيلا المثالية بسعر معقول جداً.'],
            ['title' => 'Excellent negotiation skills', 'comment' => 'Managed to get us a 20% discount plus a better payment plan. His market knowledge gave us a significant advantage in negotiations.'],
            ['title' => 'متابعة مستمرة بعد البيع', 'comment' => 'لم ينسني بعد إتمام الصفقة. يتابع معي باستمرار ويساعدني في أي مشكلة تواجهني مع الإدارة.'],
            ['title' => 'خبرة في الإجراءات القانونية', 'comment' => 'يفهم كل التفاصيل القانونية والعقود. راجع كل بند في العقد معي وشرح لي حقوقي بوضوح تام.'],
            ['title' => 'Very responsive and helpful', 'comment' => 'Always answers calls and messages promptly. Organized multiple property visits on short notice. Made the whole process stress-free.'],
            ['title' => 'أمانة وصدق في التعامل', 'comment' => 'لم يخفِ أي عيوب في العقارات التي عرضها علي. كان صادقاً تماماً مما جعلني أثق به أكثر.'],
            ['title' => 'مرونة في المواعيد', 'comment' => 'تواجد معي في عطلة نهاية الأسبوع وحتى في المساء لأن عملي لا يسمح لي بالتفرغ في أيام العمل. مرونة رائعة.'],
            ['title' => 'Professional market analysis', 'comment' => 'Provided detailed market analysis and price comparisons for different areas. His data-driven approach helped us make an informed decision.'],
            ['title' => 'توصيات دقيقة وموفقة', 'comment' => 'كل العقارات التي رشحها لي كانت مطابقة تماماً لمتطلباتي. يبدو أنه يستمع جيداً ويفهم احتياجات العميل.'],
            ['title' => 'ساعدني في خطة التقسيط', 'comment' => 'شرح لي كل خطط التقسيط المتاحة وساعدني في اختيار الأنسب. حتى تواصل مع البنك نيابة عني لتسهيل الإجراءات.'],
            ['title' => 'Great local knowledge', 'comment' => 'Knows every compound, developer, and upcoming project in the area. His insider knowledge about future developments helped us choose a property with great appreciation potential.'],
            ['title' => 'تجربة شراء سلسة', 'comment' => 'من البداية للنهاية كانت تجربة سلسة ومريحة. لم أشعر بأي توتر أو قلق لأنه كان يدير كل شيء باحترافية.'],
            ['title' => 'يهتم بأدق التفاصيل', 'comment' => 'لاحظ عيوباً في الشقة لم أكن لأنتبه لها. أصر على أن يتم إصلاحها قبل التسليم. شخص يهتم فعلاً بمصلحة العميل.'],
            ['title' => 'Outstanding after-sales support', 'comment' => 'Even months after the purchase, he helped me with administrative issues and connected me with reliable contractors for renovations.'],
            ['title' => 'خيار ممتاز للمستثمرين', 'comment' => 'يفهم سوق الاستثمار العقاري بعمق. ساعدني في بناء محفظة عقارية متنوعة ومربحة. عوائد الإيجار ممتازة.'],
            ['title' => 'سعيد جداً بالنتيجة', 'comment' => 'الشقة التي اشتريتها بمساعدته كانت أفضل قرار اتخذته. الموقع ممتاز والسعر كان مناسباً جداً مقارنة بالسوق.'],
            ['title' => 'Trustworthy and reliable', 'comment' => 'In a market full of unreliable agents, finding someone you can trust is invaluable. Delivered on every promise and exceeded expectations.'],
        ];

        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $consultants = Consultant::all();

        foreach ($consultants as $consultant) {
            $shuffledReviews = collect($reviews)->shuffle();

            foreach ($shuffledReviews as $index => $reviewData) {
                $user = $users->random();

                $overallRating = fake()->numberBetween(3, 5);
                $variance = fn () => max(1, min(5, $overallRating + fake()->numberBetween(-1, 1)));

                ConsultantReview::create([
                    'consultant_id' => $consultant->id,
                    'user_id' => $user->id,
                    'title' => $reviewData['title'],
                    'comment' => $reviewData['comment'],
                    'overall_rating' => $overallRating,
                    'local_knowledge_rating' => $variance(),
                    'process_expertise_rating' => $variance(),
                    'response_speed_rating' => $variance(),
                    'negotiation_skills_rating' => $variance(),
                    'created_at' => now()->subDays(fake()->numberBetween(1, 365)),
                ]);
            }
        }
    }
}
