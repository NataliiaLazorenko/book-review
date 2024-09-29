<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['review', 'rating'];

    // The 'belongsTo' method defines the inverse side of the one-to-many relationship, specifying that each review belongs to one book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // The simplest way to react to events in a model is by adding a static booted method to the model and defining event handlers inside it
    protected static function booted()
    {
        static::updated(callback: fn(Review $review) => cache()->forget('book:' . $review->book_id));
        static::deleted(fn(Review $review) => cache()->forget('book:' . $review->book_id));
        static::created(callback: fn(Review $review) => cache()->forget('book:' . $review->book_id));
    }
}
