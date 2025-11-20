(function ($) {
    'use strict';

    $(document).ready(function () {
        var dTable = $('#not-interested-finance-list').DataTable({
            scrollY: 425,
            scrollX: true,
            paging: true,
            fixedColumns: {
                start: 1,
                end: 1
            },
            columnDefs: [
                {
                    targets: [3, 4, 5, 10], // Hide Booking Date, B Type, Branch, Color by default
                    visible: false
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
                        { extend: 'colvisGroup', text: "All Headers", show: ':hidden', className: 'btn-info' },
                        {
                            extend: 'colvisRestore',
                            text: "Default Headers",
                            className: 'btn-warning',
                            action: function (e, dt, node, config) {
                                var defaultVisible = [0, 1, 2, 6, 7, 8, 9, 11, 12, 13, 14, 15];
                                dt.columns().visible(false);
                                dt.columns(defaultVisible).visible(true);
                            }
                        },
                        { extend: 'colvis', text: 'Customise Headers', className: 'btn-success' }
                    ]
                },
                topEnd: {
                    search: { placeholder: 'Type search here' },
                    buttons: [
                        { extend: 'excel', exportOptions: { columns: ':visible' }, className: 'btn-warning' },
                        { extend: 'print', exportOptions: { columns: ':visible' }, className: 'btn-primary' }
                    ]
                }
            },
            ajax: {
                url: './not-interested/list', // Updated URL for not-interested list
                type: "GET"
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false }, // S.No
                { data: 'booking_no', name: 'booking_no' }, // Booking No
                { data: 'created_at', name: 'created_at' }, // Posting Date
                { data: 'booking_date', name: 'booking_date' }, // Booking Date
                { data: 'b_type', name: 'b_type' }, // B Type
                { data: 'branch_id', name: 'branch_id' }, // Branch
                { data: 'location', name: 'location' }, // Location
                { data: 'segment_id', name: 'segment_id' }, // Segment
                { data: 'model', name: 'model' }, // Model
                { data: 'variant', name: 'variant' }, // Variant
                { data: 'color', name: 'color' }, // Color
                { data: 'fsc', name: 'fsc' }, // FSC
                { data: 'fin_mode', name: 'fin_mode' }, // Finance Mode
                { data: 'financier', name: 'financier' }, // Financier
                { data: 'loan_status', name: 'loan_status' }, // Loan File Status
                { data: 'action', name: 'action', orderable: false, searchable: false } // Action
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