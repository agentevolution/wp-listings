jQuery(document).ready(function($) {

	/* === Begin listing importer JS. === */
	jQuery('.grid').masonry({
		columnWidth: '.grid-sizer',
		itemSelector: '.grid-item'
	});

	$(document).on( 'click', '.delete-post', function() {
		var id = $(this).data('id');
		var nonce = $(this).data('nonce');
		var post = $(this).parents('.post:first');
		var grid = $('.grid').masonry({
			columnWidth: '.grid-sizer',
			itemSelector: '.grid-item'
		});
		$.ajax({
			type: 'post',
			url: DeleteListingAjax.ajaxurl,
			data: {
				action: 'equity_idx_listing_delete',
				nonce: nonce,
				id: id
			},
			success: function( result ) {
				if( result == 'success' ) {
					post.fadeOut( function(){
						post.remove();
						grid.masonry('layout');
					});
				}
			}
		});
		return false;
	});



	// make sure labels are drawn in the correct state
	$('li').each(function()
	{

		if ($(this).find(':checkbox').attr('checked'))
			$(this).addClass('selected');

	});

	// toggle label css when checkbox is clicked
	$(':checkbox').click(function(e)
	{

		var checked = $(this).attr('checked');
		$(this).closest('li').toggleClass('selected', checked);

	});

	// Select all
	$("#selectall").change(function(){
		$(".checkbox").prop('checked', $(this).prop("checked"));
		$(this).closest('li').addClass('selected');
	});

	/* === End listing importer JS. === */

});