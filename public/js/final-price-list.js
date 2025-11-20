(function($) {
	'use strict';
    // PL Types data table
    $(document).ready(function()
		{
			$('#final-price-list').DataTable({
				scrollY:        "500px",
				scrollX:        true,
				scrollCollapse: true,
				paging:         false,
				//fixedColumns:   true,
				fixedColumns:   {
					leftColumns: 2
				},
				responsive: true,
				dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
				select: true,
				drawCallback: function() {					$('[data-toggle="popover"]').popover({						html: true,						content: function() {							var content = $(this).attr("data-popover-content");							return $(content).children(".popover-body").html();						},						title: function() {							var title = $(this).attr("data-popover-content");							return $(title).children(".popover-heading").html();						}					});				},
			});
			$('#final-price-list').on('click', 'tr', function () {
				var name = $('td', this).eq(1).text();
				$('#PriceDescModal').modal("show");
			});
			
			$('body').on('mousedown', '.popover', function(e) {
				e.preventDefault();
			});
			
			$(document).click(function (e) {
				if ($(e.target).is('.fa-times')) {
					$('.popover').popover('hide');
				}
			});
						//disable export button			//$('.dt-buttons').css('display', 'none');
		})
})(jQuery);