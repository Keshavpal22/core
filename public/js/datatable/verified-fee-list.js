(function ($) {
    'use strict';

    $(document).ready(function () {
        var dTable = $('#verified-fee-list').DataTable({
            scrollY: 425,
            scrollX: true,
            paging: true,
            fixedColumns: {
                left: 2,
                right: 1
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
                url: './verified/list',
                type: 'GET',
                data: function (d) {
                    // Add filters if needed
                }
            },
            columns: [
                { data: null, orderable: false }, // Serial number
                { data: 'customer_name', name: 'customer_name' },
                { data: 'care_of_name', name: 'care_of_name' },
                { data: 'mobile_no', name: 'mobile_no' },
                { data: 'model', name: 'model' },
                { data: 'otf_no', name: 'otf_no' },
                { data: 'chassis_no', name: 'chassis_no' },
                { data: 'amount', name: 'amount' },
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
