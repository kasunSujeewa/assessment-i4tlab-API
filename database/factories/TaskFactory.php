<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'status' => $this->faker->randomElement(['Pending', 'In Progress', 'Completed']),
            'user_id' => \App\Models\User::factory(),
            'due_date' => $this->faker->date,
        ];
    }
}
