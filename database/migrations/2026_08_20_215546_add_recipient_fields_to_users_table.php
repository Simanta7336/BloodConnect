<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds recipient-specific columns to the shared users table.
     * Existing donor columns (blood_group, phone, location, is_available,
     * last_donation_date) are intentionally reused for recipients.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('last_donation_date');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->string('division', 100)->nullable()->after('gender');
            $table->string('district', 100)->nullable()->after('division');
            $table->string('hospital_name')->nullable()->after('district');
            $table->unsignedTinyInteger('blood_units_needed')->nullable()->after('hospital_name');
            $table->date('required_by_date')->nullable()->after('blood_units_needed');
            $table->text('medical_condition')->nullable()->after('required_by_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'division',
                'district',
                'hospital_name',
                'blood_units_needed',
                'required_by_date',
                'medical_condition',
            ]);
        });
    }
};
