<?php

/*
 * @param int $user_id The user ID
 * @param string $notify Type of notification that should happen
 * Changes the default wordpres newly-registered user email.
 *
 */
function wp_new_user_notification($user_id, $notify = ''){

	$user = new WP_User($user_id);

    $user_login = stripslashes($user->user_login);
    $user_email = stripslashes($user->user_email);

	//The subject of the email address
    $email_subject = "Welcome To Shoplync!";
	
	//User information for email
	$first_name = get_user_meta( $user_id, 'first_name', true ); 
	$last_name = get_user_meta( $user_id, 'last_name', true ); 
	
	
	// The blogname option is escaped with esc_html() on the way into the database in sanitize_option().
	// We want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

	if ( 'user' !== $notify ) {
		$switched_locale = switch_to_locale( get_locale() );

		/* translators: %s: Site title. */
		$message = sprintf( __( 'New user registration on your site %s:' ), $blogname ) . "\r\n\r\n";
		/* translators: %s: User login. */
		$message .= sprintf( __( 'Username: %s' ), $user->user_login ) . "\r\n\r\n";
		/* translators: %s: User email address. */
		$message .= sprintf( __( 'Email: %s' ), $user->user_email ) . "\r\n";

		$wp_new_user_notification_email_admin = array(
			'to'      => get_option( 'admin_email' ),
			/* translators: New user registration notification email subject. %s: Site title. */
			'subject' => __( '[%s] New User Registration' ),
			'message' => $message,
			'headers' => '',
		);

		/**
		 * Filters the contents of the new user notification email sent to the site admin.
		 *
		 * @since 4.9.0
		 *
		 * @param array   $wp_new_user_notification_email_admin {
		 *     Used to build wp_mail().
		 *
		 *     @type string $to      The intended recipient - site admin email address.
		 *     @type string $subject The subject of the email.
		 *     @type string $message The body of the email.
		 *     @type string $headers The headers of the email.
		 * }
		 * @param WP_User $user     User object for new user.
		 * @param string  $blogname The site title.
		 */
		$wp_new_user_notification_email_admin = apply_filters( 'wp_new_user_notification_email_admin', $wp_new_user_notification_email_admin, $user, $blogname );

		wp_mail(
			$wp_new_user_notification_email_admin['to'],
			wp_specialchars_decode( sprintf( $wp_new_user_notification_email_admin['subject'], $blogname ) ),
			$wp_new_user_notification_email_admin['message'],
			$wp_new_user_notification_email_admin['headers']
		);

		if ( $switched_locale ) {
			restore_previous_locale();
		}
	}

	
	
	//Create password link
	//forked from source 
	//src: https://developer.wordpress.org/reference/functions/wp_new_user_notification/https://developer.wordpress.org/reference/functions/wp_new_user_notification/
	$key = get_password_reset_key( $user );
    if ( is_wp_error( $key ) ) {
        return;
    }
	$create_pass_link = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' );
	
	ob_start();
	
	include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-header.php');
	?>
	
	<div class="title" style="font-family:Noto Sans, sans-serif;font-size:18px;font-weight:600;color:#0094D4;text-align:center;">
		Hello, <?php echo $first_name.' '.$last_name; ?>
	</div>
	<br>

	<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;text-align:center;color:#333333"> 
		Welcome to the Shoplync team! <br><br>
	</div>
	<div class="hr" style="height:1px;border-bottom:2px solid #0094D4;clear: both;">&nbsp;</div><br>
	
	<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:center;"> 
		Your Shoplync Login Credentials
	</div><br>
	
	<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
		<strong>E-mail address: <?php echo $user_email; ?></strong><br><br><strong>Please Follow the link below to set a password: </strong><br>
		<a href="<?php echo $create_pass_link ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Create New Password</a>
		<br><br><br>
		<em>*Your account has been created but will need to be approved before all features are enabled.</em>
	</div>
	
	<?php
	include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-footer.php');	
	
	$message = ob_get_contents();
    ob_end_clean();

    wp_mail($user_email, $email_subject, $message);

}

/*
 * @param WP_User $user The WP_User object containing the updated users data. 
 * Changes the default wordpres admin notify email for password change.
 *
 */	
function wp_password_change_notification($user){
	$options = get_option('shoplync-store-info');
	
	$pass_notify = (isset($options['pass-notify']) && !empty($options['pass-notify']) ? (int)$options['pass-notify'] : 0);

	//If notify is disabled or if account being updated is not an admin
	if($pass_notify && (0 !== strcasecmp($user->user_email, get_option('admin_email'))) ){
		$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		//User information for email
		$first_name = get_user_meta( $user->ID, 'first_name', true ); 
		$last_name = get_user_meta( $user->ID, 'last_name', true ); 
		$user_subscribed = get_user_meta( $user_id, 'user_subscribed', true);
		
		ob_start();
		
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-header.php');
		?>
		
		<div class="title" style="font-family:Noto Sans, sans-serif;font-size:18px;font-weight:600;color:#0094D4;text-align:center;">
			User Account Updated
		</div>
		<br>

		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;text-align:center;color:#333333"> 
			This is an important email regarding <?php echo $blogname; ?> <br><br>
		</div>
		<div class="hr" style="height:1px;border-bottom:2px solid #0094D4;clear: both;">&nbsp;</div><br>
		
		<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:center;"> 
			The Following User Has Updated Their Account Information
		</div><br>
		<?php $approved = get_the_author_meta( 'user-status', $user->ID); ?>
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			<strong style="color:#333333!important;">Full Name: <?php echo $first_name.' '.$last_name; ?> </strong><br><br>
			<strong style="color:#333333!important;">Company Name: <?php echo esc_attr( get_the_author_meta( 'company_name', $user->ID ) ); ?> </strong><br><br>
			<strong style="color:#333333!important;">Company URL: <?php echo esc_attr( get_the_author_meta( 'company_url', $user->ID ) ); ?> </strong><br><br>
			<strong style="color:#333333!important;">E-mail address: <?php echo $user->user_email; ?> </strong><br><br>
			<strong style="color:#333333!important;">E-mail Subscription: <?php if($user_subscribed !== '' && (int)$user_subscribed == 1){ echo '<span style="color:#1ed44e!important;">Subscribed</span>'; }else{ echo '<span style="color:#e31d27!important;">Not Subscribed</span>'; } ?></strong><br><br>
			<strong style="color:#333333!important;">Username: <?php echo $user->user_login; ?> </strong><br><br>
			<strong style="color:#333333!important;">Phone: <?php echo esc_attr( get_the_author_meta( 'phone', $user->ID ) ); ?> </strong><br><br>
			<strong style="color:#333333!important;">User Status: <?php if($approved !== '' && (int)$approved == 1){ echo '<span style="color:#1ed44e!important;">Approved</span>'; }else{ echo '<span style="color:#e31d27!important;">Not Approved</span>'; } ?> </strong><br><br>
			<strong style="color:#333333!important;">User ID: <?php echo $user->ID; ?> </strong><br><br>
			<a href="<?php echo admin_url('user-edit.php?user_id='.$user->ID.'&wp_http_referer=%2Fwp-admin%2Fusers.php'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Edit/View User Information</a><br><br>
		</div>		
		<?php
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-footer-admin.php');	
		
		$message = ob_get_contents();
		ob_end_clean();
		
		
		
		// The blogname option is escaped with esc_html() on the way into the database in sanitize_option().
		// We want to reverse this for the plain text arena of emails.
		

		$wp_password_change_notification_email = array(
			'to'      => get_option( 'admin_email' ),
			/* translators: Password change notification email subject. %s: Site title. */
			'subject' => __( '%s - User Has Changed Their Password' ),
			'message' => $message,
			'headers' => '',
		);

		/**
		 * Filters the contents of the password change notification email sent to the site admin.
		 *
		 * @since 4.9.0
		 *
		 * @param array   $wp_password_change_notification_email {
		 *     Used to build wp_mail().
		 *
		 *     @type string $to      The intended recipient - site admin email address.
		 *     @type string $subject The subject of the email.
		 *     @type string $message The body of the email.
		 *     @type string $headers The headers of the email.
		 * }
		 * @param WP_User $user     User object for user whose password was changed.
		 * @param string  $blogname The site title.
		 */
		$wp_password_change_notification_email = apply_filters( 'wp_password_change_notification_email', $wp_password_change_notification_email, $user, $blogname );

		wp_mail(
			$wp_password_change_notification_email['to'],
			wp_specialchars_decode( sprintf( $wp_password_change_notification_email['subject'], $blogname ) ),
			$wp_password_change_notification_email['message'],
			$wp_password_change_notification_email['headers']
		);		
	}
}
