@extends('layouts.app')

@section('content')
<h1 class="mb-10 text-2xl">Books</h1>

{{-- We need a form to search using the 'title' --}}
<form></form>
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
                        {{ number_format($book->reviews_avg_rating, 1) }}
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