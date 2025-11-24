{{-- resources/views/library/index.blade.php --}}
@extends('layouts.main')
@section('title', 'Books')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community@28.2.1/dist/styles/ag-grid.css" />
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community@28.2.1/dist/styles/ag-theme-alpine.css" />

<style>
    #myGrid {
        height: 520px;
        width: 100%;
        border: 1px solid #e3e6ea;
        border-radius: 6px;
        margin-bottom: 55px;
        /* Space for custom pagination */
        position: relative;
    }

    /* Hide AG-Grid default pagination (we keep your custom pagination) */
    .ag-paging-panel {
        display: none !important;
    }

    .grid-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .left-tools,
    .right-tools {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    /* Footer cell placeholders styling — matches grid minimal look */
    .footer-box {
        display: inline-block;
        height: 34px;
        min-width: 80px;
        box-sizing: border-box;
        border: 1px solid rgba(0, 0, 0, 0.12);
        border-radius: 4px;
        padding: 4px 8px;
        background: white;
        line-height: 24px;
        font-size: 13px;
    }

    .footer-select {
        height: 34px;
        padding: 4px 6px;
        border-radius: 4px;
        border: 1px solid rgba(0, 0, 0, 0.12);
        background: #fff;
        font-size: 13px;
    }

    .footer-result {
        display: inline-block;
        min-width: 70px;
        text-align: left;
        margin-left: 8px;
        font-weight: 600;
    }

    /* make pinned bottom row visually separated a bit */
    .ag-theme-alpine .ag-pinned-bottom-wrapper {
        background: #fff;
    }
</style>
@endpush


@section('content')
<div class="container-fluid">

    @include('include.message')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Book List (AG-Grid Premium)</h3>
            <a href="{{ route('books.create') }}" class="btn btn-primary btn-sm">Add New Book</a>
        </div>

        <div class="card-body">

            <div class="grid-toolbar">
                <div class="left-tools">

                    <select id="pageSizeSelector" class="form-control" style="width:65px;">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>

                    <input type="text" id="searchBox" class="form-control" style="width:260px;"
                        placeholder="Quick search..." />

                    <button id="btnToggleFilters" class="btn btn-outline-secondary btn-sm">
                        Toggle Filters
                    </button>

                    <button id="btnClearFilters" class="btn btn-secondary btn-sm">Reset</button>
                </div>

                <div class="right-tools">
                    <button id="btnExportCsv" class="btn btn-info btn-sm">Export CSV</button>
                    <button id="btnExportXlsx" class="btn btn-success btn-sm">Export XLSX</button>
                    <button id="btnPrint" class="btn btn-dark btn-sm">Print</button>
                </div>
            </div>

            <!-- GRID -->
            <div id="myGrid" class="ag-theme-alpine"></div>

            <!-- ❗ Custom Pagination OUTSIDE GRID -->
            <div class="d-flex justify-content-end align-items-center mt-2 gap-2" id="customPagination">
                <span id="rangeText" class="me-3 fw-bold"></span>

                <button id="firstPage" class="btn btn-light btn-sm">⏮</button>
                <button id="prevPage" class="btn btn-light btn-sm">◀</button>

                <span id="pageText" class="mx-2 fw-bold"></span>

                <button id="nextPage" class="btn btn-light btn-sm">▶</button>
                <button id="lastPage" class="btn btn-light btn-sm">⏭</button>
            </div>

        </div>
    </div>
</div>
@endsection


@push('script')
<script src="https://unpkg.com/ag-grid-enterprise@28.2.1/dist/ag-grid-enterprise.min.noStyle.js"></script>

<script>
    agGrid.LicenseManager.setLicenseKey("[TRIAL]this{AG_Charts_and_AG_Grid}Enterprise_key{AG-109598}is_granted_for_evaluation_only_Use_in_production_is_not_permitted_Please_report_misuse_to_legal@ag-grid.com_For_help_with_purchasing_a_production_key_please_contact_info@ag-grid.com_You_are_granted_a{Single_Application}Developer_License_for_one_application_only_All_Front-End_JavaScript_developers_working_on_the_application_would_need_to_be_licensed_This_key_will_deactivate_on{14 December 2025}[v3]_[0102]_MTc2NTY3MDQwMDAwMA==d709f6f1a968a8b9944a445eff396d3e");

//
// Column definitions - note special cellRenderer used for pinned bottom row
//
const columnDefs = [

    /* Checkbox */
    {
        headerName: "",
        checkboxSelection: true,
        headerCheckboxSelection: true,
        width: 50,
        pinned: "left",
        resizable: false,
        // pinned footer cell for checkbox -> show empty box
        cellRenderer: function(params) {
            // normal cell content
            if (params.node && params.node.rowPinned === 'bottom') {
                return `<div class="footer-box"></div>`;
            }
            return params.value;
        }
    },

    /* Row Drag */
    {
        headerName: "",
        rowDrag: true,
        width: 50,
        pinned: "left",
        resizable: false,
        cellRenderer: function(params) {
            if (params.node && params.node.rowPinned === 'bottom') {
                return `<div class="footer-box"></div>`;
            }
            return params.value;
        }
    },

    /* Fields */
    { headerName: "S.No", valueGetter: "node.rowIndex + 1", width: 90,
        cellRenderer: function(params){
            if (params.node && params.node.rowPinned === 'bottom') {
                return `<div class="footer-box"></div>`;
            }
            return params.value;
        }
    },

    { field: "title", sortable: true, filter: 'agTextColumnFilter', floatingFilter: true,
        cellRenderer: function(params){
            if (params.node && params.node.rowPinned === 'bottom') return `<div class="footer-box"></div>`;
            return params.value;
        }
    },

    { field: "author", sortable: true, filter: 'agTextColumnFilter', floatingFilter: true,
        cellRenderer: function(params){
            if (params.node && params.node.rowPinned === 'bottom') return `<div class="footer-box"></div>`;
            return params.value;
        }
    },

    {
        field: "genre",
        sortable: true,
        filter: 'agSetColumnFilter',
        floatingFilter: true,
        filterParams: { suppressMiniFilter: false },
        cellRenderer: function(params){
            if (params.node && params.node.rowPinned === 'bottom') return `<div class="footer-box"></div>`;
            return params.value;
        }
    },

    { field: "isbn", sortable: true, filter: 'agNumberColumnFilter', floatingFilter: true,
        cellRenderer: function(params){
            if (params.node && params.node.rowPinned === 'bottom') return `<div class="footer-box"></div>`;
            return params.value;
        }
    },

    { field: "publisher", sortable: true, filter: 'agTextColumnFilter', floatingFilter: true,
        cellRenderer: function(params){
            if (params.node && params.node.rowPinned === 'bottom') return `<div class="footer-box"></div>`;
            return params.value;
        }
    },

    { field: "publication_year", headerName: "Year", sortable: true, filter: 'agNumberColumnFilter', floatingFilter: true,
        cellRenderer: function(params){
            if (params.node && params.node.rowPinned === 'bottom') return `<div class="footer-box"></div>`;
            return params.value;
        }
    },

    /* Total copies -> pinned footer must show dropdown + result */
    {
        field: "total_copies",
        headerName: "Total",
        sortable: true,
        filter: 'agNumberColumnFilter',
        floatingFilter: true,
        cellRenderer: function(params) {
            // if this is the pinned bottom row - render select + result area
            if (params.node && params.node.rowPinned === 'bottom') {
                // unique ids: footer-total-select and footer-total-value
                return `
                    <div style="display:flex; align-items:center; gap:8px;">
                        <select id="footer-total-select" class="footer-select">
                            <option value="">Select</option>
                            <option value="SUM">SUM</option>
                            <option value="AVG">AVG</option>
                            <option value="MIN">MIN</option>
                            <option value="MAX">MAX</option>
                            <option value="COUNT">COUNT</option>
                            <option value="PRODUCT">PRODUCT</option>
                        </select>
                        <span id="footer-total-value" class="footer-result"></span>
                    </div>
                `;
            }
            return params.value;
        }
    },

    /* Available copies -> pinned footer dropdown + result */
    {
        field: "available_copies",
        headerName: "Available",
        sortable: true,
        filter: 'agNumberColumnFilter',
        floatingFilter: true,
        cellRenderer: function(params) {
            if (params.node && params.node.rowPinned === 'bottom') {
                return `
                    <div style="display:flex; align-items:center; gap:8px;">
                        <select id="footer-available-select" class="footer-select">
                            <option value="">Select</option>
                            <option value="SUM">SUM</option>
                            <option value="AVG">AVG</option>
                            <option value="MIN">MIN</option>
                            <option value="MAX">MAX</option>
                            <option value="COUNT">COUNT</option>
                            <option value="PRODUCT">PRODUCT</option>
                        </select>
                        <span id="footer-available-value" class="footer-result"></span>
                    </div>
                `;
            }
            return params.value;
        }
    },

    { field: "issued_by", headerName: "Issued By", sortable: true, filter: 'agTextColumnFilter', floatingFilter: true,
        cellRenderer: function(params){
            if (params.node && params.node.rowPinned === 'bottom') return `<div class="footer-box"></div>`;
            return params.value;
        }
    },

    /* Action Buttons */
    {
        headerName: "Action",
        pinned: "right",
        width: 150,
        cellRenderer: params => {
            if (params.node && params.node.rowPinned === 'bottom') {
                return `<div class="footer-box"></div>`;
            }
            let id = params.data.isbn;
            let viewUrl = "{{ route('books.show', ':id') }}".replace(":id", id);
            let editUrl = "{{ route('books.edit', ':id') }}".replace(":id", id);

            return `
                <a href="${viewUrl}" class="btn btn-info btn-sm me-1"><i class="ik ik-eye"></i></a>
                <a href="${editUrl}" class="btn btn-success btn-sm"><i class="ik ik-edit-2"></i></a>
            `;
        }
    }
];

const rowData = @json($books);

const gridOptions = {
    columnDefs,
    rowData,
    animateRows: true,
    rowDragManaged: true,
    rowSelection: 'multiple',
    pagination: true,
    paginationPageSize: 10,

    sideBar: {
        defaultToolPanel: "columns",
        toolPanels: [{ id: "columns", labelDefault: "Columns", iconKey: "columns", toolPanel: "agColumnsToolPanel" }]
    },

    defaultColDef: {
        resizable: true,
        sortable: true,
        filter: true,
        floatingFilter: true
    },

    // initialize a pinned bottom row (one row)
    pinnedBottomRowData: [
        // We create an object with keys matching column fields; values are ignored because renderers will handle the pinned row
        {
            title: '', author: '', genre: '', isbn: '', publisher: '',
            publication_year: '', total_copies: '', available_copies: '', issued_by: ''
        }
    ]
};

new agGrid.Grid(document.getElementById('myGrid'), gridOptions);

//
// AG-GRID helpers & custom footer aggregation logic (Option A: current page rows)
//
function getPageRowRange() {
    const api = gridOptions.api;
    const page = api.paginationGetCurrentPage();
    const pageSize = api.paginationGetPageSize();
    const start = page * pageSize;
    const totalDisplayed = api.getDisplayedRowCount();
    const end = Math.min(start + pageSize - 1, totalDisplayed - 1);
    return { start, end };
}

function collectNumericValuesOnPage(field) {
    // gather values from displayed rows on the current page only
    const api = gridOptions.api;
    const { start, end } = getPageRowRange();
    const values = [];
    for (let i = start; i <= end; i++) {
        const node = api.getDisplayedRowAtIndex(i);
        if (!node || !node.data) continue;
        let v = node.data[field];
        // ignore null/undefined/empty strings for numeric ops (but COUNT counts non-null)
        if (v === null || v === undefined || v === '') continue;
        // convert numeric-like strings to numbers (if not numeric, skip)
        const num = parseFloat(String(v).toString().replace(/,/g, ''));
        if (!isNaN(num)) values.push(num);
    }
    return values;
}

function computeAggregate(values, op) {
    if (!values || values.length === 0) {
        if (op === 'COUNT') return 0;
        return '';
    }
    switch (op) {
        case 'SUM':
            return values.reduce((a,b) => a + b, 0);
        case 'AVG':
            return values.reduce((a,b)=>a+b,0)/values.length;
        case 'MIN':
            return Math.min(...values);
        case 'MAX':
            return Math.max(...values);
        case 'COUNT':
            return values.length;
        case 'PRODUCT':
            return values.reduce((a,b)=>a*b, 1);
        default:
            return '';
    }
}

function formatNumberSmart(n) {
    if (n === '' || n === null || n === undefined) return '';
    if (typeof n === 'number' && !Number.isInteger(n)) {
        // show upto 4 decimal for safety
        return Number(n).toFixed(4).replace(/\.?0+$/,'');
    }
    return String(n);
}

// triggered when user changes dropdown for total/available
function onFooterSelectChanged(colField, op) {
    const values = collectNumericValuesOnPage(colField);
    const result = computeAggregate(values, op);
    const elId = (colField === 'total_copies') ? 'footer-total-value' : 'footer-available-value';
    const el = document.getElementById(elId);
    if (el) el.innerText = formatNumberSmart(result);
}

// safely attach listeners for selects (called after grid creates pinned row cells)
function attachFooterListeners() {
    const totalSelect = document.getElementById('footer-total-select');
    if (totalSelect) {
        totalSelect.onchange = function() {
            const op = this.value;
            onFooterSelectChanged('total_copies', op);
        };
    }
    const availableSelect = document.getElementById('footer-available-select');
    if (availableSelect) {
        availableSelect.onchange = function() {
            const op = this.value;
            onFooterSelectChanged('available_copies', op);
        };
    }
}

// update both footer results (practically when page/filter changes)
function recomputeFooterValuesKeepSelection() {
    // if select has a value, recompute result using that selection
    const totalSelect = document.getElementById('footer-total-select');
    if (totalSelect && totalSelect.value) {
        onFooterSelectChanged('total_copies', totalSelect.value);
    }
    const availableSelect = document.getElementById('footer-available-select');
    if (availableSelect && availableSelect.value) {
        onFooterSelectChanged('available_copies', availableSelect.value);
    }
}

//
// Custom Pagination UI (you already had it):
//
function updatePagination() {
    if (!gridOptions.api) return;

    let current = gridOptions.api.paginationGetCurrentPage() + 1;
    let totalPages = gridOptions.api.paginationGetTotalPages();
    let totalRows = gridOptions.api.getDisplayedRowCount();
    let pageSize = gridOptions.api.paginationGetPageSize();

    let start = (current - 1) * pageSize + 1;
    let end = Math.min(start + pageSize - 1, totalRows);

    document.getElementById("rangeText").innerHTML = `${start} to ${end} of ${totalRows}`;
    document.getElementById("pageText").innerHTML = `Page ${current} of ${totalPages}`;
}

document.getElementById("firstPage").onclick = () => { gridOptions.api.paginationGoToFirstPage(); updatePagination(); recomputeFooterValuesKeepSelection(); };
document.getElementById("prevPage").onclick  = () => { gridOptions.api.paginationGoToPreviousPage(); updatePagination(); recomputeFooterValuesKeepSelection(); };
document.getElementById("nextPage").onclick  = () => { gridOptions.api.paginationGoToNextPage(); updatePagination(); recomputeFooterValuesKeepSelection(); };
document.getElementById("lastPage").onclick  = () => { gridOptions.api.paginationGoToLastPage(); updatePagination(); recomputeFooterValuesKeepSelection(); };

// re-attach listeners after pinned bottom row renders
gridOptions.onGridReady = function(params) {
    // make sure custom pagination initial text is set
    setTimeout(function() {
        updatePagination();
        // attach listeners (DOM for pinned bottom is created after grid renders)
        attachFooterListeners();
        recomputeFooterValuesKeepSelection();
    }, 200);
};

// when filters / sorts / model / page changes, pinned bottom row remains but DOM may update, so reattach & recompute
gridOptions.onFilterChanged = function() {
    setTimeout(function(){
        attachFooterListeners();
        recomputeFooterValuesKeepSelection();
        updatePagination();
    }, 50);
};
gridOptions.onSortChanged = function() {
    setTimeout(function(){
        attachFooterListeners();
        recomputeFooterValuesKeepSelection();
    }, 50);
};
gridOptions.onPaginationChanged = function() {
    setTimeout(function(){
        attachFooterListeners();
        recomputeFooterValuesKeepSelection();
        updatePagination();
    }, 50);
};
gridOptions.onModelUpdated = function() {
    setTimeout(function(){
        attachFooterListeners();
        recomputeFooterValuesKeepSelection();
    }, 50);
};

// Page-size change: keep things synced
document.getElementById('pageSizeSelector').addEventListener('change', (e) => {
    gridOptions.api.paginationSetPageSize(Number(e.target.value));
    setTimeout(function(){
        updatePagination();
        attachFooterListeners();
        recomputeFooterValuesKeepSelection();
    }, 80);
});

// Search Quick filter
document.getElementById('searchBox').addEventListener('input', e => {
    gridOptions.api.setQuickFilter(e.target.value);
    // update footer & pagination after quick filter applies
    setTimeout(function(){
        attachFooterListeners();
        recomputeFooterValuesKeepSelection();
        updatePagination();
    }, 120);
});

// Toggle floating filters visibility (same as your previous behaviour)
let filtersVisible = true;
document.getElementById('btnToggleFilters').addEventListener('click', () => {
    filtersVisible = !filtersVisible;
    gridOptions.api.setFloatingFiltersVisible(filtersVisible);
});

// Reset
document.getElementById('btnClearFilters').addEventListener('click', () => {
    gridOptions.api.setFilterModel(null);
    gridOptions.api.setQuickFilter(null);
    setTimeout(function(){
        updatePagination();
        attachFooterListeners();
        recomputeFooterValuesKeepSelection();
    }, 80);
});

// Export Buttons
document.getElementById('btnExportCsv').addEventListener('click', () =>
    gridOptions.api.exportDataAsCsv()
);
document.getElementById('btnExportXlsx').addEventListener('click', () =>
    gridOptions.api.exportDataAsExcel()
);

// Print
document.getElementById('btnPrint').addEventListener('click', () => window.print());

// initial pagination update after grid creation
setTimeout(function(){
    updatePagination();
    attachFooterListeners();
    recomputeFooterValuesKeepSelection();
}, 600);

</script>
@endpush