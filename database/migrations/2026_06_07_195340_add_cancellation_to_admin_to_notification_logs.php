<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_logs', function (Blueprint $table) {
            $table->enum('notification_type', [
                'invitation',
                'reminder_1',
                'reminder_2',
                'confirmation_to_admin',
                'cancellation_to_admin',
            ])->change();
        });
    }

    public function down(): void
    {
        Schema::table('notification_logs', function (Blueprint $table) {
            $table->enum('notification_type', [
                'invitation',
                'reminder_1',
                'reminder_2',
                'confirmation_to_admin',
            ])->change();
        });
    }
};
