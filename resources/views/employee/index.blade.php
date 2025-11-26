{{-- resources/views/employee/index.blade.php --}}
@extends('layouts.main')
@section('title', 'Employee Performance')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css" />
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css" />
<style>
    #myGrid {
        height: 650px;
        width: 100%;
    }

    .ag-paging-panel {
        display: none !important;
    }

    .grid-toolbar {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
    }

    .custom-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        margin-bottom: 15px;
    }

    .page-info {
        font-weight: 600;
    }

    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 20px;
    }

    .pivot-toggle {
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    @include('include.message')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Employee Performance Dashboard</h3>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
        </div>
        <div class="card-body">
            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <h4>{{ $totalEmployees }}</h4>
                        <p>Total Employees</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <h4>{{ number_format($avgEfficiency, 1) }}%</h4>
                        <p>Avg Efficiency</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <h4>{{ number_format($avgAttendance, 1) }}%</h4>
                        <p>Avg Attendance</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <h4>{{ $topPerformers }}</h4>
                        <p>5-Star Performers</p>
                    </div>
                </div>
            </div>

            <!-- Pivot Toggle (Community-style grouping as pivot alternative) -->
            <div class="pivot-toggle">
                <button id="togglePivot" class="btn btn-outline-primary btn-sm">Toggle Pivot View (Group by
                    Department)</button>
            </div>

            <!-- Toolbar -->
            <div class="grid-toolbar">
                <div>
                    <input type="text" id="searchBox" class="form-control" style="width:300px;"
                        placeholder="Quick Search...">
                </div>
                <div>
                    <button id="exportCsv" class="btn btn-success btn-sm">Export CSV</button>
                    <button id="printBtn" class="btn btn-dark btn-sm">Print</button>
                </div>
            </div>

            <!-- Custom Pagination (Top) -->
            <div class="custom-pagination">
                <div class="page-info">
                    <span id="rangeText">1 - 20 of {{ $totalEmployees }}</span>
                </div>
                <div>
                    <button id="firstPage" class="btn btn-light btn-sm">First</button>
                    <button id="prevPage" class="btn btn-light btn-sm">Previous</button>
                    <span id="pageText" class="mx-2 fw-bold">Page 1 of {{ ceil($totalEmployees/20) }}</span>
                    <button id="nextPage" class="btn btn-light btn-sm">Next</button>
                    <button id="lastPage" class="btn btn-light btn-sm">Last</button>
                </div>
                <div>
                    <select id="pageSizeSelect" class="form-select form-select-sm">
                        <option value="10">10</option>
                        <option value="20" selected>20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <!-- AG Grid -->
            <div id="myGrid" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script>
<script>
    const rowData = @json($employees);

    let isPivotView = false;
    let columnDefs = [
        { field: 'emp_id', headerName: 'ID', width: 90 },
        { field: 'name', headerName: 'Name', width: 200 },
        { field: 'department', headerName: 'Department', width: 130 },
        { field: 'country', headerName: 'Country', width: 100 },
        { field: 'tasks', headerName: 'Tasks', width: 100 },
        { field: 'hours', headerName: 'Hours', width: 100 },
        { field: 'leaves', headerName: 'Leaves', width: 100 },
        { field: 'efficiency', headerName: 'Efficiency %', width: 130, valueFormatter: p => p.value ? p.value.toFixed(1)+'%' : '' },
        { field: 'attendance', headerName: 'Attendance %', width: 140, valueFormatter: p => p.value ? p.value.toFixed(1)+'%' : '' },
        { field: 'rating', headerName: 'Rating', width: 100 },
        {
            headerName: 'Action',
            width: 180,
            cellRenderer: params => {
                const id = params.data.emp_id;
                const show = "{{ route('employees.show', ':id') }}".replace(':id', id);
                const edit = "{{ route('employees.edit', ':id') }}".replace(':id', id);
                return `<a href="${show}" class="btn btn-info btn-sm">View</a> <a href="${edit}" class="btn btn-success btn-sm">Edit</a>`;
            }
        }
    ];

    // Pivot-like column defs (basic grouping on department)
    const pivotColumnDefs = [
        { field: 'department', rowGroup: true, hide: true },  // Basic grouping (Community-supported)
        { field: 'emp_id', headerName: 'ID', width: 90 },
        { field: 'name', headerName: 'Name', width: 200 },
        { field: 'country', headerName: 'Country', width: 100 },
        { field: 'tasks', headerName: 'Tasks', width: 100 },
        { field: 'hours', headerName: 'Hours', width: 100 },
        { field: 'leaves', headerName: 'Leaves', width: 100 },
        { field: 'efficiency', headerName: 'Efficiency %', width: 130, valueFormatter: p => p.value ? p.value.toFixed(1)+'%' : '' },
        { field: 'attendance', headerName: 'Attendance %', width: 140, valueFormatter: p => p.value ? p.value.toFixed(1)+'%' : '' },
        { field: 'rating', headerName: 'Rating', width: 100 },
        {
            headerName: 'Action',
            width: 180,
            cellRenderer: params => {
                if (!params.data) return 'TOTAL';  // Footer for grouped rows
                const id = params.data.emp_id;
                const show = "{{ route('employees.show', ':id') }}".replace(':id', id);
                const edit = "{{ route('employees.edit', ':id') }}".replace(':id', id);
                return `<a href="${show}" class="btn btn-info btn-sm">View</a> <a href="${edit}" class="btn btn-success btn-sm">Edit</a>`;
            }
        }
    ];

    const gridOptions = {
        columnDefs,
        rowData,
        animateRows: true,
        pagination: true,
        paginationPageSize: 20,
        paginationPageSizeSelector: [10, 20, 50, 100],
        defaultColDef: {
            resizable: true,
            sortable: true,
            filter: true,
            floatingFilter: true
        },
        groupDefaultExpanded: 1,  // For pivot-like view
        autoGroupColumnDef: {
            headerName: 'Department Group',
            minWidth: 320
        }
    };

    agGrid.createGrid(document.querySelector('#myGrid'), gridOptions);

    // Toggle Pivot View
    document.getElementById('togglePivot').onclick = () => {
        isPivotView = !isPivotView;
        gridOptions.columnDefs = isPivotView ? pivotColumnDefs : columnDefs;
        gridOptions.api.setColumnDefs(gridOptions.columnDefs);
        document.getElementById('togglePivot').textContent = isPivotView ? 'Toggle Flat View' : 'Toggle Pivot View (Group by Department)';
    };

    // Update pagination info
    function updatePaginationInfo() {
        const currentPage = gridOptions.api.paginationGetCurrentPage() + 1;
        const pageSize = gridOptions.api.paginationGetPageSize();
        const totalRows = gridOptions.api.getDisplayedRowCount();
        const totalPages = gridOptions.api.paginationGetTotalPages();
        const start = (currentPage - 1) * pageSize + 1;
        const end = Math.min(currentPage * pageSize, totalRows);

        document.getElementById('rangeText').textContent = `${start} - ${end} of ${totalRows}`;
        document.getElementById('pageText').textContent = `Page ${currentPage} of ${totalPages}`;
    }

    gridOptions.onGridReady = updatePaginationInfo;
    gridOptions.onPaginationChanged = updatePaginationInfo;

    // Search
    document.getElementById('searchBox').addEventListener('input', e => {
        gridOptions.api.setQuickFilter(e.target.value);
    });

    // Page Size
    document.getElementById('pageSizeSelect').addEventListener('change', e => {
        gridOptions.api.paginationSetPageSize(Number(e.target.value));
    });

    // Pagination Buttons
    document.getElementById('firstPage').onclick = () => gridOptions.api.paginationGoToFirstPage();
    document.getElementById('prevPage').onclick = () => gridOptions.api.paginationGoToPreviousPage();
    document.getElementById('nextPage').onclick = () => gridOptions.api.paginationGoToNextPage();
    document.getElementById('lastPage').onclick = () => gridOptions.api.paginationGoToLastPage();

    // CSV Export (Working!)
    document.getElementById('exportCsv').onclick = () => {
        gridOptions.api.exportDataAsCsv({
            fileName: 'employee_performance_' + new Date().toISOString().slice(0,10) + '.csv',
            columnSeparator: ','
        });
    };

    // Print
    document.getElementById('printBtn').onclick = () => window.print();
</script>
@endpush