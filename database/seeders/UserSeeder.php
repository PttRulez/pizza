<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Enums\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Дон Пепперони',
            'email' => 'pepperoni@mail.ru',
            'password' => Hash::make('pepperoni'),
            'role' => Role::ADMIN
        ]);
        
        User::factory()->create([
            'name' => 'Васёк',
            'email' => 'vasya@mail.ru',
            'password' => Hash::make('vasya'),
            'role' => Role::CLIENT,
        ]);
        
        User::factory()->create([
            'name' => 'Обжоркин',
            'email' => 'obzhorkin@mail.ru',
            'password' => Hash::make('obzhorkin'),
            'role' => Role::CLIENT
        ]);
    }
}
