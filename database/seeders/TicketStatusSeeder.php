<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = Carbon::now();

        $data = [
            ['name' => 'New', 'created_at' => $time],
            ['name' => 'In Progress', 'created_at' => $time],
            ['name' => 'Blocked', 'created_at' => $time],
            ['name' => 'Closed', 'created_at' => $time]
        ];
        DB::table('ticket_statuses')->insert($data);
    }
}
