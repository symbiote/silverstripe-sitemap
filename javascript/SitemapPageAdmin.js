;(function($) {
	$('#Form_EditForm_PagesToDisplay_All').livequery('click', function() {
		$('#ParentPageID, #PagesToShow').hide();
	});

	$('#Form_EditForm_PagesToDisplay_ChildrenOf').livequery('click', function() {
		$('#ParentPageID').show();
		$('#PagesToShow').hide();
	});

	$('#Form_EditForm_PagesToDisplay_Selected').livequery('click', function() {
		$('#PagesToShow').show();
		$('#ParentPageID').hide();
	});

	$('#PagesToDisplay').livequery(function() {
		var selected = $(this).find('input:checked');

		if(selected.length) {
			selected.trigger('click');
		} else {
			$('#ParentPageID, #PagesToShow').hide();
		}
	});
})(jQuery);