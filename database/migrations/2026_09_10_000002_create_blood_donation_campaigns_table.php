<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sprint 4 Foundation — Blood donation campaigns table (F18).
     * Hospitals can create campaigns / blood drives.
     */
    public function up(): void
    {
        Schema::create('blood_donation_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('campaign_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location');
            $table->unsignedInteger('target_units')->default(0);
            $table->unsignedInteger('collected_units')->default(0);
            $table->string('status', 20)->default('upcoming'); // upcoming, active, completed, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_donation_campaigns');
    }
};
