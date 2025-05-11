<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->truncateTables();

        $this->seedUsers();
        $this->seedDoctors();
        $this->seedPatients();
        $this->seedDoctorCategories();
        $this->seedDoctorSchedules();
        $this->seedAppointments();
        $this->seedPayments();
        $this->seedDoctorRatings();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Fake data seeded successfully!');
    }

    private function truncateTables(): void
    {
        DB::table('doctor_ratings')->truncate();
        DB::table('payments')->truncate();
        DB::table('appointments')->truncate();
        DB::table('doctor_schedules')->truncate();
        DB::table('doctor_category')->truncate();
        DB::table('patients')->truncate();
        DB::table('doctors')->truncate();
    }

    private function seedUsers(): void
    {
        $users = [
            [
                'name' => 'أحمد محمد',
                'email' => 'doctor1@clinic.com',
                'email_verified_at' => Carbon::parse('2025-05-01'),
                'password' => Hash::make('password'),
                'phone_number' => '01111111111',
                'status' => true,
                'last_seen' => Carbon::parse('2025-05-10 11:30:00'),
                'created_at' => Carbon::parse('2025-05-01 10:00:00'),
                'updated_at' => Carbon::parse('2025-05-10 11:30:00')
            ],
            [
                'name' => 'سارة عبدالله',
                'email' => 'doctor2@clinic.com',
                'email_verified_at' => Carbon::parse('2025-05-01'),
                'password' => Hash::make('password'),
                'phone_number' => '01122222222',
                'status' => true,
                'last_seen' => Carbon::parse('2025-05-10 10:45:00'),
                'created_at' => Carbon::parse('2025-05-01 10:30:00'),
                'updated_at' => Carbon::parse('2025-05-10 10:45:00')
            ],
            [
                'name' => 'محمد حسين',
                'email' => 'doctor3@clinic.com',
                'email_verified_at' => Carbon::parse('2025-05-01'),
                'password' => Hash::make('password'),
                'phone_number' => '01133333333',
                'status' => true,
                'last_seen' => Carbon::parse('2025-05-09 14:20:00'),
                'created_at' => Carbon::parse('2025-05-01 11:00:00'),
                'updated_at' => Carbon::parse('2025-05-09 14:20:00')
            ],
            [
                'name' => 'نورا محمود',
                'email' => 'doctor4@clinic.com',
                'email_verified_at' => Carbon::parse('2025-05-02'),
                'password' => Hash::make('password'),
                'phone_number' => '01144444444',
                'status' => true,
                'last_seen' => Carbon::parse('2025-05-10 09:15:00'),
                'created_at' => Carbon::parse('2025-05-02 09:30:00'),
                'updated_at' => Carbon::parse('2025-05-10 09:15:00')
            ],
            [
                'name' => 'خالد سعيد',
                'email' => 'doctor5@clinic.com',
                'email_verified_at' => Carbon::parse('2025-05-02'),
                'password' => Hash::make('password'),
                'phone_number' => '01155555555',
                'status' => true,
                'last_seen' => Carbon::parse('2025-05-10 08:30:00'),
                'created_at' => Carbon::parse('2025-05-02 10:15:00'),
                'updated_at' => Carbon::parse('2025-05-10 08:30:00')
            ],
            [
                'name' => 'محمود إبراهيم',
                'email' => 'patient1@example.com',
                'email_verified_at' => Carbon::parse('2025-05-03'),
                'password' => Hash::make('password'),
                'phone_number' => '01166666666',
                'status' => true,
                'last_seen' => Carbon::parse('2025-05-10 17:30:00'),
                'created_at' => Carbon::parse('2025-05-03 15:00:00'),
                'updated_at' => Carbon::parse('2025-05-10 17:30:00')
            ],
            [
                'name' => 'هدى علي',
                'email' => 'patient2@example.com',
                'email_verified_at' => Carbon::parse('2025-05-03'),
                'password' => Hash::make('password'),
                'phone_number' => '01177777777',
                'status' => true,
                'last_seen' => Carbon::parse('2025-05-10 18:45:00'),
                'created_at' => Carbon::parse('2025-05-03 16:20:00'),
                'updated_at' => Carbon::parse('2025-05-10 18:45:00')
            ],
            [
                'name' => 'عمر خالد',
                'email' => 'patient3@example.com',
                'email_verified_at' => Carbon::parse('2025-05-04'),
                'password' => Hash::make('password'),
                'phone_number' => '01188888888',
                'status' => true,
                'last_seen' => Carbon::parse('2025-05-10 20:10:00'),
                'created_at' => Carbon::parse('2025-05-04 09:10:00'),
                'updated_at' => Carbon::parse('2025-05-10 20:10:00')
            ],
            [
                'name' => 'ياسمين أحمد',
                'email' => 'patient4@example.com',
                'email_verified_at' => Carbon::parse('2025-05-04'),
                'password' => Hash::make('password'),
                'phone_number' => '01199999999',
                'status' => true,
                'last_seen' => Carbon::parse('2025-05-09 19:20:00'),
                'created_at' => Carbon::parse('2025-05-04 10:30:00'),
                'updated_at' => Carbon::parse('2025-05-09 19:20:00')
            ],
            [
                'name' => 'كريم محمد',
                'email' => 'patient5@example.com',
                'email_verified_at' => Carbon::parse('2025-05-05'),
                'password' => Hash::make('password'),
                'phone_number' => '01100000001',
                'status' => true,
                'last_seen' => Carbon::parse('2025-05-10 13:15:00'),
                'created_at' => Carbon::parse('2025-05-05 11:45:00'),
                'updated_at' => Carbon::parse('2025-05-10 13:15:00')
            ],
        ];

        foreach ($users as $index => $user) {
            $existingUser = DB::table('users')->where('email', $user['email'])->first();

            if ($existingUser) {
                $userId = $existingUser->id;

                DB::table('users')
                    ->where('id', $userId)
                    ->update([
                        'name' => $user['name'],
                        'phone_number' => $user['phone_number'],
                        'status' => $user['status'],
                        'last_seen' => $user['last_seen'],
                        'updated_at' => $user['updated_at']
                    ]);
            } else {
                $userId = DB::table('users')->insertGetId($user);
            }

            $hasRole = DB::table('model_has_roles')
                ->where('model_id', $userId)
                ->where('model_type', 'Modules\\Users\\Entities\\User')
                ->exists();

            if (!$hasRole) {
                if (strpos($user['email'], 'doctor') !== false) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => 2,
                        'model_type' => 'Modules\\Users\\Entities\\User',
                        'model_id' => $userId,
                    ]);
                } elseif (strpos($user['email'], 'patient') !== false) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => 3,
                        'model_type' => 'Modules\\Users\\Entities\\User',
                        'model_id' => $userId,
                    ]);
                }
            }
        }
    }

    private function seedDoctors(): void
    {
        $doctors = [
            [
                'user_id' => 3,
                'first_name' => 'أحمد',
                'last_name' => 'محمد',
                'image' => 'doctors/doctor1.jpg',
                'description' => 'طبيب متخصص في طب القلب مع خبرة واسعة في تشخيص وعلاج أمراض القلب والأوعية الدموية',
                'degree' => 'دكتوراه',
                'title' => 'استشاري',
                'specialization' => 'أمراض القلب والأوعية الدموية',
                'address' => 'شارع التحرير، وسط البلد، القاهرة',
                'governorate_id' => 1,
                'city_id' => 1,
                'consultation_fee' => 300.00,
                'experience_years' => 15,
                'gender' => 'ذكر',
                'status' => true,
                'waiting_time' => 20,
                'rating_avg' => 4.80,
                'is_profile_completed' => true,
                'created_at' => Carbon::parse('2025-05-01 10:00:00'),
                'updated_at' => Carbon::parse('2025-05-01 10:00:00')
            ],
            [
                'user_id' => 4,
                'first_name' => 'سارة',
                'last_name' => 'عبدالله',
                'image' => 'doctors/doctor2.jpg',
                'description' => 'طبيبة متخصصة في طب الأطفال مع خبرة في علاج الأمراض الشائعة للأطفال والرضع',
                'degree' => 'ماجستير',
                'title' => 'أخصائية',
                'specialization' => 'طب الأطفال',
                'address' => 'ميدان سفنكس، المهندسين، الجيزة',
                'governorate_id' => 2,
                'city_id' => 31,
                'consultation_fee' => 250.00,
                'experience_years' => 8,
                'gender' => 'انثي',
                'status' => true,
                'waiting_time' => 15,
                'rating_avg' => 4.50,
                'is_profile_completed' => true,
                'created_at' => Carbon::parse('2025-05-01 10:30:00'),
                'updated_at' => Carbon::parse('2025-05-01 10:30:00')
            ],
            [
                'user_id' => 5,
                'first_name' => 'محمد',
                'last_name' => 'حسين',
                'image' => 'doctors/doctor3.jpg',
                'description' => 'طبيب أسنان متخصص في تقويم وتجميل الأسنان والعلاج التحفظي',
                'degree' => 'دكتوراه',
                'title' => 'استشاري',
                'specialization' => 'طب الأسنان',
                'address' => 'شارع الخليفة المأمون، مصر الجديدة، القاهرة',
                'governorate_id' => 1,
                'city_id' => 2,
                'consultation_fee' => 350.00,
                'experience_years' => 12,
                'gender' => 'ذكر',
                'status' => true,
                'waiting_time' => 25,
                'rating_avg' => 4.70,
                'is_profile_completed' => true,
                'created_at' => Carbon::parse('2025-05-01 11:00:00'),
                'updated_at' => Carbon::parse('2025-05-01 11:00:00')
            ],
            [
                'user_id' => 6,
                'first_name' => 'نورا',
                'last_name' => 'محمود',
                'image' => 'doctors/doctor4.jpg',
                'description' => 'طبيبة متخصصة في طب العيون وجراحة العيون بالليزر',
                'degree' => 'دكتوراه',
                'title' => 'استشارية',
                'specialization' => 'جراحة العيون',
                'address' => 'شارع الجامعة، الدقي، الجيزة',
                'governorate_id' => 2,
                'city_id' => 30,
                'consultation_fee' => 400.00,
                'experience_years' => 14,
                'gender' => 'انثي',
                'status' => true,
                'waiting_time' => 30,
                'rating_avg' => 4.90,
                'is_profile_completed' => true,
                'created_at' => Carbon::parse('2025-05-02 09:30:00'),
                'updated_at' => Carbon::parse('2025-05-02 09:30:00')
            ],
            [
                'user_id' => 7,
                'first_name' => 'خالد',
                'last_name' => 'سعيد',
                'image' => 'doctors/doctor5.jpg',
                'description' => 'طبيب نفسي متخصص في علاج الاضطرابات النفسية والإدمان',
                'degree' => 'ماجستير',
                'title' => 'أخصائي',
                'specialization' => 'الطب النفسي',
                'address' => 'شارع 9، المعادي، القاهرة',
                'governorate_id' => 1,
                'city_id' => 3,
                'consultation_fee' => 300.00,
                'experience_years' => 10,
                'gender' => 'ذكر',
                'status' => true,
                'waiting_time' => 45,
                'rating_avg' => 4.20,
                'is_profile_completed' => true,
                'created_at' => Carbon::parse('2025-05-02 10:15:00'),
                'updated_at' => Carbon::parse('2025-05-02 10:15:00')
            ],
        ];

        DB::table('doctors')->insert($doctors);
    }

    private function seedPatients(): void
    {
        $patients = [
            [
                'user_id' => 8,
                'date_of_birth' => '1985-06-15',
                'gender' => 'male',
                'medical_history' => 'ضغط دم مرتفع',
                'emergency_contact' => '01012345678',
                'blood_type' => 'O+',
                'allergies' => 'المضادات الحيوية من فئة البنسلين',
                'address' => 'شارع النصر، مدينة نصر، القاهرة',
                'created_at' => Carbon::parse('2025-05-03 15:00:00'),
                'updated_at' => Carbon::parse('2025-05-03 15:00:00')
            ],
            [
                'user_id' => 9,
                'date_of_birth' => '1990-03-22',
                'gender' => 'female',
                'medical_history' => 'لا يوجد',
                'emergency_contact' => '01098765432',
                'blood_type' => 'A+',
                'allergies' => 'لا يوجد',
                'address' => 'شارع جامعة الدول العربية، المهندسين، الجيزة',
                'created_at' => Carbon::parse('2025-05-03 16:20:00'),
                'updated_at' => Carbon::parse('2025-05-03 16:20:00')
            ],
            [
                'user_id' => 10,
                'date_of_birth' => '1978-11-10',
                'gender' => 'male',
                'medical_history' => 'داء السكري من النوع الثاني',
                'emergency_contact' => '01234567890',
                'blood_type' => 'B-',
                'allergies' => 'المكسرات',
                'address' => 'شارع الهرم، الجيزة',
                'created_at' => Carbon::parse('2025-05-04 09:10:00'),
                'updated_at' => Carbon::parse('2025-05-04 09:10:00')
            ],
            [
                'user_id' => 11,
                'date_of_birth' => '1995-07-03',
                'gender' => 'female',
                'medical_history' => 'حساسية موسمية',
                'emergency_contact' => '01012345678',
                'blood_type' => 'AB+',
                'allergies' => 'حبوب اللقاح، الغبار',
                'address' => 'شارع 9، المعادي، القاهرة',
                'created_at' => Carbon::parse('2025-05-04 10:30:00'),
                'updated_at' => Carbon::parse('2025-05-04 10:30:00')
            ],
            [
                'user_id' => 12,
                'date_of_birth' => '1982-04-28',
                'gender' => 'male',
                'medical_history' => 'ارتفاع الكولسترول',
                'emergency_contact' => '01112345678',
                'blood_type' => 'O-',
                'allergies' => 'الأسبرين',
                'address' => 'شارع شبرا، القاهرة',
                'created_at' => Carbon::parse('2025-05-05 11:45:00'),
                'updated_at' => Carbon::parse('2025-05-05 11:45:00')
            ],
        ];

        DB::table('patients')->insert($patients);
    }

    private function seedDoctorCategories(): void
    {
        $doctorCategories = [
            [
                'doctor_id' => 1,
                'category_id' => 9,
                'created_at' => Carbon::parse('2025-05-01 10:00:00'),
                'updated_at' => Carbon::parse('2025-05-01 10:00:00')
            ],
            [
                'doctor_id' => 2,
                'category_id' => 3,
                'created_at' => Carbon::parse('2025-05-01 10:30:00'),
                'updated_at' => Carbon::parse('2025-05-01 10:30:00')
            ],
            [
                'doctor_id' => 3,
                'category_id' => 1,
                'created_at' => Carbon::parse('2025-05-01 11:00:00'),
                'updated_at' => Carbon::parse('2025-05-01 11:00:00')
            ],
            [
                'doctor_id' => 4,
                'category_id' => 2,
                'created_at' => Carbon::parse('2025-05-02 09:30:00'),
                'updated_at' => Carbon::parse('2025-05-02 09:30:00')
            ],
            [
                'doctor_id' => 5,
                'category_id' => 4,
                'created_at' => Carbon::parse('2025-05-02 10:15:00'),
                'updated_at' => Carbon::parse('2025-05-02 10:15:00')
            ],
        ];

        DB::table('doctor_category')->insert($doctorCategories);
    }

    private function seedDoctorSchedules(): void
    {
        $doctorSchedules = [
            [
                'doctor_id' => 1,
                'day' => 'saturday',
                'start_time' => '09:00:00',
                'end_time' => '14:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-01 10:00:00'),
                'updated_at' => Carbon::parse('2025-05-01 10:00:00')
            ],
            [
                'doctor_id' => 1,
                'day' => 'monday',
                'start_time' => '16:00:00',
                'end_time' => '20:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-01 10:00:00'),
                'updated_at' => Carbon::parse('2025-05-01 10:00:00')
            ],
            [
                'doctor_id' => 1,
                'day' => 'wednesday',
                'start_time' => '16:00:00',
                'end_time' => '20:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-01 10:00:00'),
                'updated_at' => Carbon::parse('2025-05-01 10:00:00')
            ],
            [
                'doctor_id' => 2,
                'day' => 'sunday',
                'start_time' => '10:00:00',
                'end_time' => '15:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-01 10:30:00'),
                'updated_at' => Carbon::parse('2025-05-01 10:30:00')
            ],
            [
                'doctor_id' => 2,
                'day' => 'tuesday',
                'start_time' => '16:00:00',
                'end_time' => '20:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-01 10:30:00'),
                'updated_at' => Carbon::parse('2025-05-01 10:30:00')
            ],
            [
                'doctor_id' => 2,
                'day' => 'thursday',
                'start_time' => '16:00:00',
                'end_time' => '20:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-01 10:30:00'),
                'updated_at' => Carbon::parse('2025-05-01 10:30:00')
            ],
            [
                'doctor_id' => 3,
                'day' => 'saturday',
                'start_time' => '15:00:00',
                'end_time' => '20:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-01 11:00:00'),
                'updated_at' => Carbon::parse('2025-05-01 11:00:00')
            ],
            [
                'doctor_id' => 3,
                'day' => 'tuesday',
                'start_time' => '09:00:00',
                'end_time' => '14:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-01 11:00:00'),
                'updated_at' => Carbon::parse('2025-05-01 11:00:00')
            ],
            [
                'doctor_id' => 3,
                'day' => 'thursday',
                'start_time' => '09:00:00',
                'end_time' => '14:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-01 11:00:00'),
                'updated_at' => Carbon::parse('2025-05-01 11:00:00')
            ],
            [
                'doctor_id' => 4,
                'day' => 'sunday',
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-02 09:30:00'),
                'updated_at' => Carbon::parse('2025-05-02 09:30:00')
            ],
            [
                'doctor_id' => 4,
                'day' => 'wednesday',
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-02 09:30:00'),
                'updated_at' => Carbon::parse('2025-05-02 09:30:00')
            ],
            [
                'doctor_id' => 4,
                'day' => 'friday',
                'start_time' => '14:00:00',
                'end_time' => '18:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-02 09:30:00'),
                'updated_at' => Carbon::parse('2025-05-02 09:30:00')
            ],
            [
                'doctor_id' => 5,
                'day' => 'monday',
                'start_time' => '10:00:00',
                'end_time' => '16:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-02 10:15:00'),
                'updated_at' => Carbon::parse('2025-05-02 10:15:00')
            ],
            [
                'doctor_id' => 5,
                'day' => 'thursday',
                'start_time' => '16:00:00',
                'end_time' => '21:00:00',
                'is_active' => true,
                'created_at' => Carbon::parse('2025-05-02 10:15:00'),
                'updated_at' => Carbon::parse('2025-05-02 10:15:00')
            ],
        ];

        DB::table('doctor_schedules')->insert($doctorSchedules);
    }

    private function seedAppointments(): void
    {
        $appointments = [
            [
                'doctor_id' => 1,
                'patient_id' => 1,
                'scheduled_at' => Carbon::parse('2025-05-05 10:00:00'),
                'status' => 'completed',
                'notes' => 'فحص روتيني للقلب',
                'fees' => 300.00,
                'is_important' => false,
                'waiting_time' => 20,
                'created_at' => Carbon::parse('2025-05-03 16:30:00'),
                'updated_at' => Carbon::parse('2025-05-05 11:30:00')
            ],
            [
                'doctor_id' => 2,
                'patient_id' => 2,
                'scheduled_at' => Carbon::parse('2025-05-06 11:30:00'),
                'status' => 'completed',
                'notes' => 'التهاب في الحلق',
                'fees' => 250.00,
                'is_important' => false,
                'waiting_time' => 15,
                'created_at' => Carbon::parse('2025-05-04 09:15:00'),
                'updated_at' => Carbon::parse('2025-05-06 12:45:00')
            ],
            [
                'doctor_id' => 3,
                'patient_id' => 3,
                'scheduled_at' => Carbon::parse('2025-05-07 16:00:00'),
                'status' => 'completed',
                'notes' => 'ألم في الضرس الخلفي',
                'fees' => 350.00,
                'is_important' => false,
                'waiting_time' => 25,
                'created_at' => Carbon::parse('2025-05-05 14:20:00'),
                'updated_at' => Carbon::parse('2025-05-07 17:30:00')
            ],
            [
                'doctor_id' => 4,
                'patient_id' => 4,
                'scheduled_at' => Carbon::parse('2025-05-08 10:30:00'),
                'status' => 'completed',
                'notes' => 'فحص نظر سنوي',
                'fees' => 400.00,
                'is_important' => false,
                'waiting_time' => 30,
                'created_at' => Carbon::parse('2025-05-06 11:45:00'),
                'updated_at' => Carbon::parse('2025-05-08 11:45:00')
            ],
            [
                'doctor_id' => 5,
                'patient_id' => 5,
                'scheduled_at' => Carbon::parse('2025-05-09 12:00:00'),
                'status' => 'completed',
                'notes' => 'استشارة نفسية',
                'fees' => 300.00,
                'is_important' => false,
                'waiting_time' => 45,
                'created_at' => Carbon::parse('2025-05-07 10:30:00'),
                'updated_at' => Carbon::parse('2025-05-09 13:30:00')
            ],
            [
                'doctor_id' => 1,
                'patient_id' => 2,
                'scheduled_at' => Carbon::parse('2025-05-10 11:00:00'),
                'status' => 'completed',
                'notes' => 'فحص متابعة للقلب',
                'fees' => 300.00,
                'is_important' => false,
                'waiting_time' => 20,
                'created_at' => Carbon::parse('2025-05-08 09:20:00'),
                'updated_at' => Carbon::parse('2025-05-10 12:15:00')
            ],
            [
                'doctor_id' => 2,
                'patient_id' => 1,
                'scheduled_at' => Carbon::parse('2025-05-11 12:00:00'),
                'status' => 'scheduled',
                'notes' => 'متابعة بعد العلاج',
                'fees' => 250.00,
                'is_important' => false,
                'waiting_time' => 15,
                'created_at' => Carbon::parse('2025-05-09 08:30:00'),
                'updated_at' => Carbon::parse('2025-05-09 08:30:00')
            ],
            [
                'doctor_id' => 3,
                'patient_id' => 4,
                'scheduled_at' => Carbon::parse('2025-05-12 17:00:00'),
                'status' => 'scheduled',
                'notes' => 'تنظيف أسنان',
                'fees' => 350.00,
                'is_important' => false,
                'waiting_time' => 25,
                'created_at' => Carbon::parse('2025-05-10 10:45:00'),
                'updated_at' => Carbon::parse('2025-05-10 10:45:00')
            ],
            [
                'doctor_id' => 4,
                'patient_id' => 3,
                'scheduled_at' => Carbon::parse('2025-05-13 10:30:00'),
                'status' => 'scheduled',
                'notes' => 'فحص بعد العملية',
                'fees' => 400.00,
                'is_important' => true,
                'waiting_time' => 30,
                'created_at' => Carbon::parse('2025-05-10 09:15:00'),
                'updated_at' => Carbon::parse('2025-05-10 09:15:00')
            ],
            [
                'doctor_id' => 5,
                'patient_id' => 5,
                'scheduled_at' => Carbon::parse('2025-05-14 13:00:00'),
                'status' => 'scheduled',
                'notes' => 'متابعة العلاج النفسي',
                'fees' => 300.00,
                'is_important' => true,
                'waiting_time' => 45,
                'created_at' => Carbon::parse('2025-05-10 11:30:00'),
                'updated_at' => Carbon::parse('2025-05-10 11:30:00')
            ]
        ];

        $appointmentIds = [];
        foreach ($appointments as $appointment) {
            $appointmentIds[] = DB::table('appointments')->insertGetId($appointment);
        }

        $this->appointmentIds = $appointmentIds;
    }

    private function seedPayments(): void
    {
        $payments = [
            [
                'appointment_id' => $this->appointmentIds[0],
                'amount' => 300.00,
                'currency' => 'EGP',
                'status' => 'completed',
                'payment_method' => 'cash',
                'payment_id' => null,
                'transaction_id' => 'TRX-123456789',
                'metadata' => null,
                'paid_at' => Carbon::parse('2025-05-05 11:00:00'),
                'created_at' => Carbon::parse('2025-05-03 16:30:00'),
                'updated_at' => Carbon::parse('2025-05-05 11:00:00')
            ],
            [
                'appointment_id' => $this->appointmentIds[1],
                'amount' => 250.00,
                'currency' => 'EGP',
                'status' => 'completed',
                'payment_method' => 'cash',
                'payment_id' => null,
                'transaction_id' => 'TRX-123456790',
                'metadata' => null,
                'paid_at' => Carbon::parse('2025-05-06 12:00:00'),
                'created_at' => Carbon::parse('2025-05-04 09:15:00'),
                'updated_at' => Carbon::parse('2025-05-06 12:00:00')
            ],
            [
                'appointment_id' => $this->appointmentIds[2],
                'amount' => 350.00,
                'currency' => 'EGP',
                'status' => 'completed',
                'payment_method' => 'card',
                'payment_id' => 'pay_2574684513',
                'transaction_id' => 'TRX-123456791',
                'metadata' => null,
                'paid_at' => Carbon::parse('2025-05-07 17:00:00'),
                'created_at' => Carbon::parse('2025-05-05 14:20:00'),
                'updated_at' => Carbon::parse('2025-05-07 17:00:00')
            ],
            [
                'appointment_id' => $this->appointmentIds[3],
                'amount' => 400.00,
                'currency' => 'EGP',
                'status' => 'completed',
                'payment_method' => 'cash',
                'payment_id' => null,
                'transaction_id' => 'TRX-123456792',
                'metadata' => null,
                'paid_at' => Carbon::parse('2025-05-08 11:30:00'),
                'created_at' => Carbon::parse('2025-05-06 11:45:00'),
                'updated_at' => Carbon::parse('2025-05-08 11:30:00')
            ],
            [
                'appointment_id' => $this->appointmentIds[4],
                'amount' => 300.00,
                'currency' => 'EGP',
                'status' => 'completed',
                'payment_method' => 'card',
                'payment_id' => 'pay_2574684514',
                'transaction_id' => 'TRX-123456793',
                'metadata' => null,
                'paid_at' => Carbon::parse('2025-05-09 13:00:00'),
                'created_at' => Carbon::parse('2025-05-07 10:30:00'),
                'updated_at' => Carbon::parse('2025-05-09 13:00:00')
            ],
            [
                'appointment_id' => $this->appointmentIds[5],
                'amount' => 300.00,
                'currency' => 'EGP',
                'status' => 'completed',
                'payment_method' => 'cash',
                'payment_id' => null,
                'transaction_id' => 'TRX-123456794',
                'metadata' => null,
                'paid_at' => Carbon::parse('2025-05-10 12:00:00'),
                'created_at' => Carbon::parse('2025-05-08 09:20:00'),
                'updated_at' => Carbon::parse('2025-05-10 12:00:00')
            ],
        ];

        $paymentIds = [];
        foreach ($payments as $payment) {
            $paymentIds[] = DB::table('payments')->insertGetId($payment);
        }

        for ($i = 0; $i < 6; $i++) {
            DB::table('appointments')
                ->where('id', $this->appointmentIds[$i])
                ->update(['payment_id' => $paymentIds[$i]]);
        }
    }

    private function seedDoctorRatings(): void
    {
        $doctorRatings = [
            [
                'doctor_id' => 1,
                'patient_id' => 1,
                'appointment_id' => $this->appointmentIds[0],
                'rating' => 5.0,
                'comment' => 'دكتور ممتاز، شرح لي حالتي بالتفصيل وكان متعاون جدا',
                'is_verified' => true,
                'created_at' => Carbon::parse('2025-05-05 12:00:00'),
                'updated_at' => Carbon::parse('2025-05-05 12:00:00')
            ],
            [
                'doctor_id' => 2,
                'patient_id' => 2,
                'appointment_id' => $this->appointmentIds[1],
                'rating' => 4.5,
                'comment' => 'دكتورة لطيفة جدا مع الأطفال وماهرة في التشخيص',
                'is_verified' => true,
                'created_at' => Carbon::parse('2025-05-06 13:30:00'),
                'updated_at' => Carbon::parse('2025-05-06 13:30:00')
            ],
            [
                'doctor_id' => 3,
                'patient_id' => 3,
                'appointment_id' => $this->appointmentIds[2],
                'rating' => 4.0,
                'comment' => 'علاج ممتاز للأسنان، لكن وقت الانتظار كان طويلاً قليلاً',
                'is_verified' => true,
                'created_at' => Carbon::parse('2025-05-07 18:00:00'),
                'updated_at' => Carbon::parse('2025-05-07 18:00:00')
            ],
            [
                'doctor_id' => 4,
                'patient_id' => 4,
                'appointment_id' => $this->appointmentIds[3],
                'rating' => 5.0,
                'comment' => 'دكتورة متميزة في مجالها، تشخيص دقيق وحرفية عالية',
                'is_verified' => true,
                'created_at' => Carbon::parse('2025-05-08 12:30:00'),
                'updated_at' => Carbon::parse('2025-05-08 12:30:00')
            ],
            [
                'doctor_id' => 5,
                'patient_id' => 5,
                'appointment_id' => $this->appointmentIds[4],
                'rating' => 4.5,
                'comment' => 'استفدت كثيراً من الجلسة، الدكتور مستمع جيد ويقدم نصائح فعالة',
                'is_verified' => true,
                'created_at' => Carbon::parse('2025-05-09 14:00:00'),
                'updated_at' => Carbon::parse('2025-05-09 14:00:00')
            ],
            [
                'doctor_id' => 1,
                'patient_id' => 2,
                'appointment_id' => $this->appointmentIds[5],
                'rating' => 5.0,
                'comment' => 'زيارة متابعة ناجحة، الدكتور متابع للحالة بشكل جيد',
                'is_verified' => true,
                'created_at' => Carbon::parse('2025-05-10 12:45:00'),
                'updated_at' => Carbon::parse('2025-05-10 12:45:00')
            ],
        ];

        DB::table('doctor_ratings')->insert($doctorRatings);
    }
}
