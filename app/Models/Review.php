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
}
