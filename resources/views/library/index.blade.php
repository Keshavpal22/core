{{-- resources/views/library/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Books')

@push('head')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">

    <style>

        /* ==== ACTION BUTTONS (VIEW / EDIT) ==== */
        .action-btns {
            display: flex;
            justify-content: center;
            gap: 10px;
            min-width: 120px;
        }
        .action-btns .btn {
            width: 40px !important;
            height: 40px !important;
            padding: 0 !important;
            border-radius: 50% !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.12);
        }
        .action-btns .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.18) !important;
        }

        /* ==== DATATABLE BUTTONS ==== */
        .dt-buttons .dt-button {
            margin-right: 8px !important;
            padding: 6px 12px !important;
            border-radius: 6px !important;
            font-size: 13px !important;
            font-weight: 500;
        }

        /* ===== CUSTOM BUTTON COLORS ===== */
        .btn-col-all {
            background: #28a745 !important;
            color: white !important;
        }
        .btn-col-default {
            background: #ffc107 !important;
            color: black !important;
        }
        .btn-col-custom {
            background: #6c757d !important;
            color: white !important;
        }

        /* ==== PRINT MODE ==== */
        @media print {
            body { background: #fff !important; }
            .page-header, .card-header .btn, .dt-buttons, .action-btns { display: none !important; }
            .card { border: none; box-shadow: none; }
            #books-table th, #books-table td {
                border: 1px solid #ddd !important;
                padding: 8px;
                text-align: center;
                font-size: 12px;
            }
        }
    </style>
@endpush



@section('content')
<div class="container-fluid">

    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-book bg-blue"></i>
                    <div class="d-inline">
                        <h5>Books</h5>
                        <span class="ml-1 text-muted">Library Catalog</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ik ik-home"></i> Home</a></li>
                        <li class="breadcrumb-item active">Books</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @include('include.message')

    <div class="row">
        <div class="col-md-12">

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Book List</h3>
                    <a href="{{ route('books.create') }}" class="btn btn-primary btn-sm">Add New Book</a>
                </div>

                <div class="card-body p-0">

                    <table id="books-table" class="table table-striped table-bordered display nowrap" width="100%">
                        <thead class="bg-light">
                            <tr>
                                <th>S.No</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>ISBN</th>
                                <th>Publisher</th>
                                <th>Year</th>
                                <th>Total Copies</th>
                                <th>Available</th>
                                <th>Issued By</th>
                                <th width="130">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection



@push('script')

<script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(function () {

    var table = $('#books-table').DataTable({

        processing: true,
        serverSide: true,
        ajax: '{{ route("books.list") }}',

        /* ===== TOP BAR LAYOUT ===== */
        dom:
            "<'row mb-3'<'col-md-6'l>" +
            "<'col-md-6 d-flex justify-content-end align-items-center gap-2'fB>>" +
            "<'row'<'col-12'tr>>" +
            "<'row mt-3'<'col-md-6'i><'col-md-6 d-flex justify-content-end'p>>",

        scrollY: 425,
        scrollX: true,
        paging: true,
        pageLength: 10,

        fixedColumns: { left: 1, right: 1 },

        /* ===== BUTTONS ===== */
        buttons: [

            {
                extend: 'colvisGroup',
                text: 'All Headers',
                show: ':hidden',
                className: 'btn-col-all btn-sm'
            },

            {
                text: 'Default Headers',
                className: 'btn-col-default btn-sm',
                action: function (e, dt) {
                    dt.columns().visible(false);
                    dt.columns([0,1,2,4,8,10]).visible(true);
                }
            },

            {
                extend: 'colvis',
                text: 'Customize Headers',
                className: 'btn-col-custom btn-sm'
            },

            {
                extend: 'excel',
                text: 'Excel',
                className: 'btn-success btn-sm',
                exportOptions: { columns: ':visible' },
                title: 'Books_' + new Date().toISOString().slice(0,10)
            },

            {
                extend: 'print',
                text: 'Print / Save as PDF',
                className: 'btn-primary btn-sm',
                exportOptions: { columns: ':visible' }
            }
        ],

        /* ===== COLUMNS ===== */
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title' },
            { data: 'author' },
            { data: 'genre' },
            { data: 'isbn' },
            { data: 'publisher' },
            { data: 'publication_year' },
            { data: 'total_copies' },
            { data: 'available_copies' },
            { data: 'issued_by' },
            { data: 'action', orderable: false, searchable: false }
        ],

        drawCallback: function () {
            var api = this.api();
            var start = api.page.info().start;
            api.column(0).nodes().each(function (cell, i) {
                cell.innerHTML = start + i + 1;
            });
        },

        language: {
            searchPlaceholder: "Search...",
            search: ""
        }

    });

});
</script>

@endpush
