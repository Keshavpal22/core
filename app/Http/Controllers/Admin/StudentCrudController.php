<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StudentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;

class StudentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Student::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/student');
        CRUD::setEntityNameStrings('student', 'students');

        $this->crud->setTitle('Students Management');
        $this->crud->setHeading('Students');
    }

    // YE FUNCTION BILKUL KHALI — AG-Grid khud sab handle karega
    protected function setupListOperation()
    {
        // KUCH BHI ADD MAT KARO YAHAN!
        // AG-Grid list.blade.php se data lega
        // No columns, no filters, no orderBy — sab AG-Grid karega
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StudentRequest::class);

        CRUD::field('name')->label('Full Name')->size(6);
        CRUD::field('roll_number')->hint('Unique roll number')->size(6);
        CRUD::field('email')->size(6);
        CRUD::field('phone')->size(6);

        CRUD::field('father_name')->size(6);
        CRUD::field('mother_name')->size(6);

        CRUD::field('class')->type('select_from_array')
            ->options(['10th' => '10th', '11th' => '11th', '12th' => '12th', 'B.Tech' => 'B.Tech', 'M.Tech' => 'M.Tech'])
            ->size(4);

        CRUD::field('section')->type('select_from_array')
            ->options(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'])->size(4);

        CRUD::field('gender')->type('radio')
            ->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'])->size(4);

        CRUD::field('admission_date')
            ->label('Admission Date')
            ->type('date')           // YE FREE HAI — bilkul same dikhega!
            ->size(6);
        CRUD::field('blood_group')->size(6);
        CRUD::field('address')->type('textarea');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        CRUD::column('name')->label('Full Name');
        CRUD::column('roll_number')->label('Roll Number');
        CRUD::column('email')->label('Email');
        CRUD::column('phone')->label('Phone');
        CRUD::column('father_name')->label('Father Name');
        CRUD::column('mother_name')->label('Mother Name');
        CRUD::column('class')->label('Class');
        CRUD::column('section')->label('Section');
        CRUD::column('gender')->label('Gender');
        CRUD::column('admission_date')->label('Admission Date');
        CRUD::column('blood_group')->label('Blood Group');
        CRUD::column('address')->label('Address');
        CRUD::column('created_at')->label('Added On');
        CRUD::column('updated_at')->label('Last Updated');
    }

    // Export functions (optional)
    public function exportExcel()
    {
        return Excel::download(new StudentsExport, 'students_' . date('d-m-Y') . '.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new StudentsExport, 'students_' . date('d-m-Y') . '.csv');
    }
}
