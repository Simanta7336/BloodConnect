<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sprint 4 Foundation — Donation confirmation fields (F17).
     * Allows hospitals to confirm that a donation was completed.
     */
    public function up(): void
    {
        Schema::table('donation_responses', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('status');
            $table->foreignId('confirmed_by')->nullable()->after('completed_at')
                  ->constrained('users')->nullOnDelete();
            $table->text('confirmation_notes')->nullable()->after('confirmed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donation_responses', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn(['completed_at', 'confirmed_by', 'confirmation_notes']);
        });
    }
};
