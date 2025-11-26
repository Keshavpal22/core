<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display Employee Grid (AG-Grid Community)
     */
    public function index()
    {
        $employees = Employee::all();
        $totalEmployees = $employees->count();
        $avgEfficiency = $employees->avg('efficiency') ?? 0;
        $avgAttendance = $employees->avg('attendance') ?? 0;
        $topPerformers = Employee::where('rating', 5)->count();

        return view('employee.index', compact(
            'employees',
            'totalEmployees',
            'avgEfficiency',
            'avgAttendance',
            'topPerformers'
        ));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('employee.create');
    }

    /**
     * Store new employee
     */
    public function store(Request $request)
    {
        $request->validate([
            'emp_id'      => 'required|integer|unique:employee_performance,emp_id',
            'name'        => 'required|string|max:150',
            'department'  => 'required|string|max:100',
            'country'     => 'required|string|size:2',

            'tasks'       => 'required|integer|min:0',
            'hours'       => 'required|integer|min:0',
            'leaves'      => 'required|integer|min:0',

            'efficiency'  => 'required|integer|min:0|max:100',
            'attendance'  => 'required|integer|min:0|max:100',
            'rating'      => 'required|integer|min:0|max:5',
        ]);

        Employee::create($request->all());

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee added successfully.');
    }

    /**
     * Display a single employee
     */
    public function show($emp_id)
    {
        $employee = Employee::where('emp_id', $emp_id)->firstOrFail();

        return view('employee.show', compact('employee'));
    }

    /**
     * Show edit form
     */
    public function edit($emp_id)
    {
        $employee = Employee::where('emp_id', $emp_id)->firstOrFail();

        return view('employee.edit', compact('employee'));
    }

    /**
     * Update employee data
     */
    public function update(Request $request, $emp_id)
    {
        $employee = Employee::where('emp_id', $emp_id)->firstOrFail();

        $request->validate([
            'name'        => 'required|string|max:150',
            'department'  => 'required|string|max:100',
            'country'     => 'required|string|size:2',

            'tasks'       => 'required|integer|min:0',
            'hours'       => 'required|integer|min:0',
            'leaves'      => 'required|integer|min:0',

            'efficiency'  => 'required|integer|min:0|max:100',
            'attendance'  => 'required|integer|min:0|max:100',
            'rating'      => 'required|integer|min:0|max:5',
        ]);

        $employee->update($request->all());

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Delete employee
     */
    public function destroy($emp_id)
    {
        $employee = Employee::where('emp_id', $emp_id)->firstOrFail();
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
