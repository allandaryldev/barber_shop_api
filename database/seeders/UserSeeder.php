<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'firstname' => 'Allan',
                'lastname' => 'Daryl',
                'username' => 'dev@allan',
                'password' => Hash::make('password'),
                'type' => 'developer'
            ],
        ];
        User::insert($user);
    }
}
