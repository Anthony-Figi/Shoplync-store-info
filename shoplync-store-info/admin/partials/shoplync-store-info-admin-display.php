<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.anthonygfigueroa.ca
 * @since      1.0.0
 *
 * @package    Shoplync_Store_Info
 * @subpackage Shoplync_Store_Info/admin/partials
 */
?>
<?php 

	//Grab all options
	$options = get_option($this->plugin_name);
	


	// Cleanup
	$topbar_enabled = ( isset($options['topbar']) && !empty($options['topbar']) ) ? $options['topbar'] : 0;
	if(isset($options['topbar']) && !empty($options['topbar'])){ $topbar_enabled = $options['topbar']; }
	if(isset($options['tel']) && !empty($options['tel'])){ $phone = $options['tel']; }
	if(isset($options['email']) && !empty($options['email'])){ $email = $options['email']; }
	
	if(isset($options['link-1']) && !empty($options['link-1'])){ $link_1 = $options['link-1']; }
	if(isset($options['link-2']) && !empty($options['link-2'])){ $link_2 = $options['link-2']; }
	if(isset($options['link-3']) && !empty($options['link-3'])){ $link_3 = $options['link-3']; }
	
	if(isset($options['link-1-name']) && !empty($options['link-1-name'])){ $link_1_name = $options['link-1-name']; }
	if(isset($options['link-2-name']) && !empty($options['link-2-name'])){ $link_2_name = $options['link-2-name']; }
	if(isset($options['link-3-name']) && !empty($options['link-3-name'])){ $link_3_name = $options['link-3-name']; }
	
	if(isset($options['pass-notify']) && !empty($options['pass-notify'])){ $pass_notify = $options['pass-notify']; }
	if(isset($options['new-user-notify']) && !empty($options['new-user-notify'])){ $new_user_notify = $options['new-user-notify']; }
	if(isset($options['user-subscribe-notify']) && !empty($options['user-subscribe-notify'])){ $user_subscribe_notify = $options['user-subscribe-notify']; }
	
	if( isset($options['shoplync-mailing-list']) && !empty($options['shoplync-mailing-list']) ){  
		$show_export = true;
		$number_of_email = count($options['shoplync-mailing-list']);
		
		
		
	}else {
		$show_export = false;
	}
?>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
	
	<div class="notice notice-info inline">
	<p>
		<?php
		printf('You can use the  %1$s shortcode anywhere on the website to output this data. 
		<br> Shortcode accepts an integer (1-3) as a parameter %2$s and will output a different layour acordingly 
		<br><br> %3$s <br> %4$s <br> %5$s <br> %6$s <br> %7$s <br> %8$s <br> %9$s <br> %10$s',
			'<code>[shoplync_contact]</code>',
			'<code>[shoplync_contact type=1]</code>',
			'<code>Usage: </code>',
			'<code>type=1 //Default: Contact info with labels wrapped in span tags</code>',
			'<code>type=2 //Default: Only the email and phone info is wrapped in anchor tags</code>',
			'<code>type=3 //Phone Number Only: Outputs an anchor link</code>',
			'<code>type=4 //Email: Outputs an anchor link</code>',
			'<code>type=5 //Raw: Contact info is displayed as raw text</code>',
			'<code>type=6 //Raw Phone Number: displayed as raw text</code>',
			'<code>type=7 //Raw Email: displayed as raw text</code>'
		);
		?>
	</p>
	</div>	
	
	<form method="post" action="">
	
		<h2>Client Mailing List</h2>
		<fieldset>
			<fieldset>
				<p>Export mailing list <?php if($show_export){ echo '('.$number_of_email.' total emails)'; }?></p>
				<?php 
				if($show_export) {
					submit_button('Export Client List', 'primary','export_mail_list', TRUE); 
				}else {
					echo '<style> #btn-disabled { cursor: not-allowed; } </style>';
					echo '<button class="button button-primary" id="btn-disabled" disabled>Export Client List (empty)</button>';
				}
				?>
			</fieldset>
        </fieldset>
	</form>
	
    <form method="post" name="cleanup_options" action="options.php">
	
	<?php 
	settings_fields($this->plugin_name); 
	do_settings_sections($this->plugin_name);
	?>

        <!-- Edit Store Phone Number / Email -->
		<br><br>
		<h2>Top Header Store Information</h2>
        <fieldset>
			<fieldset>
				<p>Please enter the store phone number that will be displayed at the top of the page.</p>
				<legend class="screen-reader-text"><span><?php _e('Please enter the store phone number', $this->plugin_name); ?></span></legend>
				<input type="tel" class="regular-text" id="<?php echo $this->plugin_name; ?>-store-phone" name="<?php echo $this->plugin_name; ?>[phone_number]" value="<?php if(!empty($phone)) echo $phone; ?>" placeholder="604-111-1234"/>
			</fieldset>
			<fieldset>
				<p>Please enter the store contact email that will be displayed at the top of the page.</p>
				<legend class="screen-reader-text"><span><?php _e('Please enter the store contact email', $this->plugin_name); ?></span></legend>
				<input type="email" class="regular-text" id="<?php echo $this->plugin_name; ?>-store-email" name="<?php echo $this->plugin_name; ?>[contact_email]" value="<?php if(!empty($email)) echo $email; ?>" placeholder="mail@example.com" />
			</fieldset>
			<fieldset>
				<p>Enables/Disables the topbar that displays the email and phone number of the store</p>
				<legend class="screen-reader-text"><span><?php _e('Topbar On/Off', $this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-topbar">
					<span>Enabled: </span>			
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-topbar" name="<?php echo $this->plugin_name; ?>[topbar]" <?php if(empty($topbar_enabled) || $topbar_enabled == '' || (int)$topbar_enabled == 0){ echo 'value="false" '; }else { echo 'value="'.$topbar_enabled.'" checked="checked"'; } ?> >
				</label>
			</fieldset>
        </fieldset>
		
        <!-- SMS Pro Download Links available to clients -->
		<br><br><br>
		<h2>SMS Pro Download Links</h2>
        <fieldset>
			<p>Please enter the download links for SMS Pro that will be available to users in the client area.</p>
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('Please enter the download link', $this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-link-1">
					<span><em>Link 1:</em></span>
					<input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-link-1" name="<?php echo $this->plugin_name; ?>[link-1]" value="<?php if(!empty($link_1)) echo $link_1; ?>"/>
					<input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-link-1-name" name="<?php echo $this->plugin_name; ?>[link-1-name]" value="<?php if(!empty($link_1_name)) echo $link_1_name; ?>" placeholder="filename-4.2.0.exe" />
					<span><em>(Customer Visible Filename)</em> <strong>Latest</strong> </span>
				</label>
			</fieldset>
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('Please enter the download link', $this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-link-2">
					<span><em>Link 2:</em></span>
					<input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-link-2" name="<?php echo $this->plugin_name; ?>[link-2]" value="<?php if(!empty($link_2)) echo $link_2; ?>"/>
					<input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-link-2-name" name="<?php echo $this->plugin_name; ?>[link-2-name]" value="<?php if(!empty($link_2_name)) echo $link_2_name; ?>" placeholder="filename-4.9.9.exe" />
					<span><em>(Customer Visible Filename)</em></span>
				</label>
			</fieldset>
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('Please enter the download link', $this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-link-3">
					<span><em>Link 3:</em></span>
					<input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-link-3" name="<?php echo $this->plugin_name; ?>[link-3]" value="<?php if(!empty($link_3)) echo $link_3; ?>"/>
					<input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-link-3-name" name="<?php echo $this->plugin_name; ?>[link-3-name]" value="<?php if(!empty($link_3_name)) echo $link_3_name; ?>" placeholder="filename-3.2.0.exe" />
					<span><em>(Customer Visible Filename)</em> <strong>Oldest</strong> </span>
				</label>
			</fieldset>
        </fieldset>
		
        <!-- Toggles the admin email on/off -->
		<br><br>
		<h2>Admin Email Alerts</h2>
        <fieldset>
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('User Password Change Notify On/Off', $this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-pass-notify">
					<span>Recieve An Email When Users Change Their Password: </span>			
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-pass-notify" name="<?php echo $this->plugin_name; ?>[pass-notify]" <?php if(empty($pass_notify)  || $pass_notify == '' || (int)$pass_notify == 0){ echo 'value="false" '; }else { echo 'value="'.$pass_notify.'" checked="checked"'; } ?> >
				</label>
			</fieldset>
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('New User Notify On/Off', $this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-new-user-notify">
					<span>Recieve An Email When A User Creates An Account: </span>			
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-new-user-notify" name="<?php echo $this->plugin_name; ?>[new-user-notify]" <?php if(empty($new_user_notify) || $new_user_notify == '' || (int)$new_user_notify == 0){ echo 'value="false" '; }else { echo 'value="'.$new_user_notify.'" checked="checked"'; } ?> >
				</label>
			</fieldset>
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('User Subscribed Notify On/Off', $this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-user-subscribe-notify">
					<span>Recieve An Email When User Subcribes: </span>			
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-user-subscribe-notify" name="<?php echo $this->plugin_name; ?>[user-subscribe-notify]" <?php if(empty($user_subscribe_notify) || $user_subscribe_notify == '' || (int)$user_subscribe_notify == 0){ echo 'value="false" '; }else { echo 'value="'.$user_subscribe_notify.'" checked="checked"'; } ?> >
				</label>
			</fieldset>
        </fieldset>
		
        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>
    </form>

	
</div>

