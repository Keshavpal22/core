{{-- resources/views/users/show.blade.php --}}
@extends('layouts.main')
@section('title', 'View User Details')

@push('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
<style>
    .readonly-field {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        color: #495057;
        pointer-events: none;
        height: 38px;
    }
    .readonly-textarea {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        color: #495057;
        pointer-events: none;
        resize: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <!-- Page Header (breadcrumb only) -->
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-6">
                <div class="page-header-title">
                    <i class="ik ik-user bg-blue"></i>
                    <div class="d-inline">
                        <h5>View User Details</h5>
                        <span>Complete profile information</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ik ik-home"></i> Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">View User</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @include('include.message')

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <h5 class="mb-4">Personal Information</h5>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control readonly-field" value="{{ $user->first_name }}" readonly>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control readonly-field" value="{{ $user->last_name }}" readonly>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Email Address</label>
                            <input type="email" class="form-control readonly-field" value="{{ $user->email }}" readonly>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Phone Number</label>
                            <input type="text" class="form-control readonly-field" value="+91 {{ $user->phone }}" readonly>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Gender</label>
                            <input type="text" class="form-control readonly-field"
                                   value="{{ $user->gender == 1 ? 'Male' : 'Female' }}" readonly>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Address</label>
                            <textarea class="form-control readonly-textarea" rows="3" readonly>{{ $user->address }}</textarea>
                        </div>
                    </div>

                    <h5 class="mb-4 mt-5">Professional & Commute Details</h5>
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label>Occupation Field</label>
                            <input type="text" class="form-control readonly-field"
                                   value="{{ ucfirst(str_replace('_', ' ', $user->occupation_field)) }}" readonly>
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>Experience (Years)</label>
                            <input type="text" class="form-control readonly-field"
                                   value="{{ $user->experience }} years" readonly>
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>Mode of Commute</label>
                            <input type="text" class="form-control readonly-field"
                                   value="{{ ucfirst(str_replace('_', ' ', $user->mode_of_transfer)) }}" readonly>
                        </div>
                    </div>

                    <h5 class="mb-4 mt-5">System Information</h5>
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label>User ID</label>
                            <input type="text" class="form-control readonly-field" value="#{{ $user->id }}" readonly>
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>Registered On</label>
                            <input type="text" class="form-control readonly-field"
                                   value="{{ $user->created_at->format('d M, Y') }}" readonly>
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>Registration Time</label>
                            <input type="text" class="form-control readonly-field"
                                   value="{{ $user->created_at->format('h:i A') }}" readonly>
                        </div>
                    </div>

                    <!-- Yahan pehle Print + Back the, ab sirf Delete User button -->
                    <div class="mt-5 text-center">
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-lg px-5"
                                    onclick="return confirm('Are you sure you want to permanently DELETE this user?\n\nName: {{ $user->first_name }} {{ $user->last_name }}\nEmail: {{ $user->email }}\nPhone: +91 {{ $user->phone }}\n\nThis action CANNOT be undone!')">
                                <i class="ik ik-trash-2"></i> Delete User
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
