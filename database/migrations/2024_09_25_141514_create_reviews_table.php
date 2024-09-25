<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // // Adding the Foreign Key
            // // 1. book_id is a column that needs to be of the same type as the primary key of the related table (books), but it is not yet a foreign key.
            // $table->unsignedBigInteger('book_id');

            $table->text('review');
            $table->unsignedTinyInteger('rating');

            $table->timestamps();

            // /* 
            //  * 2. Next line defines the foreign key:
            //  * foreign('book_id'): defines book_id as a foreign key
            //  * references('id'): specifies that the foreign key references the id column on the other table (in our case, the books table)
            //  * on('books'): indicates that the book_id foreign key is related to the id column of the books table
            //  * onDelete('cascade'): specifies the delete behavior. If a book record is deleted, all related reviews will also be deleted automatically.
            //  * This operation happens at the database level

            //  * Adding this foreign key ensures that the book_id column must contain a valid ID from the books table.
            //  * This means that reviews cannot exist independently; they must be associated with a specific book
            //  */
            // $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            // For a simpler relationship, we can use the shorter syntax.
            // The constrained() method automatically sets up the foreign key constraint.
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
