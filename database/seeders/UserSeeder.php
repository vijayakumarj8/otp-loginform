<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    //     User::create([
    //     'name' => 'Vijay',
    //     'email' => 'vijayj888888@gmail.com',
    //     'password' => Hash::make('123456'),
        
    // ]);

    
User::firstOrCreate(
    ['email' => 'vijayj888888@gmail.com'],
    [
        'name' => 'Vijay',
        'password' => Hash::make('123456')
    ]
);

User::firstOrCreate(
    ['email' => 'vijayakumarj.dev@gmail.com'],
    [
        'name' => 'Ajay',
        'password' => Hash::make('654321')
    ]
);
User::firstOrCreate(
    ['email' => 'ajay@gmail.com'],
    [
        'name' => 'Ajayyy',
        'password' => Hash::make('123456')
    ]
);
    }
}

