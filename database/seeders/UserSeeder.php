<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        $time = Carbon::now();

        DB::table('users')->insert(
            [
                [
                    'name' => 'Super Admin',
                    'email' => 'vinceharresh@gmail.com',
                    'password' => Hash::make('Password01!'),
                    'created_at' => $time,
                ],
                [
                    'name' => 'Admin',
                    'email' => 'vinceharresh1432@gmail.com',
                    'password' => Hash::make('Password01!'),
                    'created_at' => $time,
                ]
            ]
        );
    }
}
