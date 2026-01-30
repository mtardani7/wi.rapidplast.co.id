<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'hrd@rapidplast.co.id',
            ],
            [
                'name' => 'hrd rapidplast',
                'password' => Hash::make('HRDL0ph3^_^'), 
            ]
        );
    }
}
