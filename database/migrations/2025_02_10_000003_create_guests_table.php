<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('attending')->nullable();
            $table->string('dietary_restrictions')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedTinyInteger('position')->default(1);
            $table->timestamps();

            $table->index('invitation_id');
            $table->index('is_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
