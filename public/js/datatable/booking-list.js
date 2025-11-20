(function ($) {

    'use strict';

    $(document).ready(function () {

        var dTable = $('#booking-list').DataTable({
            scrollY: 425,
            scrollX: true,
            paging: true,
            fixedColumns: {
                start: 2,
                end: 1
            },
            columnDefs: [
                {
                    targets: [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44],
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
                        {
                            extend: 'colvisGroup',
                            text: "All Headers",
                            show: ':hidden',
                            className: 'btn-info'
                        },
                        {
                            extend: 'colvisRestore',
                            text: "Default Headers",
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
                url: './bookings/list', // Ensure this URL is correct
                type: "GET",
                data: function (d) {
                    var customerType = $('#customer-type').val();
                    d.customer_type = customerType;
                }
            },
            columns: [
                // Update: Use DataTables render for row numbering
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        // This works for both display and export!
                        return meta.row + 1 + meta.settings._iDisplayStart;
                    }
                },
                { data: 'booking_no', name: 'xceler8_booking_no.' },
                { data: 'created_at', name: 'posting_date' },
                { data: 'booking_date', name: 'booking_date' },
                { data: 'branch_id', name: 'branch' },
                { data: 'location', name: 'location' },
                { data: 'segment_id', name: 'segment_id' },
                { data: 'model', name: 'model' },
                { data: 'variant', name: 'vehicle' },
                { data: 'color', name: 'color' },
                { data: 'seating', name: 'seating' },
                { data: 'name', name: 'customer_name' },
                { data: 'apack_amount', name: 'accessories_amount' },
                { data: 'consultant', name: 'sales_consultant' },
                { data: 'b_type', name: 'b_type' },
                { data: 'b_mode', name: 'b_mode' },
                { data: 'sap_no', name: 'sap_no' },
                { data: 'dms_no', name: 'dms_no' },
                { data: 'b_source', name: 'b_source' },
                { data: 'col_type', name: 'collection_type' },
                { data: 'col_by', name: 'collected_by' },
                { data: 'dsa_id', name: 'dsa_id' },
                { data: 'online_bk_ref_no', name: 'online_bk_ref_no' },
                { data: 'dms_otf', name: 'dms_otf' },
                { data: 'dms_so', name: 'dms_so' },
                { data: 'receipt_no', name: 'receipt_no' },
                { data: 'receipt_date', name: 'receipt_date' },
                { data: 'booking_amount', name: 'booking_amount' },
                { data: 'care_of', name: 'care_of' },
                { data: 'mobile', name: 'mobile' },
                { data: 'alt_mobile', name: 'alt_mobile' },
                { data: 'pan_no', name: 'pan_no' },
                { data: 'adhar_no', name: 'adhar_no' },
                { data: 'cpd', name: 'cpd' },
                { data: 'chasis_no', name: 'chasis_no' },
                { data: 'del_type', name: 'del_type' },
                { data: 'del_date', name: 'del_date' },
                { data: 'fin_mode', name: 'fin_mode' },
                { data: 'financier', name: 'financier' },
                { data: 'loan_status', name: 'loan_status' },
                { data: 'r_name', name: 'r_name' },
                { data: 'r_mobile', name: 'r_mobile' },
                { data: 'r_model', name: 'r_model' },
                { data: 'r_variant', name: 'r_variant' },
                { data: 'r_chassis', name: 'r_chassis' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
            // Remove drawCallback for row numbering; it's now handled by `render`
        });

        // Change event for the customer-type dropdown
        $('#customer-type').on('change', function () {
            dTable.ajax.reload();
        });

    });

})(jQuery);
