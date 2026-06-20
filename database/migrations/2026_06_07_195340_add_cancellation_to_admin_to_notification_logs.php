<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $withCancellation = [
        'invitation',
        'reminder_1',
        'reminder_2',
        'confirmation_to_admin',
        'cancellation_to_admin',
    ];

    /**
     * @var list<string>
     */
    private array $withoutCancellation = [
        'invitation',
        'reminder_1',
        'reminder_2',
        'confirmation_to_admin',
    ];

    public function up(): void
    {
        $this->setNotificationTypeValues($this->withCancellation);
    }

    public function down(): void
    {
        $this->setNotificationTypeValues($this->withoutCancellation);
    }

    /**
     * @param  list<string>  $values
     */
    private function setNotificationTypeValues(array $values): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            $quoted = implode(',', array_map(fn (string $value): string => "'{$value}'", $values));

            DB::statement('ALTER TABLE notification_logs DROP CONSTRAINT IF EXISTS notification_logs_notification_type_check');
            DB::statement("ALTER TABLE notification_logs ADD CONSTRAINT notification_logs_notification_type_check CHECK (notification_type IN ({$quoted}))");

            return;
        }

        Schema::table('notification_logs', function (Blueprint $table) use ($values) {
            $table->enum('notification_type', $values)->change();
        });
    }
};
