(function ($) {
    'use strict';

    $(document).ready(function () {
        var dTable = $('#order-list').DataTable({
            scrollY: 300,
            scrollX: true,
            paging: true,
            fixedColumns: {
                start: 1,
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
                        menu: [[5, 10, 25, 50, 100, -1],
                        [5, 10, 25, 50, 100, 'All']
                        ]
                    },
                    info: true
                },
                top: {
                    buttons: [
                        {
                            extend: 'colvisGroup',
                            text: "All",
                            show: ':hidden',
                            className: 'btn-info'
                        },
                        {
                            extend: 'colvisRestore',
                            text: "Default",
                            className: 'btn-warning'
                        },
                        {
                            extend: 'colvis',
                            text: 'Show/Hide',
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

            // AJAX call to fetch spare requests
            ajax: {
                url: './spare/list',  // Replace with your correct route
                type: "GET",
                
            },

            columns: [
                { data: 'id', name: 'id' }, // ID column
                { data: 'srv_brnch_id', name: 'srv_brnch_id' }, // Branch ID
                { data: 'srv_vh_cat_id', name: 'srv_vh_cat_id' }, // Vehicle Category ID
                { data: 'workshop_type_id', name: 'workshop_type_id' }, // Workshop Type
                { data: 'model', name: 'model' }, // Model
                { data: 'variant', name: 'variant' }, // Variant
                { data: 'regn_no', name: 'regn_no' }, // Registration Number
                { data: 'cust_mobile', name: 'cust_mobile' }, // Customer Mobile
                { data: 'cust_name', name: 'cust_name' }, // Customer Name
                { data: 'person_id', name: 'person_id' }, // Person ID
                { data: 'ro_date', name: 'ro_date' }, // RO Date
                { data: 'ro_number', name: 'ro_number' }, // RO Number
                
            ]
        });    
    });

})(jQuery);
