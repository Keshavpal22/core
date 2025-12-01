{{-- resources/views/vendor/backpack/crud/list.blade.php --}}
@extends(backpack_view('blank'))

@section('header')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="m-0">{{ $crud->getHeading() ?? $crud->entity_name_plural }}</h2>
    <div class="d-flex gap-2">
        @if($crud->hasAccess('create'))
        <a href="{{ backpack_url('student/create') }}" class="btn btn-primary">
            Add Student
        </a>
        @endif
    </div>
</div>
@endsection

@section('content')
@php
$students = $crud->getEntries()->map(function($s) {
$admission = $s->admission_date;
$admissionStr = '';
$year = '';
$month = '';
if ($admission) {
$admissionStr = is_string($admission) ? $admission : $admission->format('Y-m-d');
try {
$dt = new \DateTime($admissionStr);
$year = $dt->format('Y');
$month = $dt->format('Y-m');
} catch (\Exception $e) {}
}
return [
'id' => $s->id,
'name' => (string)($s->name ?? ''),
'roll_number' => (string)($s->roll_number ?? ''),
'class' => (string)($s->class ?? ''),
'section' => (string)($s->section ?? ''),
'phone' => (string)($s->phone ?? ''),
'gender' => (string)($s->gender ?? ''),
'total_marks' => (int)($s->total_marks ?? 0),
'percentage' => (float)($s->percentage ?? 0),
'attendance_percent' => (float)($s->attendance_percent ?? 0),
'fee_paid' => (int)($s->fee_paid ?? 0),
'fee_due' => (int)($s->fee_due ?? 0),
'admission_date' => $admissionStr,
'admission_year' => $year,
'admission_month' => $month,
];
})->values()->toArray();
@endphp

<div class="container-fluid page-container">
    <div class="card dashboard-card">

        <!-- Toolbar -->
        <div class="toolbar d-flex justify-content-between align-items-center mb-3 p-3 bg-white border-bottom">
            <div class="toolbar-left d-flex gap-3 align-items-center">
                <input type="text" id="quickFilter" class="form-control" placeholder="Quick Search..."
                    style="width:320px;">
                <button id="toggleFilters" class="btn btn-outline-secondary btn-sm">Filters</button>
                <button id="resetAll" class="btn btn-outline-danger btn-sm">Reset</button>
                <button id="togglePivot" class="btn btn-outline-primary btn-sm">Pivot Mode</button>
            </div>
            <div class="toolbar-right d-flex gap-2">
                <button id="exportCsv" class="btn btn-success btn-sm">CSV</button>
                <button id="exportExcel" class="btn btn-success btn-sm">Excel</button>
                <button id="printBtn" class="btn btn-secondary btn-sm">Print</button>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid-wrapper">
            <div id="myGrid" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
@endsection

@push('after_styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@31.3.2/styles/ag-grid.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@31.3.2/styles/ag-theme-alpine.css">

<style>
    /* Toolbar top spacing */
    .toolbar {
        margin-top: 12px !important;
        margin-bottom: 12px !important;
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        border-radius: 10px;
    }

    /* Make buttons vertically centered */
    .toolbar .btn-sm,
    .toolbar input {
        margin-top: 2px !important;
        margin-bottom: 2px !important;
    }

    .ag-theme-alpine {
        height: calc(100vh - 260px);
        /* FIX: employee-like height */
        width: 100%;
    }

    /* ⭐ FIX: Mini filter dropdown not opening properly */
    .ag-floating-filter,
    .ag-floating-filter-full-body,
    .ag-floating-filter-body {
        height: auto !important;
        overflow: visible !important;
    }

    .ag-popup {
        z-index: 99999 !important;
        position: absolute !important;
    }

    .toolbar {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .ag-header-cell-label {
        font-weight: bold;
    }

    /* --- FIX: Floating Filter Perfect Alignment (Option A Clean Look) --- */
    .ag-theme-alpine .ag-floating-filter {
        height: 42px !important;
        padding: 4px 0 !important;
        display: flex !important;
        align-items: center !important;
    }

    .ag-theme-alpine .ag-floating-filter-body,
    .ag-theme-alpine .ag-floating-filter-input {
        height: 32px !important;
        display: flex !important;
        align-items: center !important;
    }

    .ag-theme-alpine .ag-floating-filter-input input {
        height: 30px !important;
        padding: 4px 8px !important;
    }

    .ag-floating-filter-button {
        display: flex !important;
        align-items: center !important;
    }
</style>
@endpush

@push('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/ag-grid-enterprise@31.3.2/dist/ag-grid-enterprise.min.js"></script>

<script>
    agGrid.LicenseManager.setLicenseKey("Using_this_AG_Grid_Enterprise_key_(AG-Grid-Enterprise-License)_is_granted_for_evaluation_only...");

    let gridApi;

    document.addEventListener('DOMContentLoaded', function () {

        const columnDefs = [

            /* ⭐ FIX: selection column correctly defined */
            {
                headerName: "",
                field: "id",
                width: 50,
                pinned: "left",
                checkboxSelection: true,
                headerCheckboxSelection: true,
                sortable: false,
                filter: false
            },

            { headerName: "Name", field: "name", flex: 2 },
            { headerName: "Roll No.", field: "roll_number" },
            { headerName: "Class", field: "class", filter: "agSetColumnFilter" },
            { headerName: "Section", field: "section", filter: "agSetColumnFilter" },
            { headerName: "Phone", field: "phone" },
            { headerName: "Gender", field: "gender", filter: "agSetColumnFilter" },
            { headerName: "Admission", field: "admission_date", filter: "agDateColumnFilter" },

            { headerName: "Total Marks", field: "total_marks", type: "numericColumn", aggFunc: "sum" },
            { headerName: "Percentage", field: "percentage", type: "numericColumn", aggFunc: "avg" },
            { headerName: "Attendance %", field: "attendance_percent", type: "numericColumn", aggFunc: "avg" },
            { headerName: "Fee Paid", field: "fee_paid", type: "numericColumn", aggFunc: "sum" },
            { headerName: "Fee Due", field: "fee_due", type: "numericColumn", aggFunc: "sum" },

            { headerName: "Year", field: "admission_year", hide: true },
            { headerName: "Month", field: "admission_month", hide: true },

            /* Actions Column */
            {
                headerName: "Actions",
                width: 140,
                sortable: false,
                filter: false,
                cellRenderer: p => `
                    <div style="display:flex;gap:6px;justify-content:center;">
                        <a href="{{ backpack_url('student') }}/${p.data.id}/show"
                           class="btn btn-info btn-sm"><i class="la la-eye"></i></a>
                        <a href="{{ backpack_url('student') }}/${p.data.id}/edit"
                           class="btn btn-success btn-sm"><i class="la la-edit"></i></a>
                    </div>
                `
            }
        ];

        const gridOptions = {
            columnDefs,
            rowData: @json($students),
            popupParent: document.body,   // ⭐ MINI FILTER FIX

            defaultColDef: {
                flex: 1,
                minWidth: 120,
                sortable: true,
                filter: true,
                floatingFilter: true,
                resizable: true
            },

            pagination: true,
            paginationPageSize: 25,
            paginationPageSizeSelector: [10,25,50,100],

            sideBar: { toolPanels: ['filters','columns'], defaultToolPanel: 'columns' },

            onGridReady: p => {
                gridApi = p.api;
                gridApi.sizeColumnsToFit();
            }
        };

        agGrid.createGrid(document.getElementById('myGrid'), gridOptions);
    });

    // Toolbar Actions
    document.getElementById('quickFilter')?.addEventListener('input', e => gridApi?.setQuickFilter(e.target.value));
    document.getElementById('toggleFilters')?.addEventListener('click', () => gridApi?.setSideBarVisible(!gridApi?.isSideBarVisible()));
    document.getElementById('resetAll')?.addEventListener('click', () => {
        gridApi?.setFilterModel(null);
        gridApi?.setQuickFilter('');
        document.getElementById('quickFilter').value = '';
    });
    document.getElementById('exportCsv')?.addEventListener('click', () => gridApi?.exportDataAsCsv({ fileName: 'students.csv' }));
    document.getElementById('exportExcel')?.addEventListener('click', () => gridApi?.exportDataAsExcel({ fileName: 'students.xlsx' }));
    document.getElementById('printBtn')?.addEventListener('click', () => window.print());
    document.getElementById('togglePivot')?.addEventListener('click', function () {
        const pivot = gridApi?.isPivotMode();
        gridApi?.setPivotMode(!pivot);
        this.innerHTML = !pivot ? "Exit Pivot" : "Pivot Mode";
        this.classList.toggle("btn-danger", !pivot);
    });
</script>
@endpush