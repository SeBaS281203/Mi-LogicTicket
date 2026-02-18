<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('venue_name');
            $table->string('venue_address');
            $table->string('city');
            $table->string('country')->default('Peru');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft');
            $table->timestamps();

            $table->index(['status', 'start_date']);
            $table->index('city');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
