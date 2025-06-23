<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Modules\Users\Entities\User;
use Modules\Specialties\Entities\Category;
use Modules\Doctors\Entities\Doctor;
use Modules\Doctors\Entities\DoctorSchedule;
use Modules\Doctors\Entities\DoctorRating;
use Modules\Patients\Entities\Patient;
use Modules\Appointments\Entities\Appointment;
use Modules\Payments\Entities\Payment;
use Modules\Users\Entities\Governorate;
use Modules\Users\Entities\City;

class LargeScaleDataSeeder extends Seeder
{
    private $doctorsCount;
    private $patientsCount;
    private $appointmentsCount;
    private $specialtiesCount;

    public function __construct()
    {
        // Get counts from environment variables or use defaults
        $this->doctorsCount = env('SEED_DOCTORS_COUNT', 300);
        $this->patientsCount = env('SEED_PATIENTS_COUNT', 500);
        $this->appointmentsCount = env('SEED_APPOINTMENTS_COUNT', 300);
        $this->specialtiesCount = env('SEED_SPECIALTIES_COUNT', 100);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $this->command->info("Starting large scale data seeding...");
        $this->command->info("Doctors: {$this->doctorsCount}, Patients: {$this->patientsCount}, Appointments: {$this->appointmentsCount}, Specialties: {$this->specialtiesCount}");

        DB::beginTransaction();

        try {
            $this->command->info("Creating {$this->specialtiesCount} specialties...");
            $this->createSpecialties();

            $this->command->info("Creating {$this->doctorsCount} doctors...");
            $this->createDoctors();

            $this->command->info("Creating {$this->patientsCount} patients...");
            $this->createPatients();

            $this->command->info("Creating {$this->appointmentsCount} appointments...");
            $this->createAppointments();

            $this->command->info('Creating ratings for appointments...');
            $this->createRatings();

            $this->command->info('Creating payments for appointments...');
            $this->createPayments();

            DB::commit();
            $this->command->info('Large scale test data seeded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding data: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create medical specialties
     */
    private function createSpecialties(): void
    {
        $specialties = [
            ['name' => 'طب القلب والأوعية الدموية', 'description' => 'تشخيص وعلاج أمراض القلب والأوعية الدموية'],
            ['name' => 'طب الأعصاب', 'description' => 'تشخيص وعلاج اضطرابات الجهاز العصبي'],
            ['name' => 'جراحة العظام', 'description' => 'علاج إصابات وأمراض العظام والمفاصل'],
            ['name' => 'طب النساء والتوليد', 'description' => 'رعاية صحة المرأة والحمل والولادة'],
            ['name' => 'طب الجلدية', 'description' => 'علاج أمراض الجلد والشعر والأظافر'],
            ['name' => 'طب الأنف والأذن والحنجرة', 'description' => 'علاج اضطرابات الأنف والأذن والحنجرة'],
            ['name' => 'طب المسالك البولية', 'description' => 'علاج أمراض الجهاز البولي والتناسلي'],
            ['name' => 'طب الروماتيزم', 'description' => 'علاج أمراض المفاصل والعضلات'],
            ['name' => 'طب الغدد الصماء', 'description' => 'علاج اضطرابات الهرمونات والغدد'],
            ['name' => 'طب الكلى', 'description' => 'تشخيص وعلاج أمراض الكلى'],
            ['name' => 'طب الجهاز الهضمي', 'description' => 'علاج أمراض المعدة والأمعاء والكبد'],
            ['name' => 'طب الصدر والرئة', 'description' => 'علاج أمراض الجهاز التنفسي'],
            ['name' => 'جراحة التجميل', 'description' => 'العمليات التجميلية والترميمية'],
            ['name' => 'طب الطوارئ', 'description' => 'الرعاية الطبية العاجلة'],
            ['name' => 'طب الأسرة', 'description' => 'الرعاية الصحية الشاملة للأسرة'],
            ['name' => 'التخدير والعناية المركزة', 'description' => 'خدمات التخدير والعناية المركزة'],
            ['name' => 'الأشعة التشخيصية', 'description' => 'التصوير الطبي والتشخيص بالأشعة'],
            ['name' => 'المختبرات الطبية', 'description' => 'التحاليل والفحوصات المخبرية'],
            ['name' => 'التغذية العلاجية', 'description' => 'العلاج بالتغذية والحميات الطبية'],
            ['name' => 'العلاج الطبيعي', 'description' => 'إعادة التأهيل والعلاج الفيزيائي'],
            ['name' => 'طب الأطفال', 'description' => 'الرعاية الصحية المتخصصة للأطفال'],
            ['name' => 'طب المسنين', 'description' => 'الرعاية الصحية المتخصصة لكبار السن'],
            ['name' => 'الطب النفسي', 'description' => 'علاج الاضطرابات النفسية والعقلية'],
            ['name' => 'جراحة الأعصاب', 'description' => 'العمليات الجراحية للجهاز العصبي'],
            ['name' => 'جراحة القلب', 'description' => 'العمليات الجراحية للقلب والأوعية الدموية'],
            ['name' => 'جراحة الصدر', 'description' => 'العمليات الجراحية للصدر والرئتين'],
            ['name' => 'طب العيون', 'description' => 'تشخيص وعلاج أمراض العين'],
            ['name' => 'طب الأسنان', 'description' => 'العناية بصحة الفم والأسنان'],
            ['name' => 'الأورام والسرطان', 'description' => 'تشخيص وعلاج الأورام السرطانية'],
            ['name' => 'الطب الرياضي', 'description' => 'علاج إصابات الرياضيين وتأهيلهم'],
            ['name' => 'طب الأمراض المعدية', 'description' => 'تشخيص وعلاج الأمراض المعدية'],
            ['name' => 'طب الذكورة والعقم', 'description' => 'علاج مشاكل الذكورة والعقم'],
            ['name' => 'جراحة الفم والوجه والفكين', 'description' => 'جراحة منطقة الفم والوجه والفكين'],
            ['name' => 'طب الأمراض الوراثية', 'description' => 'تشخيص وعلاج الأمراض الوراثية'],
            ['name' => 'طب المناعة والحساسية', 'description' => 'علاج أمراض المناعة والحساسية'],
            ['name' => 'جراحة الأوعية الدموية', 'description' => 'جراحة الأوعية الدموية والشرايين'],
            ['name' => 'طب النوم', 'description' => 'تشخيص وعلاج اضطرابات النوم'],
            ['name' => 'طب الألم', 'description' => 'تخصص إدارة وعلاج الألم المزمن'],
            ['name' => 'الطب البديل والتكميلي', 'description' => 'العلاج بالطب البديل والأعشاب'],
            ['name' => 'طب الطيران والفضاء', 'description' => 'الطب المختص بصحة الطيارين ورواد الفضاء'],
            ['name' => 'طب الغوص والأعماق', 'description' => 'الطب المختص بصحة الغواصين'],
            ['name' => 'طب البحار والسفن', 'description' => 'الرعاية الطبية في البحار والسفن'],
            ['name' => 'طب الجبال والمرتفعات', 'description' => 'الطب المختص بالمرتفعات العالية'],
            ['name' => 'طب الأسرة الريفي', 'description' => 'الرعاية الطبية في المناطق الريفية'],
            ['name' => 'الطب الشرعي', 'description' => 'الطب القانوني والتشريح الجنائي'],
            ['name' => 'طب المجتمع والوقاية', 'description' => 'الطب الوقائي وصحة المجتمع'],
            ['name' => 'طب السموم', 'description' => 'تشخيص وعلاج التسمم'],
            ['name' => 'طب الطوارئ النووية', 'description' => 'التعامل مع الحوادث النووية'],
            ['name' => 'جراحة اليد والمعصم', 'description' => 'جراحة تخصصية لليد والمعصم'],
            ['name' => 'جراحة القدم والكاحل', 'description' => 'جراحة تخصصية للقدم والكاحل'],
            ['name' => 'جراحة العمود الفقري', 'description' => 'جراحة تخصصية للعمود الفقري'],
            ['name' => 'جراحة المخ والأعصاب للأطفال', 'description' => 'جراحة المخ والأعصاب المتخصصة للأطفال'],
            ['name' => 'طب الأطفال حديثي الولادة', 'description' => 'الرعاية المركزة للأطفال حديثي الولادة'],
            ['name' => 'طب أطفال القلب', 'description' => 'أمراض القلب عند الأطفال'],
            ['name' => 'طب أطفال الجهاز الهضمي', 'description' => 'أمراض الجهاز الهضمي عند الأطفال'],
            ['name' => 'طب أطفال الكلى', 'description' => 'أمراض الكلى عند الأطفال'],
            ['name' => 'طب أطفال الغدد الصماء', 'description' => 'أمراض الغدد الصماء عند الأطفال'],
            ['name' => 'طب أطفال الأعصاب', 'description' => 'أمراض الأعصاب عند الأطفال'],
            ['name' => 'طب أطفال الدم والأورام', 'description' => 'أمراض الدم والأورام عند الأطفال'],
            ['name' => 'جراحة أطفال عامة', 'description' => 'الجراحة العامة للأطفال'],
            ['name' => 'جراحة أطفال المسالك البولية', 'description' => 'جراحة المسالك البولية للأطفال'],
            ['name' => 'جراحة أطفال القلب', 'description' => 'جراحة القلب للأطفال'],
            ['name' => 'طب النساء التناسلي', 'description' => 'علاج مشاكل الخصوبة والإنجاب'],
            ['name' => 'جراحة نساء بالمنظار', 'description' => 'الجراحات النسائية بالمنظار'],
            ['name' => 'طب النساء الأورام', 'description' => 'أورام الجهاز التناسلي الأنثوي'],
            ['name' => 'طب الحمل عالي الخطورة', 'description' => 'متابعة الحمل عالي الخطورة'],
            ['name' => 'طب الخصوبة والمساعدة على الإنجاب', 'description' => 'علاج العقم والمساعدة على الإنجاب'],
            ['name' => 'جراحة المناظير العامة', 'description' => 'الجراحة بالمناظير'],
            ['name' => 'جراحة السمنة والمنظار', 'description' => 'جراحات السمنة بالمنظار'],
            ['name' => 'جراحة الغدد الصماء', 'description' => 'جراحة الغدد الدرقية والنكفية'],
            ['name' => 'جراحة الكبد والمرارة', 'description' => 'جراحة الكبد والمرارة والقنوات الصفراوية'],
            ['name' => 'جراحة القولون والمستقيم', 'description' => 'جراحة الأمعاء الغليظة والمستقيم'],
            ['name' => 'جراحة الثدي', 'description' => 'جراحة أورام وأمراض الثدي'],
            ['name' => 'جراحة الرأس والرقبة', 'description' => 'جراحة أورام الرأس والرقبة'],
            ['name' => 'جراحة التجميل الترميمية', 'description' => 'الجراحة التجميلية الترميمية'],
            ['name' => 'جراحة التجميل التحسينية', 'description' => 'الجراحة التجميلية التحسينية'],
            ['name' => 'جراحة تجميل الوجه', 'description' => 'جراحة تجميل الوجه والفكين'],
            ['name' => 'جراحة تجميل الجسم', 'description' => 'جراحة تجميل وتنسيق الجسم'],
            ['name' => 'زراعة الشعر', 'description' => 'زراعة وترميم الشعر'],
            ['name' => 'طب وجراحة الليزر', 'description' => 'العلاج والجراحة بأشعة الليزر'],
            ['name' => 'طب الأمراض الجلدية التناسلية', 'description' => 'الأمراض الجلدية والتناسلية'],
            ['name' => 'طب الأمراض الجلدية التجميلية', 'description' => 'الطب التجميلي للجلد'],
            ['name' => 'طب أمراض الشعر', 'description' => 'تشخيص وعلاج أمراض الشعر'],
            ['name' => 'طب العيون التجميلي', 'description' => 'الجراحة التجميلية للعيون'],
            ['name' => 'طب العيون الشبكية', 'description' => 'أمراض شبكية العين'],
            ['name' => 'طب العيون القرنية', 'description' => 'أمراض وزراعة القرنية'],
            ['name' => 'طب العيون الجلوكوما', 'description' => 'تشخيص وعلاج الجلوكوما (المياه الزرقاء)'],
            ['name' => 'طب عيون الأطفال', 'description' => 'أمراض العيون عند الأطفال'],
            ['name' => 'جراحة الليزك والإبصار', 'description' => 'جراحة تصحيح النظر بالليزك'],
            ['name' => 'طب الأسنان التجميلي', 'description' => 'تجميل وتبييض الأسنان'],
            ['name' => 'طب الأسنان التقويمي', 'description' => 'تقويم الأسنان والفكين'],
            ['name' => 'جراحة زراعة الأسنان', 'description' => 'زراعة الأسنان الصناعية'],
            ['name' => 'طب أسنان الأطفال', 'description' => 'طب أسنان متخصص للأطفال'],
            ['name' => 'طب اللثة والأنسجة', 'description' => 'علاج أمراض اللثة والأنسجة المحيطة'],
            ['name' => 'طب الأسنان الوقائي', 'description' => 'الوقاية من أمراض الأسنان'],
            ['name' => 'طب جذور الأسنان', 'description' => 'علاج عصب وجذور الأسنان'],
            ['name' => 'تركيبات الأسنان الثابتة', 'description' => 'التركيبات والتيجان الثابتة'],
            ['name' => 'تركيبات الأسنان المتحركة', 'description' => 'أطقم الأسنان المتحركة'],
            ['name' => 'طب السمع والتوازن', 'description' => 'تشخيص وعلاج مشاكل السمع والتوازن'],
            ['name' => 'جراحة الحنجرة والحبال الصوتية', 'description' => 'جراحة الحنجرة والحبال الصوتية'],
            ['name' => 'طب الصوت والكلام', 'description' => 'علاج اضطرابات الصوت والكلام'],
            ['name' => 'جراحة الجيوب الأنفية', 'description' => 'جراحة الجيوب الأنفية بالمنظار'],
            ['name' => 'طب النوم والشخير', 'description' => 'علاج اضطرابات النوم والشخير'],
            ['name' => 'طب الحساسية والمناعة', 'description' => 'تشخيص وعلاج الحساسية واضطرابات المناعة'],
            ['name' => 'طب الصحة المهنية', 'description' => 'الطب المختص بصحة العمال والمهن'],
            ['name' => 'طب السفر والأمراض المدارية', 'description' => 'الطب المختص بأمراض السفر والمدارية'],
            ['name' => 'طب التأهيل الطبي', 'description' => 'إعادة التأهيل الطبي والوظيفي'],
            ['name' => 'العلاج المائي والطبيعي', 'description' => 'العلاج الطبيعي والمائي'],
            ['name' => 'العلاج الوظيفي', 'description' => 'العلاج الوظيفي وإعادة التأهيل'],
            ['name' => 'علاج النطق والتخاطب', 'description' => 'علاج اضطرابات النطق والتخاطب'],
        ];

        // Create only the required number of specialties
        $specialtiesToCreate = array_slice($specialties, 0, $this->specialtiesCount);

        $chunks = array_chunk($specialtiesToCreate, 50);
        foreach ($chunks as $chunk) {
            $data = [];
            foreach ($chunk as $specialty) {
                // Generate slug with fallback for Arabic text
                $slug = \Illuminate\Support\Str::slug($specialty['name']);

                // If slug is empty (common with Arabic text), create a transliterated version
                if (empty($slug)) {
                    // Simple transliteration for Arabic characters
                    $transliterated = $this->transliterateArabic($specialty['name']);
                    $slug = \Illuminate\Support\Str::slug($transliterated);
                }

                // If still empty, use a random string
                if (empty($slug)) {
                    $slug = 'category-' . \Illuminate\Support\Str::random(8);
                }

                $data[] = [
                    'name' => $specialty['name'],
                    'description' => $specialty['description'],
                    'status' => true,
                    'slug' => $slug,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            Category::insert($data);
        }
    }

    /**
     * Create doctors with users and schedules
     */
    private function createDoctors(): void
    {
        $categories = Category::all();
        $governorates = Governorate::all();
        $cities = City::all();

        $arabicFirstNames = [
            // أسماء الذكور المصرية
            'أحمد', 'محمد', 'علي', 'حسن', 'عبدالله', 'إبراهيم', 'يوسف', 'عمر', 'خالد', 'سعد',
            'حسام', 'طارق', 'وليد', 'كريم', 'ياسر', 'هشام', 'مصطفى', 'محمود', 'رامي', 'عماد',
            'عبدالرحمن', 'يحيى', 'كمال', 'جمال', 'إسماعيل', 'سامي', 'نبيل', 'فؤاد', 'شريف', 'أسامة',
            'عادل', 'ماجد', 'عصام', 'حاتم', 'عاصم', 'باسم', 'حازم', 'جابر', 'صابر', 'ناصر',
            'عبدالعزيز', 'عبدالحميد', 'عبدالفتاح', 'عبدالحكيم', 'عبدالناصر', 'عبدالمجيد',
            'فتحي', 'صلاح', 'رفعت', 'عاطف', 'فاروق', 'كمال', 'زكريا', 'محمود', 'عثمان', 'طه',

            // أسماء الإناث المصرية
            'فاطمة', 'عائشة', 'خديجة', 'زينب', 'مريم', 'نور', 'سارة', 'ليلى', 'هند', 'أمل',
            'منى', 'دعاء', 'إيمان', 'رنا', 'ريم', 'ندى', 'غادة', 'سمر', 'لمى', 'روان',
            'هبة', 'نورهان', 'دينا', 'رانيا', 'شيماء', 'أميرة', 'نادية', 'سميرة', 'فريدة', 'كريمة',
            'سلمى', 'ياسمين', 'حنان', 'وفاء', 'نهى', 'رشا', 'هالة', 'نهال', 'سحر', 'أسماء',
            'عبير', 'منال', 'صفاء', 'علياء', 'سناء', 'نجلاء', 'شهد', 'بسمة', 'رحمة', 'نعمة',
            'نيفين', 'عفاف', 'سوسن', 'نجوى', 'مديحة', 'فايزة', 'كوثر', 'صبرية', 'زكية', 'عزيزة'
        ];

        $arabicLastNames = [
            // عائلات مصرية شائعة
            'المصري', 'أحمد', 'علي', 'حسن', 'إبراهيم', 'سعد', 'محمد', 'عبدالله', 'يوسف', 'عمر',
            'الشافعي', 'البدوي', 'السيد', 'الدين', 'العزيز', 'الحليم', 'الصبور', 'الغني', 'الكريم',
            'خليل', 'منصور', 'قاسم', 'سالم', 'كامل', 'فهمي', 'رضا', 'زكي', 'شوقي', 'بدر',
            'عثمان', 'طه', 'فتحي', 'صالح', 'مختار', 'عطية', 'سليم', 'فرج', 'نجيب', 'حبيب',
            'الجندي', 'الشريف', 'الطيب', 'النجار', 'الحداد', 'البنا', 'الخياط', 'القاضي',
            'العطار', 'الجزار', 'البحراوي', 'الإسكندراني', 'القاهري', 'الصعيدي', 'المنياوي',
            'عبدالحميد', 'عبدالفتاح', 'عبدالرحمن', 'عبدالعزيز', 'عبدالحكيم', 'عبدالرحيم'
        ];

        $titles = ['دكتور', 'أستاذ دكتور', 'استشاري', 'أخصائي'];
        $genders = ['ذكر', 'انثي'];
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        $chunkSize = 100;
        $totalChunks = ceil($this->doctorsCount / $chunkSize);

        for ($chunk = 0; $chunk < $totalChunks; $chunk++) {
            $startIndex = $chunk * $chunkSize;
            $endIndex = min($startIndex + $chunkSize, $this->doctorsCount);

            $this->command->info("Processing doctors chunk " . ($chunk + 1) . "/{$totalChunks} (records {$startIndex}-{$endIndex})");

            $usersData = [];
            $doctorsData = [];
            $schedulesData = [];

            for ($i = $startIndex; $i < $endIndex; $i++) {
                $firstName = $arabicFirstNames[array_rand($arabicFirstNames)];
                $lastName = $arabicLastNames[array_rand($arabicLastNames)];
                $fullName = $firstName . ' ' . $lastName;
                $gender = $genders[array_rand($genders)];

                $usersData[] = [
                    'name' => $fullName,
                    'email' => 'doctor' . ($i + 1) . '@clinic.com',
                    'password' => Hash::make('password123'),
                    'phone_number' => '01' . str_pad(rand(1000000, 9999999), 8, '0', STR_PAD_LEFT),
                    'status' => true,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Insert users in chunks
            User::insert($usersData);

            // Get the inserted users with IDs
            $insertedUsers = User::where('email', 'like', 'doctor%@clinic.com')
                ->orderBy('id', 'desc')
                ->take($endIndex - $startIndex)
                ->get()
                ->reverse();

            foreach ($insertedUsers as $index => $user) {
                // Assign doctor role
                $user->assignRole('Doctor');

                $category = $categories->random();
                $governorate = $governorates->random();
                $cityQuery = $cities->where('governorate_id', $governorate->id);
                $city = $cityQuery->isNotEmpty() ? $cityQuery->random() : $cities->random();

                $doctorsData[] = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'title' => $titles[array_rand($titles)],
                    'description' => 'طبيب متخصص في ' . $category->name . ' مع سنوات من الخبرة في تقديم أفضل الخدمات الطبية للمرضى.',
                    'consultation_fee' => rand(200, 800),
                    'waiting_time' => rand(15, 45),
                    'experience_years' => rand(5, 25),
                    'gender' => $genders[array_rand($genders)],
                    'status' => true,
                    'address' => 'شارع ' . rand(1, 100) . '، ' . $city->name,
                    'governorate_id' => $governorate->id,
                    'city_id' => $city->id,
                    'category_id' => $category->id,
                    'rating_avg' => round(rand(35, 50) / 10, 1),
                    'is_profile_completed' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Insert doctors
            Doctor::insert($doctorsData);

            // Get inserted doctors to create schedules
            $insertedDoctors = Doctor::whereIn('user_id', $insertedUsers->pluck('id'))->get();

            foreach ($insertedDoctors as $doctor) {
                $workingDaysCount = rand(3, 5);
                $shuffledDays = $days;
                shuffle($shuffledDays);
                $selectedDays = array_slice($shuffledDays, 0, $workingDaysCount);

                foreach ($selectedDays as $day) {
                    $startHour = rand(8, 10);
                    $endHour = $startHour + rand(6, 8);

                    $schedulesData[] = [
                        'doctor_id' => $doctor->id,
                        'day' => $day,
                        'start_time' => sprintf('%02d:00', $startHour),
                        'end_time' => sprintf('%02d:00', min($endHour, 18)),
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            // Insert schedules
            if (!empty($schedulesData)) {
                DoctorSchedule::insert($schedulesData);
            }
        }
    }

    /**
     * Create patients with users
     */
    private function createPatients(): void
    {
        $arabicFirstNames = [
            // أسماء الذكور المصرية الشائعة
            'أحمد', 'محمد', 'علي', 'حسن', 'عبدالله', 'إبراهيم', 'يوسف', 'عمر', 'خالد', 'سعد',
            'حسام', 'طارق', 'وليد', 'كريم', 'ياسر', 'هشام', 'مصطفى', 'محمود', 'رامي', 'عماد',
            'عبدالرحمن', 'يحيى', 'كمال', 'جمال', 'إسماعيل', 'سامي', 'نبيل', 'فؤاد', 'شريف', 'أسامة',
            'ماجد', 'فهد', 'عبدالعزيز', 'ناصر', 'فيصل', 'سلمان', 'عادل', 'عصام', 'حاتم', 'عاصم',
            'فتحي', 'صلاح', 'رفعت', 'عاطف', 'فاروق', 'زكريا', 'عثمان', 'طه', 'حمدي', 'سيد',

            // أسماء الإناث المصرية الشائعة
            'فاطمة', 'عائشة', 'خديجة', 'زينب', 'مريم', 'نور', 'سارة', 'ليلى', 'هند', 'أمل',
            'منى', 'دعاء', 'إيمان', 'رنا', 'ريم', 'ندى', 'غادة', 'سمر', 'لمى', 'روان',
            'هبة', 'نورهان', 'دينا', 'رانيا', 'شيماء', 'أميرة', 'نادية', 'سميرة', 'فريدة', 'كريمة',
            'سلمى', 'ياسمين', 'حنان', 'وفاء', 'نهى', 'رشا', 'هالة', 'نهال', 'سحر', 'أسماء',
            'نيفين', 'عفاف', 'سوسن', 'نجوى', 'مديحة', 'فايزة', 'كوثر', 'صبرية', 'زكية', 'عزيزة'
        ];

        $arabicLastNames = [
            // عائلات مصرية أصيلة
            'المصري', 'أحمد', 'علي', 'حسن', 'إبراهيم', 'سعد', 'محمد', 'عبدالله', 'يوسف', 'عمر',
            'الشافعي', 'البدوي', 'السيد', 'الدين', 'العزيز', 'الحليم', 'الصبور', 'الغني', 'الكريم',
            'خليل', 'منصور', 'قاسم', 'سالم', 'كامل', 'فهمي', 'رضا', 'زكي', 'شوقي', 'بدر',
            'عثمان', 'طه', 'فتحي', 'صالح', 'مختار', 'عطية', 'سليم', 'فرج', 'نجيب', 'حبيب',
            'الجندي', 'الشريف', 'الطيب', 'النجار', 'الحداد', 'البنا', 'الخياط', 'القاضي', 'العطار',
            'الجزار', 'البحراوي', 'الإسكندراني', 'القاهري', 'الصعيدي', 'المنياوي', 'الأسيوطي',
            'عبدالحميد', 'عبدالفتاح', 'عبدالرحمن', 'عبدالعزيز', 'عبدالحكيم', 'عبدالرحيم'
        ];

        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $genders = ['male', 'female'];

        $chunkSize = 200;
        $totalChunks = ceil($this->patientsCount / $chunkSize);

        for ($chunk = 0; $chunk < $totalChunks; $chunk++) {
            $startIndex = $chunk * $chunkSize;
            $endIndex = min($startIndex + $chunkSize, $this->patientsCount);

            $this->command->info("Processing patients chunk " . ($chunk + 1) . "/{$totalChunks} (records {$startIndex}-{$endIndex})");

            $usersData = [];
            $patientsData = [];

            for ($i = $startIndex; $i < $endIndex; $i++) {
                $firstName = $arabicFirstNames[array_rand($arabicFirstNames)];
                $lastName = $arabicLastNames[array_rand($arabicLastNames)];
                $fullName = $firstName . ' ' . $lastName;

                $usersData[] = [
                    'name' => $fullName,
                    'email' => 'patient' . ($i + 1) . '@clinic.com',
                    'password' => Hash::make('password123'),
                    'phone_number' => '01' . str_pad(rand(1000000, 9999999), 8, '0', STR_PAD_LEFT),
                    'status' => true,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Insert users
            User::insert($usersData);

            // Get the inserted users with IDs
            $insertedUsers = User::where('email', 'like', 'patient%@clinic.com')
                ->orderBy('id', 'desc')
                ->take($endIndex - $startIndex)
                ->get()
                ->reverse();

            foreach ($insertedUsers as $user) {
                // Assign patient role
                $user->assignRole('Patient');

                $patientsData[] = [
                    'user_id' => $user->id,
                    'date_of_birth' => Carbon::now()->subYears(rand(18, 70))->subDays(rand(0, 365)),
                    'gender' => $genders[array_rand($genders)],
                    'address' => 'شارع ' . rand(1, 200) . '، مدينة ' . rand(1, 20),
                    'medical_history' => rand(0, 1) ? 'لا يوجد تاريخ مرضي' : 'ضغط الدم، السكري',
                    'emergency_contact' => '01' . str_pad(rand(1000000, 9999999), 8, '0', STR_PAD_LEFT),
                    'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                    'allergies' => rand(0, 1) ? null : 'حساسية من البنسلين',
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Insert patients
            Patient::insert($patientsData);
        }
    }

    /**
     * Create appointments
     */
    private function createAppointments(): void
    {
        $doctors = Doctor::where('status', true)->pluck('id')->toArray();
        $patients = Patient::where('status', true)->pluck('id')->toArray();

        // Better distribution: 50% completed, 30% scheduled, 20% cancelled
        $totalAppointments = $this->appointmentsCount;
        $completedCount = intval($totalAppointments * 0.5);
        $scheduledCount = intval($totalAppointments * 0.3);
        $cancelledCount = $totalAppointments - $completedCount - $scheduledCount;

        $chunkSize = 100;
        $totalChunks = ceil($this->appointmentsCount / $chunkSize);

        $appointmentIndex = 0;

        for ($chunk = 0; $chunk < $totalChunks; $chunk++) {
            $startIndex = $chunk * $chunkSize;
            $endIndex = min($startIndex + $chunkSize, $this->appointmentsCount);

            $this->command->info("Processing appointments chunk " . ($chunk + 1) . "/{$totalChunks} (records {$startIndex}-{$endIndex})");

            $appointmentsData = [];

            for ($i = $startIndex; $i < $endIndex; $i++) {
                $doctorId = $doctors[array_rand($doctors)];
                $patientId = $patients[array_rand($patients)];

                // Determine status based on distribution
                if ($appointmentIndex < $completedCount) {
                    $status = 'completed';
                    $daysOffset = rand(-90, -1); // Past appointments for completed
                } elseif ($appointmentIndex < $completedCount + $scheduledCount) {
                    $status = 'scheduled';
                    $daysOffset = rand(1, 30); // Future appointments for scheduled
                } else {
                    $status = 'cancelled';
                    $daysOffset = rand(-60, 20); // Mixed time for cancelled
                }

                $doctor = Doctor::find($doctorId);
                $appointmentDate = Carbon::now()->addDays($daysOffset);

                // Random time between 9 AM and 5 PM
                $hour = rand(9, 17);
                $minute = [0, 30][rand(0, 1)];
                $appointmentTime = $appointmentDate->copy()->setTime($hour, $minute);

                $appointmentsData[] = [
                    'doctor_id' => $doctorId,
                    'patient_id' => $patientId,
                    'scheduled_at' => $appointmentTime,
                    'status' => $status,
                    'notes' => 'موعد رقم ' . ($i + 1) . ' للمريض مع الطبيب - حالة: ' . $status,
                    'fees' => $doctor ? $doctor->consultation_fee : rand(200, 800),
                    'waiting_time' => $doctor ? $doctor->waiting_time : rand(15, 45),
                    'is_important' => rand(0, 1) === 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $appointmentIndex++;
            }

            // Insert appointments
            Appointment::insert($appointmentsData);
        }
    }

    /**
     * Create ratings for completed appointments
     */
    private function createRatings(): void
    {
        $completedAppointments = Appointment::where('status', 'completed')->get();

        $comments = [
            'طبيب ممتاز وذو خبرة عالية',
            'خدمة رائعة ووقت انتظار قصير',
            'تشخيص دقيق وعلاج فعال',
            'طبيب متفهم ويشرح بوضوح',
            'عيادة منظمة وطاقم متعاون',
            'أنصح بهذا الطبيب بشدة',
            'علاج ناجح ومتابعة جيدة',
            'خبرة واضحة في التخصص',
            'تعامل راقي ومهني',
            'استفدت كثيراً من الزيارة'
        ];

        $ratingsData = [];
        foreach ($completedAppointments as $appointment) {
            // 80% chance to have a rating
            if (rand(1, 100) <= 80) {
                $ratingsData[] = [
                    'doctor_id' => $appointment->doctor_id,
                    'patient_id' => $appointment->patient_id,
                    'appointment_id' => $appointment->id,
                    'rating' => rand(35, 50) / 10,
                    'comment' => $comments[array_rand($comments)],
                    'is_verified' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Insert ratings in chunks
        $chunks = array_chunk($ratingsData, 1000);
        foreach ($chunks as $chunk) {
            DoctorRating::insert($chunk);
        }
    }

    /**
     * Create payments for appointments
     */
    private function createPayments(): void
    {
        $appointments = Appointment::all();
        $paymentMethods = ['stripe', 'cash'];
        $paymentStatuses = ['completed', 'pending', 'failed', 'refunded'];

        // Target around 200 payments total
        $targetPayments = 200;
        $paymentsCreated = 0;
        $paymentsData = [];

        foreach ($appointments as $appointment) {
            if ($paymentsCreated >= $targetPayments) {
                break;
            }

            // Higher chance for completed appointments to have payments
            $paymentChance = 85; // 85% base chance
            if ($appointment->status === 'completed') {
                $paymentChance = 95; // 95% for completed
            } elseif ($appointment->status === 'cancelled') {
                $paymentChance = 40; // 40% for cancelled
            } elseif ($appointment->status === 'scheduled') {
                $paymentChance = 70; // 70% for scheduled
            }

            if (rand(1, 100) <= $paymentChance) {
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

                // Determine appropriate status based on appointment status
                if ($appointment->status === 'completed') {
                    $status = 'completed'; // Completed appointments should have completed payments
                } elseif ($appointment->status === 'cancelled') {
                    $status = rand(0, 1) ? 'failed' : 'refunded'; // Cancelled can be failed or refunded
                } else {
                    // For scheduled appointments, mix of pending and completed
                    $status = rand(0, 1) ? 'pending' : 'completed';
                }

                $paymentsData[] = [
                    'appointment_id' => $appointment->id,
                    'amount' => $appointment->fees,
                    'currency' => 'EGP',
                    'status' => $status,
                    'payment_method' => $paymentMethod,
                    'payment_id' => $paymentMethod === 'stripe' ? 'pi_' . strtoupper(substr(md5(uniqid()), 0, 24)) : null,
                    'transaction_id' => 'TXN_' . strtoupper(substr(md5(uniqid()), 0, 10)),
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $paymentsCreated++;
            }
        }

        // Insert payments in chunks
        $chunks = array_chunk($paymentsData, 100);
        foreach ($chunks as $chunk) {
            Payment::insert($chunk);
        }

        $this->command->info("Created {$paymentsCreated} payments out of target {$targetPayments}");
    }

    /**
     * Transliterate Arabic text to Latin characters for slug generation
     */
    private function transliterateArabic(string $text): string
    {
        $arabicToLatin = [
            'ا' => 'a', 'أ' => 'a', 'إ' => 'a', 'آ' => 'aa', 'ب' => 'b', 'ت' => 't', 'ث' => 'th',
            'ج' => 'j', 'ح' => 'h', 'خ' => 'kh', 'د' => 'd', 'ذ' => 'dh', 'ر' => 'r', 'ز' => 'z',
            'س' => 's', 'ش' => 'sh', 'ص' => 's', 'ض' => 'd', 'ط' => 't', 'ظ' => 'z', 'ع' => 'a',
            'غ' => 'gh', 'ف' => 'f', 'ق' => 'q', 'ك' => 'k', 'ل' => 'l', 'م' => 'm', 'ن' => 'n',
            'ه' => 'h', 'و' => 'w', 'ي' => 'y', 'ة' => 'h', 'ى' => 'a', 'ء' => 'a',
            'َ' => 'a', 'ُ' => 'u', 'ِ' => 'i', 'ً' => 'an', 'ٌ' => 'un', 'ٍ' => 'in',
            'ْ' => '', 'ّ' => '', '،' => ',', '؛' => ';', '؟' => '?'
        ];

        $transliterated = strtr($text, $arabicToLatin);

        // Remove any remaining non-ASCII characters and clean up
        $transliterated = preg_replace('/[^\x00-\x7F]/', '', $transliterated);
        $transliterated = preg_replace('/\s+/', ' ', $transliterated);
        $transliterated = trim($transliterated);

        return $transliterated;
    }
}
