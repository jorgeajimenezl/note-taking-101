<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('create:user {email}', function ($email) {
    $password = $this->secret('Enter the password');
    $name = $this->ask('Enter the name');
    $this->info("Creating user with email: $email, name: $name");

    User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
    ]);
})->purpose('Create a new user');

Artisan::command('delete:user {email}', function ($email) {
    $this->info("Deleting user with email: $email");
    User::where('email', $email)->delete();
})->purpose('Delete a user');

Artisan::command('verify:user {email}', function ($email) {
    $this->info("Verifying user with email: $email");
    User::where('email', $email)->update(['email_verified_at' => now()]);
})->purpose('Verify a user');

Schedule::command('backup:clean')->dailyAt('03:10')->timezone('Europe/Madrid');
Schedule::command('backup:run --only-db')->dailyAt('03:15')->timezone('Europe/Madrid');
Schedule::command('backup:run')->sundays()->at('03:20')->timezone('Europe/Madrid');
