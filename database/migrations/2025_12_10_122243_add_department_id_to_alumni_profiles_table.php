<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni_profiles', function (Blueprint $table) {
            // Add the optional department foreign key
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();
            
            // Add an index for better query performance
            $table->index('department_id', 'idx_alumni_profiles_department');
        });
    }

    public function down(): void
    {
        Schema::table('alumni_profiles', function (Blueprint $table) {
            $table->dropIndex('idx_alumni_profiles_department');
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
