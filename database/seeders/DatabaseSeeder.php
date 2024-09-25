<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory(33)->create() // creates 33 books
            // the each() method calls a callback for each book, with $book representing the generated book model
            ->each(function ($book) {
                $numReviews = random_int(5, 30); // randomly determines the number of reviews for each book

                Review::factory()
                    ->count($numReviews) // specifies how many reviews to generate
                    ->good() // an overriding method that sets the ratings to be rather good
                    ->for($book) // associates the created review with the specified book (sets book_id in the ReviewFactory to the ID of the created book)
                    ->create(); // creates the Review model and stores it in the database
            });

        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
                ->average()
                ->for($book)
                ->create();
        });

        Book::factory(34)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
                ->bad()
                ->for($book)
                ->create();
        });
    }
}
