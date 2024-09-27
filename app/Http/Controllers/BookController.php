<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // We need 'title' to add filtering by the book title
        $title = $request->input('title');

        /*
         * The 'when' method in Laravel is a useful tool for simplifying conditional queries.
         * It accepts a value (like $title) as the first argument, and a closure or arrow function as the second.
         * If $title is not null or empty, the closure runs, allowing you to add additional query logic.
         * If $title is empty, the function is skipped. This helps streamline the logic without additional if-statements
         */
        // $books = Book::when($title, function ($query, $title) {
        //     return  $query->title($title);
        // })

        // We can use arrow function instead of closure
        $books = Book::when(
            $title,
            // 'title()' is a query scope we added to he Book model
            fn($query, $title) => $query->title($title)
        )
            ->get();

        /*
         * With resource controllers, we should should name views to match route names (e.g., 'books.index').
         * This is a commonly used Laravel Convention
         */
        /*
         * To pass $books to the template, we have 2 options:
         * 1. Use 'compact' function (it will find a variable with the name 'books'
         * and turn it into an array with the key 'books' and the value of the variable with the same name)
         * 2. Use array and pass the $books variable
         */
        // return view('books.index', compact($books));
        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
