(function($) {
	'use strict';
    // Acc Items data table
    $(document).ready(function()
		{
			var searchable = [];
			var selectable = [];
			var dTable = $('#ac_table').DataTable({
				order: [],
				lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				responsive: false,
				serverSide: true,
				processing: true,
				language: {
					processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
				},
				scroller: {
					loadingIndicator: false
				},
				pagingType: "full_numbers",
				dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
				ajax: {
					url: accessories.routes.index,
					data:function(data){
						var cm = $('#cm').val();
						var variant = $('#variant').val();
						data.cm = cm;
						data.variant = variant;
					}
				},
				columns: [
					{
						render: function (data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						}
					},
					{data:'model', name: 'vehicle_id'},
					{data:'vehicle', name: 'vehicle_id'},
					{data:'name', name: 'name'},
					{data:'pack', name: 'pack'},
					{data:'mrp', name: 'mrp'},
					{data:'active', name: 'active'},
					//only those have manage_role permission will get access
					//{data:'action', name: 'action'}
				],
				initComplete: function () {
					var api =  this.api();
					api.columns(searchable).every(function () {
						var column = this;
						var input = document.createElement("input");
						input.setAttribute('placeholder', $(column.header()).text());
						input.setAttribute('style', 'width: 140px; height:25px; border:1px solid whitesmoke;');
						$(input).appendTo($(column.header()).empty())
						.on('keyup', function () {
							column.search($(this).val(), false, false, true).draw();
						});
						$('input', this.column(column).header()).on('click', function(e) {
							e.stopPropagation();
						});
					});
					api.columns(selectable).every( function (i, x) {
						var column = this;
						var select = $('<select style="width: 140px; height:25px; border:1px solid whitesmoke; font-size: 12px; font-weight:bold;"><option value="">'+$(column.header()).text()+'</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function(e){
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
							);
                            column.search(val ? '^'+val+'$' : '', true, false ).draw();
                            e.stopPropagation();
						});
						$.each(dropdownList[i], function(j, v) {
							select.append('<option value="'+v+'">'+v+'</option>')
						});
					});
				}
			});
			//disable export button
			$('.dt-buttons').css('display', 'none');
			$("#accessories-report").submit(function(e){
				e.preventDefault()
				dTable.draw();
			});
		});
})(jQuery);