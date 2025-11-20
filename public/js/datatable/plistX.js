(function ($) {
    'use strict';
    // Acc Items data table
    $(document).ready(function () {
        var searchable = [0, 1, 2, 3];
        var selectable = [0, 1, 2, 3];
        var pid = $('#pid').val();

        var dTable = $('#plist').DataTable({
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
                    targets: 3,
                    render: DataTable.render.datetime('DD MMM YYYY')
                }
            ],
            ajax: {
                url: '../getPlist/' + pid,
                type: "get"
            },
            columns: [
                { data: 'id', name: 'vid' },
                { data: 'group', name: 'group' },
                { data: 'model', name: 'model' },
                { data: 'year', name: 'year' },
                { data: 'exshowroom', name: 'ex_sr_price' },
                { data: 'incidental', name: 'incid_charge' },
                { data: 'ftag', name: 'fasttag' },
                { data: 'trc', name: 'trc' },
                { data: 'rto_tape', name: 'rto_tape' },
                { data: 'rsa', name: 'rsa' },
                { data: 'shield', name: 'shield' },
                { data: 'nildep', name: 'nil_dep_insu' },
                { data: 'rto', name: 'rto_charge' },
                { data: 'apack', name: 'access_mrp' },
                { data: 'total_disc', name: 'total_disc' },
                { data: 'tcs', name: 'tcs_@_1%' },
                { data: 'onroad', name: 'on_road_price' },
                { data: 'invoice', name: 'invoice_amount' },
                { data: 'action', name: 'action' }
            ],
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            // initComplete: function () {
            //     this.api()
            //         .columns()
            //         .every(function () {
            //             let column = this;

            //             // Create select element
            //             let select = document.createElement('select');
            //             select.add(new Option(''));
            //             column.footer().replaceChildren(select);

            //             // Apply listener for user change in value
            //             select.addEventListener('change', function () {
            //                 column
            //                     .search(select.value, { exact: true })
            //                     .draw();
            //             });

            //             // Add list of options
            //             column
            //                 .data()
            //                 .unique()
            //                 .sort()
            //                 .each(function (d, j) {
            //                     select.add(new Option(d));
            //                 });
            //         });
            // }
        });
    });

})(jQuery);