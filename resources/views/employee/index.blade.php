{{-- resources/views/employee/index.blade.php --}}
@extends('layouts.main')
@section('title', 'Employee Performance Dashboard (Premium)')

@push('head')
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

    /* Beautiful Blue Tick (Your Favorite Style) */
    .ag-set-filter-item .ag-checkbox-input-wrapper.ag-checked {
        background: #4dabf7 !important;
        border-color: #339af0 !important;
        box-shadow: 0 4px 12px rgba(77, 171, 247, 0.4) !important;
    }

    .ag-set-filter-item .ag-checkbox-input-wrapper.ag-checked::after {
        content: "Check" !important;
        color: white !important;
        font-weight: 900 !important;
        font-size: 15px !important;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
    }

    .ag-set-filter-item .ag-checkbox-input-wrapper {
        background: white !important;
        border: 2px solid #ddd !important;
        border-radius: 8px !important;
        width: 20px !important;
        height: 20px !important;
        position: relative !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .ag-set-filter-item:hover .ag-checkbox-input-wrapper {
        border-color: #4dabf7 !important;
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
    function ActionRenderer(params) {
        if (!params.data) return '';
        const id = params.data.emp_id;
        const showUrl = "{{ route('employees.show', ':id') }}".replace(':id', id);
        const editUrl = "{{ route('employees.edit', ':id') }}".replace(':id', id);
        return `<div style="display:flex;gap:6px;justify-content:center;height:100%;align-items:center;">
                    <a href="${showUrl}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                    <a href="${editUrl}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                </div>`;
    }

    const config = @json($gridConfig);
    let gridApi = null;

    const gridOptions = {
        components: { ActionRenderer },
        columnDefs: config.columns,
        rowData: config.data,
        theme: 'legacy',
        defaultColDef: { sortable: true, filter: true, resizable: true, floatingFilter: true, flex: 1, minWidth: 110 },
        pagination: true, paginationPageSize: 20,
        pivotMode: false, pivotPanelShow: 'always', rowGroupPanelShow: 'always',
        rowSelection: { mode: 'multiRow', checkboxes: true, headerCheckbox: true },
        sideBar: { toolPanels: ['columns', 'filters'], defaultToolPanel: 'columns' },
        animateRows: true, domLayout: 'normal',

        onGridReady: function(params) {
            gridApi = params.api;

            // YEH SABSE BEST TARIKA HAI — grid ready hone ke baad search attach karo
            const searchBox = document.getElementById('quickFilter');
            if (searchBox) {
                // Purane listeners hatao
                searchBox.removeEventListener('input', handleQuickSearch);

                // Naya clean handler
                function handleQuickSearch(e) {
                    gridApi.setQuickFilter(e.target.value);
                }

                // Attach kar do
                searchBox.addEventListener('input', handleQuickSearch);
            }

            console.log("Grid ready — Quick Search 100% ACTIVE!");
        }
    };

    agGrid.createGrid(document.getElementById('myGrid'), gridOptions);

    // Reset Button
    document.getElementById('resetAll')?.addEventListener('click', function() {
        if (!gridApi) return;
        gridApi.setFilterModel(null);
        gridApi.setQuickFilter('');
        document.getElementById('quickFilter').value = '';
        gridApi.setPivotMode(false);
        gridApi.setRowGroupColumns([]);
        gridApi.setColumnDefs(config.columns);
    });

    // Export Buttons
    document.getElementById('exportCsv')?.addEventListener('click', () => gridApi?.exportDataAsCsv({fileName: 'employees.csv'}));
    document.getElementById('exportExcel')?.addEventListener('click', () => gridApi?.exportDataAsExcel({fileName: 'employees.xlsx'}));

    // Filters & Pivot
    document.getElementById('toggleFilters')?.addEventListener('click', () => gridApi?.setSideBarVisible(!gridApi?.isSideBarVisible()));
    document.getElementById('togglePivot')?.addEventListener('click', function() {
        if (gridApi) {
            const isOn = gridApi.isPivotMode();
            gridApi.setPivotMode(!isOn);
            this.innerHTML = !isOn ? 'Exit Pivot' : 'Pivot Mode';
            this.classList.toggle('btn-outline-primary', isOn);
            this.classList.toggle('btn-danger', !isOn);
        }
    });
</script>
@endpush