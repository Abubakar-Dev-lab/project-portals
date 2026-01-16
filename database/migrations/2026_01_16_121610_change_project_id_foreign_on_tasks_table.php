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
        Schema::table('tasks', function (Blueprint $table) {
            // 1. Drop the old cascade foreign key
            // Note: Laravel naming convention for foreign keys is: table_column_foreign
            $table->dropForeign(['project_id']);

            // 2. Re-add it with RESTRICT logic
            $table->foreignId('project_id')
                ->change() // Ensures we are modifying the existing column
                ->constrained()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['project_id']);

            // Restore it to the original cascade logic in case of rollback
            $table->foreignId('project_id')
                ->change()
                ->constrained()
                ->cascadeOnDelete();
        });
    }
};
