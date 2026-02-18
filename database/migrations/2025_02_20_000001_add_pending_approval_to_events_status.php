<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM('draft', 'pending_approval', 'published', 'cancelled') DEFAULT 'draft'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM('draft', 'published', 'cancelled') DEFAULT 'draft'");
        }
    }
};
