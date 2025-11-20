(function ($) {
    'use strict';

    $(document).ready(function () {
        var dTable = $('#booking-master-report').DataTable({
            scrollY: 320,
            scrollX: true,
            paging: true,
            autoWidth: false,
            columnDefs: [
                { targets: [0], width: '50px' }, // SNO
                { targets: [1], width: '100px' }, // SEGMENT
                { targets: [2], width: '120px' }, // MODEL
                { targets: [3], width: '200px' }, // VARIANT
                { targets: [4], width: '150px' }, // COLOR
                { targets: '_all', width: '80px' }, // SNO
                {
                    targets: '_all',
                    className: 'dt-head-center dt-body-center',
                    orderable: false
                }
            ],

            ordering: false,
            fixedColumns: {
                leftColumns: 5
            },
            layout: {
                topStart: {
                    pageLength: {
                        menu: [[5, 10, 25, 50, 100, -1],
                        [5, 10, 25, 50, 100, 'All']]
                    },
                    info: true
                },

                topEnd: {
                    search: {
                        placeholder: 'Type search here'
                    },
                    buttons: [
                        {
                            extend: 'excel',
                            text: 'Export to Excel',
                            filename: function () {
                                return $('#fname').val();
                            },

                            customize: function (xlsx) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                var stylesheet = xlsx.xl['styles.xml'];

                                // Define pastel colors to match typical web table styling
                                var pastelColors = [
                                    '#e6f0ff', // Pastel Blue
                                    '#e6ffe6', // Pastel Green
                                    '#e6e6ff',  // Pastel Indigo
                                    '#fff0e6', // Pastel Yellow
                                    '#ffe6e6', // Pastel Pink
                                    '#f2e6ff', // Pastel Purple
                                    '#e6faff', // Pastel Cyan
                                    '#ffffe6' // Pastel Orange

                                ];

                                // Function to convert hex color to Excel ARGB format
                                function hexToExcelColor(hex) {
                                    return 'FF' + hex.replace('#', '').toUpperCase();
                                }

                                // Create custom fill styles for each pastel color
                                var fillStyles = {};
                                pastelColors.forEach(function (color, index) {
                                    var fillId = 'fill' + index;
                                    var fillXml = '<fill><patternFill patternType="solid"><fgColor rgb="' + hexToExcelColor(color) + '"/></patternFill></fill>';
                                    $('fills', stylesheet).append(fillXml);
                                    fillStyles[index] = $('fills fill', stylesheet).length - 1; // Index of the new fill
                                });

                                // Get the first header row from the table to determine merged cells
                                var firstHeaderRow = $('#booking-master-report thead tr:first-child th');
                                var currentColumn = 1; // Excel columns start at 1 (A=1, B=2, etc.)
                                var colorIndex = 0;

                                // Apply colors based on column groups
                                firstHeaderRow.each(function () {
                                    var $th = $(this);
                                    var colspan = parseInt($th.attr('colspan')) || 1;
                                    var color = pastelColors[colorIndex % pastelColors.length];
                                    var fillIndex = fillStyles[colorIndex % pastelColors.length];

                                    // Apply color to all cells in the spanned columns
                                    for (var i = currentColumn; i < currentColumn + colspan; i++) {
                                        // Second header row (row 2)
                                        var secondHeaderCell = $('row[r="2"] c[r="' + String.fromCharCode(64 + i) + '2"]', sheet);
                                        if (secondHeaderCell.length) {
                                            secondHeaderCell.attr('s', 'customStyle' + colorIndex);
                                        }

                                        // Data rows (rows 3 onwards)
                                        $('row[r!="1"] c[r^="' + String.fromCharCode(64 + i) + '"]', sheet).each(function () {
                                            $(this).attr('s', 'customStyle' + colorIndex);
                                        });
                                    }

                                    currentColumn += colspan;
                                    colorIndex++;
                                });

                                // Define custom styles for each color
                                pastelColors.forEach(function (color, index) {
                                    var xf = createNode(stylesheet, 'xf', {
                                        attr: {
                                            numFmtId: '0',
                                            fontId: '0',
                                            fillId: fillStyles[index],
                                            borderId: '0',
                                            applyFont: '1',
                                            applyFill: '1',
                                            applyBorder: '1',
                                            xfId: '0'
                                        }
                                    });
                                    $('cellXfs', stylesheet).append(xf);
                                });

                                // Update style indices for custom styles
                                var lastXfIndex = $('cellXfs xf', stylesheet).length - pastelColors.length;
                                $('row c', sheet).each(function () {
                                    var $cell = $(this);
                                    var styleAttr = $cell.attr('s');
                                    if (styleAttr && styleAttr.startsWith('customStyle')) {
                                        var colorIndex = parseInt(styleAttr.replace('customStyle', ''));
                                        $cell.attr('s', lastXfIndex + colorIndex);
                                    }
                                });
                            }
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

            initComplete: function () {
                var api = this.api();
                var filterColumns = [1, 2, 3, 4];

                filterColumns.forEach(function (colIdx) {
                    var column = api.column(colIdx);
                    var header = $(column.header());

                    var select = $('<select class="filter-select"><option value="">All</option></select>')
                        .appendTo(header)
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false);

                            if (colIdx === 1) {
                                resetFilter(2);
                                resetFilter(3);
                            } else if (colIdx === 2) {
                                resetFilter(3);
                            }

                            updateFilters();
                            api.draw();
                        });

                    updateFilterOptions(colIdx);
                });

                function resetFilter(colIdx) {
                    var column = api.column(colIdx);
                    var select = $(column.header()).find('.filter-select');
                    select.val('');
                    column.search('');
                }

                function updateFilters() {
                    filterColumns.forEach(function (colIdx) {
                        updateFilterOptions(colIdx);
                    });
                }

                function updateFilterOptions(colIdx) {
                    var column = api.column(colIdx);
                    var select = $(column.header()).find('.filter-select');
                    var currentVal = select.val();

                    select.empty().append('<option value="">All</option>');

                    var visibleValues = {};
                    api.rows({ search: 'applied' }).every(function () {
                        var data = this.data();
                        if (data[colIdx]) {
                            visibleValues[data[colIdx]] = true;
                        }
                    });

                    Object.keys(visibleValues).sort().forEach(function (val) {
                        if (val && val !== 'All Variants' && val !== 'All Colors') {
                            select.append($('<option></option>')
                                .attr('value', val)
                                .text(val));
                        }
                    });

                    if (currentVal && visibleValues[currentVal]) {
                        select.val(currentVal);
                    }
                }

                api.columns.adjust();
            },

            drawCallback: function (settings) {
                var api = this.api();
                var start = api.page.info().start;

                // Optional: Update serial number column if present (e.g., SNo)
                api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = start + i + 1;
                });

                // Custom footer calculations
                api.columns().every(function (colIdx) {
                    var column = this;
                    var footer = $(column.footer());
                    if (footer.length) {
                        var values = api.column(colIdx, { search: 'applied' }).data().toArray();
                        var total = 0;
                        var percentageSum = 0;
                        var count = 0;
                        var maxDays = 0;

                        values.forEach(function (value) {
                            if (typeof value === 'string') {
                                if (value.includes('%')) {
                                    var num = parseFloat(value.replace('%', '')) || 0;
                                    percentageSum += num;
                                    count++;
                                } else if (value.includes('D')) {
                                    var days = parseInt(value.replace('D', '')) || 0;
                                    maxDays = Math.max(maxDays, days);
                                } else {
                                    total += parseFloat(value) || 0;
                                }
                            }
                        });

                        var totalText = '';
                        if (total > 0) {
                            totalText = Math.round(total).toString();
                        } else if (percentageSum > 0 && count > 0) {
                            totalText = (percentageSum / count).toFixed(2) + '%';
                        } else if (maxDays > 0) {
                            totalText = maxDays + ' D';
                        } else {
                            totalText = '';
                        }
                        footer.html(totalText);
                    }
                });

                api.columns.adjust();
            }

        });
    });
    // Helper function to create XML nodes
    function createNode(doc, nodeName, opts) {
        var node = doc.createElement(nodeName);
        if (opts) {
            if (opts.attr) {
                $(node).attr(opts.attr);
            }
            if (opts.children) {
                $.each(opts.children, function (key, value) {
                    node.appendChild(value);
                });
            }
            if (opts.text !== null && opts.text !== undefined) {
                node.appendChild(doc.createTextNode(opts.text));
            }
        }
        return node;
    }

})(jQuery);