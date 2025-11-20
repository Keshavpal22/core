(function ($) {

    'use strict';



    $(document).ready(function () {

        var dTable = $('#order-list').DataTable({

            scrollY: 425,

            scrollX: true,



            paging: true,

            fixedColumns: {

                start: 2,

                end: 1

            },

            columnDefs: [

                {

                    targets: [],

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

                        menu: [[5, 10, 25, 50, 100, -1],

                        [5, 10, 25, 50, 100, 'All']

                        ]

                    },

                    info: true

                },

                // top: {

                //     buttons: [

                //         {

                //             extend: 'colvisGroup',

                //             text: "All Headers",

                //             show: ':hidden',

                //             className: 'btn-info'

                //         },

                //         {

                //             extend: 'colvisRestore',

                //             text: "Default Headers",

                //             className: 'btn-warning'

                //         },



                //         {

                //             extend: 'colvis',

                //             text: 'Customise Headers',

                //             className: 'btn-success'

                //         }

                //     ]

                // },

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



            // AJAX call to fetch data

            ajax: {

                url: './order/list',  // Ensure this URL is correct

                type: "GET",


            },


            columns: [
                { data: null, orderable: false }, // Serial number column

                { data: 'booking_no', name: 'xceler8_booking_no' },

                { data: 'created_at', name: 'posting_date' },

                { data: 'booking_date', name: 'booking_date' },

                { data: 'branch_id', name: 'branch' }, // Resolved dynamically

                { data: 'location', name: 'location' },

                { data: 'segment_id', name: 'segment_id' },

                { data: 'model', name: 'model' },

                { data: 'variant', name: 'vehicle' },

                { data: 'color', name: 'color' },

                { data: 'seating', name: 'seating' },

                { data: 'name', name: 'customer_name' },

                { data: 'live_count', name: 'live_booking_count' },

                { data: 'stock_count', name: 'vehicle_in_stock' },

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

