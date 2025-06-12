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

class ClinicTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $this->command->info('Creating 20 specialties...');
            $this->createSpecialties();

            $this->command->info('Creating 50 doctors...');
            $this->createDoctors();

            $this->command->info('Creating 50 patients...');
            $this->createPatients();

            $this->command->info('Creating 20 appointments...');
            $this->createAppointments();

            $this->command->info('Creating ratings for appointments...');
            $this->createRatings();

            $this->command->info('Creating payments for appointments...');
            $this->createPayments();

            DB::commit();
            $this->command->info('Test data seeded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding data: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create 20 medical specialties
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
            ['name' => 'العلاج الطبيعي', 'description' => 'إعادة التأهيل والعلاج الفيزيائي']
        ];

        foreach ($specialties as $specialty) {
            Category::create([
                'name' => $specialty['name'],
                'description' => $specialty['description'],
                'status' => true,
                'slug' => \Illuminate\Support\Str::slug($specialty['name'])
            ]);
        }
    }

    /**
     * Create 50 doctors with users and schedules
     */
    private function createDoctors(): void
    {
        $categories = Category::all();
        $governorates = Governorate::all();
        $cities = City::all();

        $arabicFirstNames = [
            'أحمد', 'محمد', 'علي', 'حسن', 'عبدالله', 'إبراهيم', 'يوسف', 'عمر', 'خالد', 'سعد',
            'فاطمة', 'عائشة', 'خديجة', 'زينب', 'مريم', 'نور', 'سارة', 'ليلى', 'هند', 'أمل'
        ];

        $arabicLastNames = [
            'المصري', 'الأحمد', 'العلي', 'الحسن', 'الإبراهيم', 'السعد', 'القاسم', 'الرحمن',
            'الكريم', 'الحكيم', 'الشافي', 'العزيز', 'الرؤوف', 'الغني', 'الحليم', 'الصبور'
        ];

        $titles = ['دكتور', 'أستاذ دكتور', 'استشاري', 'أخصائي'];
        $genders = ['ذكر', 'انثي'];
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        for ($i = 1; $i <= 50; $i++) {
            $firstName = $arabicFirstNames[array_rand($arabicFirstNames)];
            $lastName = $arabicLastNames[array_rand($arabicLastNames)];
            $fullName = $firstName . ' ' . $lastName;
            $gender = $genders[array_rand($genders)];

            // Create user for doctor
            $user = User::create([
                'name' => $fullName,
                'email' => 'doctor' . $i . '@clinic.com',
                'password' => Hash::make('password123'),
                'phone_number' => '01' . str_pad(rand(1000000, 9999999), 8, '0', STR_PAD_LEFT),
                'status' => true,
                'email_verified_at' => now()
            ]);

            // Assign doctor role
            $user->assignRole('Doctor');

            $category = $categories->random();
            $governorate = $governorates->random();
            $cityQuery = $cities->where('governorate_id', $governorate->id);
            $city = $cityQuery->isNotEmpty() ? $cityQuery->random() : $cities->random();

            // Create doctor profile
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'name' => $fullName,
                'title' => $titles[array_rand($titles)],
                'specialization' => $category->name,
                'description' => 'طبيب متخصص في ' . $category->name . ' مع سنوات من الخبرة في تقديم أفضل الخدمات الطبية للمرضى.',
                'consultation_fee' => rand(200, 800),
                'waiting_time' => rand(15, 45),
                'experience_years' => rand(5, 25),
                'gender' => $gender,
                'status' => true,
                'address' => 'شارع ' . rand(1, 100) . '، ' . $city->name,
                'governorate_id' => $governorate->id,
                'city_id' => $city->id,
                'category_id' => $category->id,
                'rating_avg' => round(rand(35, 50) / 10, 1), // Random rating between 3.5 and 5.0
                'is_profile_completed' => true
            ]);

            // Create random schedule for doctor (3-5 working days)
            $workingDaysCount = rand(3, 5);
            $shuffledDays = $days;
            shuffle($shuffledDays);
            $selectedDays = array_slice($shuffledDays, 0, $workingDaysCount);

            foreach ($selectedDays as $day) {
                $startHour = rand(8, 10); // Start between 8 AM and 10 AM
                $endHour = $startHour + rand(6, 8); // Work 6-8 hours

                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day' => $day,
                    'start_time' => sprintf('%02d:00', $startHour),
                    'end_time' => sprintf('%02d:00', min($endHour, 18)), // Don't go past 6 PM
                    'is_active' => true
                ]);
            }
        }
    }

    /**
     * Create 50 patients with users
     */
    private function createPatients(): void
    {
        $arabicFirstNames = [
            'أحمد', 'محمد', 'علي', 'حسن', 'عبدالله', 'إبراهيم', 'يوسف', 'عمر', 'خالد', 'سعد',
            'فاطمة', 'عائشة', 'خديجة', 'زينب', 'مريم', 'نور', 'سارة', 'ليلى', 'هند', 'أمل',
            'منى', 'دعاء', 'إيمان', 'رنا', 'ريم', 'ندى', 'غادة', 'سمر', 'لمى', 'روان'
        ];

        $arabicLastNames = [
            'المصري', 'الأحمد', 'العلي', 'الحسن', 'الإبراهيم', 'السعد', 'القاسم', 'الرحمن',
            'الكريم', 'الحكيم', 'الشافي', 'العزيز', 'الرؤوف', 'الغني', 'الحليم', 'الصبور',
            'العادل', 'الرحيم', 'الودود', 'الشكور', 'الحفيظ', 'المجيد', 'الكبير', 'المتعال'
        ];

        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $genders = ['male', 'female'];

        for ($i = 1; $i <= 50; $i++) {
            $firstName = $arabicFirstNames[array_rand($arabicFirstNames)];
            $lastName = $arabicLastNames[array_rand($arabicLastNames)];
            $fullName = $firstName . ' ' . $lastName;

            // Create user for patient
            $user = User::create([
                'name' => $fullName,
                'email' => 'patient' . $i . '@clinic.com',
                'password' => Hash::make('password123'),
                'phone_number' => '01' . str_pad(rand(1000000, 9999999), 8, '0', STR_PAD_LEFT),
                'status' => true,
                'email_verified_at' => now()
            ]);

            // Assign patient role
            $user->assignRole('Patient');

            // Create patient profile
            Patient::create([
                'user_id' => $user->id,
                'date_of_birth' => Carbon::now()->subYears(rand(18, 70))->subDays(rand(0, 365)),
                'gender' => $genders[array_rand($genders)],
                'address' => 'شارع ' . rand(1, 200) . '، مدينة ' . rand(1, 20),
                'medical_history' => rand(0, 1) ? 'لا يوجد تاريخ مرضي' : 'ضغط الدم، السكري',
                'emergency_contact' => '01' . str_pad(rand(1000000, 9999999), 8, '0', STR_PAD_LEFT),
                'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                'allergies' => rand(0, 1) ? null : 'حساسية من البنسلين',
                'status' => true
            ]);
        }
    }

    /**
     * Create 20 appointments
     */
    private function createAppointments(): void
    {
        $doctors = Doctor::where('status', true)->get();
        $patients = Patient::where('status', true)->get();
        $statuses = ['scheduled', 'completed', 'cancelled'];

        for ($i = 1; $i <= 20; $i++) {
            $doctor = $doctors->random();
            $patient = $patients->random();
            $status = $statuses[array_rand($statuses)];

            // Create appointment in the past 30 days or future 30 days
            $daysOffset = rand(-30, 30);
            $appointmentDate = Carbon::now()->addDays($daysOffset);

            // Random time between 9 AM and 5 PM
            $hour = rand(9, 17);
            $minute = [0, 30][rand(0, 1)]; // Either :00 or :30
            $appointmentTime = $appointmentDate->copy()->setTime($hour, $minute);

            Appointment::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'scheduled_at' => $appointmentTime,
                'status' => $status,
                'notes' => 'موعد ' . ($i == 1 ? 'أول' : 'رقم ' . $i) . ' للمريض مع الطبيب',
                'fees' => $doctor->consultation_fee,
                'waiting_time' => $doctor->waiting_time,
                'is_important' => rand(0, 1) === 1
            ]);
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

        foreach ($completedAppointments as $appointment) {
            // 80% chance to have a rating
            if (rand(1, 100) <= 80) {
                DoctorRating::create([
                    'doctor_id' => $appointment->doctor_id,
                    'patient_id' => $appointment->patient_id,
                    'appointment_id' => $appointment->id,
                    'rating' => rand(35, 50) / 10, // Rating between 3.5 and 5.0
                    'comment' => $comments[array_rand($comments)],
                    'is_verified' => true
                ]);
            }
        }
    }

    /**
     * Create payments for appointments
     */
    private function createPayments(): void
    {
        $appointments = Appointment::all();
        $paymentMethods = ['stripe', 'cash'];
        $statuses = ['completed', 'pending', 'failed'];

        foreach ($appointments as $appointment) {
            // 85% chance to have a payment record
            if (rand(1, 100) <= 85) {
                $status = $statuses[array_rand($statuses)];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

                // Determine appropriate status based on appointment status
                if ($appointment->status === 'completed') {
                    $status = 'completed'; // Completed appointments should have completed payments
                } elseif ($appointment->status === 'cancelled') {
                    $status = rand(0, 1) ? 'failed' : 'refunded'; // Cancelled appointments can be failed or refunded
                } else {
                    $status = rand(0, 1) ? 'pending' : 'completed'; // Scheduled appointments can be pending or completed
                }

                Payment::create([
                    'appointment_id' => $appointment->id,
                    'amount' => $appointment->fees,
                    'currency' => 'EGP',
                    'status' => $status,
                    'payment_method' => $paymentMethod,
                    'payment_id' => $paymentMethod === 'stripe' ? 'pi_' . strtoupper(substr(md5(uniqid()), 0, 24)) : null,
                    'transaction_id' => Payment::generateTransactionId()
                ]);
            }
        }
    }
}
