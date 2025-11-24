<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // ---------------------------
    // CREATE BOOK
    // ---------------------------
    public function store(Request $request)
    {
        $book = Book::create($request->all());

        // Log Create Action
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'model'     => Book::class,
            'record_id' => $book->id,
            'action'    => 'created',
            'old_data'  => null,
            'new_data'  => $book->toArray(),
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Book Created!');
    }

    // ---------------------------
    // UPDATE BOOK
    // ---------------------------
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $old  = $book->toArray(); // old values before update

        $book->update($request->all());
        $new  = $book->toArray(); // new values after update

        // Log Update Action
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'model'     => Book::class,
            'record_id' => $book->id,
            'action'    => 'updated',
            'old_data'  => $old,
            'new_data'  => $new,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Book Updated!');
    }

    // ---------------------------
    // DELETE BOOK
    // ---------------------------
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $old  = $book->toArray(); // values before delete

        $book->delete();

        // Log Delete Action
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'model'     => Book::class,
            'record_id' => $id,
            'action'    => 'deleted',
            'old_data'  => $old,
            'new_data'  => null,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Book Deleted!');
    }
}
