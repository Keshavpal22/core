(function ($) {
    'use strict';

    $(document).ready(function () {
        var dTable = $('#spare-list').DataTable({
            scrollY: 300,
            scrollX: true,
            paging: true,
            fixedColumns: {
                start: 1,
                end: 1
            },
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
                    visible: true
                },
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
                            text: "All Headers",
                            show: ':hidden',
                            className: 'btn-info'
                        },
                        {
                            extend: 'colvisRestore',
                            text: "Default Headers",
                            show: ':visible',
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
                url: './spare-order/listX',
                type: "GET"
            },
            columns: [
                { data: 's_no', name: 's_no' },
                { data: 'created_at', name: 'created_at' },
                { data: 'requirement_no', name: 'requirement_no' },
                { data: 'service_branch', name: 'service_branch' },
                { data: 'service_category', name: 'service_category' },
                { data: 'workshop_type', name: 'workshop_type' },
                { data: 'model', name: 'model' },
                { data: 'variant', name: 'variant' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'contact_no', name: 'contact_no' },
                { data: 'vehicle_reg_no', name: 'vehicle_reg_no' },
                { data: 'ro_number', name: 'ro_number' },
                { data: 'ro_date', name: 'ro_date' },
                { data: 'ro_age', name: 'ro_age' },
                { data: 'parts_count', name: 'parts_count' },
                { data: 'req_qty', name: 'req_qty' },
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
    });
})(jQuery);