<?php

/**
 * Adds ability to reorder taxonomies after creation
 * Adapted from AgentPress Listings Taxonomy Reorder plugin by Robert Iseley (http://www.robertiseley.com)
 * http://wordpress.org/plugins/agentpress-listings-taxonomy-reorder/
 * Contributors: unclhos
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package WP Listings
 * @since 0.1.0
 */

add_action('admin_menu', 'wp_listings_tax_reorder_init', 20);
function wp_listings_tax_reorder_init() {
	add_submenu_page( 'edit.php?post_type=listing', __( 'Reorder Taxonomies', 'wp-listings' ), __( 'Reorder Taxonomies', 'wp-listings' ), 'manage_options', 'wplistings-tax-reorder', 'wp_listings_tax_reorder');
}

add_action( 'admin_enqueue_scripts', 'wp_listings_tax_reorder_enqueue' );
function wp_listings_tax_reorder_enqueue() {
		wp_enqueue_script('jquery-ui-sortable');
}

function wp_listings_tax_reorder() {
	$wp_listings_taxes = get_option('wp_listings_taxonomies');

	if($_POST) {
		$new_order = $_POST['wplistings-tax'];
		$wp_listings_taxes_reordered = array();
		foreach( $new_order as $tax ) {
			if($wp_listings_taxes[$tax])
				$wp_listings_taxes_reordered[$tax] = $wp_listings_taxes[$tax];	
		}
		$wp_listings_taxes = $wp_listings_taxes_reordered;
		update_option('wp_listings_taxonomies', $wp_listings_taxes_reordered);
		
	}
screen_icon( 'themes' ); ?>
<h2><?php _e( 'Reorder Taxonomies', 'wp-listings' ); ?></h2>
<div id="col-container">
<style>
	#sortable { list-style-type: none; margin: 10px 0 ; padding: 0; }
	#sortable li .item { 
		-moz-border-radius: 6px 6px 6px 6px;
		border: 1px solid #E6E6E6;
		font-weight: bold;
		height: auto;
		line-height: 35px;
		overflow: hidden;
		padding-left: 10px;
		position: relative;
		text-shadow: 0 1px 0 white;
		width: auto;
		word-wrap: break-word;
		cursor: move;
		background: none repeat-x scroll left top #DFDFDF;
		-moz-box-shadow: 2px 2px 3px #888;
		-webkit-box-shadow: 2px 2px 3px #888;
		box-shadow: 2px 2px 3px #888;
	}
	#sortable li span { position: absolute; margin-left: -1.3em; }
	.ui-state-highlight { background: #E6E6E6; border: 1px #666 dashed; }
	.wplistings-submit { padding: 5px 10px; }
	.wplistings-submit:hover { background: #eaf2fa; font-weight: bold;}
	</style>
	<script>
	jQuery(function($) {
		$( "#sortable" ).sortable({ placeholder: 'ui-state-highlight', forcePlaceholderSize: true});
		$( "#sortable" ).disableSelection();
	});
	</script>
	<div id="col-left">
		<?php print_r($wp_listings_taxes); ?>
	<div class="col-wrap">
    <span>Drag and Drop to reorder</span>
	<form method="post">
	<ul id="sortable">
    	<?php foreach($wp_listings_taxes as $wp_listings_tax_key => $wp_listings_tax_value) { ?>
        	<li class="ui-state-default">
            	<div class="item">
					<?php echo $wp_listings_tax_value['labels']['name']; ?><input type="hidden" id="wplistings-tax[]" name="wplistings-tax[]" value="<?php echo $wp_listings_tax_key; ?>" />
                </div>
            </li>
        <?php } ?>
	</ul>
    <input class="wplistings-submit" type="submit" value="Save" />
	</form>
	</div>
	</div><!-- /col-left -->

</div><!-- /col-container -->
<?php
}

?>