<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Jorge Alejandro',
            'email' => 'jorgeajimenezl17@gmail.com',
            'password' => '$2y$12$YpLhC7.deYoqyP5bUK9KgOBDFdHoNOWg82xldHA8DT5/eOfyQ6ojy',
            // 'is_admin' => true,
        ]);

    }
}
