<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        DB::table('feedbacks')->truncate();
        DB::table('patientRecord')->truncate();
        DB::table('appointments')->truncate();
        DB::table('customers')->truncate();
        DB::table('services')->truncate();
        DB::table('staffs')->truncate();
        DB::table('users')->truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. USERS
        DB::table('users')->insert([
            // ID 1 - Admin
            [
                'id' => 1,
                'name' => 'Admin User',
                'email' => 'admin@uitm.edu.my',
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ID 2 - Instructor 1
            [
                'id' => 2,
                'name' => 'Ahmad Yusaini Abd Karim',
                'email' => 'ahmadyusaini@uitm.edu.my',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ID 3 - Instructor 2
            [
                'id' => 3,
                'name' => 'Nur Farhalina Jamaluddin',
                'email' => 'nurfarhalina@uitm.edu.my',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ID 4 - Public Customer
            [
                'id' => 4,
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ID 5 - Public Customer
            [
                'id' => 5,
                'name' => 'Muhammad Hafiz',
                'email' => 'hafiz@gmail.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ID 6 - Student Customer
            [
                'id' => 6,
                'name' => 'Nurul Aina Binti Ahmad',
                'email' => '2022123456@student.uitm.edu.my',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ID 7 - Student Customer
            [
                'id' => 7,
                'name' => 'Ahmad Danial Bin Hassan',
                'email' => '2022234567@student.uitm.edu.my',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ID 8 - Staff Customer (university staff, not instructor)
            [
                'id' => 8,
                'name' => 'Zainal Abidin',
                'email' => 'zainal@uitm.edu.my',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ID 9 - Staff Customer (university staff, not instructor)
            [
                'id' => 9,
                'name' => 'Faridah Rahman',
                'email' => 'faridah@uitm.edu.my',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. STAFF (Instructors only)
        DB::table('staffs')->insert([
            [
                'position' => 'Admin',
                'faculty' => 'Faculty of Sport Science and Recreation',
                'user_id' => 1, // Ahmad Yusaini
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'position' => 'Instructor',
                'faculty' => 'Faculty of Sport Science and Recreation',
                'user_id' => 2, // Ahmad Yusaini
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'position' => 'Instructor',
                'faculty' => 'Faculty of Sport Science and Recreation',
                'user_id' => 3, // Nur Farhalina
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. SERVICES
        DB::table('services')->insert([
            [
                'id' => 1,
                'name' => 'Electrotherapy',
                'description' => 'Uses electrical currents to relieve pain and aid recovery',
                'fee' => json_encode([
                    'student' => 10.00,
                    'staff' => 15.00,
                    'public' => 20.00
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Therapeutic Exercises',
                'description' => 'Rehab-focused exercises to restore movement',
                'fee' => json_encode([
                    'student' => 15.00,
                    'staff' => 20.00,
                    'public' => 25.00
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Body Composition Analysis',
                'description' => 'Measures fat, muscle, and body health',
                'fee' => json_encode([
                    'student' => 20.00,
                    'staff' => 25.00,
                    'public' => 30.00
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Sport Massage',
                'description' => 'Relieves muscle tension and speeds up recovery',
                'fee' => json_encode([
                    'student' => 25.00,
                    'staff' => 30.00,
                    'public' => 35.00
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Sport And Exercise Consultation',
                'description' => 'Personalized fitness advice and planning',
                'fee' => json_encode([
                    'student' => 30.00,
                    'staff' => 35.00,
                    'public' => 40.00
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Yoga',
                'description' => 'Enhances flexibility, strength, and athletic performance',
                'fee' => json_encode([
                    'student' => 15.00,
                    'staff' => 20.00,
                    'public' => 25.00
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 4. CUSTOMERS
        DB::table('customers')->insert([
            // Public Customer 1
            [
                'id' => 1,
                'ICNumber' => '990123-10-5678',
                'studentID' => null,
                'faculty' => 'N/A',
                'program' => null,
                'staffID' => null,
                'category' => 'public',
                'phoneNumber' => '0134567890',
                'user_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Public Customer 2
            [
                'id' => 2,
                'ICNumber' => '950615-08-1234',
                'studentID' => null,
                'faculty' => 'N/A',
                'program' => null,
                'staffID' => null,
                'category' => 'public',
                'phoneNumber' => '0145678901',
                'user_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Student Customer 1
            [
                'id' => 3,
                'ICNumber' => '040305-14-0987',
                'studentID' => '2022123456',
                'faculty' => 'Faculty of Computer and Mathematical Sciences',
                'program' => 'Bachelor of Computer Science (Hons.)',
                'staffID' => null,
                'category' => 'student',
                'phoneNumber' => '0156789012',
                'user_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Student Customer 2
            [
                'id' => 4,
                'ICNumber' => '030812-10-2345',
                'studentID' => '2022234567',
                'faculty' => 'Faculty of Sport Science and Recreation',
                'program' => 'Bachelor of Sport Science (Hons.)',
                'staffID' => null,
                'category' => 'student',
                'phoneNumber' => '0167890123',
                'user_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Staff Customer 1 (university staff, NOT instructor)
            [
                'id' => 5,
                'ICNumber' => '850420-05-3456',
                'studentID' => null,
                'faculty' => 'Faculty of Business and Management',
                'program' => null,
                'staffID' => 'STF2024001',
                'category' => 'staff',
                'phoneNumber' => '0178901234',
                'user_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Staff Customer 2 (university staff, NOT instructor)
            [
                'id' => 6,
                'ICNumber' => '880715-12-4567',
                'studentID' => null,
                'faculty' => 'Faculty of Administrative Science and Policy Studies',
                'program' => null,
                'staffID' => 'STF2024002',
                'category' => 'staff',
                'phoneNumber' => '0189012345',
                'user_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. APPOINTMENTS (NO customer_id - it's in patientRecord!)
        DB::table('appointments')->insert([
            // Appointment 1 - Sport Massage (will have 1 patient record)
            [
                'id' => 1,
                'date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'time' => '09:00:00',
                'status' => 'completed',
                'service_id' => 4, // Sport Massage
                'staff_id' => 1, // Ahmad Yusaini
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Appointment 2 - Therapeutic Exercises (will have 1 patient record)
            [
                'id' => 2,
                'date' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'time' => '10:30:00',
                'status' => 'completed',
                'service_id' => 2, // Therapeutic Exercises
                'staff_id' => 2, // Nur Farhalina
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Appointment 3 - Yoga (will have 3 patient records - group booking)
            [
                'id' => 3,
                'date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'time' => '14:00:00',
                'status' => 'upcoming',
                'service_id' => 6, // Yoga
                'staff_id' => 1, // Ahmad Yusaini
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Appointment 4 - Body Composition (will have 1 patient record, even though cancelled)
            [
                'id' => 4,
                'date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'time' => '11:00:00',
                'status' => 'cancelled',
                'service_id' => 3, // Body Composition Analysis
                'staff_id' => 2, // Nur Farhalina
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Appointment 5 - Yoga (will have 2 patient records - couple booking)
            [
                'id' => 5,
                'date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'time' => '15:30:00',
                'status' => 'upcoming',
                'service_id' => 6, // Yoga
                'staff_id' => 2, // Nur Farhalina
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Appointment 6 - Yoga (will have 4 patient records - group booking)
            [
                'id' => 6,
                'date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'time' => '16:00:00',
                'status' => 'completed',
                'service_id' => 6, // Yoga
                'staff_id' => 1, // Ahmad Yusaini
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 6. PATIENT RECORDS (Bridge between appointments and customers)
        DB::table('patientRecord')->insert([
            // === APPOINTMENT 1: Sport Massage - 1 customer ===
            [
                'place_of_injury' => 'Chest',
                'symptoms' => json_encode(['chronic_pain', 'back_pain', 'shoulder_injury', 'neck_pain']),
                'type_of_injury' => json_encode(['chronic_pain', 'back_pain', 'shoulder_injury', 'neck_pain']),
                'diagnosis' => json_encode(['post_surgery_recovery', 'chronic_condition_care', 'injury_rehabilitation']),
                'treatment' => json_encode(['reduce_pain', 'increase_flexibility', 'enhance_strength']),
                'notes' => 'Patient responding well to treatment. Continue with current therapy plan.',
                'referral_letter' => 'referrals/letter_2.pdf',
                'customer_id' => 1, // Siti Nurhaliza (Public)
                'appointment_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // === APPOINTMENT 2: Therapeutic Exercises - 1 customer ===
            [
                'place_of_injury' => 'Left Leg',
                'symptoms' => json_encode(['knee_injury']),
                'type_of_injury' => json_encode(['knee_injury']),
                'diagnosis' => json_encode(['injury_rehabilitation', 'pain_management', 'mobility_support']),
                'treatment' => json_encode(['reduce_pain', 'increase_flexibility', 'enhance_strength']),
                'notes' => 'Good progress. Continue exercises at home.',
                'referral_letter' => null,
                'customer_id' => 3, // Nurul Aina (Student)
                'appointment_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // === APPOINTMENT 3: Yoga Group - 3 customers ===
            [
                'place_of_injury' => null,
                'symptoms' => json_encode(['stress', 'poor_flexibility']),
                'type_of_injury' => json_encode(['wellness']),
                'diagnosis' => null,
                'treatment' => null,
                'notes' => 'First time yoga participant. Booked for self and 2 friends.',
                'referral_letter' => null,
                'customer_id' => 4, // Ahmad Danial (Student) - Main booker
                'appointment_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'place_of_injury' => null,
                'symptoms' => json_encode(['back_pain', 'posture_issues']),
                'type_of_injury' => json_encode(['wellness']),
                'diagnosis' => null,
                'treatment' => null,
                'notes' => 'Friend of Ahmad Danial. Office worker.',
                'referral_letter' => null,
                'customer_id' => 5, // Zainal (Staff) - Friend 1
                'appointment_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'place_of_injury' => null,
                'symptoms' => json_encode(['stress', 'anxiety']),
                'type_of_injury' => json_encode(['wellness']),
                'diagnosis' => null,
                'treatment' => null,
                'notes' => 'Friend of Ahmad Danial. Looking for relaxation.',
                'referral_letter' => null,
                'customer_id' => 6, // Faridah (Staff) - Friend 2
                'appointment_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // === APPOINTMENT 4: Body Composition - 1 customer (CANCELLED) ===
            [
                'place_of_injury' => null,
                'symptoms' => json_encode(['weight_management']),
                'type_of_injury' => json_encode(['wellness']),
                'diagnosis' => null,
                'treatment' => null,
                'notes' => 'Appointment cancelled by customer.',
                'referral_letter' => null,
                'customer_id' => 2, // Muhammad Hafiz (Public)
                'appointment_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // === APPOINTMENT 5: Yoga Couple - 2 customers (UPCOMING) ===
            [
                'place_of_injury' => null,
                'symptoms' => json_encode(['flexibility_issues']),
                'type_of_injury' => json_encode(['wellness']),
                'diagnosis' => null,
                'treatment' => null,
                'notes' => 'Regular practitioner. Booked for self and partner.',
                'referral_letter' => null,
                'customer_id' => 1, // Siti Nurhaliza (Public) - Main booker
                'appointment_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'place_of_injury' => null,
                'symptoms' => json_encode(['stress', 'sleep_issues']),
                'type_of_injury' => json_encode(['wellness']),
                'diagnosis' => null,
                'treatment' => null,
                'notes' => 'Partner of Siti. New to yoga.',
                'referral_letter' => null,
                'customer_id' => 2, // Muhammad Hafiz (Public) - Partner
                'appointment_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // === APPOINTMENT 6: Yoga Group - 4 customers (COMPLETED) ===
            [
                'place_of_injury' => null,
                'symptoms' => json_encode(['general_wellness']),
                'type_of_injury' => json_encode(['wellness']),
                'diagnosis' => json_encode(['fitness_maintenance']),
                'treatment' => json_encode(['yoga_therapy', 'flexibility_training']),
                'notes' => 'Good session. Student booked for 3 friends.',
                'referral_letter' => null,
                'customer_id' => 3, // Nurul Aina (Student) - Main booker
                'appointment_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'place_of_injury' => null,
                'symptoms' => json_encode(['muscle_tension']),
                'type_of_injury' => json_encode(['wellness']),
                'diagnosis' => json_encode(['tension_relief']),
                'treatment' => json_encode(['yoga_therapy', 'stretching']),
                'notes' => 'Friend 1 of Nurul Aina.',
                'referral_letter' => null,
                'customer_id' => 4, // Ahmad Danial (Student) - Friend 1
                'appointment_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'place_of_injury' => null,
                'symptoms' => json_encode(['stress', 'fatigue']),
                'type_of_injury' => json_encode(['wellness']),
                'diagnosis' => json_encode(['stress_management', 'energy_boost']),
                'treatment' => json_encode(['yoga_therapy', 'breathing_exercises']),
                'notes' => 'Friend 2 of Nurul Aina.',
                'referral_letter' => null,
                'customer_id' => 5, // Zainal (Staff) - Friend 2
                'appointment_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'place_of_injury' => null,
                'symptoms' => json_encode(['poor_posture', 'back_pain']),
                'type_of_injury' => json_encode(['wellness']),
                'diagnosis' => json_encode(['posture_improvement', 'back_strengthening']),
                'treatment' => json_encode(['yoga_therapy', 'core_strengthening']),
                'notes' => 'Friend 3 of Nurul Aina.',
                'referral_letter' => null,
                'customer_id' => 6, // Faridah (Staff) - Friend 3
                'appointment_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 7. FEEDBACK (Only for completed appointments)
        DB::table('feedbacks')->insert([
            [
                'message' => 'Excellent service! The sport massage really helped with my muscle recovery. Instructor Ahmad was very professional.',
                'rating' => 5,
                'appointment_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'message' => 'The therapeutic exercises were very helpful. I feel much better now. Thank you!',
                'rating' => 4,
                'appointment_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'message' => 'Amazing yoga session! We all enjoyed it as a group. The instructor was patient and encouraging. Highly recommend!',
                'rating' => 5,
                'appointment_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✅ Database seeded successfully with Malaysian data!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - 9 users (1 admin, 2 instructors, 6 customers)');
        $this->command->info('   - 2 staff (instructors)');
        $this->command->info('   - 6 services');
        $this->command->info('   - 6 customers (2 public, 2 students, 2 staff)');
        $this->command->info('   - 6 appointments');
        $this->command->info('   - 13 patient records (bridge between appointments & customers)');
        $this->command->info('   - 3 feedback entries');
        $this->command->info('');
        $this->command->info('🧘 Yoga Group Bookings:');
        $this->command->info('   - Appointment 3: 3 people (upcoming)');
        $this->command->info('   - Appointment 5: 2 people (upcoming)');
        $this->command->info('   - Appointment 6: 4 people (completed)');
    }
}