<?php

use App\Models\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Ensure the column is not null
        Task::whereNull('description')->update(['description' => '']);

        Schema::table('tasks', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });
    }
};
