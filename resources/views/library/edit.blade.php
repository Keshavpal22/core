{{-- resources/views/library/create.blade.php --}}
@extends('layouts.main')

@section('title', 'Add New Book')

@section('content')
<div class="container-fluid">

    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-plus-circle bg-blue"></i>
                    <div class="d-inline">
                        <h5>Add New Book</h5>
                        <span>Enter complete book details</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}"><i class="ik ik-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('books.index') }}">Books</a>
                        </li>
                        <li class="breadcrumb-item active">Add Book</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="row justify-content-center mt-4">
        <div class="col-md-10">
            <div class="card">

                <div class="card-header">
                    <h3>Create New Book</h3>
                </div>

                <div class="card-body">

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mt-2 mb-0">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Success --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('books.store') }}">
                        @csrf

                        <div class="row">

                            <!-- Title -->
                            <div class="col-sm-6 form-group">
                                <label>Book Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control"
                                       value="{{ old('title') }}" required>
                            </div>

                            <!-- Author -->
                            <div class="col-sm-6 form-group">
                                <label>Author <span class="text-danger">*</span></label>
                                <input type="text" name="author" class="form-control"
                                       value="{{ old('author') }}" required>
                            </div>

                            <!-- Genre -->
                            <div class="col-sm-4 form-group">
                                <label>Genre <span class="text-danger">*</span></label>
                                <input type="text" name="genre" class="form-control"
                                       value="{{ old('genre') }}" required>
                            </div>

                            <!-- ISBN -->
                            <div class="col-sm-4 form-group">
                                <label>ISBN <span class="text-danger">*</span></label>
                                <input type="number" name="isbn" class="form-control"
                                       value="{{ old('isbn') }}" required>
                            </div>

                            <!-- Publisher -->
                            <div class="col-sm-4 form-group">
                                <label>Publisher <span class="text-danger">*</span></label>
                                <input type="text" name="publisher" class="form-control"
                                       value="{{ old('publisher') }}" required>
                            </div>

                            <!-- Publication Year -->
                            <div class="col-sm-4 form-group">
                                <label>Publication Year <span class="text-danger">*</span></label>
                                <input type="number" name="publication_year" class="form-control"
                                       min="1500" max="2050"
                                       value="{{ old('publication_year') }}" required>
                            </div>

                            <!-- Total Copies -->
                            <div class="col-sm-4 form-group">
                                <label>Total Copies <span class="text-danger">*</span></label>
                                <input type="number" name="total_copies" class="form-control"
                                       min="1" value="{{ old('total_copies') }}" required>
                            </div>

                            <!-- Available Copies -->
                            <div class="col-sm-4 form-group">
                                <label>Available Copies <span class="text-danger">*</span></label>
                                <input type="number" name="available_copies" class="form-control"
                                       min="0" value="{{ old('available_copies') }}" required>
                            </div>

                            <!-- Issued By -->
                            <div class="col-sm-6 form-group">
                                <label>Issued By (Optional)</label>
                                <input type="text" name="issued_by" class="form-control"
                                       value="{{ old('issued_by') }}">
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="ik ik-save"></i> Save Book
                            </button>
                            <a href="{{ route('books.index') }}" class="btn btn-light">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

</div>
@endsection
