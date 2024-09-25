<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            // we can use the 'name' property instead of the 'name()' method if we don't need to specify anything special
            'author' => fake()->name,
            /* We set the 'created_at' and 'updated_at' timestamps manually because, later in the course, we will query data from specific timeframes,
             * such as last week or last month. This requires the timestamps to be generated in a specific format
             * dateTimeBetween('-2 years') will ensure that the books are added within the last two years.
             * dateTimeBetween('created_at', 'now') - the 'updated_at' will start from the value of the 'created_at' column and can be up to the current date and time
             */
            'created_at' => fake()->dateTimeBetween('-2 years'),
            'updated_at' => fake()->dateTimeBetween('created_at', 'now'),
        ];
    }
}
