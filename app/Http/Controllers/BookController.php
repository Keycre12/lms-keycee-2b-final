<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function getBooks()
    {
        $books = Book::all();
        return response()->json(['books' => $books]);
    }

    public function addBook(Request $request)
    {
        $books = $request->all();

        if (isset($books[0])) {
            // Batch creation
            $createdBooks = [];

            foreach ($books as $bookData) {
                $validator = Validator::make($bookData, [
                    'b_title' => ['required', 'string', 'max:255'],
                    'b_author' => ['required', 'string', 'max:255'],
                    'b_category' => ['required', 'string', 'max:255'],
                    'b_availability' => ['nullable', 'in:Available,Not Available,Pending'],
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                $book = Book::create([
                    'b_title' => $bookData['b_title'],
                    'b_author' => $bookData['b_author'],
                    'b_category' => $bookData['b_category'],
                    'b_availability' => $bookData['b_availability'] ?? 'Pending',
                ]);

                $createdBooks[] = $book;
            }

            return response()->json(['message' => 'Books created successfully!', 'books' => $createdBooks]);
        } else {
            // Single book creation
            $request->validate([
                'b_title' => ['required', 'string', 'max:255'],
                'b_author' => ['required', 'string', 'max:255'],
                'b_category' => ['required', 'string', 'max:255'],
                'b_availability' => ['nullable', 'in:Available,Not Available,Pending'],
            ]);

            $book = Book::create([
                'b_title' => $request->b_title,
                'b_author' => $request->b_author,
                'b_category' => $request->b_category,
                'b_availability' => $request->b_availability ?? 'Pending',
            ]);

            return response()->json(['message' => 'Book successfully created!', 'book' => $book]);
        }
    }

    public function editBook(Request $request, $id)
    {
        $request->validate([
            'b_title' => ['required', 'string', 'max:255'],
            'b_author' => ['required', 'string', 'max:255'],
            'b_category' => ['required', 'string', 'max:255'],
            'b_availability' => ['required', 'in:Available,Not Available,Pending'],
        ]);

        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found!'], 404);
        }

        $book->update([
            'b_title' => $request->b_title,
            'b_author' => $request->b_author,
            'b_category' => $request->b_category,
            'b_availability' => $request->b_availability,
        ]);

        return response()->json(['message' => 'Book successfully updated!', 'book' => $book]);
    }

    public function deleteBook($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found!'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Book successfully deleted!']);
    }
}
