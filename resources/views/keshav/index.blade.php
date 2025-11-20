{{-- resources/views/users/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Registered Users')

@push('head')
<link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">

<style>
    .action-btns {
        display: flex;
        justify-content: center;
        gap: 14px;
        min-width: 120px;
    }
    .action-btns .btn {
        width: 42px !important;
        height: 42px !important;
        padding: 0 !important;
        border-radius: 50% !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }
    .action-btns .btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.22) !important;
    }
</style>
@endpush


@section('content')
<div class="container-fluid">

    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-users bg-blue"></i>
                    <div class="d-inline"><h5>Registered Users</h5></div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ik ik-home"></i> Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
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
                    <h3>User List</h3>
                    <a href="{{ route('newuser.create') }}" class="btn btn-primary btn-sm">Add New User</a>
                </div>

                <div class="card-body p-0">

                    <table id="users-table" class="table table-striped table-bordered display nowrap" width="100%">
                        <thead class="bg-light">
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Occupation</th>
                                <th>Exp.</th>
                                <th>Transfer</th>
                                <th>Created At</th>
                                <th width="120">Action</th>
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
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


<script>
$(document).ready(function () {

    var userTable = $('#users-table').DataTable({

        processing: true,
        serverSide: false,
        ajax: '{{ route('users.list') }}',

        scrollY: 425,
        scrollX: true,
        scrollCollapse: true,

        paging: true,
        pageLength: 10,
        lengthMenu: [[5,10,25,50,-1],[5,10,25,50,"All"]],

        fixedColumns: { left: 1, right: 1 },

        // ⭐ Right-end alignment (TOP + BOTTOM)
        dom:
            "<'row mb-3'<'col-md-6'l><'col-md-6 d-flex justify-content-end align-items-center'fB>>" +
            "<'row'<'col-12'tr>>" +
            "<'row mt-3'<'col-md-6'i><'col-md-6 d-flex justify-content-end'p>>",

        buttons: [
            {
                extend: 'excel',
                text: 'Excel',
                className: 'btn-success btn-sm',
                title: 'Registered_Users_' + new Date().toISOString().slice(0,10)
            },
            {
                extend: 'print',
                text: 'Print / Save as PDF',
                className: 'btn-primary btn-sm',
                exportOptions: { columns: ':visible' },
                title: '',
                customize: function (win) {
                    $(win.document.body).prepend(`
                        <div style="text-align:center; padding:15px; border-bottom:3px solid #007bff;">
                            <h2>Registered Users Report</h2>
                            <h4>${new Date().toLocaleDateString('en-IN')} ${new Date().toLocaleTimeString('en-IN')}</h4>
                            <h4>Total Records: ${$(win.document.body).find('table tbody tr').length}</h4>
                        </div>
                    `);
                }
            }
        ],

        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'full_name' },
            { data: 'email' },
            { data: 'phone' },
            { data: 'gender' },
            { data: 'occupation_field' },
            { data: 'experience' },
            { data: 'mode_of_transfer' },
            { data: 'created_at' },
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

});
</script>
@endpush
