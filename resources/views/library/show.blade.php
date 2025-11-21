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

                    <!-- Buttons -->
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

    <!-- ⭐ ACTIVITY LOGS SECTION ⭐ -->
    <h4 class="mt-5 mb-3"><i class="ik ik-clock"></i> Activity Logs</h4>

    @php
        use App\Models\ActivityLog;

        $logs = ActivityLog::where('model', App\Models\Book::class)
            ->where('record_id', $book->isbn)
            ->latest()
            ->get();
    @endphp

    @if($logs->count())

        @foreach($logs as $log)
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-light">
                    <strong>
                        <i class="ik ik-refresh-cw text-warning"></i>
                        {{ ucfirst($log->action) }} By:
                        <span class="text-primary">{{ $log->user->name ?? 'System' }}</span>
                    </strong>
                </div>

                <div class="card-body">

                    @php
                        $old = $log->old_values ? json_decode($log->old_values, true) : [];
                        $new = $log->new_values ? json_decode($log->new_values, true) : [];
                    @endphp

                    {{-- CREATED --}}
                    @if($log->action == 'created')
                        <p class="text-success mb-1">
                            <i class="ik ik-plus-circle"></i>
                            A new book record was created.
                        </p>
                    @endif

                    {{-- UPDATED --}}
                    @if($log->action == 'updated')
                        @foreach($new as $column => $newValue)
                            <p class="mb-1">
                                <i class="ik ik-arrow-right text-secondary"></i>
                                <strong>{{ ucfirst($column) }}</strong>:
                                "<span class="text-danger">{{ $old[$column] }}</span>"
                                → "<span class="text-success">{{ $newValue }}</span>"
                            </p>
                        @endforeach
                    @endif

                    {{-- DELETED --}}
                    @if($log->action == 'deleted')
                        <p class="text-danger mb-1">
                            <i class="ik ik-trash"></i>
                            This record was deleted.
                        </p>
                    @endif

                    <hr>

                    {{-- Date & Time --}}
                    <div class="d-flex gap-3">

                        <div class="p-2 border rounded bg-light">
                            <i class="ik ik-calendar"></i>
                            <strong>Date:</strong>
                            {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}
                        </div>

                        <div class="p-2 border rounded bg-light">
                            <i class="ik ik-clock"></i>
                            <strong>Time:</strong>
                            {{ \Carbon\Carbon::parse($log->created_at)->format('h:i A') }}
                        </div>

                    </div>

                </div>
            </div>
        @endforeach

    @else
        <p class="text-muted">No logs found for this book.</p>
    @endif
    <!-- ⭐ END LOGS SECTION ⭐ -->

</div>
@endsection
