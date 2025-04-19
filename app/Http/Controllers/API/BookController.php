<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

class BookController extends Controller
{
    public function index()
    {
        $books = Cache::remember('books_list', 60, function () {
            return Book::paginate(10);
        });
        return response()->json([
            "status" => 200,
            "data" => $books,
            "message" => "All books fetched successfully."
        ]);
    }


    public function store(StoreBookRequest $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
        ]);

        $book = Book::create($validated);
        Cache::forget('books_list'); // Invalidate cache

        return response()->json([
            'status' => 200,
            'data' => $book,
            'message' => "Book Created Successfully.",
        ]);
    }

    public function show(Book $book)
    {
        return $book;
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());
        Cache::forget('books_list');

        return response()->json([
            'status' => 200,
            'data' => $book,
            'message' => "Book updated Successfully.",
        ]);
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json([
            'status' => 200,
            'data' => [],
            'message' => "Book deleted Successfully.",
        ]);
    }
}
