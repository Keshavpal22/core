<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Show Books List Page (AG-GRID HIERARCHY VERSION)
     */
    public function index()
    {
        // All books sorted for better tree grouping
        $books = Book::orderBy('genre')
            ->orderBy('publisher')
            ->orderBy('author')
            ->orderBy('title')
            ->get();

        /**
         * Convert flat books into AG-Grid tree-compatible structure:
         * Hierarchy Path = [ genre, publisher, author, title ]
         */
        $hierarchyData = $books->map(function ($book) {
            return [
                "isbn" => $book->isbn,
                "title" => $book->title,
                "author" => $book->author,
                "genre" => $book->genre,
                "publisher" => $book->publisher,
                "publication_year" => $book->publication_year,
                "total_copies" => $book->total_copies,
                "available_copies" => $book->available_copies,
                "issued_by" => $book->issued_by,

                // *** MOST IMPORTANT LINE FOR TREE VIEW ***
                "hierarchy" => [
                    $book->genre,
                    $book->publisher,
                    $book->author,
                    $book->title . ' (' . $book->isbn . ')'
                ],
            ];
        });

        // Send tree data to blade
        return view('library.index', [
            'books' => $hierarchyData
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('library.create');
    }

    /**
     * Store new book
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'author'            => 'required|string|max:150',
            'genre'             => 'required|string|max:50',
            'isbn'              => 'required|integer|unique:books,isbn',
            'publisher'         => 'required|string|max:150',
            'publication_year'  => 'required|integer|min:1800|max:2099',
            'total_copies'      => 'required|integer|min:1',
            'available_copies'  => 'required|integer|min:0|max:10000',
            'issued_by'         => 'required|string|max:150',
        ]);

        Book::create($request->all());

        return redirect()->route('books.index')
            ->with('success', 'Book added successfully!');
    }

    /**
     * Show a single book
     */
    public function show($isbn)
    {
        $book = Book::where('isbn', $isbn)->firstOrFail();
        return view('library.show', compact('book'));
    }

    /**
     * Edit book
     */
    public function edit($isbn)
    {
        $book = Book::where('isbn', $isbn)->firstOrFail();
        return view('library.edit', compact('book'));
    }

    /**
     * Update book
     */
    public function update(Request $request, $isbn)
    {
        $book = Book::where('isbn', $isbn)->firstOrFail();

        $request->validate([
            'title'             => 'required|string|max:255',
            'author'            => 'required|string|max:150',
            'genre'             => 'required|string|max:50',
            'isbn'              => [
                'required',
                'integer',
                Rule::unique('books', 'isbn')->ignore($book->isbn, 'isbn')
            ],
            'publisher'         => 'required|string|max:150',
            'publication_year'  => 'required|integer|min:1800|max:2099',
            'total_copies'      => 'required|integer|min:1',
            'available_copies'  => 'required|integer|min:0|max:10000',
            'issued_by'         => 'required|string|max:150',
        ]);

        $book->update($request->all());

        return redirect()->route('books.index')
            ->with('success', 'Book updated successfully!');
    }

    /**
     * Delete book
     */
    public function destroy($isbn)
    {
        $book = Book::where('isbn', $isbn)->firstOrFail();
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully!');
    }
}
