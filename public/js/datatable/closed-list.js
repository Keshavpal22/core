(function ($) {
    'use strict';

    $(document).ready(function () {
        var dTable = $('#closure-list').DataTable({
            scrollY: 425,
            scrollX: true,
            paging: true,
            fixedColumns: {
                start: 2,
                end: 1
            },
            columnDefs: [
                {
                    targets: '_all',
                    className: 'dt-head-center dt-body-center'
                }
            ],
            layout: {
                topStart: {
                    pageLength: {
                        menu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'All']]
                    },
                    info: true
                },
                top: {
                    buttons: [
                        {
                            extend: 'colvisGroup',
                            text: 'All Headers',
                            show: ':hidden',
                            className: 'btn-info'
                        },
                        {
                            extend: 'colvisRestore',
                            text: 'Default Headers',
                            className: 'btn-warning'
                        },
                        {
                            extend: 'colvis',
                            text: 'Customise Headers',
                            className: 'btn-success'
                        }
                    ]
                },
                topEnd: {
                    search: {
                        placeholder: 'Type search here'
                    },
                    buttons: [
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: ':visible'
                            },
                            className: 'btn-warning'
                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible'
                            },
                            className: 'btn-primary'
                        }
                    ]
                }
            },
            ajax: {
                url: './closed/list',
                type: 'GET',
                data: function (d) {
                    // Add filters if needed
                }
            },
            columns: [
                { data: null, orderable: false }, // Serial number
                { data: 'ro_no', name: 'ro_no' },
                { data: 'tech1', name: 'tech1' },
                { data: 'tech2', name: 'tech2' },
                { data: 'tech3', name: 'tech3' },
                { data: 'remarks', name: 'remarks' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            drawCallback: function (settings) {
                var api = this.api();
                var start = api.page.info().start;
                api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = start + i + 1;
                });
            }
        });

        // Handle verify button click
        $('#closure-list').on('click', '.verify-btn', function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will verify the RO closure and change its status.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, verify it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/xcelr8/public/spare/closure/verify/' + id,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire(
                                    'Verified!',
                                    response.message,
                                    'success'
                                );
                                dTable.ajax.reload();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                'Failed to verify RO Closure: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Server error'),
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
})(jQuery);

