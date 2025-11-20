{{-- resources/views/library/show.blade.php --}}
@extends('layouts.main')

@section('title', 'View Book Details')

@push('head')
<style>
    .readonly-box {
        background: #f8f9fa;
        border: 1px solid #ddd;
        padding: 10px 12px;
        border-radius: 6px;
        height: 40px;
        display: flex;
        align-items: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="page-header">
        <div class="row align-items-end">

            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-book bg-blue"></i>
                    <div class="d-inline">
                        <h5>Book Details</h5>
                        <span>Complete information of the selected book</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ik ik-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Books</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>

    @include('include.message')

    <!-- Book Info Card -->
    <div class="row mt-4">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">

                    {{-- MAIN DETAILS --}}
                    <h5 class="mb-4">Book Information</h5>
                    <div class="row">

                        <div class="col-sm-6 form-group">
                            <label>Book Title</label>
                            <div class="readonly-box">{{ $book->title }}</div>
                        </div>

                        <div class="col-sm-6 form-group">
                            <label>Author</label>
                            <div class="readonly-box">{{ $book->author }}</div>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label>Genre</label>
                            <div class="readonly-box">{{ $book->genre }}</div>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label>ISBN</label>
                            <div class="readonly-box">{{ $book->isbn }}</div>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label>Publisher</label>
                            <div class="readonly-box">{{ $book->publisher }}</div>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label>Publication Year</label>
                            <div class="readonly-box">{{ $book->publication_year }}</div>
                        </div>
                    </div>

                    <h5 class="mb-4 mt-5">Inventory Details</h5>
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label>Total Copies</label>
                            <div class="readonly-box">{{ $book->total_copies }}</div>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label>Available Copies</label>
                            <div class="readonly-box">{{ $book->available_copies }}</div>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label>Issued By</label>
                            <div class="readonly-box">{{ $book->issued_by ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <h5 class="mb-4 mt-5">System Information</h5>
                    <div class="row">

                        <div class="col-sm-4 form-group">
                            <label>Record Created On</label>
                            <div class="readonly-box">
                                {{ $book->created_at ? $book->created_at->format('d M, Y h:i A') : 'N/A' }}
                            </div>
                        </div>

                        <div class="col-sm-4 form-group">
                            <label>Last Updated On</label>
                            <div class="readonly-box">
                                {{ $book->updated_at ? $book->updated_at->format('d M, Y h:i A') : 'N/A' }}
                            </div>
                        </div>

                    </div>

                    <div class="mt-5 text-center">

                        <a href="{{ route('books.index') }}" class="btn btn-secondary btn-lg px-4">
                            <i class="ik ik-arrow-left"></i> Back
                        </a>

                        <form action="{{ route('books.destroy', $book->isbn) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to DELETE this book record?\n\nTitle: {{ $book->title }}\nAuthor: {{ $book->author }}\nISBN: {{ $book->isbn }}\n\nThis action CANNOT be undone!');">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-lg px-4">
                                <i class="ik ik-trash-2"></i> Delete Book
                            </button>
                        </form>

                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
