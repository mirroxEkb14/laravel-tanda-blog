<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\User;

class BlogArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::first();
        if (! $author) {
            return;
        }

        $admissionsCategory = BlogCategory::where('name', 'Поступление')->first();
        $parentsCategory = BlogCategory::where('name', 'Родителям')->first();
        $privateSchoolCategory = BlogCategory::where('name', 'Частные школы')->first();
        $examPrepCategory = BlogCategory::where('name', 'Подготовка к экзаменам')->first();
        $internationalCategory = BlogCategory::where('name', 'Международное обучение')->first();

        $adviceTags = BlogTag::whereIn('name', ['Рекомендации', 'Выбор школы', 'Родительское сообщество'])
            ->pluck('id')
            ->all();
        $examTags = BlogTag::whereIn('name', ['Подготовка', 'Экзамены', 'Мотивация'])
            ->pluck('id')
            ->all();
        $supportTags = BlogTag::whereIn('name', ['Психология', 'Питание', 'Безопасность'])
            ->pluck('id')
            ->all();
        $internationalTags = BlogTag::whereIn('name', ['Переезд', 'Стипендии'])
            ->pluck('id')
            ->all();
        $activityTags = BlogTag::whereIn('name', ['Внеурочка', 'Учебный план'])
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
                'featured' => false,
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
    }
}
