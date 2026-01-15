<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\User;

class BlogArticleSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::first();
        if (! $author) {
            return;
        }

        $tipsCategory = BlogCategory::where('name', 'Tips')->first();
        $parentsCategory = BlogCategory::where('name', 'For Parents')->first();
        $privateSchoolCategory = BlogCategory::where('name', 'Private Schools')->first();
        $admissionsCategory = BlogCategory::where('name', 'Admissions')->first();
        $examPrepCategory = BlogCategory::where('name', 'Exam Preparation')->first();
        $preschoolCategory = BlogCategory::where('name', 'Preschool Education')->first();
        $internationalCategory = BlogCategory::where('name', 'International Education')->first();
        $activitiesCategory = BlogCategory::where('name', 'Extracurricular Activities')->first();
        $psychologyCategory = BlogCategory::where('name', 'Psychology and Motivation')->first();
        $edtechCategory = BlogCategory::where('name', 'Education Technology')->first();

        $adviceTags = BlogTag::whereIn('name', ['Recommendations', 'School Choice', 'Parent Community'])
            ->pluck('id')
            ->all();
        $examTags = BlogTag::whereIn('name', ['Preparation', 'Exams', 'Motivation'])
            ->pluck('id')
            ->all();
        $supportTags = BlogTag::whereIn('name', ['Psychology', 'Nutrition', 'Safety'])
            ->pluck('id')
            ->all();
        $internationalTags = BlogTag::whereIn('name', ['Relocation', 'Scholarships'])
            ->pluck('id')
            ->all();
        $activityTags = BlogTag::whereIn('name', ['Extracurriculars', 'Curriculum'])
            ->pluck('id')
            ->all();
        $digitalTags = BlogTag::whereIn('name', ['Digital Services', 'Curriculum'])
            ->pluck('id')
            ->all();
        $financeTags = BlogTag::whereIn('name', ['Scholarships', 'School Choice'])
            ->pluck('id')
            ->all();
        $wellbeingTags = BlogTag::whereIn('name', ['Psychology', 'Motivation', 'Parent Community'])
            ->pluck('id')
            ->all();
        $preschoolTags = BlogTag::whereIn('name', ['Preparation', 'Recommendations'])
            ->pluck('id')
            ->all();

        $published = BlogArticle::updateOrCreate(
            ['slug' => 'kak-vybrat-chastnuyu-shkolu'],
            [
                'title' => 'Как выбрать частную школу: чек-лист для семьи',
                'slug' => 'kak-vybrat-chastnuyu-shkolu',
                'excerpt' => 'Критерии, которые помогут сравнить частные школы и выбрать подходящую.',
                'content' => <<<HTML
<h2>Определите приоритеты семьи</h2>
<p>Сначала сформулируйте, что для вас важно: углубленные предметы, небольшие классы, проектное обучение или насыщенная внеурочная жизнь. Составьте короткий список критериев, чтобы оценки школ были объективными.</p>
<h3>Проверьте образовательную программу</h3>
<ul>
    <li>Уточните, какие профили доступны с 8–9 класса.</li>
    <li>Посмотрите, сколько часов отведено на ключевые предметы.</li>
    <li>Спросите о системе оценки и обратной связи.</li>
</ul>
<p>Не забудьте запросить пример учебного плана и расписания — так легче понять реальную нагрузку ребенка.</p>
<h3>Посетите школу лично</h3>
<p>На экскурсии обратите внимание на атмосферу, коммуникацию между педагогами и учениками, доступность психолога и тьюторов. Обязательно задайте вопросы о поддержке ребенка в период адаптации.</p>
<p><strong>Совет:</strong> составьте короткий отчет после визита, чтобы не перепутать впечатления от разных школ.</p>
HTML,
                'status' => 'published',
                'featured' => true,
                'publish_at' => now()->subDays(5),
                'author_id' => $author->id,
                'category_id' => $privateSchoolCategory?->id,
            ]
        );
        $published->tags()->sync($adviceTags);

        $scheduled = BlogArticle::updateOrCreate(
            ['slug' => 'plan-podgotovki-k-ekzamenam'],
            [
                'title' => 'План подготовки к экзаменам: 6 шагов без перегруза',
                'slug' => 'plan-podgotovki-k-ekzamenam',
                'excerpt' => 'Структурированный подход к экзаменам с учетом темпа ребенка.',
                'content' => <<<HTML
<h2>Шаг 1. Диагностика знаний</h2>
<p>Начните с пробного теста, чтобы увидеть сильные стороны и пробелы. На основе результатов составьте список тем, которые требуют внимания.</p>
<h2>Шаг 2. Расписание на 10–12 недель</h2>
<p>Разбейте подготовку на недели и чередуйте сложные темы с повторением. Планируйте 1–2 дня в неделю для отдыха и восстановления.</p>
<h2>Шаг 3. Формат экзамена</h2>
<p>Тренируйте именно тот формат, который будет на экзамене: тайминг, типовые задания, критерии оценки. Это снижает тревожность и улучшает результат.</p>
<h3>Полезные привычки</h3>
<ol>
    <li>Вести дневник подготовки.</li>
    <li>Ставить измеримые цели на неделю.</li>
    <li>Заранее планировать консультации с преподавателем.</li>
</ol>
HTML,
                'status' => 'scheduled',
                'featured' => false,
                'publish_at' => Carbon::now()->addDays(3),
                'author_id' => $author->id,
                'category_id' => $examPrepCategory?->id,
            ]
        );
        $scheduled->tags()->sync($examTags);

        $draft = BlogArticle::updateOrCreate(
            ['slug' => 'oshibki-roditeley-pri-vybore-shkoly'],
            [
                'title' => 'Частые ошибки родителей при выборе школы',
                'slug' => 'oshibki-roditeley-pri-vybore-shkoly',
                'excerpt' => 'На что часто не обращают внимания при выборе школы и как этого избежать.',
                'content' => <<<HTML
<h2>Ошибка 1. Ориентироваться только на рейтинг</h2>
<p>Рейтинг важен, но не рассказывает о нагрузке, стиле преподавания и подходе к детям. Обязательно поговорите с учениками и родителями.</p>
<h2>Ошибка 2. Игнорировать логистику</h2>
<p>Долгая дорога до школы быстро снижает мотивацию и качество отдыха. Рассмотрите транспорт и возможность гибкого графика.</p>
<h2>Ошибка 3. Не обсуждать ожидания с ребенком</h2>
<p>Если ребенку важно общение, а школа делает ставку на индивидуальные занятия, это может привести к стрессу. Сформулируйте ожидания вместе.</p>
<p><strong>Вывод:</strong> выбирайте школу, где совпадают ценности семьи и образовательная стратегия.</p>
HTML,
                'status' => 'draft',
                'featured' => false,
                'author_id' => $author->id,
                'category_id' => $parentsCategory?->id,
            ]
        );
        $draft->tags()->sync($adviceTags);

        $publishedSupport = BlogArticle::updateOrCreate(
            ['slug' => 'bezopasnost-i-pitanie-v-shkole'],
            [
                'title' => 'Безопасность и питание в школе: что спросить на встрече',
                'slug' => 'bezopasnost-i-pitanie-v-shkole',
                'excerpt' => 'Ключевые вопросы о безопасности, медподдержке и рационе ребенка.',
                'content' => <<<HTML
<h2>Безопасность и инфраструктура</h2>
<p>Уточните, как устроена система доступа в школу, есть ли видеонаблюдение, кто сопровождает детей на прогулках и экскурсиях.</p>
<h2>Медицинская поддержка</h2>
<p>Спросите, есть ли медкабинет, как оформляется хранение лекарств и кто следит за самочувствием учащихся.</p>
<h2>Питание</h2>
<p>Попросите пример меню на неделю и узнайте, можно ли адаптировать рацион для аллергиков. Важно понимать, насколько гибко школа реагирует на запросы семьи.</p>
<p><strong>Совет:</strong> попросите контакты ответственного за питание, чтобы быстро решать вопросы в течение года.</p>
HTML,
                'status' => 'published',
                'featured' => false,
                'publish_at' => now()->subDays(2),
                'author_id' => $author->id,
                'category_id' => $parentsCategory?->id,
            ]
        );
        $publishedSupport->tags()->sync($supportTags);

        $publishedInternational = BlogArticle::updateOrCreate(
            ['slug' => 'kak-podgotovitsya-k-obucheniyu-za-rubezhom'],
            [
                'title' => 'Как подготовиться к обучению за рубежом',
                'slug' => 'kak-podgotovitsya-k-obucheniyu-za-rubezhom',
                'excerpt' => 'План переезда, документы и бытовые вопросы для семей.',
                'content' => <<<HTML
<h2>Документы и сроки</h2>
<p>Составьте календарь дедлайнов: подача заявок, оформление визы, бронь жилья. Чем раньше начнете, тем меньше стресс в финале.</p>
<h2>Финансовый план</h2>
<p>Посчитайте расходы на обучение, проживание и перелеты. Некоторые школы предлагают стипендии — уточните критерии заранее.</p>
<h2>Адаптация ребенка</h2>
<p>Обсудите с ребенком культурные особенности, школьные правила и возможные трудности. Поддержка семьи в первые месяцы критически важна.</p>
HTML,
                'status' => 'published',
                'featured' => true,
                'publish_at' => now()->subDays(1),
                'author_id' => $author->id,
                'category_id' => $internationalCategory?->id,
            ]
        );
        $publishedInternational->tags()->sync($internationalTags);

        $publishedAdmissions = BlogArticle::updateOrCreate(
            ['slug' => 'spisok-dokumentov-dlya-postupleniya'],
            [
                'title' => 'Список документов для поступления: без лишней суеты',
                'slug' => 'spisok-dokumentov-dlya-postupleniya',
                'excerpt' => 'Какие документы подготовить заранее и как избежать ошибок.',
                'content' => <<<HTML
<h2>Базовый пакет</h2>
<ul>
    <li>Свидетельство о рождении или паспорт.</li>
    <li>Медицинская карта и прививки.</li>
    <li>Характеристика из предыдущего места обучения.</li>
</ul>
<h2>Дополнительные материалы</h2>
<p>Некоторые школы просят портфолио, результаты олимпиад или эссе. Спросите, в каком формате нужно предоставить материалы.</p>
<h2>Советы по организации</h2>
<p>Сканируйте документы и храните их в облаке. Так проще отправлять копии и не терять оригиналы.</p>
HTML,
                'status' => 'published',
                'featured' => false,
                'publish_at' => now()->subDays(7),
                'author_id' => $author->id,
                'category_id' => $admissionsCategory?->id,
            ]
        );
        $publishedAdmissions->tags()->sync($activityTags);

        $kazakhTips = BlogArticle::updateOrCreate(
            ['slug' => 'bala-okuyna-dayyndyk-kenester'],
            [
                'title' => 'Баланы оқуға дайындау бойынша қысқа кеңестер',
                'slug' => 'bala-okuyna-dayyndyk-kenester',
                'excerpt' => 'Үйдегі қарапайым әдеттер оқу жылын жеңіл бастауға көмектеседі.',
                'content' => <<<HTML
<h2>Күн тәртібін алдын ала түзетіңіз</h2>
<p>Ұйқы мен таңертеңгі уақытты 2–3 апта бұрын реттеу баланың шаршамауына көмектеседі.</p>
<h2>Оқу бұрышын ұйымдастырыңыз</h2>
<p>Жарық жақсы түсетін, тыныш орын таңдаңыз. Үстелде қажет құралдар ғана болғаны дұрыс.</p>
<h2>Мотивацияны қолдаңыз</h2>
<p>Мақтау мен шағын мақсаттар баланың өзіне сенімді болуына ықпал етеді.</p>
HTML,
                'status' => 'published',
                'featured' => false,
                'publish_at' => now()->subDays(10),
                'author_id' => $author->id,
                'category_id' => $tipsCategory?->id,
            ]
        );
        $kazakhTips->tags()->sync($adviceTags);

        $afterSchool = BlogArticle::updateOrCreate(
            ['slug' => 'balanced-after-school-schedule'],
            [
                'title' => 'How to build a balanced after-school schedule',
                'slug' => 'balanced-after-school-schedule',
                'excerpt' => 'A framework for combining clubs, sports, and rest without overload.',
                'content' => <<<HTML
<h2>Start with energy, not hours</h2>
<p>Map the week around your child’s energy levels. Place demanding activities on lighter academic days.</p>
<h2>Choose one growth focus</h2>
<p>Pick a main skill to develop each semester—language, sport, or arts—and avoid stacking too many similar clubs.</p>
<h2>Protect recovery time</h2>
<p>Schedule at least one low-commitment evening each week. Consistent rest keeps motivation strong.</p>
HTML,
                'status' => 'published',
                'featured' => false,
                'publish_at' => now()->subDays(4),
                'author_id' => $author->id,
                'category_id' => $activitiesCategory?->id,
            ]
        );
        $afterSchool->tags()->sync($activityTags);

        $digitalLearning = BlogArticle::updateOrCreate(
            ['slug' => 'digital-tools-for-home-learning'],
            [
                'title' => 'Digital tools for learning at home',
                'slug' => 'digital-tools-for-home-learning',
                'excerpt' => 'Recommended apps and routines that keep remote study productive.',
                'content' => <<<HTML
<h2>Pick one platform for organization</h2>
<p>Use a single hub for assignments and deadlines so students can track tasks in one place.</p>
<h2>Mix interactive and offline study</h2>
<p>Combine short digital lessons with offline practice to avoid screen fatigue and boost retention.</p>
<h2>Set clear boundaries</h2>
<p>Agree on a start and end time for online study and keep devices in a shared space for better focus.</p>
HTML,
                'status' => 'published',
                'featured' => true,
                'publish_at' => now()->subDays(6),
                'author_id' => $author->id,
                'category_id' => $edtechCategory?->id,
            ]
        );
        $digitalLearning->tags()->sync($digitalTags);

        $newSchool = BlogArticle::updateOrCreate(
            ['slug' => 'helping-a-child-adjust-to-a-new-school'],
            [
                'title' => 'Helping a child adjust to a new school',
                'slug' => 'helping-a-child-adjust-to-a-new-school',
                'excerpt' => 'Practical steps to reduce anxiety and build confidence after a move.',
                'content' => <<<HTML
<h2>Prepare the first week</h2>
<p>Visit the campus, walk the schedule, and meet the teacher if possible. Familiarity reduces stress.</p>
<h2>Build a social bridge</h2>
<p>Arrange one or two low-pressure meetups with classmates. Early connections help children feel safer.</p>
<h2>Keep communication open</h2>
<p>Ask specific questions about the day and acknowledge emotions. Consistency matters more than a single talk.</p>
HTML,
                'status' => 'published',
                'featured' => false,
                'publish_at' => now()->subDays(3),
                'author_id' => $author->id,
                'category_id' => $psychologyCategory?->id,
            ]
        );
        $newSchool->tags()->sync($wellbeingTags);

        $preschoolReadiness = BlogArticle::updateOrCreate(
            ['slug' => 'preschool-readiness-checklist'],
            [
                'title' => 'Preschool readiness checklist for families',
                'slug' => 'preschool-readiness-checklist',
                'excerpt' => 'Key skills, habits, and routines to practice before the first day.',
                'content' => <<<HTML
<h2>Daily independence</h2>
<p>Practice dressing, washing hands, and tidying up toys. Small habits make the transition smoother.</p>
<h2>Communication skills</h2>
<p>Encourage your child to ask for help and express needs. Role-play simple classroom situations.</p>
<h2>Gentle routines</h2>
<p>Shift bedtime and morning routines 2–3 weeks before school starts to avoid fatigue.</p>
HTML,
                'status' => 'draft',
                'featured' => false,
                'publish_at' => null,
                'author_id' => $author->id,
                'category_id' => $preschoolCategory?->id,
            ]
        );
        $preschoolReadiness->tags()->sync($preschoolTags);

        $scholarships = BlogArticle::updateOrCreate(
            ['slug' => 'private-school-scholarships-and-aid'],
            [
                'title' => 'Private school scholarships and financial aid',
                'slug' => 'private-school-scholarships-and-aid',
                'excerpt' => 'Where to look for funding and how to prepare a strong application.',
                'content' => <<<HTML
<h2>Understand aid types</h2>
<p>Ask whether the school offers merit awards, need-based aid, or sibling discounts. Each has different criteria.</p>
<h2>Gather documents early</h2>
<p>Prepare income statements, recommendation letters, and a short family statement in advance.</p>
<h2>Track the calendar</h2>
<p>Financial aid deadlines often come before admissions decisions. Create reminders to submit on time.</p>
HTML,
                'status' => 'published',
                'featured' => true,
                'publish_at' => now()->subDays(9),
                'author_id' => $author->id,
                'category_id' => $privateSchoolCategory?->id,
            ]
        );
        $scholarships->tags()->sync($financeTags);

        $kazakhFamilies = BlogArticle::updateOrCreate(
            ['slug' => 'otbasy-oku-zhospary'],
            [
                'title' => 'Отбасылық оқу жоспарын бірге жасау',
                'slug' => 'otbasy-oku-zhospary',
                'excerpt' => 'Апталық жоспарлау мен қолдау әдеттері баланың оқуына тұрақтылық береді.',
                'content' => <<<HTML
<h2>Мақсаттарды бірге белгілеңіз</h2>
<p>Апта басында 2–3 нақты мақсат қойыңыз: оқу сағаты, бір кітап тарауы немесе жоба қадамы.</p>
<h2>Көрнекі жоспар жасаңыз</h2>
<p>Қарапайым кесте немесе тақта пайдаланыңыз. Баланың өзі белгілеп отырса, жауапкершілік артады.</p>
<h2>Үзіліс пен демалысты ұмытпаңыз</h2>
<p>Әр 30–40 минут сайын шағын үзіліс жасап, қозғалыс қосыңыз. Бұл назарды сақтауға көмектеседі.</p>
HTML,
                'status' => 'published',
                'featured' => false,
                'publish_at' => now()->subDays(2),
                'author_id' => $author->id,
                'category_id' => $tipsCategory?->id,
                'cover_image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1200&h=675&q=80',
            ]
        );
        $kazakhFamilies->tags()->sync($adviceTags);
    }
}
