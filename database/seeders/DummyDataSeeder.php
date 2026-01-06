<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks so truncate works cleanly
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate all tables
        DB::table('feedback')->truncate();
        DB::table('appointments')->truncate();
        DB::table('patientRecord')->truncate();
        DB::table('service')->truncate();
        DB::table('students')->truncate();
        DB::table('publics')->truncate();
        DB::table('staff')->truncate();
        DB::table('customers')->truncate();
        DB::table('users')->truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // USERS
        DB::statement("
            INSERT INTO users (id, name, email, phoneNumber, email_verified_at, password, remember_token, created_at, updated_at) VALUES
            (1, 'Aiman Rosli', 'aiman.rosli@example.com', '0123456789', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (2, 'Nurul Izzah', 'nurul.izzah@example.com', '0139876543', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (3, 'Lim Wei Sheng', 'lim.ws@example.com', '0141234567', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (4, 'Siti Zulaikha', 'siti.z@example.com', '0171234567', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (5, 'Arun Kumar', 'arun.k@example.com', '0161112223', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (6, 'Farah Nadia', 'farah.n@example.com', '0192233445', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (7, 'Lee Jia Wen', 'leejw@example.com', '0113344556', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (8, 'Syafiq Rahman', 'syafiq.r@example.com', '0188877665', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (9, 'Tan Mei Ling', 'tan.ml@example.com', '0129876543', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (10, 'Ahmad Faiz', 'ahmad.f@example.com', '0178877665', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (11, 'Hassan Ali', 'hassan.a@example.com', '0135566778', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (12, 'Ravi Chandran', 'ravi.c@example.com', '0158877664', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (13, 'Nur Afiqah', 'afiqah.n@example.com', '0182233445', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (14, 'Chong Wei', 'chong.wei@example.com', '0165544332', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (15, 'Hafiz Azmi', 'hafiz.azmi@example.com', '0193344556', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (16, 'Alya Hani', 'alya.h@example.com', '0179988776', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (17, 'Mira Tan', 'mira.tan@example.com', '0126667778', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (18, 'Rajesh Pillai', 'rajesh.p@example.com', '0139988775', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (19, 'Dr. Kamal Hassan', 'kamal.hassan@example.com', '0194445556', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (20, 'Admin Clinic', 'admin.clinic@example.com', '0102223334', NOW(), 'hashed_pw', NULL, NOW(), NOW()),
            (21, 'Zarina Yusuf', 'zarina.yusuf@example.com', '0195566778', NOW(), 'hashed_pw', NULL, NOW(), NOW());

        ");

        // CUSTOMERS
        DB::statement("
            INSERT INTO customers (id, ICNumber, user_id, created_at, updated_at) VALUES
            (1, '010101-14-5678', 1, NOW(), NOW()),
            (2, '020202-10-1122', 2, NOW(), NOW()),
            (3, '990303-07-3344', 3, NOW(), NOW()),
            (4, '970404-08-2233', 4, NOW(), NOW()),
            (5, '950505-12-6677', 5, NOW(), NOW()),
            (6, '011010-10-7788', 6, NOW(), NOW()),
            (7, '001212-05-1112', 7, NOW(), NOW()),
            (8, '981111-06-9988', 8, NOW(), NOW()),
            (9, '960909-09-2233', 9, NOW(), NOW()),
            (10, '990505-10-4455', 10, NOW(), NOW()),
            (11, '970707-14-9988', 11, NOW(), NOW()),
            (12, '950808-12-3322', 12, NOW(), NOW()),
            (13, '980101-08-5544', 13, NOW(), NOW()),
            (14, '991212-07-6677', 14, NOW(), NOW()),
            (15, '000808-11-8899', 15, NOW(), NOW()),
            (16, '030303-10-5544', 16, NOW(), NOW()),
            (17, '971010-06-7788', 17, NOW(), NOW()),
            (18, '940909-08-5566', 18, NOW(), NOW());
        ");

        // STAFF
       
        DB::statement("
            INSERT INTO staff (id, staffID, position, faculty, user_id, customer_id, created_at, updated_at) VALUES
            (1, 'STF001', 'Instructor', 'Faculty of Health Science', 19, NULL, NOW(), NOW()),
            (2, 'STF002', 'Admin', 'Clinic Management', 20, NULL, NOW(), NOW()),
            (3, 'STF003', 'Student Assistant', 'Faculty of IT', 7, 7, NOW(), NOW()),
            (4, 'STF004', 'Intern', 'Faculty of Medical', 8, 8, NOW(), NOW()),
            (5, 'STF005', 'Nurse', 'Faculty of Health', 9, 9, NOW(), NOW()),
            (6, 'STF006', 'Lab Assistant', 'Faculty of Science', 10, 10, NOW(), NOW()),
            (7, 'STF007', 'Receptionist', 'Clinic Admin', 11, 11, NOW(), NOW()),
            (8, 'STF008', 'Medical Intern', 'Faculty of Medicine', 12, 12, NOW(), NOW()),
            (9, 'STF009', 'Instructor', 'Faculty of Movement Therapy', 21, NULL, NOW(), NOW());
        ");

        // PUBLICS
        DB::statement("
            INSERT INTO publics (id, customer_id, created_at, updated_at) VALUES
            (1, 13, NOW(), NOW()),
            (2, 14, NOW(), NOW()),
            (3, 15, NOW(), NOW()),
            (4, 16, NOW(), NOW()),
            (5, 17, NOW(), NOW()),
            (6, 18, NOW(), NOW());
        ");

        // STUDENTS
        DB::statement("
            INSERT INTO students (id, studentId, faculty, program, customer_id, created_at, updated_at) VALUES
            (1, '20220001', 'Faculty of IT', 'Software Engineering', 1, NOW(), NOW()),
            (2, '20220002', 'Faculty of Business', 'Marketing', 2, NOW(), NOW()),
            (3, '20220003', 'Faculty of Health', 'Physiotherapy', 3, NOW(), NOW()),
            (4, '20220004', 'Faculty of Science', 'Biology', 4, NOW(), NOW()),
            (5, '20220005', 'Faculty of Medicine', 'Nursing', 5, NOW(), NOW()),
            (6, '20220006', 'Faculty of IT', 'Information Systems', 6, NOW(), NOW());
        ");

        // SERVICES
       DB::statement("
            INSERT INTO service (id, name, description, fee, created_at, updated_at) VALUES
            (1, 'Electrotherapy', 'Electrical stimulation for pain relief and muscle recovery', '{\"student\": 30, \"public\": 60}', NOW(), NOW()),
            (2, 'Therapeutic Exercises', 'Customized exercise programs for rehabilitation and mobility', '{\"student\": 25, \"public\": 50}', NOW(), NOW()),
            (3, 'Body Composition Analysis', 'Assessment of body fat, muscle mass, and hydration levels', '{\"student\": 20, \"public\": 40}', NOW(), NOW()),
            (4, 'Sport Massage', 'Deep tissue massage for athletic recovery and injury prevention', '{\"student\": 35, \"public\": 70}', NOW(), NOW()),
            (5, 'Sport And Exercise Consultation', 'Expert advice on training, recovery, and injury management', '{\"student\": 40, \"public\": 80}', NOW(), NOW()),
            (6, 'Yoga', 'Guided sessions for flexibility, balance, and mental wellness', '{\"student\": 20, \"public\": 40}', NOW(), NOW());
        ");

        // PATIENT RECORDS
      DB::statement("
            INSERT INTO patientRecord (id, place_of_injury, symptoms, type_of_injury, diagnosis, treatment, notes, referral_letter, customer_id, created_at, updated_at) VALUES
            (1, 'Lower Back', 'Persistent pain and stiffness', 'Muscle strain', 'Lumbar strain', 'Electrotherapy', 'Avoid heavy lifting', NULL, 1, NOW(), NOW()),
            (2, 'Right Knee', 'Swelling and limited mobility', 'Joint inflammation', 'Patellar tendinitis', 'Therapeutic Exercises', 'Stretch daily and ice after activity', NULL, 3, NOW(), NOW()),
            (3, 'Whole Body', 'Fatigue and imbalance', 'General weakness', 'Poor body composition', 'Body Composition Analysis', 'Improve nutrition and hydration', NULL, 5, NOW(), NOW()),
            (4, 'Hamstring', 'Tightness and soreness', 'Muscle tension', 'Overuse injury', 'Sport Massage', 'Hydrate and rest', NULL, 7, NOW(), NOW()),
            (5, 'Left Shoulder', 'Sharp pain during movement', 'Rotator cuff strain', 'Exercise overload', 'Sport And Exercise Consultation', 'Modify workout plan', NULL, 9, NOW(), NOW()),
            (6, NULL, NULL, NULL, NULL, NULL, 'Attending yoga sessions for posture correction, stress relief, and general wellness.', NULL, 11, NOW(), NOW()),
            (7, 'Right Ankle', 'Instability and weakness', 'Ligament sprain', 'Chronic sprain', 'Therapeutic Exercises', 'Use ankle brace and avoid uneven surfaces', NULL, 13, NOW(), NOW()),
            (8, 'Upper Back', 'Muscle knots and discomfort', 'Muscle tension', 'Thoracic strain', 'Sport Massage', 'Adjust sleeping posture and stretch', NULL, 15, NOW(), NOW()),
            (9, 'Hip', 'Tightness and clicking', 'Mobility restriction', 'Hip impingement', 'Yoga', 'Gentle stretching and posture awareness', NULL, 17, NOW(), NOW()),
            (10, 'Left Wrist', 'Pain during flexion', 'Tendonitis', 'Wrist tendonitis', 'Electrotherapy', 'Limit wrist movement', NULL, 2, NOW(), NOW()),
            (11, 'Right Elbow', 'Swelling and pain', 'Overuse injury', 'Tennis elbow', 'Therapeutic Exercises', 'Use elbow strap', NULL, 4, NOW(), NOW()),
            (12, 'Lower Neck', 'Stiffness and fatigue', 'Postural strain', 'Cervical fatigue', 'Sport And Exercise Consultation', 'Ergonomic correction', NULL, 6, NOW(), NOW());
        ");



        // APPOINTMENTS
      DB::statement("
            INSERT INTO appointments (id, date, time, status, service_id, staff_id, patientRecord_id, created_at, updated_at) VALUES
            (1, '2025-11-05', '09:00:00', 'completed', 1, 1, 1, NOW(), NOW()),
            (2, '2025-11-06', '10:30:00', 'confirmed', 2, 9, 2, NOW(), NOW()),
            (3, '2025-11-07', '14:00:00', 'upcoming', 3, 1, 3, NOW(), NOW()),
            (4, '2025-11-08', '15:00:00', 'cancelled', 4, 9, 4, NOW(), NOW()),
            (5, '2025-11-09', '11:00:00', 'completed', 5, 1, 5, NOW(), NOW()),
            (6, '2025-11-10', '10:00:00', 'confirmed', 6, 9, 6, NOW(), NOW()),
            (7, '2025-11-11', '13:30:00', 'completed', 2, 1, 7, NOW(), NOW()),
            (8, '2025-11-12', '09:45:00', 'upcoming', 4, 9, 8, NOW(), NOW()),
            (9, '2025-11-13', '15:15:00', 'confirmed', 6, 1, 9, NOW(), NOW()),
            (10, '2025-11-14', '08:30:00', 'completed', 3, 9, 10, NOW(), NOW()),
            (11, '2025-11-15', '12:00:00', 'completed', 1, 1, 11, NOW(), NOW()),
            (12, '2025-11-16', '16:00:00', 'confirmed', 5, 9, 12, NOW(), NOW());
        ");

        // FEEDBACK
        DB::statement("
            INSERT INTO feedback (id, message, rating, appointment_id, created_at, updated_at) VALUES
            (1, 'Electrotherapy really eased my back pain.', 5, 1, NOW(), NOW()),
            (2, 'The exercises helped me regain knee mobility.', 4, 2, NOW(), NOW()),
            (3, 'Body analysis gave me clear health goals.', 5, 5, NOW(), NOW()),
            (4, 'Massage was relaxing and effective.', 5, 7, NOW(), NOW()),
            (5, 'Consultation helped me adjust my training.', 4, 10, NOW(), NOW()),
            (6, 'Yoga sessions improved my posture and mood.', 5, 6, NOW(), NOW()),
            (7, 'Staff were knowledgeable and supportive.', 5, 11, NOW(), NOW()),
            (8, 'Great rehab experience overall.', 5, 12, NOW(), NOW());
        ");

    }
}
