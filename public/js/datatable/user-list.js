(function ($) {

    'use strict';

    // Acc Items data table

    $(document).ready(function () {

        var searchable = [];

        var selectable = [];





        var dTable = $('#user-list').DataTable({

            scrollY: 300,

            scrollX: true,

            scrollCollapse: true,

            colReorder: true,

            //responsive: true,

            keys: true,

            paging: true,

            fixedColumns: {

                start: 3

            },

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

                    buttons: [{

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

                    },

                    {

                        extend: 'pdf',

                        exportOptions: {

                            columns: ':visible'

                        },

                        className: 'btn-outline-secondary'

                    }]

                },

                topEnd: {

                    search: {

                        placeholder: 'Type search here'

                    },

                    buttons: ['colvis']

                }



            },

            columnDefs: [

                {
                    targets: '_all',
                    className: 'dt-head-center dt-body-center'
                }
            ],

            ajax: {

                url: './listX', // Change this URL to your cancelled bookings endpoint

                type: "get"

            },

            columns: [
                { data: 'id', name: 'id' },
                { data: 'emp_code', name: 'emp_code' },
                { data: 'name', name: 'name' },
                { data: 'profile', name: 'profile_pic' },
                { data: 'email', name: 'email' },
                { data: 'mobile', name: 'mobile' },
                { data: 'designames', name: 'designations' },
                { data: 'departnames', name: 'departments' },
                { data: 'divinames', name: 'divisions' },
                { data: 'vertinames', name: 'verticals' },
                { data: 'brnames', name: 'branches' },
                { data: 'locnames', name: 'locations' },
                { data: 'segnames', name: 'segments' },
                { data: 'cmnames', name: 'models' },
                { data: 'username', name: 'username' },
                { data: 'rolenames', name: 'roles' },
                { data: 'permnames', name: 'permissions' },
                { data: 'action', name: 'actions', orderable: false, searchable: false }
            ],



        });

    });



})(jQuery);