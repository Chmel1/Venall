<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('habbits', function (Blueprint $table) {
            $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->enum('frequency_type', ['daily', 'weekly', 'custom'])->default('daily');
    $table->json('days_of_week')->nullable(); // для weekly: ["mon", "wed", "fri"]
    $table->integer('interval_days')->default(1); // для custom: раз в N дней
    $table->integer('reward_points')->default(1);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habbits');
    }
};
