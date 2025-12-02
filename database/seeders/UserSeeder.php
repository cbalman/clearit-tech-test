<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Crear un Agente (Role: agent)
        DB::table('users')->insert([
            'name' => 'Agente Clearit',
            'email' => 'agent@clearit.test',
            'password' => Hash::make('password'), // Contraseña simple para pruebas
            'role' => 'agent', // <-- Rol AGENT
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2. Crear un Usuario (Role: user)
        DB::table('users')->insert([
            'name' => 'Usuario Cliente',
            'email' => 'user@clearit.test',
            'password' => Hash::make('password'), // Contraseña simple para pruebas
            'role' => 'user', // <-- Rol USER
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Opcional: Crear 10 usuarios aleatorios más usando el Factory
        // Asegúrate de que el UserFactory tenga valores de 'role' sensatos.
        // \App\Models\User::factory(10)->create();
    }
}
