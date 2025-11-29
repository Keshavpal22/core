@extends('layouts.main')
@section('title', 'Employee Details')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Employee Details</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">ID</label>
                    <div class="readonly-box p-2 bg-light border rounded">{{ $employee->emp_id }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Name</label>
                    <div class="readonly-box p-2 bg-light border rounded">{{ $employee->name }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Department</label>
                    <div class="readonly-box p-2 bg-light border rounded">{{ $employee->department }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Country</label>
                    <div class="readonly-box p-2 bg-light border rounded">{{ $employee->country }}</div>
                </div>

                <!-- MOBILE NUMBER — DIV MEIN AUR CLEAN -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Mobile Number</label>
                    <div class="readonly-box p-2 bg-light border rounded">
                        {{ $employee->mobile ?? 'Not Provided' }}
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Tasks</label>
                    <div class="readonly-box p-2 bg-light border rounded">{{ $employee->tasks }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Hours</label>
                    <div class="readonly-box p-2 bg-light border rounded">{{ $employee->hours }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Leaves</label>
                    <div class="readonly-box p-2 bg-light border rounded">{{ $employee->leaves }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Efficiency</label>
                    <div class="readonly-box p-2 bg-light border rounded">{{ $employee->efficiency }}%</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Attendance</label>
                    <div class="readonly-box p-2 bg-light border rounded">{{ $employee->attendance }}%</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Rating</label>
                    <div class="readonly-box p-2 bg-light border rounded">{{ $employee->rating }}</div>
                </div>
            </div>

            <div class="mt-5 text-center">
                <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-lg px-4">Back to List</a>
                <a href="{{ route('employees.edit', $employee->emp_id) }}" class="btn btn-warning btn-lg px-4">Edit</a>

                <form action="{{ route('employees.destroy', $employee->emp_id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Are you sure you want to DELETE this employee?\n\nName: {{ $employee->name }}\nID: {{ $employee->emp_id }}\n\nThis action CANNOT be undone!');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg px-4">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection