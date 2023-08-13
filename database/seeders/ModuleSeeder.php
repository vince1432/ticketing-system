<?php

namespace Database\Seeders;

use App\Models\Module;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Module::factory()
        // ->count(4)
        // ->create();

        $time = Carbon::now();

        $data = [
            ['name' => 'Bug Tracker', 'description' => 'Bug Tracker', 'created_at' => $time],
        ];

        DB::table('modules')->insert($data);
    }
}
