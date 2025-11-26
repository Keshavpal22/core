@extends('layouts.main')

@section('title', 'Edit Employee')

@section('content')

<div class="container-fluid">

    <div class="card">
        <div class="card-header"><h3>Edit Employee</h3></div>

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

            <form method="POST" action="{{ route('employees.update', $employee->emp_id) }}">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4 form-group">
                        <label>Employee ID</label>
                        <input type="text" class="form-control" value="{{ $employee->emp_id }}" disabled>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ $employee->name }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Department</label>
                        <input type="text" name="department" class="form-control"
                               value="{{ $employee->department }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Country</label>
                        <input type="text" name="country" class="form-control"
                               maxlength="2" value="{{ $employee->country }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Tasks</label>
                        <input type="number" name="tasks" class="form-control"
                               value="{{ $employee->tasks }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Hours</label>
                        <input type="number" name="hours" class="form-control"
                               value="{{ $employee->hours }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Leaves</label>
                        <input type="number" name="leaves" class="form-control"
                               value="{{ $employee->leaves }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Efficiency (%)</label>
                        <input type="number" name="efficiency" class="form-control"
                               value="{{ $employee->efficiency }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Attendance (%)</label>
                        <input type="number" name="attendance" class="form-control"
                               value="{{ $employee->attendance }}" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Rating</label>
                        <input type="number" name="rating" class="form-control"
                               value="{{ $employee->rating }}" required>
                    </div>

                </div>

                <button class="btn btn-success mt-3">Update</button>
                <a href="{{ route('employees.index') }}" class="btn btn-light mt-3">Cancel</a>

            </form>
        </div>
    </div>
</div>

@endsection
