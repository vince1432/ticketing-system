<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ticket::factory()
        ->count(30)
        ->has(TicketComment::factory()->count(3), 'comments')
        ->create();
    }
}
