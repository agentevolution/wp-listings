<?php

if ( !class_exists( 'Idx_Broker_Plugin' ) ) {
	echo wp_listings_admin_notice( __( '<strong>Integrate your MLS Listings into WordPress with IDX Broker!</strong> <a href="http://www.idxbroker.com/features/idx-wordpress-plugin">Find out how</a>', 'wp_listings' ), false, 'activate_plugins', 'wpl_notice_idx' );
}
if( !function_exists( 'equity' ) ) {
	echo wp_listings_admin_notice( __( '<strong>Want enhanced listings? Automatically import extra details and photos with Equity.</strong> <a href="http://www.agentevolution.com/equity/">Learn how</a>', 'wp_listings' ), false, 'activate_plugins', 'wpl_notice_equity' );
}

if( isset($_GET['settings-updated']) ) { ?>
    <div id="message" class="updated">
        <p><strong><?php _e('Settings saved.','wp_listings'); ?></strong></p>
    </div>
<?php
}

?>
<div id="icon-options-general" class="icon32"></div>
<div class="wrap">
	<h1><?php _e('WP Listings Settings', 'wp_listings'); ?></h1>
	<hr>
	<div id="poststuff" class="metabox-holder has-right-sidebar">
		<div id="side-info-column" class="inner-sidebar">
		<?php do_meta_boxes('wp-listings-options', 'side', null); ?>
		</div>

        <div id="post-body">
            <div id="post-body-content" class="has-sidebar-content">

            	<?php $options = get_option('plugin_wp_listings_settings');

            	if ( !isset($options['wp_listings_stylesheet_load']) ) {
					$options['wp_listings_stylesheet_load'] = 0;
				}
				if ( !isset($options['wp_listings_widgets_stylesheet_load']) ) {
					$options['wp_listings_widgets_stylesheet_load'] = 0;
				}
				if ( !isset($options['wp_listings_default_state']) ) {
					$options['wp_listings_default_state'] = '';
				}
				if ( !isset($options['wp_listings_archive_posts_num']) ) {
					$options['wp_listings_archive_posts_num'] = 9;
				}
				if ( !isset($options['wp_listings_slug']) ) {
					$options['wp_listings_slug'] = 'listings';
				}
				if ( !isset($options['wp_listings_default_form']) ) {
					$options['wp_listings_default_form'] = '';
				}
				if ( !isset($options['wp_listings_custom_wrapper']) ) {
					$options['wp_listings_custom_wrapper'] = 0;
				}
				if ( !isset($options['wp_listings_start_wrapper']) ) {
					$options['wp_listings_start_wrapper'] = '';
				}
				if ( !isset($options['wp_listings_end_wrapper']) ) {
					$options['wp_listings_end_wrapper'] = '';
				}

            	?>

            	
				<?php
				if ($options['wp_listings_stylesheet_load'] == 1)
					echo '<p style="color:red; font-weight: bold;">The plugin\'s main stylesheet (wp-listings.css) has been deregistered<p>';
				if ($options['wp_listings_widgets_stylesheet_load'] == 1)
					echo '<p style="color:red; font-weight: bold;">The plugin\'s widget stylesheet (wp-listings-widgets.css) has been deregistered<p>';
				?>
				<form action="options.php" method="post" id="wp-listings-settings-options-form">
					<?php
					settings_fields('wp_listings_options');
				
					
					_e('<h2>Include CSS?</h2>', 'wp_listings');
					_e('<p>Here you can deregister the WP Listings CSS files and move to your theme\'s css file for ease of customization</p>', 'wp_listings');
					_e('<p><input name="plugin_wp_listings_settings[wp_listings_stylesheet_load]" id="wp_listings_stylesheet_load" type="checkbox" value="1" class="code" ' . checked(1, $options['wp_listings_stylesheet_load'], false ) . ' /> Deregister WP Listings main CSS (wp-listings.css)?</p>', 'wp-listings' );

					_e('<p><input name="plugin_wp_listings_settings[wp_listings_widgets_stylesheet_load]" id="wp_listings_widgets_stylesheet_load" type="checkbox" value="1" class="code" ' . checked(1, $options['wp_listings_widgets_stylesheet_load'], false ) . ' /> Deregister WP Listings widgets CSS (wp-listings-widgets.css)?</p><hr>', 'wp-listings' );

					
					_e("<h2>Default State</h2><p>You can enter a default state that will automatically be output on template pages and widgets that show the state. When you are create a listing and leave the state field empty, the default entered below will be shown. You can override the default on each listing by entering a value into the state field.</p>", 'wp_listings' );
				    echo '<p>Default State: <input name="plugin_wp_listings_settings[wp_listings_default_state]" id="wp_listings_default_state" type="text" value="' . $options['wp_listings_default_state'] . '" size="1" /></p><hr>';
				

					
					_e("<h2>Default Number of Posts</h2><p>The default number of posts displayed on a listing archive page is 9. Here you can set a custom number. Enter <span style='color: #f00;font-weight: 700;'>-1</span> to display all listing posts.<br /><em>If you have more than 20-30 posts, it's not recommended to show all or your page will load slow.</em></p>", 'wp_listings' );
				    _e('<p>Number of posts on listing archive page: <input name="plugin_wp_listings_settings[wp_listings_archive_posts_num]" id="wp_listings_archive_posts_num" type="text" value="' . $options['wp_listings_archive_posts_num'] . '" size="1" /></p><hr>', 'wp-listings' );
				

					
					_e("<h2>Default Form shortcode</h2><p>If you use a Contact Form plugin, you may enter the form shortcode here to display on all listings. Additionally, each listing can use a custom form. If no shortcode is entered, the template will use a default contact form:</p>", 'wp_listings' );
				    _e('<p>Form shortcode: <input name="plugin_wp_listings_settings[wp_listings_default_form]" id="wp_listings_default_form" type="text" value="' . esc_html($options['wp_listings_default_form']) . '" size="40" /></p><hr>', 'wp-listings');
				

					
					_e("<h2>Custom Wrapper</h2><p>If your theme's content HTML ID's and Classes are different than the included template, you can enter the HTML of your content wrapper beginning and end:</p>", 'wp_listings' );
					_e('<p><label><input name="plugin_wp_listings_settings[wp_listings_custom_wrapper]" id="wp_listings_custom_wrapper" type="checkbox" value="1" class="code" ' . checked(1, $options['wp_listings_custom_wrapper'], false ) . ' /> Use Custom Wrapper?</p>', 'wp-listings' );
				    _e('<p><label>Wrapper Start HTML: </p><input name="plugin_wp_listings_settings[wp_listings_start_wrapper]" id="wp_listings_start_wrapper" type="text" value="' . esc_html($options['wp_listings_start_wrapper']) . '" size="80" /></label>', 'wp-listings' );
				    _e('<p><label>Wrapper End HTML: </p><input name="plugin_wp_listings_settings[wp_listings_end_wrapper]" id="wp_listings_end_wrapper" type="text" value="' . esc_html($options['wp_listings_end_wrapper']) . '" size="80" /></label><hr>', 'wp-listings' );
				

					_e('<h2>Listings slug</h2><p>Optionally change the slug of the listing post type<br /><input type="text" name="plugin_wp_listings_settings[wp_listings_slug]" value="' . $options['wp_listings_slug'] . '" /></p>', 'wp-listings' );
					_e("<em>Don't forget to <a href='../wp-admin/options-permalink.php'>reset your permalinks</a> if you change the slug!</em></p>", 'wp-listings' );
					?>
					<input name="submit" class="button-primary" type="submit" value="<?php esc_attr_e('Save Settings'); ?>" />
				</form>
            </div>
        </div>
    </div>
</div>