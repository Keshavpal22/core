<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::select(
            'emp_id',
            'name',
            'department',
            'country',
            'mobile',
            'tasks',
            'hours',
            'leaves',
            'efficiency',
            'attendance',
            'rating'
        )->get();

        // Convert to proper numeric types (CRITICAL FIX)
        $employees = $employees->map(function ($emp) {
            $emp->tasks       = (int) $emp->tasks;
            $emp->hours       = (int) $emp->hours;
            $emp->leaves      = (int) $emp->leaves;
            $emp->efficiency  = (float) $emp->efficiency;
            $emp->attendance  = (float) $emp->attendance;
            $emp->rating      = (int) $emp->rating;
            return $emp;
        });

        $totalEmployees = $employees->count();
        $avgEfficiency  = $employees->avg('efficiency') ?? 0;
        $avgAttendance  = $employees->avg('attendance') ?? 0;
        $topPerformers  = $employees->where('rating', 5)->count();

        $gridConfig = [
            "columns" => [
                ["field" => "emp_id", "headerName" => "ID", "width" => 80, "pinned" => "left"],
                ["field" => "name", "headerName" => "Name", "width" => 180],
                ["field" => "department", "headerName" => "Department", "enableRowGroup" => true, "enablePivot" => true],
                ["field" => "country", "headerName" => "Country", "enableRowGroup" => true, "enablePivot" => true],
                ["field" => "mobile", "headerName" => "Mobile", "width" => 130, "filter" => true, "sortable" => true],
                ["field" => "tasks", "headerName" => "Tasks", "aggFunc" => "sum", "enableValue" => true],
                ["field" => "hours", "headerName" => "Hours", "aggFunc" => "sum", "enableValue" => true],
                ["field" => "leaves", "headerName" => "Leaves", "aggFunc" => "sum", "enableValue" => true],
                [
                    "field" => "efficiency",
                    "headerName" => "Efficiency %",
                    "aggFunc" => "avg",
                    "enableValue" => true,
                    "valueFormatter" => "params.value != null ? Number(params.value).toFixed(1) + '%' : ''"
                ],
                [
                    "field" => "attendance",
                    "headerName" => "Attendance %",
                    "aggFunc" => "avg",
                    "enableValue" => true,
                    "valueFormatter" => "params.value != null ? Number(params.value).toFixed(1) + '%' : ''"
                ],
                ["field" => "rating", "headerName" => "Rating", "aggFunc" => "avg", "enableValue" => true],
                [
                    "headerName" => "Action",
                    "width" => 110,
                    "pinned" => "right",
                    "sortable" => false,
                    "filter" => false,
                    "cellRenderer" => "ActionRenderer"
                ]
            ],
            "data" => $employees->toArray()
        ];

        return view('employee.index', compact(
            'totalEmployees',
            'avgEfficiency',
            'avgAttendance',
            'topPerformers',
            'gridConfig'
        ));
    }

    // Baaki sab methods same rahega (create, store, show, edit, update, destroy)
    public function create()
    {
        return view('employee.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'emp_id' => 'required|integer|unique:employee_performance,emp_id',
            'name' => 'required|string|max:150',
            'department' => 'required|string|max:100',
            'country' => 'required|string|size:2',
            'mobile' => 'nullable|string|regex:/^[6-9]\d{9}$/|size:10',
            'tasks' => 'required|integer|min:0',
            'hours' => 'required|integer|min:0',
            'leaves' => 'required|integer|min:0',
            'efficiency' => 'required|numeric|min:0|max:100',
            'attendance' => 'required|numeric|min:0|max:100',
            'rating' => 'required|integer|min:0|max:5',
        ]);
        Employee::create($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }

    public function show($emp_id)
    {
        $employee = Employee::where('emp_id', $emp_id)->firstOrFail();
        return view('employee.show', compact('employee'));
    }

    public function edit($emp_id)
    {
        $employee = Employee::where('emp_id', $emp_id)->firstOrFail();
        return view('employee.edit', compact('employee'));
    }

    public function update(Request $request, $emp_id)
    {
        $employee = Employee::where('emp_id', $emp_id)->firstOrFail();
        $request->validate([
            'name' => 'required|string|max:150',
            'department' => 'required|string|max:100',
            'country' => 'required|string|size:2',
            'mobile' => 'nullable|string|regex:/^[6-9]\d{9}$/|size:10',
            'tasks' => 'required|integer|min:0',
            'hours' => 'required|integer|min:0',
            'leaves' => 'required|integer|min:0',
            'efficiency' => 'required|numeric|min:0|max:100',
            'attendance' => 'required|numeric|min:0|max:100',
            'rating' => 'required|integer|min:0|max:5',
        ]);
        $employee->update($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy($emp_id)
    {
        $employee = Employee::where('emp_id', $emp_id)->firstOrFail();
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
