<?php

namespace App\Models;

use Carbon\Month;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Book extends Model
{
    use HasFactory;

    /*
     * The method name 'reviews' represents the relationship.
     * The 'hasMany' method defines a one-to-many relationship between the Book and Review models, indicating that a book can have many reviews
     * Review::class refers to the related Review model
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /* 
     * Defines a local query scope for searching titles that contain a specific keyword.
     * This allows users to perform the query without needing to specify operators or wildcards.
     * Example usage: \App\Models\Book::title('pariatur')->get();
     * Note: When calling the scope, use the lowercase version of the method name, omitting the 'scope' prefix
     */
    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    // Retrieves books with the highest number of reviews received in a given time frame
    public function scopePopular(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withCount([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ])
            ->orderBy('reviews_count', 'desc');
    }

    // Retrieves the highest-rated books in a given time frame
    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ], 'rating')
            ->orderBy('reviews_avg_rating', 'desc');
    }

    /*
     * We use 'having' for aggregate values like 'reviews_count' instead of 'where', which is for standard columns.
     * 'having' filters dynamically calculated aggregates, while 'where' targets existing table columns.
     * Before calling this scope, we must first call popular() to ensure 'reviews_count' is available
     */
    public function scopeMinReviews(Builder $query, int $minReviews): Builder|QueryBuilder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    // Since the query is an object and objects are passed by reference, not by copy, we don’t need to return anything from this method.
    // We are directly modifying the existing query object within the method
    private function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    // The scope will return books that received the most reviews in the last month, along with their average ratings,
    // but only for books that have received at least two reviews
    public function scopePopularLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);
    }

    public function scopePopularLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(5);
    }


    /*
     * The 'scopePopularLastMonth' and 'scopeHighestRatedLastMonth' return the average rating and count of ratings
     * The order of these query scopes matters, as the results are primarily sorted by the first column, with subsequent columns acting as tiebreakers
     */
    public function scopeHighestRatedLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(2);
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(5);
    }
}

/*
 * Query example:
 * \App\Models\Book::highestRated('2024-06-01', '2024-08-30')->popular('2024-06-01', '2024-08-30')->minReviews(2)->get();
 */