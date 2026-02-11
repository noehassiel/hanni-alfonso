<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->string('access_code', 12)->unique();
            $table->string('magic_link_token')->unique()->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->unsignedTinyInteger('max_guests')->default(1);
            $table->text('personal_message')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'declined', 'maybe'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('invitation_sent_at')->nullable();
            $table->timestamp('first_reminder_sent_at')->nullable();
            $table->timestamp('second_reminder_sent_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->unsignedInteger('access_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('access_code');
            $table->index('magic_link_token');
            $table->index('status');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
