<?php

if ( !class_exists( 'Idx_Broker_Plugin' ) ) {
	echo wp_listings_admin_notice( __( '<strong>Integrate your MLS Listings into WordPress with IDX Broker!</strong> <a href="http://www.idxbroker.com/features/idx-wordpress-plugin">Find out how</a>', 'wp-listings' ), false, 'activate_plugins', 'wpl_notice_idx' );
}
if( !function_exists( 'equity' ) ) {
	echo wp_listings_admin_notice( __( '<strong>Want enhanced listings? Automatically import extra details and photos with Equity.</strong> <a href="http://www.agentevolution.com/equity/">Learn how</a>', 'wp-listings' ), false, 'activate_plugins', 'wpl_notice_equity' );
}

if( isset($_GET['settings-updated']) ) { ?>
	<div id="message" class="updated">
		<p><strong><?php _e('Settings saved.', 'wp-listings'); ?></strong></p>
	</div>
<?php
}

?>
<div id="icon-options-general" class="icon32"></div>
<div class="wrap">
	<h1><?php _e('WP Listings Settings', 'wp-listings'); ?></h1>
	<hr>
	<div id="poststuff" class="metabox-holder has-right-sidebar">
		<div id="side-info-column" class="inner-sidebar">
		<?php do_meta_boxes('wp-listings-options', 'side', null); ?>
		</div>

		<div id="post-body">
			<div id="post-body-content" class="has-sidebar-content">

				<?php $options = get_option('plugin_wp_listings_settings');

				$defaults = array(
					'wp_listings_stylesheet_load'         => 0,
					'wp_listings_widgets_stylesheet_load' => 0,
					'wp_listings_default_state'           => '',
					'wp_listings_currency_symbol'         => '',
					'wp_listings_currency_code'           => '',
					'wp_listings_display_currency_code'   => 0,
					'wp_listings_archive_posts_num'       => 9,
					'wp_listings_global_disclaimer'       => '',
					'wp_listings_slug'                    => 'listings',
					'wp_listings_captcha_site_key'        => '',
					'wp_listings_captcha_secret_key'      => '',
					'wp_listings_default_form'            => '',
					'wp_listings_custom_wrapper'          => 0,
					'wp_listings_start_wrapper'           => '',
					'wp_listings_end_wrapper'             => '',
					'wp_listings_idx_update'              => 'update-all',
					'wp_listings_idx_sold'                => 'sold-keep'
					);

				foreach($defaults as $name => $value) {
					if ( !isset($options[$name]) ) {
						$options[$name] = $value;
					}
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


					_e('<h3>Include CSS?</h3>', 'wp-listings');
					_e('<p>Here you can deregister the WP Listings CSS files and move to your theme\'s css file for ease of customization</p>', 'wp-listings');
					_e('<p><input name="plugin_wp_listings_settings[wp_listings_stylesheet_load]" id="wp_listings_stylesheet_load" type="checkbox" value="1" class="code" ' . checked(1, $options['wp_listings_stylesheet_load'], false ) . ' /> Deregister WP Listings main CSS (wp-listings.css)?</p>', 'wp-listings' );

					_e('<p><input name="plugin_wp_listings_settings[wp_listings_widgets_stylesheet_load]" id="wp_listings_widgets_stylesheet_load" type="checkbox" value="1" class="code" ' . checked(1, $options['wp_listings_widgets_stylesheet_load'], false ) . ' /> Deregister WP Listings widgets CSS (wp-listings-widgets.css)?</p><hr>', 'wp-listings' );


					_e("<h3>Default State</h3><p>You can enter a default state that will automatically be output on template pages and widgets that show the state. When you are create a listing and leave the state field empty, the default entered below will be shown. You can override the default on each listing by entering a value into the state field.</p>", 'wp-listings' );
					echo '<p>Default State: <input name="plugin_wp_listings_settings[wp_listings_default_state]" id="wp_listings_default_state" type="text" value="' . $options['wp_listings_default_state'] . '" size="1" /></p><hr>';

					_e("<h3>Default Currency</h3><p>Select a default currency symbol and optional currency code to display on listings.</p>", 'wp-listings' );
					_e('<p>Currency Symbol: ', 'wp-listings');
					echo '<select name="plugin_wp_listings_settings[wp_listings_currency_symbol]" id="wp_listings_currency_symbol">
							 <option value="" ' . selected($options['wp_listings_currency_symbol'], '', false) . '>None</option>
							 <option value="&#36;" ' . selected($options['wp_listings_currency_symbol'], '$', false) . '>&#36;</option>
							 <option value="&#163;" ' . selected($options['wp_listings_currency_symbol'], '£', false) . '>&#163;</option>
							 <option value="&#8364;" ' . selected($options['wp_listings_currency_symbol'], '€', false) . '>&#8364;</option>
							 <option value="&#165;" ' . selected($options['wp_listings_currency_symbol'], '¥', false) . '>&#165;</option>
							 <option value="&#8369;" ' . selected($options['wp_listings_currency_symbol'], '₱', false) . '>&#8369;</option>
							 <option value="&#8361;" ' . selected($options['wp_listings_currency_symbol'], '₩', false) . '>&#8361;</option>
							 <option value="&#402;" ' . selected($options['wp_listings_currency_symbol'], 'ƒ', false) . '>&#402;</option>
							 <option value="&#8358;" ' . selected($options['wp_listings_currency_symbol'], '₦', false) . '>&#8358;</option>
							</select>
						  </p>';
					$codes = array (
						''    => 'None',
						'USD' => 'United States dollar',
						'GBP' => 'British pound',
						'CAD' => 'Canadian dollar',
						'EUR' => 'Euro',
						'MXN' => 'Mexican peso',
						'---' => '---',
						'AED' => 'United Arab Emirates dirham',
						'AFN' => 'Afghan afghani',
						'ALL' => 'Albanian lek',
						'AMD' => 'Armenian dram',
						'AOA' => 'Angolan kwanza',
						'ARS' => 'Argentine peso',
						'AUD' => 'Australian dollar',
						'AWG' => 'Aruban florin',
						'AZN' => 'Azerbaijani manat',
						'BAM' => 'Bosnia and Herzegovina convertible mark',
						'BBD' => 'Barbadian dollar',
						'BDT' => 'Bangladeshi taka',
						'BGN' => 'Bulgarian lev',
						'BHD' => 'Bahraini dinar',
						'BIF' => 'Burundian franc',
						'BMD' => 'Bermudian dollar',
						'BND' => 'Brunei dollar',
						'BOB' => 'Bolivian boliviano',
						'BRL' => 'Brazilian real',
						'BSD' => 'Bahamian dollar',
						'BTN' => 'Bhutanese ngultrum',
						'BWP' => 'Botswana pula',
						'BYR' => 'Belarusian ruble',
						'BZD' => 'Belize dollar',
						'CDF' => 'Congolese franc',
						'CHF' => 'Swiss franc',
						'CLP' => 'Chilean peso',
						'CNY' => 'Chinese yuan',
						'COP' => 'Colombian peso',
						'CRC' => 'Costa Rican colón',
						'CUP' => 'Cuban convertible peso',
						'CVE' => 'Cape Verdean escudo',
						'CZK' => 'Czech koruna',
						'DJF' => 'Djiboutian franc',
						'DKK' => 'Danish krone',
						'DOP' => 'Dominican peso',
						'DZD' => 'Algerian dinar',
						'EGP' => 'Egyptian pound',
						'ERN' => 'Eritrean nakfa',
						'ETB' => 'Ethiopian birr',
						'FJD' => 'Fijian dollar',
						'FKP' => 'Falkland Islands pound',
						'GEL' => 'Georgian lari',
						'GHS' => 'Ghana cedi',
						'GMD' => 'Gambian dalasi',
						'GNF' => 'Guinean franc',
						'GTQ' => 'Guatemalan quetzal',
						'GYD' => 'Guyanese dollar',
						'HKD' => 'Hong Kong dollar',
						'HNL' => 'Honduran lempira',
						'HRK' => 'Croatian kuna',
						'HTG' => 'Haitian gourde',
						'HUF' => 'Hungarian forint',
						'IDR' => 'Indonesian rupiah',
						'ILS' => 'Israeli new shekel',
						'IMP' => 'Manx pound',
						'INR' => 'Indian rupee',
						'IQD' => 'Iraqi dinar',
						'IRR' => 'Iranian rial',
						'ISK' => 'Icelandic króna',
						'JEP' => 'Jersey pound',
						'JMD' => 'Jamaican dollar',
						'JOD' => 'Jordanian dinar',
						'JPY' => 'Japanese yen',
						'KES' => 'Kenyan shilling',
						'KGS' => 'Kyrgyzstani som',
						'KHR' => 'Cambodian riel',
						'KMF' => 'Comorian franc',
						'KPW' => 'North Korean won',
						'KRW' => 'South Korean won',
						'KWD' => 'Kuwaiti dinar',
						'KYD' => 'Cayman Islands dollar',
						'KZT' => 'Kazakhstani tenge',
						'LAK' => 'Lao kip',
						'LBP' => 'Lebanese pound',
						'LKR' => 'Sri Lankan rupee',
						'LRD' => 'Liberian dollar',
						'LSL' => 'Lesotho loti',
						'LTL' => 'Lithuanian litas',
						'LVL' => 'Latvian lats',
						'LYD' => 'Libyan dinar',
						'MAD' => 'Moroccan dirham',
						'MDL' => 'Moldovan leu',
						'MGA' => 'Malagasy ariary',
						'MKD' => 'Macedonian denar',
						'MMK' => 'Burmese kyat',
						'MNT' => 'Mongolian tögrög',
						'MOP' => 'Macanese pataca',
						'MRO' => 'Mauritanian ouguiya',
						'MUR' => 'Mauritian rupee',
						'MVR' => 'Maldivian rufiyaa',
						'MWK' => 'Malawian kwacha',
						'MYR' => 'Malaysian ringgit',
						'MZN' => 'Mozambican metical',
						'NAD' => 'Namibian dollar',
						'NGN' => 'Nigerian naira',
						'NIO' => 'Nicaraguan córdoba',
						'NOK' => 'Norwegian krone',
						'NPR' => 'Nepalese rupee',
						'NZD' => 'New Zealand dollar',
						'OMR' => 'Omani rial',
						'PAB' => 'Panamanian balboa',
						'PEN' => 'Peruvian nuevo sol',
						'PGK' => 'Papua New Guinean kina',
						'PHP' => 'Philippine peso',
						'PKR' => 'Pakistani rupee',
						'PLN' => 'Polish złoty',
						'PRB' => 'Transnistrian ruble',
						'PYG' => 'Paraguayan guaraní',
						'QAR' => 'Qatari riyal',
						'RON' => 'Romanian leu',
						'RSD' => 'Serbian dinar',
						'RUB' => 'Russian ruble',
						'RWF' => 'Rwandan franc',
						'SAR' => 'Saudi riyal',
						'SBD' => 'Solomon Islands dollar',
						'SCR' => 'Seychellois rupee',
						'SDG' => 'Singapore dollar',
						'SEK' => 'Swedish krona',
						'SGD' => 'Singapore dollar',
						'SHP' => 'Saint Helena pound',
						'SLL' => 'Sierra Leonean leone',
						'SOS' => 'Somali shilling',
						'SRD' => 'Surinamese dollar',
						'SSP' => 'South Sudanese pound',
						'STD' => 'São Tomé and Príncipe dobra',
						'SVC' => 'Salvadoran colón',
						'SYP' => 'Syrian pound',
						'SZL' => 'Swazi lilangeni',
						'THB' => 'Thai baht',
						'TJS' => 'Tajikistani somoni',
						'TMT' => 'Turkmenistan manat',
						'TND' => 'Tunisian dinar',
						'TOP' => 'Tongan paʻanga',
						'TRY' => 'Turkish lira',
						'TTD' => 'Trinidad and Tobago dollar',
						'TWD' => 'New Taiwan dollar',
						'TZS' => 'Tanzanian shilling',
						'UAH' => 'Ukrainian hryvnia',
						'UGX' => 'Ugandan shilling',
						'UYU' => 'Uruguayan peso',
						'UZS' => 'Uzbekistani som',
						'VEF' => 'Venezuelan bolívar',
						'VND' => 'Vietnamese đồng',
						'VUV' => 'Vanuatu vatu',
						'WST' => 'Samoan tālā',
						'XAF' => 'Central African CFA franc',
						'XCD' => 'East Caribbean dollar',
						'XOF' => 'West African CFA franc',
						'XPF' => 'CFP franc',
						'YER' => 'Yemeni rial',
						'ZAR' => 'South African rand',
						'ZMW' => 'Zambian kwacha',
						'ZWL' => 'Zimbabwean dollar'
					);
					_e('<p>Currency Code: ', 'wp-listings');
					echo '<select name="plugin_wp_listings_settings[wp_listings_currency_code]" id="plugin_wp_listings_settings[wp_listings_currency_code]" data-currency="USD">';
						foreach ($codes as $code => $currency_name) {
							echo '<option value=' . $code . ' ' . selected($options['wp_listings_currency_code'], $code, false) . '>' . $currency_name . '</option>';
						}
					echo '</select>
					  </p>';

					_e('<p><input name="plugin_wp_listings_settings[wp_listings_display_currency_code]" id="wp_listings_display_currency_code" type="checkbox" value="1" class="code" ' . checked(1, $options['wp_listings_display_currency_code'], 0 ) . ' /> Display currency code?</p><hr>', 'wp-listings' );

					_e("<h3>Default Number of Posts</h3><p>The default number of posts displayed on a listing archive page is 9. Here you can set a custom number. Enter <span style='color: #f00;font-weight: 700;'>-1</span> to display all listing posts.<br /><em>If you have more than 20-30 posts, it's not recommended to show all or your page will load slow.</em></p>", 'wp-listings' );
					_e('<p>Number of posts on listing archive page: <input name="plugin_wp_listings_settings[wp_listings_archive_posts_num]" id="wp_listings_archive_posts_num" type="text" value="' . $options['wp_listings_archive_posts_num'] . '" size="1" /></p><hr>', 'wp-listings' );

					_e("<h3>Default Disclaimer</h3><p>Optionally enter a disclaimer to show on single listings. This can be overridden on individual listings.</p>", 'wp-listings' );
					_e('<p><textarea name="plugin_wp_listings_settings[wp_listings_global_disclaimer]" id="wp_listings_global_disclaimer" type="text" value="' . $options['wp_listings_global_disclaimer'] . '" rows="4" style="width: 80%"></textarea></p><hr>', 'wp-listings' );

					_e("<h3>Forms</h3><h4>Google Recaptcha (anti-spam)</h4><p>With the default contact form, you can choose to add Google Recaptcha to prevent spam, or use a form shortcode plugin with anti-spam protection. To use Google Recaptcha, you must first <a href=\"https://www.google.com/recaptcha/admin\">sign up for a key</a>, then enter the site and secret key below:</p>", 'wp-listings' );
					_e('<p>Site key: <input name="plugin_wp_listings_settings[wp_listings_captcha_site_key]" id="wp_listings_captcha_site_key" type="text" value="' . esc_html($options['wp_listings_captcha_site_key']) . '" size="40" /></p>', 'wp-listings');
					_e('<p>Secret key: <input name="plugin_wp_listings_settings[wp_listings_captcha_secret_key]" id="wp_listings_captcha_secret_key" type="text" value="' . esc_html($options['wp_listings_captcha_secret_key']) . '" size="40" /></p><hr>', 'wp-listings');


					_e("<h4>Default Form shortcode</h4><p>If you use a Contact Form plugin, you may enter the form shortcode here to display on all listings. Additionally, each listing can use a custom form. If no shortcode is entered, the template will use a default contact form:</p>", 'wp-listings' );
					_e('<p>Form shortcode: <input name="plugin_wp_listings_settings[wp_listings_default_form]" id="wp_listings_default_form" type="text" value="' . esc_html($options['wp_listings_default_form']) . '" size="40" /></p><hr>', 'wp-listings');



					_e("<h3>Custom Wrapper</h3><p>If your theme's content HTML ID's and Classes are different than the included template, you can enter the HTML of your content wrapper beginning and end:</p>", 'wp-listings' );
					_e('<p><label><input name="plugin_wp_listings_settings[wp_listings_custom_wrapper]" id="wp_listings_custom_wrapper" type="checkbox" value="1" class="code" ' . checked(1, $options['wp_listings_custom_wrapper'], false ) . ' /> Use Custom Wrapper?</p>', 'wp-listings' );
					_e('<p><label>Wrapper Start HTML: </p><input name="plugin_wp_listings_settings[wp_listings_start_wrapper]" id="wp_listings_start_wrapper" type="text" value="' . esc_html($options['wp_listings_start_wrapper']) . '" size="80" /></label>', 'wp-listings' );
					_e('<p><label>Wrapper End HTML: </p><input name="plugin_wp_listings_settings[wp_listings_end_wrapper]" id="wp_listings_end_wrapper" type="text" value="' . esc_html($options['wp_listings_end_wrapper']) . '" size="80" /></label><hr>', 'wp-listings' );


					_e('<h3>Listings slug</h3><p>Optionally change the slug of the listing post type<br /><input type="text" name="plugin_wp_listings_settings[wp_listings_slug]" value="' . $options['wp_listings_slug'] . '" /></p>', 'wp-listings' );
					_e("<em>Don't forget to <a href='../wp-admin/options-permalink.php'>reset your permalinks</a> if you change the slug!</em></p>", 'wp-listings' );

					if(class_exists( 'Idx_Broker_Plugin' )) {
						_e("<hr style=\"margin: 25px 0;\"><h3>IDX Imported Listings</h3><p>These settings apply to any imported IDX listings. Imported listings are updated via the latest API response twice daily.</p>", 'wp-listings' );
						_e("<h2>Update Listings</h2>", 'wp-listings' );
						_e('<div class="idx-import-option update-all"><label><h4>Update All</h4> <span class="dashicons dashicons-update"></span><input name="plugin_wp_listings_settings[wp_listings_idx_update]" id="wp_listings_idx_update" type="radio" value="update-all" class="code" ' . checked('update-all', $options['wp_listings_idx_update'], false ) . ' /> <p>Update all imported fields including gallery and featured image. <br /><em>* Excludes Post Title and Post Content</em></p></label></div>', 'wp-listings' );
						_e('<div class="idx-import-option update-noimage"><label><h4>Update Excluding Images</h4> <span class="dashicons dashicons-update"></span><input name="plugin_wp_listings_settings[wp_listings_idx_update]" id="wp_listings_idx_update" type="radio" value="update-noimage" class="code" ' . checked('update-noimage', $options['wp_listings_idx_update'], false ) . ' /> <p>Update all imported fields, but excluding the gallery and featured image.<br /><em>* Also excludes Post Title and Post Content</em></p></label></div>', 'wp-listings' );
						_e('<div class="idx-import-option update-none"><label><h4>Do Not Update</h4> <span class="dashicons dashicons-dismiss"></span><input name="plugin_wp_listings_settings[wp_listings_idx_update]" id="wp_listings_idx_update" type="radio" value="update-none" class="code" ' . checked('update-none', $options['wp_listings_idx_update'], false ) . ' /> <p><strong>Not recommended as displaying inaccurate MLS data may violate your IDX agreement.</strong><br /> Does not update any fields.<br /><em>* Listing will be changed to sold status if it exists in the sold data feed.</em></p></label></div>', 'wp-listings' );

						_e("<h2>Sold Listings</h2>", 'wp-listings' );
						_e('<div class="idx-import-option sold-keep"><label><h4>Keep All</h4> <span class="dashicons dashicons-admin-post"></span><input name="plugin_wp_listings_settings[wp_listings_idx_sold]" id="wp_listings_idx_sold" type="radio" value="sold-keep" class="code" ' . checked('sold-keep', $options['wp_listings_idx_sold'], false ) . ' /> <p>This will keep all imported listings published with the status changed to reflect as sold.</p></label></div>', 'wp-listings' );
						_e('<div class="idx-import-option sold-draft"><label><h4>Keep as Draft</h4> <span class="dashicons dashicons-hidden"></span><input name="plugin_wp_listings_settings[wp_listings_idx_sold]" id="wp_listings_idx_sold" type="radio" value="sold-draft" class="code" ' . checked('sold-draft', $options['wp_listings_idx_sold'], false ) . ' /> <p>This will keep all imported listings that have been sold, but they will be changed to draft status in WordPress.</p></label></div>', 'wp-listings' );
						_e('<div class="idx-import-option sold-delete"><label><h4>Delete Sold</h4> <span class="dashicons dashicons-trash"></span><input name="plugin_wp_listings_settings[wp_listings_idx_sold]" id="wp_listings_idx_sold" type="radio" value="sold-delete" class="code" ' . checked('sold-delete', $options['wp_listings_idx_sold'], false ) . ' /> <p><strong>Not recommended</strong> <br />This will delete all sold listings and attached featured images from your WordPress database and media library.</p></label></div>', 'wp-listings' );

					}
					?>
					<input name="submit" class="button-primary" type="submit" value="<?php esc_attr_e('Save Settings'); ?>" />
				</form>
			</div>
		</div>
	</div>
</div>
