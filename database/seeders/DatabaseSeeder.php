<?php

namespace Database\Seeders;

use App\Models\BloodDonationCampaign;
use App\Models\BloodRequest;
use App\Models\DonationResponse;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with shared standard demo data for all team members.
     * All seeded accounts use the universal password: password123
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');

        // ============================================================
        // 1. ADMIN USER
        // ============================================================
        User::updateOrCreate(
            ['email' => 'admin@bloodconnect.com'],
            [
                'name' => 'System Admin',
                'email' => 'admin@bloodconnect.com',
                'password' => $defaultPassword,
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // ============================================================
        // 2. HOSPITAL ACCOUNTS
        // ============================================================
        $hospitalUser1 = User::updateOrCreate(
            ['email' => 'sbmc@gmail.com'],
            [
                'name' => 'Sher-e-Bangla Medical College Hospital',
                'email' => 'sbmc@gmail.com',
                'password' => $defaultPassword,
                'role' => 'hospital',
                'email_verified_at' => now(),
            ]
        );
        $hospital1 = Hospital::updateOrCreate(
            ['user_id' => $hospitalUser1->id],
            [
                'hospital_name' => 'Sher-e-Bangla Medical College Hospital (SBMC)',
                'license_number' => 'SBMC-MED-2024-001',
                'address' => 'Band Road, Barisal',
                'division' => 'Barisal',
                'district' => 'Barisal',
                'contact_phone' => '+8801711223344',
                'contact_email' => 'sbmc@gmail.com',
                'is_verified' => true,
            ]
        );

        $hospitalUser2 = User::updateOrCreate(
            ['email' => 'hospital@bloodconnect.com'],
            [
                'name' => 'Dhaka Medical College Hospital',
                'email' => 'hospital@bloodconnect.com',
                'password' => $defaultPassword,
                'role' => 'hospital',
                'email_verified_at' => now(),
            ]
        );
        $hospital2 = Hospital::updateOrCreate(
            ['user_id' => $hospitalUser2->id],
            [
                'hospital_name' => 'Dhaka Medical College Hospital (DMCH)',
                'license_number' => 'DMCH-MED-2023-889',
                'address' => 'Secretariat Road, Ramna, Dhaka',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'contact_phone' => '+8801700112233',
                'contact_email' => 'hospital@bloodconnect.com',
                'is_verified' => true,
            ]
        );

        $hospitalUser3 = User::updateOrCreate(
            ['email' => 'chittagong.gen@gmail.com'],
            [
                'name' => 'Chittagong General Hospital',
                'email' => 'chittagong.gen@gmail.com',
                'password' => $defaultPassword,
                'role' => 'hospital',
                'email_verified_at' => now(),
            ]
        );
        $hospital3 = Hospital::updateOrCreate(
            ['user_id' => $hospitalUser3->id],
            [
                'hospital_name' => 'Chittagong General Hospital',
                'license_number' => 'CGH-MED-2024-554',
                'address' => 'Anderkilla, Chittagong',
                'division' => 'Chittagong',
                'district' => 'Chittagong',
                'contact_phone' => '+8801811445566',
                'contact_email' => 'chittagong.gen@gmail.com',
                'is_verified' => false, // Pending verification for admin testing
            ]
        );

        // ============================================================
        // 3. DONOR ACCOUNTS
        // ============================================================
        $donor1 = User::updateOrCreate(
            ['email' => 'maishatanjil017@gmail.com'],
            [
                'name' => 'Maisha Tanjil',
                'email' => 'maishatanjil017@gmail.com',
                'password' => $defaultPassword,
                'role' => 'donor',
                'blood_group' => 'A+',
                'phone' => '+8801712345678',
                'location' => 'Dhaka',
                'is_available' => true,
                'last_donation_date' => now()->subMonths(4), // Eligible (>90 days)
                'email_verified_at' => now(),
            ]
        );

        $donor2 = User::updateOrCreate(
            ['email' => 'vtae@gmail.com'],
            [
                'name' => 'Kim Taehyung',
                'email' => 'vtae@gmail.com',
                'password' => $defaultPassword,
                'role' => 'donor',
                'blood_group' => 'O+',
                'phone' => '+8801812345678',
                'location' => 'Chittagong',
                'is_available' => true,
                'last_donation_date' => now()->subDays(30), // Ineligible (<90 days)
                'email_verified_at' => now(),
            ]
        );

        $donor3 = User::updateOrCreate(
            ['email' => 'donor.b@bloodconnect.com'],
            [
                'name' => 'Rafiq Ahmed',
                'email' => 'donor.b@bloodconnect.com',
                'password' => $defaultPassword,
                'role' => 'donor',
                'blood_group' => 'B+',
                'phone' => '+8801912345678',
                'location' => 'Dhaka',
                'is_available' => true,
                'last_donation_date' => null, // Eligible immediately
                'email_verified_at' => now(),
            ]
        );

        $donor4 = User::updateOrCreate(
            ['email' => 'donor.onegate@bloodconnect.com'],
            [
                'name' => 'Farhan Karim',
                'email' => 'donor.onegate@bloodconnect.com',
                'password' => $defaultPassword,
                'role' => 'donor',
                'blood_group' => 'O-',
                'phone' => '+8801512345678',
                'location' => 'Dhaka',
                'is_available' => true,
                'last_donation_date' => now()->subMonths(5),
                'email_verified_at' => now(),
            ]
        );

        $donor5 = User::updateOrCreate(
            ['email' => 'donor.ab@bloodconnect.com'],
            [
                'name' => 'Nusrat Jahan',
                'email' => 'donor.ab@bloodconnect.com',
                'password' => $defaultPassword,
                'role' => 'donor',
                'blood_group' => 'AB+',
                'phone' => '+8801612345678',
                'location' => 'Sylhet',
                'is_available' => false, // Currently unavailable
                'last_donation_date' => now()->subMonths(6),
                'email_verified_at' => now(),
            ]
        );

        // ============================================================
        // 4. RECIPIENT ACCOUNTS
        // ============================================================
        $recipient1 = User::updateOrCreate(
            ['email' => 'tan3095@gmail.com'],
            [
                'name' => 'Tanisa',
                'email' => 'tan3095@gmail.com',
                'password' => $defaultPassword,
                'role' => 'recipient',
                'blood_group' => 'A+',
                'phone' => '+8801733445566',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'hospital_name' => 'Dhaka Medical College Hospital',
                'blood_units_needed' => 2,
                'medical_condition' => 'Post-surgery recovery',
                'date_of_birth' => '2001-05-14',
                'gender' => 'Female',
                'email_verified_at' => now(),
            ]
        );

        $recipient2 = User::updateOrCreate(
            ['email' => 'ryan21@gmail.com'],
            [
                'name' => 'Ryan',
                'email' => 'ryan21@gmail.com',
                'password' => $defaultPassword,
                'role' => 'recipient',
                'blood_group' => 'O+',
                'phone' => '+8801833445566',
                'division' => 'Chittagong',
                'district' => 'Chittagong',
                'hospital_name' => 'Chittagong Medical College Hospital',
                'blood_units_needed' => 1,
                'medical_condition' => 'Thalassemia monthly transfusion',
                'date_of_birth' => '1999-11-20',
                'gender' => 'Male',
                'email_verified_at' => now(),
            ]
        );

        $recipient3 = User::updateOrCreate(
            ['email' => 'kookie@gmail.com'],
            [
                'name' => 'Jeon Jungkook',
                'email' => 'kookie@gmail.com',
                'password' => $defaultPassword,
                'role' => 'recipient',
                'blood_group' => 'B+',
                'phone' => '+8801933445566',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'hospital_name' => 'Evercare Hospital Dhaka',
                'blood_units_needed' => 3,
                'medical_condition' => 'Accident emergency',
                'date_of_birth' => '1997-09-01',
                'gender' => 'Male',
                'email_verified_at' => now(),
            ]
        );

        // ============================================================
        // 5. BLOOD REQUESTS
        // ============================================================
        $req1 = BloodRequest::updateOrCreate(
            ['patient_name' => 'Shahed Alam', 'user_id' => $recipient1->id],
            [
                'hospital_id' => $hospital2->id,
                'patient_name' => 'Shahed Alam',
                'blood_group' => 'A+',
                'location' => 'Dhaka',
                'units_required' => 2,
                'needed_by_date' => now()->addDays(2),
                'notes' => 'Emergency bypass surgery blood required immediately at DMCH.',
                'priority' => 'emergency',
                'status' => 'pending',
            ]
        );

        $req2 = BloodRequest::updateOrCreate(
            ['patient_name' => 'Mariam Begum', 'user_id' => $recipient2->id],
            [
                'hospital_id' => $hospital3->id,
                'patient_name' => 'Mariam Begum',
                'blood_group' => 'O+',
                'location' => 'Chittagong',
                'units_required' => 1,
                'needed_by_date' => now()->addDays(5),
                'notes' => 'Regular thalassemia blood transfusion needed.',
                'priority' => 'urgent',
                'status' => 'accepted',
            ]
        );

        $req3 = BloodRequest::updateOrCreate(
            ['patient_name' => 'Tariq Hasan', 'user_id' => $recipient3->id],
            [
                'hospital_id' => $hospital1->id,
                'patient_name' => 'Tariq Hasan',
                'blood_group' => 'B+',
                'location' => 'Dhaka',
                'units_required' => 3,
                'needed_by_date' => now()->addDays(4),
                'notes' => 'Platelet and blood needed for dengue fever complications.',
                'priority' => 'emergency',
                'status' => 'pending',
            ]
        );

        $req4 = BloodRequest::updateOrCreate(
            ['patient_name' => 'Fatima Noor', 'user_id' => $recipient1->id],
            [
                'hospital_id' => $hospital2->id,
                'patient_name' => 'Fatima Noor',
                'blood_group' => 'O-',
                'location' => 'Dhaka',
                'units_required' => 1,
                'needed_by_date' => now()->subDays(3),
                'notes' => 'Cesarean delivery requirement.',
                'priority' => 'normal',
                'status' => 'fulfilled',
            ]
        );

        // ============================================================
        // 6. DONATION RESPONSES (F13 & F17)
        // ============================================================
        // Donor 1 accepted Request 2
        DonationResponse::updateOrCreate(
            ['blood_request_id' => $req2->id, 'donor_id' => $donor1->id],
            [
                'status' => 'accepted',
                'completed_at' => null,
            ]
        );

        // Donor 4 fulfilled Request 4
        DonationResponse::updateOrCreate(
            ['blood_request_id' => $req4->id, 'donor_id' => $donor4->id],
            [
                'status' => 'accepted',
                'completed_at' => now()->subDays(2),
                'confirmed_by' => $hospitalUser2->id,
                'confirmation_notes' => 'Donation verified and completed successfully by DMCH staff.',
            ]
        );

        // ============================================================
        // 7. BLOOD DONATION CAMPAIGNS (F18)
        // ============================================================
        BloodDonationCampaign::updateOrCreate(
            ['title' => 'National Blood Drive 2026 - Dhaka', 'hospital_id' => $hospital2->id],
            [
                'description' => 'Annual voluntary blood donation drive organized by Dhaka Medical College Hospital in collaboration with BloodConnect.',
                'campaign_date' => now()->addDays(14)->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'location' => 'DMCH Auditorium, Dhaka',
                'target_units' => 100,
                'collected_units' => 0,
                'status' => 'upcoming',
            ]
        );

        BloodDonationCampaign::updateOrCreate(
            ['title' => 'Barisal Community Blood Donation Camp', 'hospital_id' => $hospital1->id],
            [
                'description' => 'Free health checkup and voluntary blood donation camp organized by SBMC.',
                'campaign_date' => now()->addDays(20)->toDateString(),
                'start_time' => '10:00:00',
                'end_time' => '16:00:00',
                'location' => 'SBMC Campus Ground, Barisal',
                'target_units' => 50,
                'collected_units' => 0,
                'status' => 'upcoming',
            ]
        );
    }
}
