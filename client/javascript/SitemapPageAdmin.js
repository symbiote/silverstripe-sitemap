;(function($) {
    $.entwine('ss', function($){

        // Luckily entwine event binders still work with the new react stuff...

        $('#Form_EditForm_PagesToDisplay_All').entwine({
            onclick: function() {
                $('#Form_EditForm_PagesToShow_Holder').hide();
                $('#Form_EditForm_ParentPageID_Holder').hide();
            }
        });

        $('#Form_EditForm_PagesToDisplay_ChildrenOf').entwine({
            onclick: function() {
                $('#Form_EditForm_PagesToShow_Holder').hide();
                $('#Form_EditForm_ParentPageID_Holder').show();
            }
        });

        $('#Form_EditForm_PagesToDisplay_Selected').entwine({
            onclick: function() {
                $('#Form_EditForm_PagesToShow_Holder').show();
                $('#Form_EditForm_ParentPageID_Holder').hide();
            }
        });

        // init
        $('#Form_EditForm_PagesToDisplay').entwine({
            onmatch: function() {
                this.find('input:checked').trigger('click');
            }
        });

    })
})(jQuery);