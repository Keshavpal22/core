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

        {{-- ⭐ PREMIUM HEADER --}}
        <div class="card-header premium-header">
            <h4 class="mb-0"><i class="fas fa-user-graduate me-2"></i> Student Management Dashboard</h4>
        </div>

        {{-- ⭐ TOOLBAR --}}
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

        {{-- ⭐ GRID --}}
        <div class="grid-wrapper">
            <div id="myGrid" class="ag-theme-alpine"></div>
        </div>

    </div>
</div>
@endsection


{{-- ===================================================================================== --}}
{{-- CSS (UNCHANGED) --}}
{{-- ===================================================================================== --}}
@push('after_styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@31.3.2/styles/ag-grid.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community@31.3.2/styles/ag-theme-alpine.css">

<style>
    body {
        background: #f5f7fa;
    }

    .premium-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        padding: 18px 25px !important;
        border-radius: 8px 8px 0 0 !important;
    }

    .premium-header h4 {
        font-size: 20px !important;
        font-weight: 600 !important;
    }

    .toolbar input#quickFilter {
        width: 250px !important;
        height: 34px !important;
    }

    .btn-sm {
        height: 34px !important;
    }

    /* ⭐ Premium Print Button */
    #printBtn.btn-sm {
        background: #6c757d !important;
        border-color: #6c757d !important;
        color: white !important;
        padding-left: 16px !important;
        padding-right: 16px !important;
        height: 36px !important;
        font-weight: 500 !important;

        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        transition: 0.2s ease-in-out;
    }

    #printBtn.btn-sm:hover {
        background: #5a6268 !important;
        border-color: #545b62 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .ag-theme-alpine {
        height: calc(100vh - 260px) !important;
        width: 100%;
    }

    .ag-theme-alpine .ag-floating-filter {
        padding-left: 10px !important;
        padding-right: 10px !important;
        gap: 8px !important;
        display: flex !important;
        align-items: center !important;
    }

    .ag-floating-filter-input input {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    .ag-floating-filter-button {
        margin-left: 5px !important;
    }

    /* ⭐ Toolbar Left Premium Spacing */
    .toolbar-left {
        display: flex;
        align-items: center;
        gap: 14px !important;
    }

    .toolbar-left .btn-sm {
        padding-left: 14px !important;
        padding-right: 14px !important;
        font-weight: 500 !important;
    }

    #quickFilter {
        margin-right: 4px !important;
    }

    .toolbar-right {
        display: flex;
        align-items: center;
        gap: 12px !important;
    }

    .toolbar-right .btn-sm {
        padding-left: 14px !important;
        padding-right: 14px !important;
    }

    /* ⭐⭐⭐ YOUR FINAL REQUESTED DRAG FIX ⭐⭐⭐ */
    /* Exact AG-Grid Official Demo Drag Box Size (Performance wala) */
    /* OFFICIAL AG-GRID DEMO Jaisa Perfect Small Drag Box */
    .ag-dnd-ghost {
        background: white !important;
        border: 1px solid #babfc7 !important;
        border-radius: 4px !important;
        padding: 4px 8px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        line-height: 1.4 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        min-height: 28px !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        max-width: 160px !important;
        overflow: hidden !important;
    }

    .ag-dnd-ghost .ag-dnd-ghost-icon {
        font-size: 12px !important;
        opacity: 0.7 !important;
    }

    .ag-dnd-ghost span {
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
</style>


@endpush


{{-- ===================================================================================== --}}
{{-- JAVASCRIPT -> GROUPING + PIVOT ENABLED (UI UNTOUCHED) --}}
{{-- ===================================================================================== --}}
@push('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/ag-grid-enterprise@31.3.2/dist/ag-grid-enterprise.min.js"></script>

<script>
    agGrid.LicenseManager.setLicenseKey("Using_this_AG_Grid_Enterprise_key_(AG-Grid-Enterprise-License)_is_granted_for_evaluation_only...");

let gridApi;

document.addEventListener('DOMContentLoaded', function () {

    const columnDefs = [

        { headerName: "", field: "id", width: 50, pinned: "left",
            checkboxSelection: true, headerCheckboxSelection: true,
            sortable: false, filter: false, enableRowGroup: true },

        { headerName: "Name", field: "name", flex: 2, enableRowGroup: true },
        { headerName: "Roll No.", field: "roll_number", enableRowGroup: true },
        { headerName: "Class", field: "class", filter: "agSetColumnFilter", enableRowGroup: true },
        { headerName: "Section", field: "section", filter: "agSetColumnFilter", enableRowGroup: true },
        { headerName: "Phone", field: "phone", enableRowGroup: true },
        { headerName: "Gender", field: "gender", filter: "agSetColumnFilter", enableRowGroup: true },
        { headerName: "Admission", field: "admission_date", filter: "agDateColumnFilter", enableRowGroup: true },

        { headerName: "Total Marks", field: "total_marks", type: "numericColumn", aggFunc: "sum", enableRowGroup: true },
        { headerName: "Percentage", field: "percentage", type: "numericColumn", aggFunc: "avg", enableRowGroup: true },
        { headerName: "Attendance %", field: "attendance_percent", type: "numericColumn", aggFunc: "avg", enableRowGroup: true },
        { headerName: "Fee Paid", field: "fee_paid", type: "numericColumn", aggFunc: "sum", enableRowGroup: true },
        { headerName: "Fee Due", field: "fee_due", type: "numericColumn", aggFunc: "sum", enableRowGroup: true },

        { headerName: "Year", field: "admission_year", hide: true, enableRowGroup: true },
        { headerName: "Month", field: "admission_month", hide: true, enableRowGroup: true },

        {
            headerName: "Actions",
            width: 140,
            filter: false,
            sortable: false,
            enableRowGroup: false,
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

        popupParent: document.body,

        defaultColDef: {
            flex: 1,
            minWidth: 120,
            floatingFilter: true,
            sortable: true,
            filter: true,
            resizable: true,
        },

        pagination: true,
        paginationPageSize: 10,

        paginationPageSizeSelector: [5, 10, 25, 50, 100, 200],


        /* ⭐⭐⭐ MAIN ADD — GROUPING ENABLED */
        rowGroupPanelShow: 'always',

        /* ⭐⭐⭐ PIVOT PANEL */
        pivotPanelShow: 'always',

        /* Sidebar */
        sideBar: { toolPanels: ['columns','filters'], defaultToolPanel: 'columns' },

        onGridReady: p => {
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
    gridApi?.setPivotMode(false);
    gridApi?.setRowGroupColumns([]);
    document.getElementById('quickFilter').value = '';
});

document.getElementById('exportCsv')
?.addEventListener('click', () => gridApi?.exportDataAsCsv({ fileName: 'students.csv' }));

document.getElementById('exportExcel')
?.addEventListener('click', () => gridApi?.exportDataAsExcel({ fileName: 'students.xlsx' }));

document.getElementById('printBtn')
?.addEventListener('click', () => window.print());

document.getElementById('togglePivot')
?.addEventListener('click', function () {
    const on = gridApi?.isPivotMode();
    gridApi?.setPivotMode(!on);
    this.innerHTML = !on ? "<i class='fas fa-table'></i> Exit Pivot" :
                           "<i class='fas fa-table'></i> Pivot Mode";
    this.classList.toggle("btn-danger", !on);
});
</script>
@endpush