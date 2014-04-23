<div id="icon-options-general" class="icon32"></div>
<div class="wrap">
	<h2>WP Listings Settings</h2>
	<hr>
	<div id="poststuff" class="metabox-holder has-right-sidebar">
		<div id="side-info-column" class="inner-sidebar">
		<?php do_meta_boxes('wp-listings-options', 'side', null); ?>
		</div>

        <div id="post-body">
            <div id="post-body-content" class="has-sidebar-content">
				<p>If you would like to move the WP Listings CSS to your theme's css file for purposes of avoiding an additional HTTP request or for ease of customization, check the box below.</p>
				<?php
				if (get_option('wp_listings_stylesheet_load') == 1)
					echo '<p style="color:red; font-weight: bold;">The plugin stylesheet has been deregistered<p>';
				?>
				<form action="options.php" method="post" id="wp-listings-stylesheet-options-form">
					<?php settings_fields('wp_listings_options'); ?>
					<?php echo '<h4><input name="wp_listings_stylesheet_load" id="wp_listings_stylesheet_load" type="checkbox" value="1" class="code" ' . checked(1, get_option('wp_listings_stylesheet_load'), false ) . ' /> Deregister WP Listings CSS?</h4><hr>'; ?>

					<?php
					_e("<p>You may enter a default state that will automatically be output on template pages that show the state the listing is located in. When you are creating a listing post and you leave the state option of the property details metabox empty the default will be shown on template pages. You can override the default by simpling giving the state field any value at all. Leave it blank to keep the default.</p>", 'wp_listings' );
				    echo '<h4>Default State: <input name="wp_listings_default_state" id="wp_listings_default_state" type="text" value="' . get_option('wp_listings_default_state') . '" size="1" /></h4><hr>';
					?>

					<?php
					_e("<p>The default number of posts displayed on a listing archive page is 9. Here you can set a custom number. Enter <span style='color: #f00;font-weight: 700;'>-1</span> to display all listing posts. <em>If you have more than 20-30 posts, it's not recommended to show all or your page will load slow.</em></p>", 'wp_listings' );
				    echo '<h4>Number of posts on listing archive page: <input name="wp_listings_archive_posts_num" id="wp_listings_archive_posts_num" type="text" value="' . get_option('wp_listings_archive_posts_num') . '" size="1" /></h4>';
					?>
					<br />
					<input name="submit" class="button-primary" type="submit" value="<?php esc_attr_e('Save Settings'); ?>" />
				</form>
            </div>
        </div>
    </div>
</div>