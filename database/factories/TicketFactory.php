<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\TicketPrioty;
use App\Models\User;
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
            'email' => fake()->email(),
            'priority_id' => TicketPrioty::all(['id'])->random(),
            'assigned_to' => User::all(['id'])->random(),
            'closed_at' => NULL,
            'closed_by' => NULL,
            'resolution' => fake()->paragraph(3),
        ];
    }
}
