(function ($) {

    'use strict';

    // Acc Items data table

    $(document).ready(function () {

        var searchable = [];

        var selectable = [];





        var dTable = $('#stock-report').DataTable({

            scrollY: 300,

            scrollX: true,



            paging: true,


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