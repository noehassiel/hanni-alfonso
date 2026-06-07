<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE notification_logs MODIFY COLUMN notification_type ENUM('invitation','reminder_1','reminder_2','confirmation_to_admin','cancellation_to_admin') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE notification_logs MODIFY COLUMN notification_type ENUM('invitation','reminder_1','reminder_2','confirmation_to_admin') NOT NULL");
    }
};
