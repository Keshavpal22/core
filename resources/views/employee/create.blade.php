@extends('layouts.main')

@section('title', 'Add Employee')

@section('content')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3>Add Employee</h3>
        </div>

        <div class="card-body">

            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the errors:</strong>
                <ul>
                    @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('employees.store') }}">
                @csrf

                <div class="row">

                    <div class="col-md-4 form-group">
                        <label>Employee ID</label>
                        <input type="number" name="emp_id" class="form-control" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Department</label>
                        <input type="text" name="department" class="form-control" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Country (2-letter code)</label>
                        <input type="text" name="country" class="form-control" maxlength="2" required>
                    </div>

                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <input type="text" name="mobile" id="mobile"
                            class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}"
                            placeholder="9876543210" maxlength="15">
                        @error('mobile')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Tasks</label>
                        <input type="number" name="tasks" class="form-control" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Hours</label>
                        <input type="number" name="hours" class="form-control" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Leaves</label>
                        <input type="number" name="leaves" class="form-control" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Efficiency (%)</label>
                        <input type="number" name="efficiency" class="form-control" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Attendance (%)</label>
                        <input type="number" name="attendance" class="form-control" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Rating (0–5)</label>
                        <input type="number" name="rating" class="form-control" required>
                    </div>

                </div>

                <button class="btn btn-primary mt-3">Save</button>
                <a href="{{ route('employees.index') }}" class="btn btn-light mt-3">Cancel</a>

            </form>
        </div>
    </div>
</div>

@endsection