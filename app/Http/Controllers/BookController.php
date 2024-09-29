<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
// use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Optional 'title' parameter is used for filtering by the book title
        $title = $request->input('title');
        // Retrieve the filter using the request->input() method, and specify a default empty ('') value
        $filter = $request->input('filter', '');
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
        );

        /*
         * To implement filtering based on the filter variable, we use a match expression (similar to a switch statement but directly returns a value)
         * and assign the result to the $books variable
         */
        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest(),
        };
        // $books  = $books->get();

        /*
         * To cache data, we can use one of the following:
         * 1. Static Cache facade
         * 2. Global helper function
         * Both methods return the same object and include all necessary methods
         */
        // $books = Cache::remember('books', 3600, fn() => $books->get());

        $cacheKey = 'books:' . $filter . ':' . '$title';
        $books = cache()->remember($cacheKey, 3600, fn() => $books->get());

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
    public function show(Book $book)
    {
        // The 'load' method allows us to load specific relationships of a model after the model itself has been loaded, rather than relying on lazy loading in the template.
        // This approach reduces the number of queries. However, it makes sense to eager load relationships when dealing with multiple entities (books, in our case),
        // as lazy loading would make a separate query for each entity's relationships (e.g., each book's reviews).

        return view(
            'books.show',
            ['book' => $book->load([
                'reviews' => fn($query) => $query->latest()
            ])]
        );
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
