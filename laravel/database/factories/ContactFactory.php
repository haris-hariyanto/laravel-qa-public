<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->firstName() . ' ' . fake()->lastName(),
            'email' => fake()->email(),
            'subject' => fake()->sentence(),
            'message' => implode('<br>', fake()->paragraphs(3)),
            'status' => 'UNREAD',
        ];
    }
}
