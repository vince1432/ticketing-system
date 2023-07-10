<?php

namespace Database\Seeders;

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
        $data = [
            ['level' => 1, 'name' => 'Low'],
            ['level' => 2, 'name' => 'Moderate'],
            ['level' => 3, 'name' => 'High'],
            ['level' => 4, 'name' => 'Highest']
        ];
        DB::table('ticket_prioties')->insert($data);
    }
}
