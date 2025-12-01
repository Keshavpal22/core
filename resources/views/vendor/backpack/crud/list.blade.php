{{-- resources/views/vendor/backpack/crud/list.blade.php --}}
@extends(backpack_view('blank'))

@section('header')
<div class="content-header pt-2 pb-0">
    <h2 class="m-0">{{ $crud->getHeading() ?? $crud->entity_name_plural }}</h2>
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

        {{-- PREMIUM HEADER --}}
        <div class="card-header premium-header">
            <h4 class="mb-0"><i class="fas fa-user-graduate me-2"></i> Student Management Dashboard</h4>
        </div>

        {{-- TOOLBAR --}}
        <div class="toolbar d-flex justify-content-between align-items-center mb-3 p-3 bg-white">
            <div class="toolbar-left d-flex gap-3 align-items-center">

                <input type="text" id="quickFilter" class="form-control" placeholder="Quick Search...">

                <button id="toggleFilters" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-filter"></i> Filters
                </button>

                <button id="resetAll" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-undo"></i> Reset
                </button>

                <button id="togglePivot" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-table"></i> Pivot Mode
                </button>
            </div>

            <div class="toolbar-right d-flex gap-2 align-items-center">

                <button id="exportCsv" class="btn btn-success btn-sm">
                    <i class="fas fa-file-csv"></i> CSV
                </button>

                <button id="exportExcel" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Excel
                </button>

                <button id="printBtn" class="btn btn-secondary btn-sm">
                    Print
                </button>

                @if($crud->hasAccess('create'))
                <a href="{{ backpack_url('student/create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Student
                </a>
                @endif
            </div>
        </div>

        {{-- GRID --}}
        <div class="grid-wrapper">
            <div id="myGrid" class="ag-theme-alpine"></div>
        </div>

    </div>
</div>
@endsection


{{-- ===================================================================================== --}}
{{-- STYLES --}}
{{-- ===================================================================================== --}}
@push('after_styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@31.3.2/styles/ag-grid.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@31.3.2/styles/ag-theme-alpine.css">

<style>
    body {
        background: #f5f7fa;
    }

    .premium-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 18px 25px;
    }

    .ag-theme-alpine {
        height: calc(100vh - 260px) !important;
        width: 100%;
    }

    /* Toolbar spacing */
    .toolbar-left .btn,
    .toolbar-right .btn {
        margin-right: 10px !important;
    }

    .toolbar-left .btn:last-child,
    .toolbar-right .btn:last-child {
        margin-right: 0 !important;
    }

    /* Pivot button size fix */
    #togglePivot.btn-sm {
        padding: 4px 12px !important;
        height: 34px !important;
        line-height: 1 !important;
    }

    /* ------------------------------------------------------------------------- */
    /* ⭐ FINAL DRAG GHOST FIX — EXACT CELL SIZE */
    /* ------------------------------------------------------------------------- */
    .ag-dnd-ghost {
        background: #fff !important;
        border: 1px solid #ccc !important;
        border-radius: 4px !important;

        height: 38px !important;
        /* EXACT header height */
        line-height: 38px !important;

        padding: 0 12px !important;

        font-size: 14px !important;
        font-weight: 500 !important;

        display: flex !important;
        align-items: center !important;

        width: auto !important;
        min-width: 120px !important;
        max-width: 200px !important;

        overflow: hidden !important;
        white-space: nowrap !important;
        text-overflow: ellipsis !important;

        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15) !important;
    }

    /* Hide original moving header */
    .ag-header-cell.ag-header-cell-moving,
    .ag-header-group-cell.ag-header-group-cell-moving {
        opacity: 0 !important;
    }

    /* Remove giant overlay */
    .ag-dragging-overlay {
        display: none !important;
    }
</style>
@endpush


{{-- ===================================================================================== --}}
{{-- SCRIPTS --}}
{{-- ===================================================================================== --}}
@push('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/ag-grid-enterprise@31.3.2/dist/ag-grid-enterprise.min.js"></script>

<script>
    agGrid.LicenseManager.setLicenseKey("Using_this_AG_Grid_Enterprise_key_is_for_evaluation_only");

let gridApi;

document.addEventListener('DOMContentLoaded', function() {

    const columnDefs = [
        { headerName:"", field:"id", width:50, pinned:"left",
            checkboxSelection:true, headerCheckboxSelection:true,
            sortable:false, filter:false, enableRowGroup:true },

        { headerName:"Name", field:"name", flex:2, enableRowGroup:true },
        { headerName:"Roll No.", field:"roll_number", enableRowGroup:true },
        { headerName:"Class", field:"class", filter:"agSetColumnFilter", enableRowGroup:true },
        { headerName:"Section", field:"section", filter:"agSetColumnFilter", enableRowGroup:true },
        { headerName:"Phone", field:"phone", enableRowGroup:true },
        { headerName:"Gender", field:"gender", filter:"agSetColumnFilter", enableRowGroup:true },
        { headerName:"Admission", field:"admission_date", filter:"agDateColumnFilter", enableRowGroup:true },

        { headerName:"Total Marks", field:"total_marks", type:"numericColumn", aggFunc:"sum", enableRowGroup:true },
        { headerName:"Percentage", field:"percentage", type:"numericColumn", aggFunc:"avg", enableRowGroup:true },
        { headerName:"Attendance %", field:"attendance_percent", type:"numericColumn", aggFunc:"avg", enableRowGroup:true },
        { headerName:"Fee Paid", field:"fee_paid", type:"numericColumn", aggFunc:"sum", enableRowGroup:true },
        { headerName:"Fee Due", field:"fee_due", type:"numericColumn", aggFunc:"sum", enableRowGroup:true },

        { headerName:"Year", field:"admission_year", hide:true, enableRowGroup:true },
        { headerName:"Month", field:"admission_month", hide:true, enableRowGroup:true },
    ];

    const gridOptions = {
        columnDefs,
        rowData: @json($students),

        defaultColDef: {
            flex:1, minWidth:120, sortable:true, filter:true,
            floatingFilter:true, resizable:true,
        },

        pagination:true,
        paginationPageSize:10,
        paginationPageSizeSelector:[5,10,25,50,100,200],

        rowGroupPanelShow:'always',
        pivotPanelShow:'always',

        sideBar:{ toolPanels:['columns','filters'], defaultToolPanel:'columns' },

        onGridReady:p=>{
            gridApi = p.api;
            gridApi.sizeColumnsToFit();
        }
    };

    agGrid.createGrid(document.getElementById('myGrid'), gridOptions);
});


/* Toolbar Actions */
document.getElementById('quickFilter')
?.addEventListener('input', e => gridApi?.setQuickFilter(e.target.value));

document.getElementById('toggleFilters')
?.addEventListener('click', () => gridApi?.setSideBarVisible(!gridApi?.isSideBarVisible()));

document.getElementById('resetAll')
?.addEventListener('click', () => {
    gridApi?.setFilterModel(null);
    gridApi?.setQuickFilter('');
    document.getElementById('quickFilter').value = '';
    gridApi?.setPivotMode(false);
    gridApi?.setRowGroupColumns([]);
});

document.getElementById('exportCsv')
?.addEventListener('click', () =>
    gridApi?.exportDataAsCsv({ fileName:'students.csv' })
);

document.getElementById('exportExcel')
?.addEventListener('click', () =>
    gridApi?.exportDataAsExcel({ fileName:'students.xlsx' })
);

document.getElementById('printBtn')
?.addEventListener('click', () => window.print());

document.getElementById('togglePivot')
?.addEventListener('click', function() {
    const on = gridApi?.isPivotMode();
    gridApi?.setPivotMode(!on);
    this.innerHTML = !on
        ? "<i class='fas fa-table'></i> Exit Pivot"
        : "<i class='fas fa-table'></i> Pivot Mode";
    this.classList.toggle("btn-danger", !on);
});
</script>
@endpush