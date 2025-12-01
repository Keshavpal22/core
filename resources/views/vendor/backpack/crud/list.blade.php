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
        <div class="card-header premium-header">
            <h4 class="mb-0">Student Management Dashboard</h4>
        </div>
        <div class="toolbar d-flex justify-content-between align-items-center mb-3 p-3 bg-white">
            <div class="toolbar-left d-flex gap-3 align-items-center">
                <input type="text" id="quickFilter" class="form-control" placeholder="Quick Search...">
                <button id="toggleFilters" class="btn btn-outline-secondary btn-sm">Filters</button>
                <button id="resetAll" class="btn btn-outline-danger btn-sm">Reset</button>
                <button id="togglePivot" class="btn btn-outline-primary btn-sm">Pivot Mode</button>
            </div>
            <div class="toolbar-right d-flex gap-2 align-items-center">
                <button id="exportCsv" class="btn btn-success btn-sm">CSV</button>
                <button id="exportExcel" class="btn btn-success btn-sm">Excel</button>
                <button id="printBtn" class="btn btn-secondary btn-sm">Print</button>
                @if($crud->hasAccess('create'))
                <a href="{{ backpack_url('student/create') }}" class="btn btn-primary">Add Student</a>
                @endif
            </div>
        </div>
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
    body {
        background: #f5f7fa;
    }

    .premium-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 18px 25px;
        border-radius: 8px 8px 0 0;
    }

    .ag-theme-alpine {
        height: calc(100vh - 260px) !important;
        width: 100%;
    }

    /* FINAL DRAG GHOST — sirf 1 cell jitna chhota aur bilkul Employee jaisa */
    .ag-dnd-ghost {
        background: white !important;
        border: 1px solid #ccc !important;
        border-radius: 6px !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #333 !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2) !important;
        white-space: nowrap !important;
        max-width: none !important;
        width: auto !important;
        height: auto !important;
        line-height: normal !important;
    }

    /* Hide all default junk hide */
    .ag-dragging-overlay,
    .ag-header-cell-moving,
    .ag-header-group-cell-moving {
        display: none !important;
    }
</style>
@endpush

@push('after_scripts')
{{-- YEHI CHANGE KIYA — noStyle bundle use kar raha hu --}}
<script src="https://cdn.jsdelivr.net/npm/ag-grid-enterprise@31.3.2/dist/ag-grid-enterprise.min.noStyle.js"></script>

<script>
    agGrid.LicenseManager.setLicenseKey("Using_this_AG_Grid_Enterprise_key_is_for_evaluation_only");

    let gridApi;

    document.addEventListener('DOMContentLoaded', function () {
        const columnDefs = [
            { headerName:"", field:"id", width:50, pinned:"left", checkboxSelection:true, headerCheckboxSelection:true, sortable:false, filter:false },
            { headerName:"Name", field:"name", flex:2, enableRowGroup:true },
            { headerName:"Roll No.", field:"roll_number", enableRowGroup:true },
            { headerName:"Class", field:"class", filter:"agSetColumnFilter", enableRowGroup:true },
            { headerName:"Section", field:"section", filter:"agSetColumnFilter", enableRowGroup:true },
            { headerName:"Phone", field:"phone" },
            { headerName:"Gender", field:"gender", filter:"agSetColumnFilter", enableRowGroup:true },
            { headerName:"Admission", field:"admission_date", filter:"agDateColumnFilter", enableRowGroup:true },
            { headerName:"Total Marks", field:"total_marks", type:"numericColumn", aggFunc:"sum", enableValue:true },
            { headerName:"Percentage", field:"percentage", type:"numericColumn", aggFunc:"avg", enableValue:true },
            { headerName:"Attendance %", field:"attendance_percent", type:"numericColumn", aggFunc:"avg", enableValue:true },
            { headerName:"Fee Paid", field:"fee_paid", type:"numericColumn", aggFunc:"sum", enableValue:true },
            { headerName:"Fee Due", field:"fee_due", type:"numericColumn", aggFunc:"sum", enableValue:true },
            { headerName:"Year", field:"admission_year", hide:true, enableRowGroup:true },
            { headerName:"Month", field:"admission_month", hide:true, enableRowGroup:true },
        ];

        const gridOptions = {
            columnDefs,
            rowData: @json($students),
            defaultColDef: {
                flex:1,
                minWidth:120,
                sortable:true,
                filter:true,
                floatingFilter:true,
                resizable:true,
            },
            headerHeight: 48,
            floatingFiltersHeight: 40,
            pagination:true,
            paginationPageSize: 10,
            paginationPageSizeSelector:[10,25,50,100],
            rowGroupPanelShow:'always',
            pivotPanelShow:'always',
            sideBar:{ toolPanels:['columns','filters'], defaultToolPanel:'columns' },
            onGridReady:p => {
                gridApi = p.api;
                p.api.sizeColumnsToFit();
            }
        };

        agGrid.createGrid(document.getElementById('myGrid'), gridOptions);
    });

    // Toolbar actions (same as before)
    document.getElementById('quickFilter')?.addEventListener('input', e => gridApi?.setQuickFilter(e.target.value));
    document.getElementById('toggleFilters')?.addEventListener('click', () => gridApi?.setSideBarVisible(!gridApi?.isSideBarVisible()));
    document.getElementById('resetAll')?.addEventListener('click', () => {
        gridApi?.setFilterModel(null);
        gridApi?.setQuickFilter('');
        document.getElementById('quickFilter').value = '';
        gridApi?.setPivotMode(false);
        gridApi?.setRowGroupColumns([]);
    });
    document.getElementById('exportCsv')?.addEventListener('click', () => gridApi?.exportDataAsCsv({fileName:'students.csv'}));
    document.getElementById('exportExcel')?.addEventListener('click', () => gridApi?.exportDataAsExcel({fileName:'students.xlsx'}));
    document.getElementById('printBtn')?.addEventListener('click', () => window.print());
    document.getElementById('togglePivot')?.addEventListener('click', function(){
        const on = gridApi?.isPivotMode();
        gridApi?.setPivotMode(!on);
        this.innerHTML = !on ? 'Exit Pivot' : 'Pivot Mode';
        this.classList.toggle('btn-danger', !on);
    });
</script>
@endpush