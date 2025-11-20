(function ($) {
    'use strict';

    $(document).ready(function () {
        try {
            console.log('Initializing DataTable for #fee-list');
            const ajaxUrl = $('#fee-list').data('ajax-url');
            console.log('AJAX URL:', ajaxUrl);
            if (!ajaxUrl) {
                Swal.fire({
                    icon: 'error',
                    title: 'AJAX URL Missing',
                    text: 'Could not find AJAX URL for DataTable.'
                });
                return;
            }

            var dTable = $('#fee-list').DataTable({
                scrollY: 425,
                scrollX: true,
                paging: true,
                fixedColumns: { leftColumns: 2, rightColumns: 1 },
                columnDefs: [{ targets: [13, 14, 15, 16, 17, 18, 19, 20], visible: false }],
                layout: {
                    topStart: {
                        pageLength: { menu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'All']] },
                        info: true
                    },
                    top: {
                        buttons: [
                            { extend: 'colvisGroup', text: 'Show All Headers', show: ':hidden', className: 'btn-info' },
                            { extend: 'colvisRestore', text: 'Default Headers', className: 'btn-warning' },
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
                    type: 'GET', // Changed to GET
                    data: function (d) {
                        d.fee_collection_status = $('#fee_collection_status_filter').val();
                        d.data_status = $('#data_status_filter').val();
                        console.log('Preparing AJAX request:', {
                            url: ajaxUrl,
                            fee_collection_status: d.fee_collection_status,
                            data_status: d.data_status,
                            draw: d.draw,
                            start: d.start,
                            length: d.length
                        });
                        return d;
                    },
                    dataSrc: function (json) {
                        console.log('AJAX Response:', json);
                        if (!json || !json.data) {
                            console.error('Invalid AJAX response: json.data is undefined', json);
                            return [];
                        }
                        return json.data;
                    },
                    error: function (xhr, error, thrown) {
                        console.error('AJAX Error:', { status: xhr.status, responseText: xhr.responseText, error, thrown });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load data: ' + xhr.statusText
                        });
                    }
                },
                columns: [
                    { data: null, name: 'no', orderable: false, searchable: false },
                    { data: 'branch', name: 'branch', defaultContent: 'N/A' },
                    { data: 'case_date', name: 'case_date', defaultContent: 'N/A' },
                    { data: 'invoice_date', name: 'invoice_date', defaultContent: 'N/A' },
                    { data: 'otf_no', name: 'otf_no', defaultContent: 'N/A' },
                    { data: 'customer_name', name: 'customer_name', defaultContent: 'N/A' },
                    { data: 'model', name: 'model', defaultContent: 'N/A' },
                    { data: 'category', name: 'category', defaultContent: 'N/A', render: data => data === false || data === null || data === undefined ? 'N/A' : data },
                    { data: 'permit', name: 'permit', defaultContent: 'N/A' },
                    { data: 'mode', name: 'mode', defaultContent: 'N/A' },
                    { data: 'amount', name: 'amount', defaultContent: 'N/A' },
                    { data: 'fee_collection_status', name: 'fee_collection_status', defaultContent: 'N/A' },
                    { data: 'data_status', name: 'data_status', defaultContent: 'N/A' },
                    { data: 'chassis_no', name: 'chassis_no', defaultContent: 'N/A' },
                    { data: 'location', name: 'location', defaultContent: 'N/A' },
                    { data: 'invoice_number', name: 'invoice_number', defaultContent: 'N/A' },
                    { data: 'care_of', name: 'care_of', defaultContent: 'N/A' },
                    { data: 'care_of_name', name: 'care_of_name', defaultContent: 'N/A' },
                    { data: 'mobile_no', name: 'mobile_no', defaultContent: 'N/A' },
                    { data: 'agent_name', name: 'agent_name', defaultContent: 'N/A', render: data => data === false || data === null || data === undefined ? 'N/A' : data },
                    { data: 'agent_location', name: 'agent_location', defaultContent: 'N/A' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, defaultContent: 'N/A' }
                ],
                drawCallback: function (settings) {
                    var api = this.api();
                    var start = api.page.info().start;
                    api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = start + i + 1;
                    });
                },
                initComplete: function () {
                    console.log('DataTable initialization completed');
                    $('#fee_collection_status_filter, #data_status_filter').on('change', function () {
                        console.log('Filter changed:', { id: this.id, value: $(this).val() });
                        dTable.ajax.reload();
                    });
                }
            });

            console.log('DataTable instance:', dTable);
        } catch (e) {
            console.error('DataTable initialization error:', e);
            Swal.fire({
                icon: 'error',
                title: 'Initialization Error',
                text: 'Failed to initialize DataTable. Please check the console for details.'
            });
        }
    });
})(jQuery);
