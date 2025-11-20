(function ($) {
    'use strict';

    $(document).ready(function () {
        var partId = window.location.pathname.split('/').pop();
        console.log('Part ID:', partId);

        // Fetch available stock (cls_qnty) for the part
        var availableStock = 0;
        $.ajax({
            url: '../fetch-stock/' + partId,
            type: 'GET',
            async: false,
            success: function (response) {
                availableStock = parseFloat(response.cls_qnty) || 0;
                console.log('Available Stock:', availableStock);
                $('#stock-display').text('Available Stock: ' + availableStock);
            },
            error: function (xhr, error, thrown) {
                console.error('Error fetching stock:', xhr.responseText, error, thrown);
                alert('Failed to fetch available stock.');
            }
        });

        var dTable = $('#allotment-list').DataTable({
            scrollY: 300,
            scrollX: true,
            paging: true,
            fixedColumns: {
                start: 1,
                end: 1
            },
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],
                    visible: true
                },
                {
                    targets: '_all',
                    className: 'dt-head-center dt-body-center'
                },
                {
                    targets: 23,
                    orderable: false,
                    searchable: false
                }
            ],
            layout: {
                topStart: {
                    pageLength: {
                        menu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'All']]
                    },
                    info: true
                },
                topEnd: {
                    search: {
                        placeholder: 'Type search here'
                    }
                }
            },
            ajax: {
                url: '../spare-part-allotment/list/' + partId,
                type: 'GET',
                dataSrc: function (json) {
                    console.log('AJAX Response Data:', json.data);
                    if (json.error) {
                        console.error('Server Error:', json.error);
                        return [];
                    }
                    return json.data;
                },
                error: function (xhr, error, thrown) {
                    console.error('AJAX Error:', xhr.responseText, error, thrown);
                }
            },
            columns: [
                { data: 's_no', name: 's_no' },
                { data: 'ro_age', name: 'ro_age' },
                { data: 'ro_date', name: 'ro_date' },
                { data: 'ro_number', name: 'ro_number' },
                { data: 'service_branch', name: 'service_branch' },
                { data: 'service_category', name: 'service_category' },
                { data: 'workshop_type', name: 'workshop_type' },
                { data: 'order_type', name: 'order_type' },
                { data: 'model', name: 'model' },
                { data: 'variant', name: 'variant' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'contact_no', name: 'contact_no' },
                { data: 'vehicle_reg_no', name: 'vehicle_reg_no' },
                { data: 'req_qty', name: 'req_qty' },
                { data: 'allotment', name: 'allotment' },
                { data: 'deallotment', name: 'deallotment' },
                { data: 'net_allotment', name: 'net_allotment' },
                { data: 'issued', name: 'issued' },
                { data: 'return', name: 'return' },
                { data: 'verify_return', name: 'verify_return' },
                { data: 'net_issued', name: 'net_issued' },
                { data: 'balance', name: 'balance' },
                { data: 'remarks', name: 'remarks' },
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

        $('#allotment-list').on('click', '.edit-row', function (e) {
            e.preventDefault();
            var row = $(this).closest('tr');
            var rowData = dTable.row(row).data();

            if (row.hasClass('editing')) {
                return;
            }

            row.addClass('editing');

            var cells = row.find('td');
            var reqQty = parseFloat(rowData.req_qty) || 0;
            var allotment = parseFloat(rowData.allotment) || 0;
            var deallotment = parseFloat(rowData.deallotment) || 0;
            var netAllotment = parseFloat(rowData.net_allotment) || 0;
            var issued = parseFloat(rowData.issued) || 0;
            var returnQty = parseFloat(rowData.return) || 0;
            var verifyReturn = parseFloat(rowData.verify_return) || 0;

            var allotmentCell = cells.eq(14);
            allotmentCell.data('original', allotmentCell.text());
            allotmentCell.html('<input type="number" class="form-control allotment-input" value="' + allotment + '" required>');

            var deallotmentCell = cells.eq(15);
            deallotmentCell.data('original', deallotmentCell.text());
            deallotmentCell.html('<input type="number" class="form-control deallotment-input" value="' + deallotment + '" required>');

            var netAllotmentCell = cells.eq(16);
            netAllotmentCell.data('original', netAllotmentCell.text());
            netAllotmentCell.html('<input type="text" class="form-control net-allotment-input" value="' + netAllotment + '" readonly>');

            var issuedCell = cells.eq(17);
            issuedCell.data('original', issuedCell.text());
            issuedCell.html('<input type="number" class="form-control issued-input" value="' + issued + '" required>');

            var returnCell = cells.eq(18);
            returnCell.data('original', returnCell.text());
            returnCell.html('<input type="number" class="form-control return-input" value="' + returnQty + '" disabled>');

            var verifyReturnCell = cells.eq(19);
            verifyReturnCell.data('original', verifyReturnCell.text());
            var isReturnNonZero = returnQty > 0;
            verifyReturnCell.html('<input type="number" class="form-control verify-return-input" value="' + verifyReturn + '" ' + (isReturnNonZero ? '' : 'readonly placeholder="Return quantity is 0"') + ' required>');

            var netIssuedCell = cells.eq(20);
            netIssuedCell.data('original', netIssuedCell.text());
            netIssuedCell.html('<input type="text" class="form-control net-issued-input" value="' + (issued - verifyReturn) + '" readonly>');

            var balanceCell = cells.eq(21);
            balanceCell.data('original', balanceCell.text());
            balanceCell.html('<input type="text" class="form-control balance-input" value="' + (reqQty - (issued - verifyReturn)) + '" readonly>');

            var remarksCell = cells.eq(22);
            remarksCell.data('original', remarksCell.text() || '');
            remarksCell.html('<input type="text" class="form-control remarks-input" value="' + (rowData.remarks || '') + '">');

            var actionCell = cells.eq(23);
            actionCell.html(
                '<button class="btn btn-success btn-sm save-row" title="Save"><i class="ik ik-check"></i></button>' +
                '<button class="btn btn-danger btn-sm cancel-row" title="Cancel"><i class="ik ik-x"></i></button>'
            );

            row.find('.allotment-input').on('input', function () {
                var allotment = parseFloat($(this).val()) || 0;
                var deallotment = parseFloat(row.find('.deallotment-input').val()) || 0;
                var netAllotment = allotment - deallotment;
                var issued = parseFloat(row.find('.issued-input').val()) || 0;
                var verifyReturn = parseFloat(row.find('.verify-return-input').val()) || 0;

                // Update dependent fields without validation
                row.find('.net-allotment-input').val(netAllotment);
                row.find('.net-issued-input').val(issued - verifyReturn);
                row.find('.balance-input').val(reqQty - (issued - verifyReturn));
            }).on('blur', function () {
                var allotment = parseFloat($(this).val()) || 0;
                var deallotment = parseFloat(row.find('.deallotment-input').val()) || 0;
                var netAllotment = allotment - deallotment;
                var issued = parseFloat(row.find('.issued-input').val()) || 0;
                var verifyReturn = parseFloat(row.find('.verify-return-input').val()) || 0;

                // Allotment cannot be less than original allotment
                if (allotment < rowData.allotment) {
                    alert('Allotment cannot be less than original allotment (' + rowData.allotment + ').');
                    allotment = rowData.allotment;
                    $(this).val(allotment);
                    netAllotment = allotment - deallotment;
                }

                // Validate net_allotment
                if (netAllotment > reqQty) {
                    alert('Net allotment cannot exceed required quantity (' + reqQty + ').');
                    allotment = reqQty + deallotment;
                    $(this).val(allotment);
                    netAllotment = allotment - deallotment;
                }
                if (netAllotment > availableStock) {
                    alert('Net allotment cannot exceed available stock (' + availableStock + ').');
                    allotment = availableStock + deallotment;
                    $(this).val(allotment);
                    netAllotment = allotment - deallotment;
                }
                if (netAllotment < issued) {
                    alert('Net allotment cannot be less than issued quantity (' + issued + ').');
                    allotment = issued + deallotment;
                    $(this).val(allotment);
                    netAllotment = allotment - deallotment;
                }

                // Update dependent fields
                row.find('.net-allotment-input').val(netAllotment);
                row.find('.net-issued-input').val(issued - verifyReturn);
                row.find('.balance-input').val(reqQty - (issued - verifyReturn));
            });

            row.find('.deallotment-input').on('input', function () {
                var allotment = parseFloat(row.find('.allotment-input').val()) || 0;
                var deallotment = parseFloat($(this).val()) || 0;
                var netAllotment = allotment - deallotment;
                var issued = parseFloat(row.find('.issued-input').val()) || 0;
                var verifyReturn = parseFloat(row.find('.verify-return-input').val()) || 0;

                // Update dependent fields without validation
                row.find('.net-allotment-input').val(netAllotment);
                row.find('.net-issued-input').val(issued - verifyReturn);
                row.find('.balance-input').val(reqQty - (issued - verifyReturn));
            }).on('blur', function () {
                var allotment = parseFloat(row.find('.allotment-input').val()) || 0;
                var deallotment = parseFloat($(this).val()) || 0;
                var netAllotment = allotment - deallotment;
                var issued = parseFloat(row.find('.issued-input').val()) || 0;
                var verifyReturn = parseFloat(row.find('.verify-return-input').val()) || 0;

                // Deallotment cannot be less than original deallotment
                if (deallotment < rowData.deallotment) {
                    alert('Deallotment cannot be less than original deallotment (' + rowData.deallotment + ').');
                    deallotment = rowData.deallotment;
                    $(this).val(deallotment);
                    netAllotment = allotment - deallotment;
                }

                // Validate net_allotment
                if (netAllotment > reqQty) {
                    alert('Net allotment cannot exceed required quantity (' + reqQty + ').');
                    deallotment = allotment - reqQty;
                    $(this).val(deallotment);
                    netAllotment = allotment - deallotment;
                }
                // if (netAllotment > availableStock) {
                //     alert('Net allotment cannot exceed available stock (' + availableStock + ').');
                //     deallotment = allotment - availableStock;
                //     $(this).val(deallotment);
                //     netAllotment = allotment - deallotment;
                // }
                if (netAllotment < issued) {
                    alert('Net allotment cannot be less than issued quantity (' + issued + ').');
                    deallotment = allotment - issued;
                    $(this).val(deallotment);
                    netAllotment = allotment - deallotment;
                }

                // Update dependent fields
                row.find('.net-allotment-input').val(netAllotment);
                row.find('.net-issued-input').val(issued - verifyReturn);
                row.find('.balance-input').val(reqQty - (issued - verifyReturn));
            });

            row.find('.issued-input').on('input', function () {
                var issued = parseFloat($(this).val()) || 0;
                var netAllotment = parseFloat(row.find('.net-allotment-input').val()) || 0;
                var verifyReturn = parseFloat(row.find('.verify-return-input').val()) || 0;

                // Update dependent fields without validation
                row.find('.net-issued-input').val(issued - verifyReturn);
                row.find('.balance-input').val(reqQty - (issued - verifyReturn));
            }).on('blur', function () {
                var issued = parseFloat($(this).val()) || 0;
                var netAllotment = parseFloat(row.find('.net-allotment-input').val()) || 0;
                var verifyReturn = parseFloat(row.find('.verify-return-input').val()) || 0;

                // Issued cannot be less than original issued
                if (issued < rowData.issued) {
                    alert('Issued cannot be less than original issued (' + rowData.issued + ').');
                    issued = rowData.issued;
                    $(this).val(issued);
                }
                // Issued cannot exceed net_allotment
                if (issued > netAllotment) {
                    alert('Issued cannot exceed net allotment (' + netAllotment + ').');
                    issued = netAllotment;
                    $(this).val(issued);
                }

                // Update dependent fields
                row.find('.net-issued-input').val(issued - verifyReturn);
                row.find('.balance-input').val(reqQty - (issued - verifyReturn));
            });

            row.find('.verify-return-input').on('input', function () {
                var issued = parseFloat(row.find('.issued-input').val()) || 0;
                var verifyReturn = parseFloat($(this).val()) || 0;

                // Update dependent fields without validation
                row.find('.net-issued-input').val(issued - verifyReturn);
                row.find('.balance-input').val(reqQty - (issued - verifyReturn));
            }).on('blur', function () {
                var verifyReturn = parseFloat($(this).val()) || 0;
                var returnQty = parseFloat(row.find('.return-input').val()) || 0;
                var issued = parseFloat(row.find('.issued-input').val()) || 0;

                // Verify return cannot be less than original verify_return
                if (verifyReturn < rowData.verify_return) {
                    alert('Verify return cannot be less than original verify return (' + rowData.verify_return + ').');
                    verifyReturn = rowData.verify_return;
                    $(this).val(verifyReturn);
                }
                // Verify return cannot exceed returnQty
                if (verifyReturn > returnQty) {
                    alert('Verify return cannot exceed return quantity (' + returnQty + ').');
                    verifyReturn = returnQty;
                    $(this).val(verifyReturn);
                }

                // Update dependent fields
                row.find('.net-issued-input').val(issued - verifyReturn);
                row.find('.balance-input').val(reqQty - (issued - verifyReturn));
            });
        });

        $('#allotment-list').on('click', '.cancel-row', function (e) {
            e.preventDefault();
            var row = $(this).closest('tr');
            var cells = row.find('td');

            cells.eq(14).text(cells.eq(14).data('original'));
            cells.eq(15).text(cells.eq(15).data('original'));
            cells.eq(16).text(cells.eq(16).data('original'));
            cells.eq(17).text(cells.eq(17).data('original'));
            cells.eq(18).text(cells.eq(18).data('original'));
            cells.eq(19).text(cells.eq(19).data('original'));
            cells.eq(20).text(cells.eq(20).data('original'));
            cells.eq(21).text(cells.eq(21).data('original'));
            cells.eq(22).text(cells.eq(22).data('original'));

            cells.eq(23).html('<a href="#" class="edit-row" title="Edit"><i class="ik ik-edit f-16 text-green"></i></a>');

            row.removeClass('editing');
        });

        $('#allotment-list').on('click', '.save-row', function (e) {
            e.preventDefault();
            var row = $(this).closest('tr');
            var rowData = dTable.row(row).data();
            var cells = row.find('td');

            var updatedData = {
                "_token": $("#x_csrf").val(),
                id: rowData.id,
                allotment: parseFloat(cells.eq(14).find('.allotment-input').val()) || 0,
                deallotment: parseFloat(cells.eq(15).find('.deallotment-input').val()) || 0,
                issued: parseFloat(cells.eq(17).find('.issued-input').val()) || 0,
                return: parseFloat(cells.eq(18).find('.return-input').val()) || 0,
                verify_return: parseFloat(cells.eq(19).find('.verify-return-input').val()) || 0,
                remarks: cells.eq(22).find('.remarks-input').val() || ''
            };

            console.log('Row Data:', rowData);
            console.log('Sending Data:', updatedData);

            $.ajax({
                url: '../spare-part-allotment/update',
                type: 'POST',
                data: updatedData,
                success: function (response) {
                    console.log('Update Response:', response);
                    if (response.success) {
                        rowData.allotment = updatedData.allotment;
                        rowData.deallotment = updatedData.deallotment;
                        rowData.net_allotment = updatedData.allotment - updatedData.deallotment;
                        rowData.issued = updatedData.issued;
                        rowData.return = updatedData.return;
                        rowData.verify_return = updatedData.verify_return;
                        rowData.net_issued = updatedData.issued - updatedData.verify_return;
                        rowData.balance = rowData.req_qty - rowData.net_issued;
                        rowData.remarks = updatedData.remarks;

                        cells.eq(14).text(updatedData.allotment);
                        cells.eq(15).text(updatedData.deallotment);
                        cells.eq(16).text(rowData.net_allotment);
                        cells.eq(17).text(updatedData.issued);
                        cells.eq(18).text(updatedData.return);
                        cells.eq(19).text(updatedData.verify_return);
                        cells.eq(20).text(rowData.net_issued);
                        cells.eq(21).text(rowData.balance);
                        cells.eq(22).text(updatedData.remarks);

                        cells.eq(23).html('<a href="#" class="edit-row" title="Edit"><i class="ik ik-edit f-16 text-green"></i></a>');

                        row.removeClass('editing');

                        // Refetch stock to update stock display
                        $.ajax({
                            url: '../fetch-stock/' + partId,
                            type: 'GET',
                            success: function (stockResponse) {
                                availableStock = parseFloat(stockResponse.cls_qnty) || 0;
                                console.log('Updated Stock:', availableStock);
                                $('#stock-display').text('Available Stock: ' + availableStock);
                            },
                            error: function (xhr, error, thrown) {
                                console.error('Error refetching stock:', xhr.responseText, error, thrown);
                                alert('Failed to update stock display.');
                            }
                        });

                        alert('Data updated successfully!');
                    } else {
                        console.error('Update Error:', response.message);
                        alert('Failed to update data: ' + response.message);
                    }
                },
                error: function (xhr, error, thrown) {
                    console.error('AJAX Update Error:', {
                        status: xhr.status,
                        responseText: xhr.responseText,
                        error: error,
                        thrown: thrown
                    });
                    alert('Error updating data: ' + (xhr.responseText || 'Unknown error'));
                }
            });
        });
    });
})(jQuery);