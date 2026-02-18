<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->decimal('ticket_price', 10, 2)->default(0)->after('end_date');
            $table->unsignedInteger('available_tickets')->default(0)->after('ticket_price');
            $table->string('event_image')->nullable()->after('available_tickets');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['ticket_price', 'available_tickets', 'event_image']);
        });
    }
};
