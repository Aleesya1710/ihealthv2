<?php
// database/seeders/AppointmentAndPatientRecordSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentAndPatientRecordSeeder extends Seeder
{
    private $symptomsOptions = [
        ['chronic_pain', 'back_pain', 'shoulder_injury', 'neck_pain'],
        ['knee_injury'],
        ['stress', 'poor_flexibility'],
        ['back_pain', 'posture_issues'],
        ['stress', 'anxiety'],
        ['weight_management'],
        ['flexibility_issues'],
        ['muscle_tension'],
        ['stress', 'fatigue'],
        ['poor_posture', 'back_pain'],
        ['general_wellness'],
        ['sports_injury', 'knee_injury'],
        ['post_stroke'],
    ];
    
    private $typeOfInjuryOptions = [
        ['chronic_pain', 'back_pain', 'shoulder_injury', 'neck_pain'],
        ['knee_injury'],
        ['wellness'],
        ['muscle_strain'],
    ];
    
    private $diagnosisOptions = [
        ['post_surgery_recovery', 'chronic_condition_care', 'injury_rehabilitation'],
        ['injury_rehabilitation', 'pain_management', 'mobility_support'],
        ['stress_management', 'flexibility_improvement'],
        ['posture_correction', 'back_strengthening'],
        ['stress_management', 'mental_wellness'],
        ['fitness_maintenance'],
        ['tension_relief'],
        ['stress_management', 'energy_boost'],
        ['posture_improvement', 'back_strengthening'],
    ];

    private $treatmentOptions = [
        ['reduce_pain', 'increase_flexibility', 'enhance_strength'],
        ['yoga_therapy'],
        ['stretching'],
        ['yoga_therapy', 'breathing_exercises'],
        ['yoga_therapy', 'core_strengthening'],
        ['yoga_therapy', 'flexibility_training'],
        ['enhance_strength', 'balance_training', 'range_of_motion'],
        ['reduce_pain', 'flexibility_training'],
    ];
    
    private $notes = [
        'Patient responding well to treatment. Continue with current therapy plan.',
        'Good progress. Continue exercises at home.',
        'First time yoga participant. Needs beginner modifications.',
        'Friend of Ahmad Danial. Office worker.',
        'Friend of Ahmad Danial. Looking for relaxation.',
        'Appointment cancelled by customer.',
        'Regular yoga practitioner.',
        'Partner of Siti. New to yoga.',
        'Friend 1 of Nurul Aina.',
        'Friend 2 of Nurul Aina.',
        'Friend 3 of Nurul Aina.',
        'New patient. First session.',
        'Follow-up session.',
        'Chronic pain management.',
        'Post-injury rehabilitation.',
    ];

    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('patientRecord')->delete();
        DB::table('appointments')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $today = Carbon::parse('2026-02-08'); 
        $appointmentId = 1;
        $patientRecordId = 1;
        $this->createAppointment($appointmentId++, '2025-11-10', '09:00:00', 'completed', 4, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 1, 'place' => 'Chest', 'service_id' => 4],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-11-15', '10:00:00', 'completed', 1, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 2, 'place' => 'Head', 'service_id' => 1],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-11-20', '14:00:00', 'completed', 2, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 3, 'place' => 'Left Leg', 'service_id' => 2],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-11-25', '11:00:00', 'completed', 5, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 4, 'place' => 'Right Arm', 'service_id' => 5],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-02', '09:00:00', 'completed', 3, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 5, 'place' => 'Chest', 'service_id' => 3],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-05', '13:00:00', 'completed', 6, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 6, 'place' => null, 'service_id' => 6],
            ['id' => $patientRecordId++, 'customer_id' => 8, 'place' => null, 'service_id' => 6], 
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-08', '10:00:00', 'completed', 4, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 8, 'place' => 'Left Arm', 'service_id' => 4],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-10', '15:00:00', 'cancelled', 1, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 9, 'place' => 'Head', 'service_id' => 1],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-12', '11:00:00', 'completed', 2, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 1, 'place' => 'Right Leg', 'service_id' => 2],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-15', '14:00:00', 'completed', 5, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 2, 'place' => 'Chest', 'service_id' => 5],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-18', '09:00:00', 'completed', 6, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 3, 'place' => null, 'service_id' => 6],
            ['id' => $patientRecordId++, 'customer_id' => 4, 'place' => null, 'service_id' => 6],
            ['id' => $patientRecordId++, 'customer_id' => 5, 'place' => null, 'service_id' => 6],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-20', '13:00:00', 'completed', 3, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 6, 'place' => 'Left Leg', 'service_id' => 3],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-22', '10:00:00', 'cancelled', 4, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 8, 'place' => 'Right Arm', 'service_id' => 4], 
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-24', '16:00:00', 'completed', 1, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 8, 'place' => 'Head', 'service_id' => 1], 
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-27', '11:00:00', 'completed', 2, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 9, 'place' => 'Chest', 'service_id' => 2],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-28', '14:00:00', 'completed', 6, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 1, 'place' => null, 'service_id' => 6],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-29', '09:00:00', 'completed', 5, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 2, 'place' => 'Left Arm', 'service_id' => 5],
        ]);
        
        $this->createAppointment($appointmentId++, '2025-12-30', '15:00:00', 'completed', 3, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 3, 'place' => 'Right Leg', 'service_id' => 3],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-01-09', '09:00:00', 'completed', 4, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 4, 'place' => 'Chest', 'service_id' => 4],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-01-15', '10:00:00', 'completed', 1, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 5, 'place' => 'Head', 'service_id' => 1],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-01-20', '14:00:00', 'completed', 2, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 6, 'place' => 'Left Leg', 'service_id' => 2],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-01-27', '09:00:00', 'completed', 4, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 8, 'place' => 'Right Arm', 'service_id' => 4], 
        ]);
        
        $this->createAppointment($appointmentId++, '2026-01-29', '10:00:00', 'completed', 2, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 8, 'place' => 'Left Leg', 'service_id' => 2], 
        ]);
        
        $this->createAppointment($appointmentId++, '2026-02-04', '11:00:00', 'cancelled', 3, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 9, 'place' => 'Head', 'service_id' => 3],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-02-06', '09:00:00', 'completed', 5, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 1, 'place' => 'Chest', 'service_id' => 5],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-02-07', '13:00:00', 'completed', 6, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 2, 'place' => null, 'service_id' => 6],
            ['id' => $patientRecordId++, 'customer_id' => 3, 'place' => null, 'service_id' => 6],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-02-09', '14:00:00', 'upcoming', 6, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 4, 'place' => null, 'service_id' => 6],
            ['id' => $patientRecordId++, 'customer_id' => 5, 'place' => null, 'service_id' => 6],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-02-09', '13:00:00', 'upcoming', 6, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 6, 'place' => null, 'service_id' => 6],
            ['id' => $patientRecordId++, 'customer_id' => 8, 'place' => null, 'service_id' => 6], 
            ['id' => $patientRecordId++, 'customer_id' => 8, 'place' => null, 'service_id' => 6], 
        ]);
        
        $this->createAppointment($appointmentId++, '2026-02-11', '15:00:00', 'upcoming', 6, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 9, 'place' => null, 'service_id' => 6],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-02-11', '15:00:00', 'upcoming', 1, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 1, 'place' => 'Right Arm', 'service_id' => 1],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-02-11', '09:00:00', 'upcoming', 6, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 2, 'place' => null, 'service_id' => 6],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-02-13', '12:00:00', 'upcoming', 3, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 3, 'place' => 'Left Arm', 'service_id' => 3],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-02-20', '15:00:00', 'upcoming', 3, 3, [
            ['id' => $patientRecordId++, 'customer_id' => 4, 'place' => 'Right Leg', 'service_id' => 3],
        ]);
        
        $this->createAppointment($appointmentId++, '2026-02-26', '15:00:00', 'upcoming', 3, 2, [
            ['id' => $patientRecordId++, 'customer_id' => 5, 'place' => 'Chest', 'service_id' => 3],
        ]);

        $this->command->info('✅ Appointments and Patient Records seeded successfully!');
        $this->command->info('📊 2025 Appointments: 23');
        $this->command->info('📊 2026 Appointments: 12');
        $this->command->info('📊 Total Appointments: ' . ($appointmentId - 1));
        $this->command->info('📊 Total Patient Records: ' . ($patientRecordId - 1));
        $this->command->info('📊 Customer IDs used: 1-6, 8-9 (no 7)');
    }

    private function createAppointment($id, $date, $time, $status, $serviceId, $staffId, $patients)
    {
        DB::table('appointments')->insert([
            'id' => $id,
            'date' => $date,
            'time' => $time,
            'status' => $status,
            'service_id' => $serviceId,
            'staff_id' => $staffId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($patients as $patient) {
            $this->createPatientRecord(
                $patient['id'],
                $patient['customer_id'],
                $id,
                $patient['place'],
                $serviceId,
                $staffId,
                $status
            );
        }
    }

    private function createPatientRecord($id, $customerId, $appointmentId, $placeOfInjury, $serviceId, $staffId, $status)
    {
        $isYoga = ($serviceId == 6);
        $isStaff3 = ($staffId == 3);
        $isCompleted = ($status == 'completed');
        $isCancelled = ($status == 'cancelled');

        if ($isYoga || $isCancelled) {
            DB::table('patientRecord')->insert([
                'id' => $id,
                'place_of_injury' => $placeOfInjury,
                'symptoms' => $isYoga ? json_encode($this->getRandomSymptoms()) : null,
                'type_of_injury' => $isYoga ? json_encode(['wellness']) : null,
                'diagnosis' => $isCompleted && $isYoga ? json_encode($this->getRandomDiagnosis()) : null,
                'treatment' => $isCompleted && $isYoga && !$isStaff3 ? json_encode(['yoga_therapy']) : null,
                'notes' => $this->getRandomNote(),
                'referral_letter' => null, 
                'customer_id' => $customerId,
                'appointment_id' => $appointmentId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
        
            DB::table('patientRecord')->insert([
                'id' => $id,
                'place_of_injury' => $placeOfInjury,
                'symptoms' => json_encode($this->getRandomSymptoms()),
                'type_of_injury' => json_encode($this->getRandomTypeOfInjury()),
                'diagnosis' => $isCompleted ? json_encode($this->getRandomDiagnosis()) : null,
                'treatment' => $isCompleted && !$isStaff3 ? json_encode($this->getRandomTreatment()) : null,
                'notes' => $this->getRandomNote(),
                'referral_letter' => $isCompleted ? $this->getRandomReferralLetter() : null,
                'customer_id' => $customerId,
                'appointment_id' => $appointmentId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function getRandomSymptoms()
    {
        return $this->symptomsOptions[array_rand($this->symptomsOptions)];
    }

    private function getRandomTypeOfInjury()
    {
        return $this->typeOfInjuryOptions[array_rand($this->typeOfInjuryOptions)];
    }

    private function getRandomDiagnosis()
    {
        return $this->diagnosisOptions[array_rand($this->diagnosisOptions)];
    }

    private function getRandomTreatment()
    {
        return $this->treatmentOptions[array_rand($this->treatmentOptions)];
    }

    private function getRandomNote()
    {
        return $this->notes[array_rand($this->notes)];
    }

    private function getRandomReferralLetter()
    {
        $letters = [
            'referrals/letter_2.pdf',
            'referrals/1UnTH0UC36YOJMMWebLmRaRC7j3ytKZ3w5iYAcaiii.pdf',
            'referrals/qvKMnfMXQN3Et30LPmNECG5TmveqR5Vqco4EzBjGCp.pdf',
            'referrals/biIvPderor9EH92V9LAZw8HpPIxTwEq7TWSeyTj.pdf',
        ];
        return $letters[array_rand($letters)];
    }
}