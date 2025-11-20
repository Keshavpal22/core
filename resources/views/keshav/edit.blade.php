{{-- resources/views/users/edit.blade.php --}}
@extends('layouts.main')

@section('title', 'Edit User - ' . $user->first_name . ' ' . $user->last_name)

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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-6">
                <div class="page-header-title">
                    <i class="ik ik-edit bg-blue"></i>
                    <div class="d-inline">
                        <h5>Edit User</h5>
                        <span>Update user information</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ik ik-home"></i> Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @include('include.message')

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Edit User Details</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name', $user->first_name) }}" required>
                                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name', $user->last_name) }}" required>
                                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $user->phone) }}" maxlength="10" required>
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                    <option value="male" {{ old('gender', $user->gender == 1 ? 'male' : '') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender == 0 ? 'female' : '') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Address <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', $user->address) }}</textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label>Occupation Field <span class="text-danger">*</span></label>
                                <select name="occupation_field" class="form-control @error('occupation_field') is-invalid @enderror" required>
                                    <option value="engineering" {{ old('occupation_field', $user->occupation_field) == 'engineering' ? 'selected' : '' }}>Engineering</option>
                                    <option value="doctor" {{ old('occupation_field', $user->occupation_field) == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="teacher" {{ old('occupation_field', $user->occupation_field) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="business" {{ old('occupation_field', $user->occupation_field) == 'business' ? 'selected' : '' }}>Business</option>
                                    <option value="other" {{ old('occupation_field', $user->occupation_field) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('occupation_field') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Experience (Years) <span class="text-danger">*</span></label>
                                <input type="number" name="experience" class="form-control @error('experience') is-invalid @enderror"
                                       value="{{ old('experience', $user->experience) }}" min="0" max="60" required>
                                @error('experience') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Mode of Commute <span class="text-danger">*</span></label>
                                <select name="mode_of_transfer" class="form-control @error('mode_of_transfer') is-invalid @enderror" required>
                                    <option value="car" {{ old('mode_of_transfer', $user->mode_of_transfer) == 'car' ? 'selected' : '' }}>Car</option>
                                    <option value="bike" {{ old('mode_of_transfer', $user->mode_of_transfer) == 'bike' ? 'selected' : '' }}>Bike</option>
                                    <option value="bus" {{ old('mode_of_transfer', $user->mode_of_transfer) == 'bus' ? 'selected' : '' }}>Bus</option>
                                    <option value="metro" {{ old('mode_of_transfer', $user->mode_of_transfer) == 'metro' ? 'selected' : '' }}>Metro</option>
                                    <option value="cycle" {{ old('mode_of_transfer', $user->mode_of_transfer) == 'cycle' ? 'selected' : '' }}>Cycle</option>
                                    <option value="walking" {{ old('mode_of_transfer', $user->mode_of_transfer) == 'walking' ? 'selected' : '' }}>Walking</option>
                                </select>
                                @error('mode_of_transfer') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="ik ik-save"></i> Update User
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-lg">
                                <i class="ik ik-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
