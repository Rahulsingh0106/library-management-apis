<?php

namespace App\Http\Controllers\API;

use App\Events\BookBorrowed;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function borrow($id)
    {
        $book = Book::findOrFail($id);

        if ($book->is_borrowed) {
            return response()->json(['message' => 'Book already borrowed'], 400);
        }

        $book->update(['is_borrowed' => 1]);

        Borrowing::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id
        ]);

        event(new BookBorrowed($book, auth()->user())); // Dispatch event

        return response()->json(['message' => 'Book borrowed successfully']);
    }

    public function return($id)
    {
        $book = Book::findOrFail($id);
        $borrowing = Borrowing::where('user_id', auth()->id())
            ->where('book_id', $book->id)->first();

        if (!$borrowing) {
            return response()->json(['message' => 'You did not borrow this book'], 403);
        }

        $book->update(['is_borrowed' => false]);
        $borrowing->delete();

        return response()->json(['message' => 'Book returned successfully']);
    }
}
