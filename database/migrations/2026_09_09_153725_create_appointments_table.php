<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Blood request being scheduled
            $table->foreignId('blood_request_id')
                ->constrained('blood_requests')
                ->cascadeOnDelete();

            // Recipient who created the request
            $table->foreignId('recipient_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Donor who accepted the request
            $table->foreignId('donor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Appointment details
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('location');

            // pending / confirmed / cancelled / completed
            $table->string('status')->default('confirmed');

            $table->timestamps();

            // One appointment per blood request
            $table->unique('blood_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

