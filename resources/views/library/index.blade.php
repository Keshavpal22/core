{{-- resources/views/library/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Books')

@push('head')
<!-- DataTables CSS -->
<link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">

<style>

    /* Action Buttons */
    .action-btns {
        display: flex;
        justify-content: center;
        gap: 10px;
        min-width: 120px;
    }
    .action-btns .btn {
        width: 40px !important;
        height: 40px !important;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }

    /* Center Header Buttons Styling */
    .header-btns .dt-button {
        margin: 0 8px;
        padding: 6px 14px !important;
        border-radius: 6px;
        font-size: 13px;
    }

    /* Export Buttons — bigger & spaced */
    .export-btns .dt-button {
        padding: 8px 18px !important;
        font-size: 14px !important;
        margin-left: 14px !important;
        margin-right: 8px !important;
        border-radius: 6px !important;
    }

    /* Button Colors */
    .btn-col-all     { background:#28a745 !important; color:#fff !important; }
    .btn-col-default { background:#ffc107 !important; color:#000 !important; }
    .btn-col-custom  { background:#6c757d !important; color:#fff !important; }

    /* ⭐⭐⭐ Fix: Perfectly Center Header Buttons ⭐⭐⭐ */
    .header-btns {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
    }

    .header-btns .dt-buttons {
        display: flex !important;
        justify-content: center !important;
        width: 100% !important;
    }

</style>
@endpush



@section('content')
<div class="container-fluid">

    @include('include.message')

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

        /* ⭐⭐⭐ FINAL: SCROLL ENABLE ⭐⭐⭐ */
        scrollY: 450,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: { left: 1, right: 1 },

        dom:
            "<'row mb-3'<'col-md-12 text-center header-btns'B>>" +
            "<'row mb-3'<'col-md-6'l><'col-md-6 d-flex justify-content-end align-items-center'<'search-container'f><'export-btns ml-3'>>>" +
            "<'row'<'col-12'tr>>" +
            "<'row mt-3'<'col-md-6'i><'col-md-6 d-flex justify-content-end'p>>",

        buttons: [

            /* ==== CENTER HEADER BUTTONS ==== */
            {
                extend: 'colvisGroup',
                text: 'All Headers',
                show: ':hidden',
                className: 'btn-col-all'
            },
            {
                text: 'Default Headers',
                className: 'btn-col-default',
                action: function (e, dt) {
                    dt.columns().visible(false);
                    dt.columns([0,1,2,4,8,10]).visible(true);
                }
            },
            {
                extend: 'colvis',
                text: 'Customize Headers',
                className: 'btn-col-custom'
            },

            /* ==== EXPORT BUTTONS ==== */
            {
                extend: 'excel',
                text: 'Excel',
                className: 'btn-success export-move'
            },
            {
                extend: 'print',
                text: 'Print / Save PDF',
                className: 'btn-primary export-move'
            }
        ],

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
        }
    });

    // Move Excel & Print into export button div
    $(".export-move").appendTo(".export-btns");

});
</script>

@endpush
