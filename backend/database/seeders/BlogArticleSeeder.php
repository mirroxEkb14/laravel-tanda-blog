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
            ['slug' => 'choosing-a-private-school-checklist'],
            [
                'title' => 'How to choose a private school: a family checklist',
                'slug' => 'choosing-a-private-school-checklist',
                'excerpt' => 'Criteria that help compare private schools and pick the best fit.',
                'content' => <<<HTML
<h2>Define your family priorities</h2>
<p>Start by clarifying what matters most: advanced academics, small classes, project-based learning, or strong extracurriculars. A short list of criteria keeps the comparison objective.</p>
<h3>Review the academic program</h3>
<ul>
    <li>Ask which tracks are available in middle and high school.</li>
    <li>Check how many hours are allocated to core subjects.</li>
    <li>Learn how feedback and grading are handled.</li>
</ul>
<p>Request a sample curriculum and weekly schedule to see the real workload.</p>
<h3>Visit the campus</h3>
<p>During the tour, note the atmosphere, teacher-student communication, and availability of counseling or tutoring. Ask how the school supports students during the first months.</p>
<p><strong>Tip:</strong> write a short summary after each visit to keep impressions organized.</p>
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
            ['slug' => 'exam-prep-plan-6-steps'],
            [
                'title' => 'Exam prep plan: 6 steps without burnout',
                'slug' => 'exam-prep-plan-6-steps',
                'excerpt' => 'A structured approach to exam preparation that respects the student pace.',
                'content' => <<<HTML
<h2>Step 1. Diagnose knowledge gaps</h2>
<p>Start with a diagnostic test to identify strengths and weak spots. Use the results to map out the topics that need attention.</p>
<h2>Step 2. Build a 10–12 week schedule</h2>
<p>Break preparation into weekly blocks and alternate heavy topics with review. Leave 1–2 days per week for rest and recovery.</p>
<h2>Step 3. Practice the real format</h2>
<p>Train with the same format, timing, and scoring criteria as the exam. This reduces anxiety and improves performance.</p>
<h3>Helpful habits</h3>
<ol>
    <li>Keep a prep journal.</li>
    <li>Set measurable weekly goals.</li>
    <li>Plan tutor check-ins early.</li>
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
            ['slug' => 'parent-mistakes-when-choosing-a-school'],
            [
                'title' => 'Common parent mistakes when choosing a school',
                'slug' => 'parent-mistakes-when-choosing-a-school',
                'excerpt' => 'What families often overlook when selecting a school and how to avoid it.',
                'content' => <<<HTML
<h2>Mistake 1. Relying only on rankings</h2>
<p>Rankings matter, but they rarely explain workload, teaching style, or student support. Talk to current families for real context.</p>
<h2>Mistake 2. Ignoring logistics</h2>
<p>A long commute can drain energy quickly. Review transportation options and scheduling flexibility.</p>
<h2>Mistake 3. Not aligning expectations with the child</h2>
<p>If a child values social connections and the school focuses on individual work, stress can follow. Discuss priorities together.</p>
<p><strong>Bottom line:</strong> choose a school where your family values and the learning approach align.</p>
HTML,
                'status' => 'draft',
                'featured' => false,
                'author_id' => $author->id,
                'category_id' => $parentsCategory?->id,
            ]
        );
        $draft->tags()->sync($adviceTags);

        $publishedSupport = BlogArticle::updateOrCreate(
            ['slug' => 'school-safety-and-nutrition-questions'],
            [
                'title' => 'School safety and nutrition: questions to ask',
                'slug' => 'school-safety-and-nutrition-questions',
                'excerpt' => 'Key questions about security, health support, and meals.',
                'content' => <<<HTML
<h2>Safety and facilities</h2>
<p>Ask how campus access is controlled, whether cameras are used, and who supervises students during breaks and trips.</p>
<h2>Health support</h2>
<p>Check if there is an on-site nurse, how medications are stored, and who monitors student well-being.</p>
<h2>Nutrition</h2>
<p>Request a sample menu and ask how allergies or dietary needs are handled. Flexibility matters for long-term comfort.</p>
<p><strong>Tip:</strong> get the direct contact for the nutrition coordinator to resolve issues quickly.</p>
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
            ['slug' => 'preparing-to-study-abroad'],
            [
                'title' => 'Preparing to study abroad',
                'slug' => 'preparing-to-study-abroad',
                'excerpt' => 'A plan for relocation, paperwork, and family logistics.',
                'content' => <<<HTML
<h2>Documents and deadlines</h2>
<p>Create a calendar for applications, visas, and housing. The earlier you start, the less stressful the final weeks will be.</p>
<h2>Financial planning</h2>
<p>Estimate tuition, housing, and travel costs. Many schools offer scholarships—learn the criteria in advance.</p>
<h2>Helping your child adapt</h2>
<p>Discuss cultural expectations, school rules, and likely challenges. Family support in the first months is essential.</p>
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
            ['slug' => 'admissions-document-checklist'],
            [
                'title' => 'Admissions document checklist without the stress',
                'slug' => 'admissions-document-checklist',
                'excerpt' => 'What to prepare early and how to avoid common paperwork mistakes.',
                'content' => <<<HTML
<h2>Core documents</h2>
<ul>
    <li>Birth certificate or passport.</li>
    <li>Medical records and vaccination history.</li>
    <li>Transcript or recommendation from the previous school.</li>
</ul>
<h2>Additional materials</h2>
<p>Some schools ask for portfolios, competition results, or essays. Confirm the required format and deadlines.</p>
<h2>Organization tips</h2>
<p>Scan documents and keep them in cloud storage to share copies quickly and protect originals.</p>
HTML,
                'status' => 'published',
                'featured' => false,
                'publish_at' => now()->subDays(7),
                'author_id' => $author->id,
                'category_id' => $admissionsCategory?->id,
            ]
        );
        $publishedAdmissions->tags()->sync($activityTags);

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
                'featured' => false,
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
                'status' => 'published',
                'featured' => false,
                'publish_at' => now()->subDays(8),
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
                'featured' => false,
                'publish_at' => now()->subDays(9),
                'author_id' => $author->id,
                'category_id' => $privateSchoolCategory?->id,
            ]
        );
        $scholarships->tags()->sync($financeTags);
    }
}
