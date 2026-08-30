<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the blood_requests table for F07 — Create Blood Request.
     */
    public function up(): void
    {
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // the recipient who made the request
            $table->string('patient_name');
            $table->string('blood_group', 5);
            $table->string('location');
            $table->unsignedTinyInteger('units_required')->default(1);
            $table->date('needed_by_date');
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('pending'); // pending, fulfilled, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
