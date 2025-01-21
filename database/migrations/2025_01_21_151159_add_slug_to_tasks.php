<?php

use App\Models\Task;
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
            $table->string('slug')->nullable();
        });

        $tasks = Task::all();
        foreach ($tasks as $task) {
            $task->slug = Task::generateRandomSlug();
            $task->save();
        }

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('slug')->index()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
