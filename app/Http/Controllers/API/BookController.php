<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookController extends Controller
{
    private function jsonResponse($status, $data = [], $message = '')
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ]);
    }

    public function index()
    {
        try {
            $books = Cache::remember('books_list', 60, fn() => Book::paginate(10));
            return $this->jsonResponse(200, $books, "All books fetched successfully.");
        } catch (Exception $th) {
            return $this->jsonResponse(500, [], "Something went wrong! Please try again later.");
        }
    }

    public function store(StoreBookRequest $request)
    {
        try {
            $book = Book::create($request->validated());
            Cache::forget('books_list'); // Invalidate cache
            return $this->jsonResponse(200, $book, "Book Created Successfully.");
        } catch (Exception $th) {
            return $this->jsonResponse(500, [], "Something went wrong! Please try later again.");
        }
    }

    public function show($id)
    {
        try {
            $book = Book::findOrFail($id);
            return $this->jsonResponse(200, $book, "Book fetched successfully.");
        } catch (ModelNotFoundException $th) {
            return $this->jsonResponse(400, [], "Book not found.");
        } catch (Exception $th) {
            return $this->jsonResponse(500, [], "Something went wrong! Please try again later.");
        }
    }

    public function update(UpdateBookRequest $request, $id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->update($request->validated());
            Cache::forget('books_list');
            return $this->jsonResponse(200, $book, "Book updated Successfully.");
        } catch (ModelNotFoundException $th) {
            return $this->jsonResponse(400, [], "Book not found.");
        } catch (Exception $th) {
            return $this->jsonResponse(500, [], "Something went wrong! Please try later again.");
        }
    }

    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();
            Cache::forget('books_list');
            return $this->jsonResponse(200, [], "Book deleted Successfully.");
        } catch (ModelNotFoundException $th) {
            return $this->jsonResponse(400, [], "Book not found.");
        } catch (Exception $th) {
            return $this->jsonResponse(500, [], "Something went wrong! Please try later again.");
        }
    }
}
