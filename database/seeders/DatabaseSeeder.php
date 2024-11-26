<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Jorge Alejandro',
            'email' => 'jorgeajimenezl17@gmail.com',
            'password' => '$2y$12$YpLhC7.deYoqyP5bUK9KgOBDFdHoNOWg82xldHA8DT5/eOfyQ6ojy',
            // 'is_admin' => true,
        ]);

        User::factory(10)->create();
        Task::factory(10)->create();
        Tag::factory(10)->create();

        // Give each task between 1 and 4 tags
        foreach (Task::all() as $task) {
            $tags = Tag::inRandomOrder()->take(random_int(1, 4))->get();
            $task->tags()->attach($tags);
        }

        // Create contributors
        foreach (Task::all() as $task) {
            $contributors = User::inRandomOrder()->take(random_int(1, 3))->get();
            $task->contributors()->attach($contributors);
        }
    }
}
