<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
