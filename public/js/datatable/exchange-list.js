(function ($) {
    'use strict';

    $(document).ready(function () {
        var tableId = $('table').attr('id');
        var ajaxUrl;

        // Determine the AJAX URL based on table ID
        switch (tableId) {
            case 'exchange-list':
                ajaxUrl = './exchange/list';
                break;
            case 'scrappage-list':
                ajaxUrl = './exchange/int-in-scrappage/list';
                break;
            case 'not-interested-list':
                ajaxUrl = './exchange/not-interested/list';
                break;
            default:
                ajaxUrl = './exchange/list'; // Fallback
        }

        var dTable = $('#' + tableId).DataTable({
            scrollY: 425,
            scrollX: true,
            paging: true,
            fixedColumns: {
                start: 1,
                end: 1
            },
            columnDefs: [
                {
                    targets: [3, 4, 5, 7, 10],
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
                                var defaultVisible = [0, 1, 2, 6, 8, 9, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21];
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
                url: ajaxUrl,
                type: "GET"
            },
            columns: [
                { data: null, orderable: false },
                { data: 'booking_no', name: 'booking_no' },
                { data: 'created_at', name: 'created_at' },
                { data: 'booking_date', name: 'booking_date' },
                { data: 'b_type', name: 'b_type' },
                { data: 'branch_id', name: 'branch_id' },
                { data: 'location', name: 'location' },
                { data: 'segment_id', name: 'segment_id' },
                { data: 'model', name: 'model' },
                { data: 'variant', name: 'variant' },
                { data: 'color', name: 'color' },
                { data: 'consultant', name: 'sales_consultant' },
                { data: 'brand_make_1', name: 'brand_make_1' },
                { data: 'model_variant_1', name: 'model_variant_1' },
                { data: 'vehicle_reg_no', name: 'vehicle_reg_no' },
                { data: 'vehicle_mfg_year', name: 'vehicle_mfg_year' },
                { data: 'vehicle_odo_reading', name: 'vehicle_odo_reading' },
                { data: 'used_vehicle_exp_price', name: 'used_vehicle_exp_price' },
                { data: 'used_vehicle_off_price', name: 'used_vehicle_off_price' },
                { data: 'new_vehicle_exc_bonus', name: 'new_vehicle_exc_bonus' },
                { data: 'price_gap', name: 'price_gap' },
                { data: 'action', name: 'action' }
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