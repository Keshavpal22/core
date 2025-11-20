(function ($) {

    'use strict';



    $(document).ready(function () {

        var dTable = $('#dsa-list').DataTable({

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



            // AJAX call to fetch data

            ajax: {

                url: './dsa/list',  // Ensure this URL is correct

                type: "GET",

            },


            columns: [
                
                { data: 'dsa_id', name: 'dsa_id' },
                { data: 'name', name: 'name' },
                { data: 'mobile', name: 'mobile' },
                { data: 'email', name: 'email' },
                { data: 'dlocation', name: 'dlocation' },
                { data: 'state', name: 'state' },
                { data: 'firm_name', name: 'firm_name' },
                { data: 'firm_gst', name: 'firm_gst' },
                { data: 'alt_mobile', name: 'alt_mobile' },
                { data: 'bank_name', name: 'bank_name' },
                { data: 'account_number', name: 'account_number' },
                { data: 'ifsc', name: 'ifsc' },
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

