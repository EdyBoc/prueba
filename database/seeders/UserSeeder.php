<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $usuario = User::create([
            'name' => 'Usuario Normal',
            'email' => 'usuario@example.com',
            'password' => Hash::make('password'),
        ]);
        $usuario->assignRole('usuario');

        $tecnico = User::create([
            'name' => 'Técnico',
            'email' => 'tecnico@example.com',
            'password' => Hash::make('password'),
        ]);
        $tecnico->assignRole('tecnico');

        $analista = User::create([
            'name' => 'Analista',
            'email' => 'analista@example.com',
            'password' => Hash::make('password'),
        ]);
        $analista->assignRole('analista');

    }
}
