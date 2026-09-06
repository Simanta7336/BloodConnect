<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates donation_responses table for F13 — Accept/Reject Donation Request.
     */
    public function up(): void
    {
        Schema::create('donation_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_request_id')->constrained('blood_requests')->onDelete('cascade');
            $table->foreignId('donor_id')->constrained('users')->onDelete('cascade');
            $table->string('status', 20); // accepted, rejected
            $table->timestamps();

            // Enforce that a donor can only respond once to a specific blood request
            $table->unique(['blood_request_id', 'donor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_responses');
    }
};
