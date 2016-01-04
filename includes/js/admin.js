jQuery(document).ready(function($) {
	// Save dismiss state
	$( '.notice.is-dismissible' ).on('click', '.notice-dismiss', function ( event ) {
		event.preventDefault();
		var $this = $(this);
		if( ! $this.parent().data( 'key' ) ){
			return;
		}
		$.post( wp_listings_adminL10n.ajaxurl, {
			action: "wp_listings_admin_notice",
			url: wp_listings_adminL10n.ajaxurl,
			nag: $this.parent().data( 'key' ),
			nonce: wp_listings_adminL10n.nonce || ''
		});

	});

	// Make notices dismissible - backward compatabity -4.2 - copied from WordPress 4.2
	$( '.notice.is-dismissible' ).each( function() {
		if( wp_listings_adminL10n.wp_version ){
			return;
		}

		var $this = $( this ),
			$button = $( '<button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button>' ),
			btnText = wp_listings_adminL10n.dismiss || '';

		// Ensure plain text
		$button.find( '.screen-reader-text' ).text( btnText );

		$this.append( $button );

		$button.on( 'click.wp-dismiss-notice', function( event ) {
			event.preventDefault();
			$this.fadeTo( 100 , 0, function() {
				$(this).slideUp( 100, function() {
					$(this).remove();
				});
			});
		});
	});

	/* === Begin term image JS. === */

	/* If the <img> source has a value, show it.  Otherwise, hide. */
	if ( $( '.wpl-term-image-url' ).attr( 'src' ) ) {
		$( '.wpl-term-image-url' ).show();
	} else {
		$( '.wpl-term-image-url' ).hide();
	}

	/* If there's a value for the term image input. */
	if ( $( 'input#wpl-term-image' ).val() ) {

		/* Hide the 'set term image' link. */
		$( '.wpl-add-media-text' ).hide();

		/* Show the 'remove term image' link, the image. */
		$( '.wpl-remove-media, .wpl-term-image-url' ).show();
	}

	/* Else, if there's not a value for the term image input. */
	else {

		/* Show the 'set term image' link. */
		$( '.wpl-add-media-text' ).show();

		/* Hide the 'remove term image' link, the image. */
		$( '.wpl-remove-media, .wpl-term-image-url' ).hide();
	}

	/* When the 'remove term image' link is clicked. */
	$( '.wpl-remove-media' ).click(
		function( j ) {

			/* Prevent the default link behavior. */
			j.preventDefault();

			/* Set the term image input value to nothing. */
			$( '#wpl-term-image' ).val( '' );

			/* Show the 'set term image' link. */
			$( '.wpl-add-media-text' ).show();

			/* Hide the 'remove term image' link, the image. */
			$( '.wpl-remove-media, .wpl-term-image-url, .wpl-errors' ).hide();
		}
	);

	/*
	 * The following code deals with the custom media modal frame for the term image.  It is a 
	 * modified version of Thomas Griffin's New Media Image Uploader example plugin.
	 *
	 * @link      https://github.com/thomasgriffin/New-Media-Image-Uploader
	 * @license   http://www.opensource.org/licenses/gpl-license.php
	 * @author    Thomas Griffin <thomas@thomasgriffinmedia.com>
	 * @copyright Copyright 2013 Thomas Griffin
	 */

	/* Prepare the variable that holds our custom media manager. */
	var wpl_term_image_frame;

	/* When the 'set term image' link is clicked. */
	$( '.wpl-add-media' ).click(

		function( j ) {

			/* Prevent the default link behavior. */
			j.preventDefault();

			/* If the frame already exists, open it. */
			if ( wpl_term_image_frame ) {
				wpl_term_image_frame.open();
				return;
			}

			/* Creates a custom media frame. */
			wpl_term_image_frame = wp.media.frames.wpl_term_image_frame = wp.media(
				{
					className: 'media-frame',            // Custom CSS class name
					frame:     'select',                 // Frame type (post, select)
					multiple:  false,                   // Allow selection of multiple images
					title:     wpl_term_image.title, // Custom frame title

					library: {
						type: 'image' // Media types allowed
					},

					button: {
						text:  wpl_term_image.button // Custom insert button text
					}
				}
			);

			/*
			 * The following handles the image data and sending it back to the meta box once an 
			 * an image has been selected via the media frame.
			 */
			wpl_term_image_frame.on( 'select',

				function() {

					/* Construct a JSON representation of the model. */
					var media_attachment = wpl_term_image_frame.state().get( 'selection' ).toJSON();

					/* If the custom term image size is available, use it. */
					/* Note the 'width' is contrained by $content_width. */
					if ( media_attachment[0].sizes.wpl_term_image ) {
						var wpl_media_url    = media_attachment[0].sizes.wpl_term_image.url;
						var wpl_media_width  = media_attachment[0].sizes.wpl_term_image.width;
						var wpl_media_height = media_attachment[0].sizes.wpl_term_image.height;
					}

					/* Else, use the full size b/c it will always be available. */
					else {
						var wpl_media_url    = media_attachment[0].sizes.full.url;
						var wpl_media_width  = media_attachment[0].sizes.full.width;
						var wpl_media_height = media_attachment[0].sizes.full.height;
					}

					/* === Begin image dimensions error wplcks. === */

					var wpl_errors = '';

					/*
					 * Note that we must use the "full" size width in some error wplcks 
					 * b/c I haven't found a way around WordPress constraining the image 
					 * size via the $content_width global. This means that the error 
					 * wplcking isn't 100%, but it should do fine for the most part since 
					 * we're using a custom image size. If not, the error wplcking is good 
					 * on the PHP side once the data is saved.
					 */
					if ( wpl_term_image.min_width > media_attachment[0].sizes.full.width && wpl_term_image.min_height > wpl_media_height ) {
						wpl_errors = wpl_term_image.min_width_height_error;
					}

					else if ( wpl_term_image.max_width < wpl_media_width && wpl_term_image.max_height < wpl_media_height ) {
						wpl_errors = wpl_term_image.max_width_height_error;
					}

					else if ( wpl_term_image.min_width > media_attachment[0].sizes.full.width ) {
						wpl_errors = wpl_term_image.min_width_error;
					}

					else if ( wpl_term_image.min_height > wpl_media_height ) {
						wpl_errors = wpl_term_image.min_height_error;
					}

					else if ( wpl_term_image.max_width < wpl_media_width ) {
						wpl_errors = wpl_term_image.max_width_error;
					}

					else if ( wpl_term_image.max_height < wpl_media_height ) {
						wpl_errors = wpl_term_image.max_height_error;
					}

					/* If there are error strings, show them. */
					if ( wpl_errors ) {
						$( '.wpl-errors p' ).text( wpl_errors );
						$( '.wpl-errors' ).show();
					}

					/* If no error strings, make sure the errors <div> is hidden. */
					else {
						$( '.wpl-errors' ).hide();
					}

					/* === End image dimensions error wplcks. === */

					/* Add the image attachment ID to our hidden form field. */
					$( '#wpl-term-image').val( media_attachment[0].id );

					/* Change the 'src' attribute so the image will display in the meta box. */
					$( '.wpl-term-image-url' ).attr( 'src', wpl_media_url );

					/* Hides the add image link. */
					$( '.wpl-add-media-text' ).hide();

					/* Displays the term image and remove image link. */
					$( '.wpl-term-image-url, .wpl-remove-media' ).show();
				}
			);

			/* Open up the frame. */
			wpl_term_image_frame.open();
		}
	);

	/* === End term image JS. === */

});