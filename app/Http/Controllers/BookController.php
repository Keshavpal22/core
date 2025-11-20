<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller
{
    /**
     * Show Books List Page
     */
    public function index()
    {
        return view('library.index');
    }

    /**
     * DataTable AJAX List
     */
    public function list(Request $request)
    {
        $books = Book::orderBy('isbn', 'DESC')->get(); // FIXED

        return DataTables::of($books)
            ->addIndexColumn()
            ->addColumn('action', function ($book) {

                $id = $book->isbn; // FIXED

                return '
                    <div class="action-btns">
                        <a href="' . route('books.show', $id) . '" class="btn btn-info btn-sm">
                            <i class="ik ik-eye"></i>
                        </a>

                        <a href="' . route('books.edit', $id) . '" class="btn btn-success btn-sm">
                            <i class="ik ik-edit-2"></i>
                        </a>


                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
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
        $book = Book::where('isbn', $isbn)->firstOrFail(); // FIXED
        return view('library.show', compact('book'));
    }

    /**
     * Edit book
     */
    public function edit($isbn)
    {
        $book = Book::where('isbn', $isbn)->firstOrFail(); // FIXED
        return view('library.edit', compact('book'));
    }

    /**
     * Update book
     */
    public function update(Request $request, $isbn)
    {
        $book = Book::where('isbn', $isbn)->firstOrFail(); // FIXED

        $request->validate([
            'title'             => 'required|string|max:255',
            'author'            => 'required|string|max:150',
            'genre'             => 'required|string|max:50',
            'isbn'              => [
                'required',
                'integer',
                Rule::unique('books', 'isbn')->ignore($book->isbn, 'isbn') // FIXED
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
        $book = Book::where('isbn', $isbn)->firstOrFail(); // FIXED
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully!');
    }
}
