;(function($) {
	$('#Form_EditForm_PagesToDisplay_All').livequery('click', function() {
		$('#Form_EditForm_ParentPageID_Holder, #Form_EditForm_PagesToShow_Holder').hide();
	});

	$('#Form_EditForm_PagesToDisplay_ChildrenOf').livequery('click', function() {
		$('#Form_EditForm_ParentPageID_Holder').show();
		$('#Form_EditForm_PagesToShow_Holder').hide();
	});

	$('#Form_EditForm_PagesToDisplay_Selected').livequery('click', function() {
		$('#Form_EditForm_PagesToShow_Holder').show();
		$('#Form_EditForm_ParentPageID_Holder').hide();
	});

	$('#PagesToDisplay').livequery(function() {
		var selected = $(this).find('input:checked');

		if(selected.length) {
			selected.trigger('click');
		} else {
			$('#Form_EditForm_ParentPageID_Holder, #Form_EditForm_PagesToShow_Holder').hide();
		}
	});
})(jQuery);
