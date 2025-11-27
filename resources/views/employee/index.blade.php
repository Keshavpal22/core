{{-- resources/views/employee/index.blade.php --}}
@extends('layouts.main')
@section('title', 'Employee Performance Dashboard (Premium)')

@push('head')
<!-- AG Grid CSS – बिना पुरानी थीम के (नई थीम API यूज़ करेंगे) -->
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css" />
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<style>
    body {
        background: #f5f7fa;
    }

    .page-container {
        padding: 20px 0;
    }

    .dashboard-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 18px 25px;
        border: none;
    }

    .toolbar {
        background: white;
        padding: 14px 20px;
        border-bottom: 1px solid #e0e0e0;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .toolbar-left,
    .toolbar-right {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .quick-search {
        width: 320px;
    }

    .grid-wrapper {
        height: calc(100vh - 280px);
        min-height: 600px;
    }

    #myGrid {
        height: 100% !important;
        width: 100% !important;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        padding: 0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: all 0.2s;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    /* Perfect blue tick in popup filters */
    .ag-popup .ag-checkbox-input-wrapper {
        background: white !important;
        border: 2px solid #c0c0c0 !important;
        border-radius: 4px !important;
        width: 18px !important;
        height: 18px !important;
        position: relative !important;
    }

    .ag-popup .ag-checkbox-input-wrapper::after {
        content: "✓" !important;
        color: #007bff !important;
        font-size: 16px !important;
        font-weight: bold !important;
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        opacity: 0 !important;
    }

    .ag-popup .ag-checkbox-input-wrapper:checked {
        border-color: #007bff !important;
        background: #007bff !important;
    }

    .ag-popup .ag-checkbox-input-wrapper:checked::after {
        opacity: 1 !important;
        color: white !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid page-container">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $totalEmployees }}</h3>
                <p>Total Employees</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ number_format($avgEfficiency, 1) }}%</h3>
                <p>Avg Efficiency</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ number_format($avgAttendance, 1) }}%</h3>
                <p>Avg Attendance</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h3>{{ $topPerformers }}</h3>
                <p>Top Performers</p>
            </div>
        </div>
    </div>

    <div class="card dashboard-card">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i> Employee Performance Dashboard (Premium)</h4>
        </div>

        <div class="toolbar">
            <div class="toolbar-left">
                <input type="text" id="quickFilter" class="form-control quick-search" placeholder="Quick Search...">
                <button id="toggleFilters" class="btn btn-outline-secondary btn-sm"><i class="fas fa-filter"></i>
                    Filters</button>
                <button id="resetAll" class="btn btn-outline-danger btn-sm"><i class="fas fa-undo"></i> Reset</button>
                <button id="togglePivot" class="btn btn-outline-primary btn-sm"><i class="fas fa-table"></i> Pivot
                    Mode</button>
            </div>
            <div class="toolbar-right">
                <button id="exportCsv" class="btn btn-success btn-sm"><i class="fas fa-file-csv"></i> CSV</button>
                <button id="exportExcel" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Excel</button>
                <button onclick="window.print()" class="btn btn-secondary btn-sm"><i class="fas fa-print"></i></button>
                <a href="{{ route('employees.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add
                    Employee</a>
            </div>
        </div>

        <div class="grid-wrapper">
            <div id="myGrid" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/ag-grid-enterprise/dist/ag-grid-enterprise.min.js"></script>
<script>
    // अगर आपके पास वैलिड लाइसेंस है तो यहाँ डालें, नहीं तो 2 महीने तक फ्री चलेगा
    // agGrid.LicenseManager.setLicenseKey("your_key_here");

    const rowData = @json($employees);

    const columnDefs = [
        { field: "emp_id", headerName: "ID", width: 80 },
        { field: "name", headerName: "Name", width: 180 },
        { field: "department", headerName: "Department", enableRowGroup: true, enablePivot: true },
        { field: "country", headerName: "Country", enableRowGroup: true, enablePivot: true },
        { field: "tasks", headerName: "Tasks", aggFunc: "sum", enableValue: true },
        { field: "hours", headerName: "Hours", aggFunc: "sum", enableValue: true },
        { field: "leaves", headerName: "Leaves", aggFunc: "sum", enableValue: true },
        { field: "efficiency", headerName: "Efficiency %", aggFunc: "avg", enableValue: true,
          valueFormatter: p => p.value ? p.value.toFixed(1) + '%' : '' },
        { field: "attendance", headerName: "Attendance %", aggFunc: "avg", enableValue: true,
          valueFormatter: p => p.value ? p.value.toFixed(1) + '%' : '' },
        { field: "rating", headerName: "Rating", aggFunc: "avg", enableValue: true },
        { headerName: "Action", width: 110, pinned: 'right', sortable: false, filter: false,
          cellRenderer: p => p.data ? `<div style="display:flex;gap:6px;justify-content:center;height:100%;align-items:center;">
              <a href="{{ route('employees.show', ':id') }}".replace(':id', p.data.emp_id) class="btn btn-info action-btn" title="View"><i class="fas fa-eye"></i></a>
              <a href="{{ route('employees.edit', ':id') }}".replace(':id', p.data.emp_id) class="btn btn-success action-btn" title="Edit"><i class="fas fa-edit"></i></a>
          </div>` : '' }
    ];

    const gridOptions = {
        columnDefs,
        rowData,
        pagination: true,
        paginationPageSize: 20,
        pivotMode: false,                    // शुरू में बंद
        pivotPanelShow: 'always',
        rowGroupPanelShow: 'always',
        popupParent: document.body,
        theme: 'ag-theme-alpine',            // नई थीम API – एरर #239 गायब
        rowSelection: { mode: 'multiRow', checkboxes: true, headerCheckbox: true },
        cellSelection: true,
        sideBar: { toolPanels: ['columns', 'filters'], defaultToolPanel: 'columns' },
        defaultColDef: { sortable: true, filter: true, resizable: true, floatingFilter: true, flex: 1, minWidth: 110 },
        autoGroupColumnDef: { headerName: "Group", minWidth: 280, cellRendererParams: { suppressCount: true } },
        suppressAggFuncInHeader: true,
        animateRows: true,
        domLayout: 'normal'
    };

    const gridApi = agGrid.createGrid(document.getElementById('myGrid'), gridOptions);

    // Toolbar Actions
    document.getElementById('quickFilter').addEventListener('input', e => gridApi.setQuickFilter(e.target.value));
    document.getElementById('toggleFilters').onclick = () => gridApi.setSideBarVisible(!gridApi.isSideBarVisible());

    // Perfect Reset
    document.getElementById('resetAll').onclick = () => {
        gridApi.setPivotMode(false);
        gridApi.setRowGroupColumns([]);
        gridApi.setPivotColumns([]);
        gridApi.setValueColumns([]);
        gridApi.setFilterModel(null);
        gridApi.setQuickFilter('');
        document.getElementById('quickFilter').value = '';
        gridApi.setColumnDefs(columnDefs);
    };

    // Pivot Toggle Button
    document.getElementById('togglePivot')?.addEventListener('click', () => {
        const isOn = gridApi.isPivotMode();
        gridApi.setPivotMode(!isOn);
        const btn = document.getElementById('togglePivot');
        btn.innerHTML = !isOn ? '<i class="fas fa-times"></i> Exit Pivot' : '<i class="fas fa-table"></i> Pivot Mode';
        btn.classList.toggle('btn-outline-primary');
        btn.classList.toggle('btn-danger');
    });

    document.getElementById('exportCsv').onclick = () => gridApi.exportDataAsCsv({ fileName: 'employees.csv' });
    document.getElementById('exportExcel').onclick = () => gridApi.exportDataAsExcel({ fileName: 'employees.xlsx' });
</script>
@endpush