<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthSeeder extends Seeder
{
    /**
     * Usuarios de prueba para el módulo de autenticación.
     * Ejecutar: php artisan db:seed --class=AuthSeeder
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@logicticket.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Organizador Demo',
                'email' => 'organizer@logicticket.com',
                'password' => Hash::make('password'),
                'role' => 'organizer',
            ],
            [
                'name' => 'Cliente Demo',
                'email' => 'client@logicticket.com',
                'password' => Hash::make('password'),
                'role' => 'client',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
