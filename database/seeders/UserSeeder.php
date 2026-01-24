<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador GITIC',
            'email' => 'admin@sena.edu.co',
            'password' => Hash::make('S3n@yopal'), // Usa una clave robusta
            'email_verified_at' => now(),
        ]);
    }
}