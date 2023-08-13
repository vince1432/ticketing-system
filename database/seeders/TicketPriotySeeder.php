<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketPriotySeeder extends Seeder
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
            ['level' => 1, 'name' => 'Low', 'created_at' => $time,],
            ['level' => 2, 'name' => 'Moderate', 'created_at' => $time,],
            ['level' => 3, 'name' => 'High', 'created_at' => $time,],
            ['level' => 4, 'name' => 'Highest', 'created_at' => $time,]
        ];
        DB::table('ticket_prioties')->insert($data);
    }
}
