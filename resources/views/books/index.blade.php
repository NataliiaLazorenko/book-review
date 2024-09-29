@extends('layouts.app')

@section('content')
<h1 class="mb-10 text-2xl">Books</h1>

{{-- This form allows searching by 'title'. 
'GET' is the default method, but we're including it explicitly for clarity. 
The current route is set as 'action' to send the form data back to the same page

1. Keeping the active tab (filter) selected during searches.
We use the form since it controls the query parameters sent and hidden input.
In GET form, all submitted values appear as query variables in the URL, unlike POST requests, where data is sent in the request body and isn't visible in the URL.
Hidden input is not visible to the user but allows to send any value, such as the current filter, along with the form data. --}}
<form method="GET" action="{{ route('books.index') }}" class="mb-4 flex items-center space-x-2">
    {{-- The 'name' attribute is required to identify the input and will be included with the form submission.
    The 'request' function is used to show the previous input value. If the form was submitted before, 
    this input will be pre-filled with the last used filter value --}}
    <input type="text" name="title" placeholder="Search by title" value="{{ request('title') }}" class="input h-10" />
    <input type="hidden" name="filter" value={{ request('filter') }} />
    <button type="submit" class="btn h-10">Search</button>
    <!-- The simplest way to clear the form is to link to the page without query parameters -->
    <a href="{{ route('books.index') }}" class="btn h-10">Clear</a>
</form>

<div class="filter-container mb-4 flex">
    {{-- Using the 'php' directive in Blade --}}
    @php
    /*
    * This array contains keys that represent what to pass to the query parameters for the request, allowing specific queries to be performed.
    * We could define this array in the controller and pass it along, but since it's not directly tied to the logic,
    * it makes sense to define it here in the template.
    */
    $filters = [
    '' => 'Latest',
    'popular_last_month' => 'Popular Last Month',
    'popular_last_6months' => 'Popular Last 6 Months',
    'highest_rated_last_month' => 'Highest Rated Last Month',
    'highest_rated_last_6months' => 'Highest Rated Last 6 Month',
    ]
    @endphp

    {{-- Using key-value pairs: the key identifies the filter, and the label is displayed to the user --}}
    @foreach ($filters as $key => $label)
    {{-- A query parameter (in our case, its name is 'filter') can be passed as an array in the second parameter of the 'route' function.
    The 'request' helper gives us access to the 'filter' query parameter
    
    2. Selecting the 'Latest' tab by default when no filter is specified or if it is empty.
    We update the condition for applying the class: if the query parameter 'filter' is null or
    if the current tab is the first element of the filters (the 'Latest' tab), we apply the 'filter-item-active' class.
    
    3. Retaining all other request parameters when navigating to any of the tab links.
    To keep all other request parameters while switching between tab links, we extend the array of query parameters.
    This is achieved using the request()->query() method, which returns an array of all current query parameters.
    Since this method always returns an array and we're already inside an array context, we utilize the spread operator --}}
    <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}"
        class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<ul>
    @forelse ( $books as $book)
    <li class="mb-4">
        <div class="book-item">
            <div
                class="flex flex-wrap items-center justify-between">
                <div class="w-full flex-grow sm:w-auto">
                    <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
                    <span class="book-author">by {{ $book->author }}</span>
                </div>
                <div>
                    <div class="book-rating">
                        {{-- number_format is a PHP function. The '1' specifies one decimal place after the comma or dot (based on locale settings) --}}
                        {{-- {{ number_format($book->reviews_avg_rating, 1) }} --}}
                        <x-star-rating :rating="$book->reviews_avg_rating" />
                    </div>
                    <div class="book-review-count">
                        {{-- 'Str::plural' method allows to display the singular or plural form of the word based on the count. It excepts a noun and a count
                        If the count is singular, it returns the singular form (e.g., "review"); if it's plural, it converts it to the plural form ("reviews") --}}
                        out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
                    </div>
                </div>
            </div>
        </div>
    </li>
    @empty
    <li class="mb-4">
        <div class="empty-book-item">
            <p class="empty-text">No books found</p>
            {{-- We need a reset link to clear any applied filters. The simplest way to implement this is to link to the index page without any parameters --}}
            <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
        </div>
    </li>
    @endforelse
</ul>
@endsection