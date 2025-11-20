(function($) {
	'use strict';
    $(document).ready(function()
		{
			var searchable = [];
			var selectable = [];
			var dTable = $('#duplicate_vehicles').DataTable({
				order: [],
				lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				processing: true,
				responsive: true,
				scrollX: true,
				serverSide: true,
				language: {
					processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
				},
				scroller: {
					loadingIndicator: false
				},
				pagingType: "full_numbers",
				dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
				ajax: {
					url: 'duplicate-list',
					type: "get"
				},
				columns: [
					{data:'id', name: 'id'},
					{data:'model', name: 'model'},
					{data:'cm1', name: 'cm1'},
					{data:'variant', name: 'variant'},
					{data:'local_name', name: 'local_name'},
					{data:'cond', name: 'status'},
					{data:'replacement', name: 'replace_with'}						
				],
				buttons: [
					{
						extend: 'excel',
						className: 'btn-sm btn-success',
						title: 'Make List',
						header: false,
						footer: true,
						exportOptions: {
							// columns: ':visible',
						}
					},
					{
						extend: 'pdf',
						className: 'btn-sm btn-danger',
						title: 'Make List',
						pageSize: 'A2',
						header: false,
						footer: true,
						exportOptions: {
							// columns: ':visible'
						}
					}
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
		});
})(jQuery);