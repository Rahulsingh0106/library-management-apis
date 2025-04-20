<?php

namespace App\Http\Controllers\API;

use App\Events\BookBorrowed;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BorrowController extends Controller
{
    public function borrow($id)
    {
        try {
            $book = Book::findOrFail($id);

            if ($book->is_borrowed) {
                return response()->json([
                    'status' => 400,
                    'data' => [],
                    'message' => 'Book already borrowed.'
                ]);
            }

            $book->update(['is_borrowed' => 1]);

            Borrowing::create([
                'user_id' => auth()->id(),
                'book_id' => $book->id
            ]);

            event(new BookBorrowed($book, auth()->user())); // Dispatch event

            return response()->json([
                'status' => 200,
                'data' => $book,
                'message' => 'Book borrowed successfully.'
            ]);
        } catch (ModelNotFoundException $th) {
            return response()->json([
                'status' => 400,
                'data' => [],
                'message' => 'Book not found.'
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' => 500,
                'data' => [],
                'message' => 'Something went wrong! Please try again later.'
            ]);
        }
    }

    public function return($id)
    {
        try {
            $book = Book::findOrFail($id);
            $borrowing = Borrowing::where('user_id', auth()->id())
                ->where('book_id', $book->id)->first();

            if (!$borrowing) {
                return response()->json([
                    'status' => 400,
                    'data' => [],
                    'message' => 'You did not borrow this book.'
                ]);
            }

            $book->update(['is_borrowed' => 0]);
            $borrowing->delete();

            return response()->json([
                'status' => 200,
                'data' => [],
                'message' => 'Book returned successfully'
            ]);
        } catch (ModelNotFoundException $th) {
            return response()->json([
                'status' => 500,
                'data' => [],
                'message' => 'Book not found.'
            ]);
        } catch (Exception $th) {
            return response()->json([
                'status' => 500,
                'data' => [],
                'message' => 'Something went wrong! Please try again later.'
            ]);
        }
    }
}
