<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->text('description');

            $table->date('campaign_date');

            $table->time('start_time');

            $table->time('end_time');

            $table->string('location');

            $table->string('organizer');

            $table->string('contact');

            $table->string('status')->default('upcoming');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
