<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
}
