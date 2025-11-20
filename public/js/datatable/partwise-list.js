(function ($) {
    'use strict';

    $(document).ready(function () {
        var dTable = $('#partwise-list').DataTable({
            scrollY: 300,
            scrollX: true,
            paging: true,
            fixedColumns: {
                start: 1,
                end: 1
            },
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22],
                    visible: true
                },
                {
                    targets: '_all',
                    className: 'dt-head-center dt-body-center'
                },
                {
                    targets: 22, // Index of the 'allotment' column
                    orderable: false,
                    searchable: false
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
                url: '../spare-partwise-requirement/listX',
                type: "GET",
                dataSrc: function (json) {
                    if (!json || !json.data) {
                        console.error('Invalid or empty AJAX response:', json);
                        return [];
                    }
                    return json.data;
                }
            },
            columns: [
                { data: 's_no', name: 's_no', defaultContent: '' },
                { data: 'ro_age', name: 'ro_age', defaultContent: '' },
                { data: 'part_number', name: 'part_number', defaultContent: '' },
                { data: 'part_description', name: 'part_description', defaultContent: '' },
                { data: 'total_required_qty', name: 'total_required_qty', defaultContent: '0' },
                { data: 'total_ro_count', name: 'total_ro_count', defaultContent: '0' },
                { data: 'total_cs_count', name: 'total_cs_count', defaultContent: '0' },
                { data: 'workshop_required_qty', name: 'workshop_required_qty', defaultContent: '0' },
                { data: 'workshop_ro_count', name: 'workshop_ro_count', defaultContent: '0' },
                { data: 'workshop_cs_count', name: 'workshop_cs_count', defaultContent: '0' },
                { data: 'bodyshop_required_qty', name: 'bodyshop_required_qty', defaultContent: '0' },
                { data: 'bodyshop_ro_count', name: 'bodyshop_ro_count', defaultContent: '0' },
                { data: 'bodyshop_cs_count', name: 'bodyshop_cs_count', defaultContent: '0' },
                { data: 'physical_stock_qty', name: 'physical_stock_qty', defaultContent: '0' },
                { data: 'mat_in_transit_qty', name: 'mat_in_transit_qty', defaultContent: '0' },
                { data: 'back_order_qty', name: 'back_order_qty', defaultContent: '0' },
                { data: 'total_stock_qty', name: 'total_stock_qty', defaultContent: '0' },
                { data: 'status', name: 'status', defaultContent: '' },
                { data: 'allotted_qty', name: 'allotted_qty', defaultContent: '0' },
                { data: 'issued_qty', name: 'issued_qty', defaultContent: '0' },
                { data: 'returned_qty', name: 'returned_qty', defaultContent: '0' },
                { data: 'balance_qty', name: 'balance_qty', defaultContent: '0' },
                { data: 'allotment', name: 'allotment', orderable: false, searchable: false, defaultContent: '' }
            ],
            rowCallback: function (row, data, index) {
                if (data && data[3] && data[3] === '0112AAG06531C') {
                    $(row).addClass('important');
                }
            },
            drawCallback: function (settings) {
                var api = this.api();
                var start = api.page.info().start;
                api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = start + i + 1;
                });
                api.column(22).nodes().each(function (cell, i) {
                    var rowData = api.row(cell).data();
                    if (rowData && rowData.allotment) {
                        $(cell).html(rowData.allotment); // Inject HTML directly
                    } else {
                        $(cell).text(''); // Fallback for empty or undefined data
                    }
                });
            }
        });

        $('#customer-type').on('change', function () {
            dTable.ajax.reload();
        });
    });
})(jQuery);