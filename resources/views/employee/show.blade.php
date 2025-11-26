@extends('layouts.main')

@section('title', 'Employee Details')

@section('content')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3>Employee Details</h3>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 form-group"><label>ID</label>
                    <div class="readonly-box">{{ $employee->emp_id }}</div>
                </div>
                <div class="col-md-4 form-group"><label>Name</label>
                    <div class="readonly-box">{{ $employee->name }}</div>
                </div>
                <div class="col-md-4 form-group"><label>Department</label>
                    <div class="readonly-box">{{ $employee->department }}</div>
                </div>

                <div class="col-md-4 form-group"><label>Country</label>
                    <div class="readonly-box">{{ $employee->country }}</div>
                </div>

                <div class="col-md-4 form-group"><label>Tasks</label>
                    <div class="readonly-box">{{ $employee->tasks }}</div>
                </div>
                <div class="col-md-4 form-group"><label>Hours</label>
                    <div class="readonly-box">{{ $employee->hours }}</div>
                </div>
                <div class="col-md-4 form-group"><label>Leaves</label>
                    <div class="readonly-box">{{ $employee->leaves }}</div>
                </div>

                <div class="col-md-4 form-group"><label>Efficiency</label>
                    <div class="readonly-box">{{ $employee->efficiency }}%</div>
                </div>
                <div class="col-md-4 form-group"><label>Attendance</label>
                    <div class="readonly-box">{{ $employee->attendance }}%</div>
                </div>
                <div class="col-md-4 form-group"><label>Rating</label>
                    <div class="readonly-box">{{ $employee->rating }}</div>
                </div>

            </div>

            <div class="mt-5 text-center">

                <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-lg">Back</a>

                <form action="{{ route('employees.destroy', $employee->emp_id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Are you sure you want to DELETE this employee?\n\nName: {{ $employee->name }}\nEmployee ID: {{ $employee->emp_id }}\n\nThis action CANNOT be undone!');">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-lg">
                        Delete
                    </button>
                </form>

            </div>

        </div>
    </div>

</div>

@endsection