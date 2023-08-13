<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\Ticket;
use App\Models\TicketPrioty;
use App\Models\TicketStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->sentence(5),
            'summary' => fake()->paragraph(3),
            'priority_id' => TicketPrioty::all(['id'])->random(),
            'status_id' => TicketStatus::all(['id'])->random(),
            'module_id' => Module::all(['id'])->random(),
            'assigned_to' => User::all(['id'])->random(),
            'created_at' => fake()->date(),
            'closed_at' => NULL,
            'closed_by' => NULL,
            'resolution' => fake()->paragraph(3),
        ];
    }
}
