<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = Carbon::now();

        DB::table('roles')->insert(
            [
                [
                    'name' => 'Super Admin',
                    'level' => 1,
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'name' => 'Admin',
                    'level' => 2,
                    'created_at' => $time,
                    'updated_at' => $time
                ],
                [
                    'name' => 'Agent',
                    'level' => 3,
                    'created_at' => $time,
                    'updated_at' => $time
                ]
            ]
        );

        DB::table('role_user')->insert(
            [
                [
                    'role_id' => 1,
                    'user_id' => 1
                ],
                [
                    'role_id' => 1,
                    'user_id' => 2
                ]
            ]
        );
    }
}
