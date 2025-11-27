<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE events MODIFY status ENUM('draft', 'pending', 'approved', 'rejected', 'published', 'ongoing', 'completed') DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE events MODIFY status ENUM('pending', 'approved', 'rejected', 'published', 'ongoing', 'completed') DEFAULT 'pending'");
    }
};
