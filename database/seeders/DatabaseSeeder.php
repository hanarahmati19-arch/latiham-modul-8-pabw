<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminEmail = config('app.admin.email');
        $adminPassword = config('app.admin.password');

        if (filled($adminEmail) && filled($adminPassword)) {
            User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => 'Administrator',
                    'password' => $adminPassword,
                    'role' => 'admin',
                ],
            );
        }
    }
}
