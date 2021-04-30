<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.anthonygfigueroa.ca
 * @since      1.0.0
 *
 * @package    Shoplync_Store_Info
 * @subpackage Shoplync_Store_Info/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Shoplync_Store_Info
 * @subpackage Shoplync_Store_Info/admin
 * @author     Anthony Figueroa <mail@anthonygfigueroa.ca>
 */
class Shoplync_Store_Info_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->load_dependencies();

	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Shoplync_Store_Info_Loader. Orchestrates the hooks of the plugin.
	 * - Shoplync_Store_Info_i18n. Defines internationalization functionality.
	 * - Shoplync_Store_Info_Admin. Defines all hooks for the admin area.
	 * - Shoplync_Store_Info_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shoplync-store-info-helpers.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Shoplync_Store_Info_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Shoplync_Store_Info_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/shoplync-store-info-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Shoplync_Store_Info_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Shoplync_Store_Info_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/shoplync-store-info-admin.js', array( 'jquery' ), $this->version, false );

	}
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	
	public function add_plugin_admin_menu() {
	
		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		add_menu_page( 'Shoplync Theme Top Bar Store Information', 'Store Information', 'manage_options', $this->plugin_name, array($this, 'show_options_page'), plugin_dir_url( __FILE__ )."/img/settings-ico.png");
	}
	 /**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */

	public function add_action_links( $links ) {
		/*
		*  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		*/
	   $settings_link = array(
		'<a href="' . admin_url( 'admin.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   //https://staging.shoplync.com/wp-admin/admin.php?page=shoplync-store-info
	   return array_merge(  $settings_link, $links );

	}
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	
	public function show_options_page() {
		include_once( 'partials/shoplync-store-info-admin-display.php' );
	}
	
	/**
	 * Add footer link to plugin description in /wp-admin/plugins.php
	 *
	 * @param  array  $plugin_meta All met data to a plugin.
	 * @param  string $plugin_file The main file of the plugin with the meta data.
	 *
	 * @return array
	 */
	public function footer_link( $plugin_links ) {

		echo 'Fueled by <a href="https://www.wordpress.org" target="_blank">WordPress</a> | Designed by <a href="https://anthonygfigueroa.ca/" target="_blank">Anthony Figueroa</a></p>';
	}
	/** 
	 * Saves plugin option
	 */
	public function store_info_update() {
		register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate_store_info'));
	}
	public function export_mail_list(){
		global $plugin_page;
		$options = get_option('shoplync-store-info');
		
		//If dowload csv was selected
		if( $plugin_page == 'shoplync-store-info' && isset($_POST['export_mail_list']) && isset($options['shoplync-mailing-list']) && !empty($options['shoplync-mailing-list']) ){
			$data = 'Shoplync Mailing List'."\n";
			foreach($options['shoplync-mailing-list'] as $email){
				$data .= $email."\n";
			}
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename="shoplync-export-'.date("Y-m-d").'.csv"');
			echo $data; 
			die();
		}
	}
	/** 
	 * Validates store phone number, email address and check the SMS download links to ensure they are not empty
	 */
	public function validate_store_info($input) {
		
		$inputCleaned = array();
		$invalidChars = array('-', '.', '(', ')', ' ');
		
		//Topbar display status enabled/disabled
		if(isset($input['topbar']) && !empty($input['topbar']) ){
			$inputCleaned['topbar'] = 1;
		}else {
			$inputCleaned['email'] = 0;
		}
		//Cleanup Phone Number
		if(isset($input['phone_number']) && !empty($input['phone_number']) ){
			$inputCleaned['tel'] = preg_replace('/\s+/', '', $input['phone_number']); //remove whitespace
			$inputCleaned['tel'] = str_replace($invalidChars,'', $inputCleaned['tel']); // remove - . ( )
			
			if(preg_match('/^[0-9]?[0-9]{10}$/', $inputCleaned['tel'])) {
					//phone number ength verified
					$inputCleaned['tel'] = $input['phone_number'];
			}else {
				$inputCleaned['tel'] = "1.604.325.2252";
			}
		}
		//Cleanup Email
		if(isset($input['contact_email']) && !empty($input['contact_email']) ){
			$sanitize_email = sanitize_email($input['contact_email']);
			$inputCleaned['email'] = (is_email($sanitize_email) ?  $sanitize_email : "sales@baysideperformance.com");
		}else {
			$inputCleaned['email'] = "sales@baysideperformance.com";
		}
		
		//set default links
		$default_link = home_url('/customers/');
		if(!isset($input['link-1']) || empty($input['link-1'])  ){
			$inputCleaned['link-1'] = $default_link;
		}else {
			$inputCleaned['link-1'] = $input['link-1'];
		}
		
		if(!isset($input['link-2']) || empty($input['link-2']) ){
			$inputCleaned['link-2'] = $default_link;
		}else {
			$inputCleaned['link-2'] = $input['link-2'];
		}
		
		if(!isset($input['link-3']) || empty($input['link-3']) ){
			$inputCleaned['link-3'] = $default_link;
		}else {
			$inputCleaned['link-3'] = $input['link-3'];
		}
		
		$inputCleaned['link-1-name'] = $input['link-1-name'];
		$inputCleaned['link-2-name'] = $input['link-2-name'];
		$inputCleaned['link-3-name'] = $input['link-3-name'];
		
		
		if(!isset($input['pass-notify']) || empty($input['pass-notify']) ){
			$inputCleaned['pass-notify'] = 0;
		}else {
			$inputCleaned['pass-notify'] = 1;
		}
		
		if(!isset($input['new-user-notify']) || empty($input['new-user-notify']) ){
			$inputCleaned['new-user-notify'] = 0;
		}else {
			$inputCleaned['new-user-notify'] = 1;
		}
		
		if(!isset($input['user-subscribe-notify']) || empty($input['user-subscribe-notify']) ){
			$inputCleaned['user-subscribe-notify'] = 0;
		}else {
			$inputCleaned['user-subscribe-notify'] = 1;
		}
		
		//generates a mailing list array if it doesn't exists
		$options = get_option($this->plugin_name);	
		if( !isset($options['shoplync-mailing-list']) || empty($options['shoplync-mailing-list']) ){
			$mailing_list = array();
			array_push($mailing_list, get_bloginfo('admin_email'));//add admin email to mailing list
			
			$inputCleaned['shoplync-mailing-list'] = $mailing_list; //adds the mailing list array
		}else {
			$inputCleaned['shoplync-mailing-list'] = $options['shoplync-mailing-list'];
		}
		
		return $inputCleaned;
	}
	
	
	/*
	 * Allows us to input a users company name, company website and phone number
	 * under their profile information.
	 */
	public function user_extra_fields( $user ){
		$user_exists = ((!empty($user) && isset($user->ID)) ? true : false);
	?>
		<h3>SMS User Details</h3>
		<table class="form-table">
			<tr>
			<th><label for="sms-company">Company Name:</label></th>
			<td>
				<input type="text" name="sms-company" id="sms-company" value="<?php if($user_exists){echo esc_attr( get_the_author_meta( 'company_name', $user->ID ) ); } ?>" class="regular-text" required /><br />
				<span class="description">Please enter the name of the company this client belongs to. (Required)</span>
			</td>
			</tr>
			<tr>
			<th><label for="sms-company-url">Company Website:</label></th>
			<td>
				<input type="text" placeholder="https://example.ca" name="sms-company-url" id="sms-company-url" value="<?php if($user_exists){echo esc_attr( get_the_author_meta( 'company_url', $user->ID ) ); } ?>" class="regular-text" /><br />
				<span class="description">Please enter the clients company website URL. (Optional)</span>
			</td>
			</tr>
			<tr>
			<th><label for="sms-company-phone">Phone Number:</label></th>
			<td>
				<input type="tel" name="sms-company-phone" id="sms-company-phone" value="<?php if($user_exists){echo esc_attr( get_the_author_meta( 'phone', $user->ID ) ); } ?>" class="regular-text" required /><br />
				<span class="description">Please enter the clients main phone number. (Required)</span>
			</td>
			</tr>
		</table>
	<?php
	}
	/*
	 * Allows us to input a users company name, company website and phone number
	 * under their profile information.
	 */
	public function save_user_extra_fields($user_id){
		if ( !current_user_can( 'edit_user', $user_id ) ) { 
			return false; //Does not have permission to edit users information
		}
		
		if ( !empty($_POST['sms-company']) ){//Set default username if non has been entered
			update_user_meta( $user_id, 'company_name', $_POST['sms-company'] );
		}
		if ( !empty($_POST['sms-company-url']) ){//Set default username if non has been entered
			update_user_meta( $user_id, 'company_url', $_POST['sms-company-url'] );
		}
		if ( !empty($_POST['sms-company-phone']) ){//Set default username if non has been entered
			update_user_meta( $user_id, 'phone', $_POST['sms-company-phone'] );
		}
	}
	/*
	 * Allow us to put in a user login credential to access SMS Pro
	 */	
	public function user_sms_login( $user ){
		//implement html code that will show the input field for admins to input login credentials
		$user_exists = ((!empty($user) && isset($user->ID)) ? true : false);
		?>
		<h3>SMS User Login Credentials</h3>
		<table class="form-table">
			<tr>
			<th><label for="sms-username">Username:</label></th>
			<td>
				<input type="text" name="sms-username" id="sms-username" value="<?php if($user_exists){echo esc_attr( get_the_author_meta( 'sms-username', $user->ID ) ); } ?>" class="regular-text" /><br />
				<span class="description">Please enter the clients SMS username. (If left blank will automatically use the clients email)</span>
			</td>
			</tr>
			<tr>
			<th><label for="sms-password">Password:</label></th>
			<td>
				<input type="text" name="sms-password" id="sms-password" value="<?php if($user_exists){echo esc_attr( get_the_author_meta( 'sms-password', $user->ID ) ); } ?>" class="regular-text" /><br />
				<span class="description">Please enter the clients SMS password. (If left blank will automatically use generated password)</span>
			</td>
			</tr>
			<tr>
			<th><label for="sms-random-pass"></label></th>
			<td>
				<input type="text" name="sms-random-pass" id="sms-random-pass" value="<?php echo Shoplync_Store_Info_Helpers::random_str(10); ?>" class="regular-text disabled" /><br />
				<span class="description">Randomly generated password.</span>
			</td>
			</tr>
		</table>
	<?php }
	/*
	 * Allows us to save the users sms login
	 *
	 * @param int $user_id      the id of the user
	 */
	public function save_user_sms_login($user_id){
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
			return; //Core data missing
		}
		
		if ( !current_user_can( 'edit_user', $user_id ) ) { 
			return false; //Does not have permission to edit users information
		}
		
		if ( empty($_POST['sms-username']) ){//Set default username if non has been entered
			$user_info = get_userdata($user_id);
			$email = $user_info->user_email;
			update_user_meta( $user_id, 'sms-username', $email );
		}else {
			update_user_meta( $user_id, 'sms-username', $_POST['sms-username'] );			
		}
		
		if ( empty($_POST['sms-password']) ) {//Uses the generated password if non has been entered
			update_user_meta( $user_id, 'sms-password', $_POST['sms-random-pass'] );
		}else {
			update_user_meta( $user_id, 'sms-password', $_POST['sms-password'] );	
		}
	}
	/*
	 * Allows admin to approve or deny users via check box
	 */	
	public function user_status( $user ){
	$user_exists = ((!empty($user) && isset($user->ID)) ? true : false);
	?>
		<h3>User Status</h3>
		<table class="form-table">
			<tr>
			<th><label for="user-status">Approved:</label></th>
			<td>
				<?php if($user_exists){$approved = get_the_author_meta( 'user-status', $user->ID); }else{ $approved = 0; } ?>
				<?php if($approved !== '' && (int)$approved == 1): ?>
				<input type="checkbox" name="user-status" id="user-status" value="<?php echo $approved; ?>" checked="checked">
				<?php else: ?>
				<input type="checkbox" name="user-status" id="user-status">
				<?php endif ?>
				<span class="description">Whether the user is approved for accessing Shoplync client area.</span>
			</td>
			</tr>
			<tr>
			<th><label for="user-status">Subscribed To Mailing List:</label></th>
			<td>
				<?php if($user_exists){$user_subscribed = get_the_author_meta( 'user_subscribed', $user->ID); }else{ $user_subscribed = 0; } ?>
				<?php if($user_subscribed !== '' && (int)$user_subscribed == 1): ?>
				<input type="checkbox" name="user-subscribed" id="user-subscribed" value="<?php echo $user_subscribed; ?>" checked="checked">
				<?php else: ?>
				<input type="checkbox" name="user-subscribed" id="user-subscribed">
				<?php endif ?>
				<span class="description">The user would like to receive news and updates.</span>
			</td>
			</tr>
		</table>
	<?php }
	/*
	 * Allows us to save the users sms login
	 *
	 * @param int $user_id      the id of the user
	 */
	public function save_user_status($user_id){
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
			return; //Core data missing
		}
		
		if ( !current_user_can( 'edit_user', $user_id ) ) { 
			return false; //Does not have permission to edit users information
		}
		
		//if admin set to true
		$is_admin = in_array('administrator',  get_userdata($user_id)->roles);
		if ($is_admin){
			update_user_meta( $user_id, 'user-status', 1 );
			update_user_meta( $user_id, 'user_subscribed', 1 );
		}
		
		
		if ( !$is_admin && isset($_POST['user-subscribed']) && (int)$_POST['user-subscribed'] != 1) {//Check if checkbox is set
			update_user_meta( $user_id, 'user_subscribed', 1 ); //user is in mailing list
		}else if( !$is_admin && !isset($_POST['user-subscribed']) ){
			update_user_meta( $user_id, 'user_subscribed', 0 ); //user is removed from mailing list
			
			$this->remove_user_from_mailing_list($user_id);
		}		
		
		if ( !$is_admin && isset($_POST['user-status']) && (int)$_POST['user-status'] != 1) {//Check if checkbox is set
			update_user_meta( $user_id, 'user-status', 1 );
			
			$this->user_status_notify($user_id);//send an email to the user that they are now appoved
		}else if( !$is_admin && !isset($_POST['user-status']) ){
			update_user_meta( $user_id, 'user-status', 0 );
		}
	}
	public function user_status_notify($user_id){
		$user = get_userdata($user_id);
		$email_subject = 'Shoplync - Your Account Has Been Approved!';
		$first_name = get_user_meta( $user_id, 'first_name', true ); 
		$last_name = get_user_meta( $user_id, 'last_name', true ); 
		
		ob_start();
		
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-header.php');
		?>
		
		<div class="title" style="font-family:Noto Sans, sans-serif;font-size:18px;font-weight:600;color:#0094D4;text-align:center;">
			Hello, <?php echo $first_name.' '.$last_name; ?>
		</div>
		<br>

		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;text-align:center;color:#333333"> 
			Get ready to <span style="color:#0094D4">supercharge</span> your business! <br><br>
		</div>
		<div class="hr" style="height:1px;border-bottom:2px solid #0094D4;clear: both;">&nbsp;</div><br>
		
		<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:center;"> 
			Your Account Status
		</div><br>
		
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			<strong>Your Account Has Been Approved!</strong><br><br>
			<a href="<?php echo home_url('/customers/'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Login To You Account</a>
			<br><br><br>
			<em>*When your account is approved you get instant access to your Shoplync product subscriptions and instant downloads.</em>
		</div>
		
		<?php
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-footer.php');	
		
		$message = ob_get_contents();
		ob_end_clean();

		wp_mail($user->user_email, $email_subject, $message);
		
	}
	/*
	 * Add column to manage user roles
	 */	
	public function user_status_table_column( $column ){
		$column['user-status'] = 'Approved';
		return $column;
	}
	/*
	 * Allow admin to edit manage user approved status in admin panel
	 */	
	public function user_status_table_row( $val, $column_name, $user_id ) {
		if ($column_name == 'user-status'){
			$disabled = (in_array('administrator',  get_userdata($user_id)->roles) ? ' disabled style="cursor:not-allowed;"' : '');
			$approved = get_the_author_meta( 'user-status', $user_id);
			if($approved !== '' && (int)$approved == 1){
				return '<input type="checkbox" name="user-status" id="user-status" value="'.$approved.'" checked="checked"'.$disabled.' style="cursor:not-allowed;">';
			}else {
				return '<input type="checkbox" name="user-status" id="user-status" value="false"'.$disabled.' style="cursor:not-allowed;">';
			}
		}
		return $val;
	}
	/*
	 * @param integer $user_id The id assigned to the newly created user
	 * Once a user is registered they will automatically be added to mailing list.
	 *
	 */
	public function save_user_to_mailinglist( $user_id ){
		$user_data = get_userdata($user_id);
		$options = get_option($this->plugin_name);
		
		update_user_meta( $user_id, 'user_subscribed', 1 );
		
		$this->add_email_to_mailing_list($user_data->user_email, $options);
		
	}
	public function remove_user_from_mailing_list ( $user_id ){
		$user_data = get_userdata($user_id);
		
		$this->remove_email_from_mailing_list($user_data->user_email);
	}
	/*
	 * @param string $original_email The default email address used by WP
	 * Changes the default wordpres@yourdomain.com outgoing email address
	 *
	 */
	public function change_wp_email_name_outgoing( $original_name){
		// The $original_email_address value is wordpress@yourdomain.tld
		// So we just change 'wordpress@' to something else like 'noreply@' or etc
		return 'Shoplync Inc.';
	}
	//add_filter( 'wp_mail_from_name', 'change_wp_email_name_outgoing' );


	/*
	 * @param string $original_email The default email address used by WP
	 * Changes the default wordpres@yourdomain.com outgoing email address
	 *
	 */
	public function change_wp_email_outgoing( $original_email ){
		// The $original_email_address value is wordpress@yourdomain.tld
		// So we just change 'wordpress@' to something else like 'noreply@' or etc
		return str_replace( 'wordpress@', 'do-not-reply@', $original_email );
	}
	//add_filter( 'wp_mail_from', 'change_wp_email_outgoing' );

	/*
	 * Changes the default wordpress email type to use HTML
	 *
	 */
	public function change_wp_email_content_type(){
		// The $original_email_address value is wordpress@yourdomain.tld
		// So we just change 'wordpress@' to something else like 'noreply@' or etc
		return "text/html";
	}
	//add_filter( 'wp_mail_content_type', 'change_wp_email_content_type' );
	
	/*
	 * Changes the default wordpress password reset subject line
	 *
	 */
	public function change_retrieve_password_subject(){
		return "You Requested A Password Change";
	}
	
	/*
	 * @param string $content The original email message
	 * @param string $key The activation key used in the login url
	 * @param string $user_login The username for the user
	 * @param WP_User $user_data An object with important user information
	 * Changes the default wordpress password reset 
	 * message body
	 *
	 */
	public function change_retrieve_password_message($content, $key, $user_login, $user_data){
		
		//User information for email
		$first_name = get_user_meta( $user_data->ID, 'first_name', true ); 
		$last_name = get_user_meta( $user_data->ID, 'last_name', true ); 
		
		$create_pass_link = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' );
		
		ob_start();
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-header.php');
		?>
		
		<div class="title" style="font-family:Noto Sans, sans-serif;font-size:18px;font-weight:600;color:#0094D4;text-align:center;">
			Hello, <?php echo $first_name.' '.$last_name; ?>
		</div>
		<br>

		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;text-align:center;color:#333333"> 
			We have received a password reset request for your account. <br><br>
		</div>
		<div class="hr" style="height:1px;border-bottom:2px solid #0094D4;clear: both;">&nbsp;</div><br>
		
		<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:center;"> 
			Please Follow the link below to set a password
		</div><br>
		
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			<a href="<?php echo $create_pass_link; ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Reset Password</a>
			<br><br><br><em>*If your account has not been approved, you will not have access to all account features.</em>
		</div>
		
		<?php
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-footer.php');	
		
		$message = ob_get_contents();
		ob_end_clean();

		return $message;

	}
	
	
	public function change_admin_new_user_notify($notification_array, $user, $blogname){
		$options = get_option('shoplync-store-info');
		
		$new_user_notify = ( isset($options['new-user-notify']) && !empty($options['new-user-notify']) ? (int)$options['new-user-notify'] : 0 );

		//If notify is disabled or if account being updated is not an admin
		if($new_user_notify){
			//User information for email
			$first_name = get_user_meta( $user->ID, 'first_name', true ); 
			$last_name = get_user_meta( $user->ID, 'last_name', true ); 
			$user_subscribed = get_user_meta( $user_id, 'user_subscribed', true);
			ob_start();
			
			include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-header.php');
			?>
			
			<div class="title" style="font-family:Noto Sans, sans-serif;font-size:18px;font-weight:600;color:#0094D4;text-align:center;">
				New User Registration
			</div>
			<br>

			<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;text-align:center;color:#333333"> 
				This is an important email regarding <?php echo $blogname; ?> <br><br>
			</div>
			<div class="hr" style="height:1px;border-bottom:2px solid #0094D4;clear: both;">&nbsp;</div><br>
			
			<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:center;"> 
				The Following User Has Successfully Registered For An Account
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
				<?php if($approved == '' || (int)$approved == 0): ?>
				<a href="<?php echo admin_url('user-edit.php?user_id='.$user->ID.'&wp_http_referer=%2Fwp-admin%2Fusers.php#user-status'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Approve User</a><br><br>
				<?php endif; ?>
				<a href="<?php echo admin_url('user-edit.php?user_id='.$user->ID.'&wp_http_referer=%2Fwp-admin%2Fusers.php'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Edit/View User Information</a><br><br>
				<a href="mailto:<?php echo $user->user_email; ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Contact User Directly</a><br><br>
			</div>		
			<?php
			include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-footer-admin.php');	
			
			$message = ob_get_contents();
			ob_end_clean();
			
			
			$notification_array['message'] = $message;
			$notification_array['subject'] = '%s - New User Registration';
			
			return $notification_array;
		
		}
		
	}
	/*
	 * @param WPCF7_object $contact_form The object which containes the posted form data
	 * Changes the WP contact form 7 default message to user shoplync custom emails
	 *
	 */
	public function change_wpcf7_send_mail($contact_form){
		if ( !isset($contact_form->posted_data) && class_exists('WPCF7_Submission') ) {
			$submission = WPCF7_Submission::get_instance();
			if ( $submission ) {
				$formData = $submission->get_posted_data();     
			}
		} else {
			// We can't retrieve the form data
			return $contact_form;
		}

		if ( 'Above Footer Contact Newsletter' == $contact_form->title() ) {   
			/*$name = $formData['your-name'];     
			$email = $formData['your-email'];   
			$company = $formData['your-company'];   
			$title = $formData['your-title'];   
			$location = $formData['your-location']; */
			$user_email = $formData['email-above-footer'];
			
			$options = get_option($this->plugin_name);
			$subscriber_user_notify = ( isset($options['user-subscribe-notify']) && !empty($options['user-subscribe-notify']) ? (int)$options['user-subscribe-notify'] : 0 );
			
			
			//Admin email 
			$mail = $contact_form->prop( 'mail' ); // returns array with mail values
			if($subscriber_user_notify) {
				$mail['body'] = $this->admin_newsletter_notify($user_email);
			}else {
				$mail['recipient'] = 'do-not-reply@shoplync.com';
			}
			
			
			//User Subscriber Email
			$mail2 = $contact_form->prop('mail_2'); // returns array with mail values
			if(in_array($user_email, $options['shoplync-mailing-list'])){
				$mail2['recipient'] = 'do-not-reply@shoplync.com';//already on list do nothing
				$contact_form->skip_mail = true;
			}else {
				$mail2['body'] = $this->user_newsletter_notify($user_email);
				//add email to array list	
				$this->add_email_to_mailing_list($user_email, $options);
			}
			// Save the email body
			$contact_form->set_properties(array("mail" => $mail,"mail_2" => $mail2));
			
		} else if ( 'Before Footer Contact' == $contact_form->title() ||  'Contact Us Page' == $contact_form->title()) {   
			$user_email = $formData['your-email'];
			$user_data = array(
				'full_name' => $formData['your-name'],    
				'customer_message' => $formData['your-message'],
				'subject_type' => $formData['subject-type'][0],
				'user_email' => $user_email,
			); 	
			
			//Admin email 
			$mail = $contact_form->prop( 'mail' ); // returns array with mail values
			$mail['body'] = $this->admin_contact_us_notify($user_data);


			//User Subscriber Email
			$mail2 = $contact_form->prop('mail_2'); // returns array with mail values
			$mail2['body'] = $this->user_contact_us_notify($user_data);
			
			// Save the email body
			$contact_form->set_properties(array("mail" => $mail,"mail_2" => $mail2));
			
		}else {
			$contact_form->set_properties( array( 'mail' => $mail ) );					
		}



		return $contact_form;
	}
	/*
	 * @param string $user_email The object which containes the posted form data
	 * Adds email to shoplync options db
	 *
	 */
	public function add_email_to_mailing_list($user_email, $options ){
		//if not set, create the options and set default values
		if( !isset($options['shoplync-mailing-list']) || empty($options['shoplync-mailing-list']) ){
			$options = get_option($this->plugin_name);
			$mailing_list = array();
			array_push($mailing_list, get_bloginfo('admin_email'));//add admin email to mailing list
			
			$options['shoplync-mailing-list'] = $mailing_list; //adds the mailing list array
		}
		
		array_push($options['shoplync-mailing-list'], $user_email);//add email to mailing list
		update_option($this->plugin_name, $options);
	}
	/*
	 * @param string $new_user_email The object which containes the new user email
	 * @param string $old_user_email The object which containes the old user email
	 * @param array string $options The options
	 * Adds email to shoplync options db
	 *
	 */
	public function remove_email_from_mailing_list($old_user_email){
		//if not set, create the options and set default values
		$options = get_option($this->plugin_name);
		if( isset($options['shoplync-mailing-list']) && !empty($options['shoplync-mailing-list']) ){
			$options['shoplync-mailing-list'] = array_values( array_diff( $options['shoplync-mailing-list'], array($old_user_email) ) );//remove email from mailing list
			update_option($this->plugin_name, $options);
		}
		
	}
	/*
	 * @param string $user_email The object which containes the posted form data
	 * Changes the WP contact form 7 default message for administators
	 *
	 */
	public function admin_newsletter_notify($user_email){
		
		ob_start();
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-header.php');
		?>
		
		<div class="title" style="font-family:Noto Sans, sans-serif;font-size:18px;font-weight:600;color:#0094D4;text-align:center;">
			New Email Subscribtion
		</div>
		<br>

		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;text-align:center;color:#333333"> 
			A user has requested to be added to the mailing list. <br><br>
		</div>
		<div class="hr" style="height:1px;border-bottom:2px solid #0094D4;clear: both;">&nbsp;</div><br>
		<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:center;"> 
			The Following User Has Been Added To The Mailing List
		</div><br>
		<?php if( username_exists($user_email) || email_exists($user_email) ): ?>
		<?php 
		$user = get_user_by( 'email', $user_email );
		//User information for email
		$first_name = get_user_meta( $user->ID, 'first_name', true ); 
		$last_name = get_user_meta( $user->ID, 'last_name', true ); 
		$user_subscribed = get_user_meta( $user_id, 'user_subscribed', true);
		?>
		<?php $approved = get_the_author_meta( 'user-status', $user->ID); ?>
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			<strong style="color:#333333!important;">Full Name: <?php echo $first_name.' '.$last_name; ?> </strong><br><br>
			<strong style="color:#333333!important;">Company Name: <?php echo esc_attr( get_the_author_meta( 'company_name', $user->ID ) ); ?> </strong><br><br>
			<strong style="color:#333333!important;">Company URL: <?php echo esc_attr( get_the_author_meta( 'company_url', $user->ID ) ); ?> </strong><br><br>
			<strong style="color:#333333!important;">E-mail address: <?php echo $user->user_email; ?> </strong><br><br>
			<strong style="color:#333333!important;">Username: <?php echo $user->user_login; ?> </strong><br><br>
			<strong style="color:#333333!important;">E-mail Subscription: <?php if($user_subscribed !== '' && (int)$user_subscribed == 1){ echo '<span style="color:#1ed44e!important;">Subscribed</span>'; }else{ echo '<span style="color:#e31d27!important;">Not Subscribed</span>'; } ?></strong><br><br>
			<strong style="color:#333333!important;">Phone: <?php echo esc_attr( get_the_author_meta( 'phone', $user->ID ) ); ?> </strong><br><br>
			<strong style="color:#333333!important;">User Status: <?php if($approved !== '' && (int)$approved == 1){ echo '<span style="color:#1ed44e!important;">Approved</span>'; }else{ echo '<span style="color:#e31d27!important;">Not Approved</span>'; } ?> </strong><br><br>
			<strong style="color:#333333!important;">User ID: <?php echo $user->ID; ?> </strong><br><br>
			<?php if($approved == '' || (int)$approved == 0): ?>
			<a href="<?php echo admin_url('user-edit.php?user_id='.$user->ID.'&wp_http_referer=%2Fwp-admin%2Fusers.php#user-status'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Approve User</a><br><br>
			<?php endif; ?>
			<a href="<?php echo admin_url('user-edit.php?user_id='.$user->ID.'&wp_http_referer=%2Fwp-admin%2Fusers.php'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Edit/View User Information</a><br><br>
			<a href="mailto:<?php echo $user_email; ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Contact User Directly</a><br><br>
		</div>	
		<?php else: ?>
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			<strong style="color:#333333!important;">E-mail address: <?php echo $user_email; ?> </strong><br><br>
			<strong style="color:#333333!important;">User Status: <?php echo '<span style="color:#ff9a52!important;">Not Registered</span>'; ?> </strong><br><br>
		</div>
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">		
			<a href="mailto:<?php echo $user_email; ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Contact User Directly</a><br><br>
		</div>
		<?php endif ?>
		
		<?php
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-footer-admin.php');	
		
		$message = ob_get_contents();
		ob_end_clean();
		
		return $message;
		
	}
	/*
	 * @param string $user_email The object which containes the posted form data
	 * Changes the WP contact form 7 default message to user shoplync custom emails
	 *
	 */
	public function user_newsletter_notify($user_email){
		
		ob_start();
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-header.php');
		?>
		
		<div class="title" style="font-family:Noto Sans, sans-serif;font-size:18px;font-weight:600;color:#0094D4;text-align:center;">
			Email Subscribtion
		</div>
		<br>

		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;text-align:center;color:#333333"> 
			Your have chosen to subscribe to our newsletter and important company updates. <br><br>
		</div>
		<div class="hr" style="height:1px;border-bottom:2px solid #0094D4;clear: both;">&nbsp;</div><br>
		
		<?php if( username_exists($user_email) || email_exists($user_email) ): ?>
		<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:center;"> 
			Log Into Your Shoplync Account
		</div><br>
		
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			<a href="<?php echo home_url('/customers/'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">My Account</a>
			<br><br><br>
			<em>*Your email has been added to our mailing list, if you wish to no longer recieve email updates please 
			<a href="<?php echo home_url('/contact/'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Contact Us</a>.</em>
		</div>
		<?php else: ?>
		<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:center;"> 
			Create A Shoplync Account
		</div><br>
		
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			<strong style="color:#333333!important;">E-mail address: <?php echo $user_email; ?> </strong><br><br>
			<strong style="color:#333333!important;">User Status: <?php echo '<span style="color:#ff9a52!important;">Not Registered</span>'; ?> </strong><br><br>
			<a href="<?php echo home_url('/customers/?login=register'); echo '&email='.$user_email; ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Create An Account</a><br><br>
			<p style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			With a Shoplync account you'll have instant access to all our products along with instant software downloads. 
			<a href="<?php echo home_url('/our-products/'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;"> Learn More</a>
			</p>
			<br>
			<em>*Your email has been added to our mailing list, if you wish to no longer recieve email updates please 
			<a href="<?php echo home_url('/contact/'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Contact Us</a>.</em>
		</div>
		<?php endif ?>
		
		<?php
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-footer.php');	
		
		$message = ob_get_contents();
		ob_end_clean();
		
		return $message;
		
	}
	/*
	 * @param array string $user_data The users data
	 * Changes the WP contact form 7 default message to user shoplync custom emails
	 *
	 */
	public function admin_contact_us_notify($user_data){
		$user_email = $user_data['user_email'];
		
		ob_start();
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-header.php');
		?>
		
		<div class="title" style="font-family:Noto Sans, sans-serif;font-size:18px;font-weight:600;color:#0094D4;text-align:center;">
			<?php echo $user_data['subject_type']; ?> - New Message From Customer
		</div>
		<br>
		<div class="hr" style="height:1px;border-bottom:2px solid #0094D4;clear: both;">&nbsp;</div><br>
		<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:center;"> 
		  Customer Information
		</div><br>
		<?php if( username_exists($user_email) || email_exists($user_email) ): ?>
		<?php 
		$user = get_user_by( 'email', $user_email );
		//User information for email
		$first_name = get_user_meta( $user->ID, 'first_name', true ); 
		$last_name = get_user_meta( $user->ID, 'last_name', true ); 
		$user_subscribed = get_user_meta( $user_id, 'user_subscribed', true);
		?>
		<?php $approved = get_the_author_meta( 'user-status', $user->ID); ?>
			
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			<strong style="color:#333333!important;">Full Name: <?php echo $first_name.' '.$last_name; ?> </strong><br><br>
			<strong style="color:#333333!important;">Company Name: <?php echo esc_attr( get_the_author_meta( 'company_name', $user->ID ) ); ?> </strong><br><br>
			<strong style="color:#333333!important;">Company URL: <?php echo esc_attr( get_the_author_meta( 'company_url', $user->ID ) ); ?> </strong><br><br>
			<strong style="color:#333333!important;">E-mail address: <?php echo $user->user_email; ?> </strong><br><br>
			<strong style="color:#333333!important;">Username: <?php echo $user->user_login; ?> </strong><br><br>
			<strong style="color:#333333!important;">Phone: <?php echo esc_attr( get_the_author_meta( 'phone', $user->ID ) ); ?> </strong><br><br>
			<strong style="color:#333333!important;">E-mail Subscription: <?php if($user_subscribed !== '' && (int)$user_subscribed == 1){ echo '<span style="color:#1ed44e!important;">Subscribed</span>'; }else{ echo '<span style="color:#e31d27!important;">Not Subscribed</span>'; } ?></strong><br><br>
			<strong style="color:#333333!important;">User Status: <?php if($approved !== '' && (int)$approved == 1){ echo '<span style="color:#1ed44e!important;">Approved</span>'; }else{ echo '<span style="color:#e31d27!important;">Not Approved</span>'; } ?> </strong><br><br>
			<strong style="color:#333333!important;">User ID: <?php echo $user->ID; ?> </strong><br><br>
			<?php if($approved == '' || (int)$approved == 0): ?>
			<a href="<?php echo admin_url('user-edit.php?user_id='.$user->ID.'&wp_http_referer=%2Fwp-admin%2Fusers.php#user-status'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Approve User</a><br><br>
			<?php endif; ?>
			<a href="<?php echo admin_url('user-edit.php?user_id='.$user->ID.'&wp_http_referer=%2Fwp-admin%2Fusers.php'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Edit/View User Information</a><br><br>
		</div>
		<?php else: ?>
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			<strong style="color:#333333!important;">Full Name: <?php echo $user_data['full_name']; ?> </strong><br><br>
			<strong style="color:#333333!important;">E-mail address: <?php echo $user_email; ?> </strong><br><br>
			<strong style="color:#333333!important;">User Status: <?php echo '<span style="color:#ff9a52!important;">Not Registered</span>'; ?> </strong><br><br>
		</div>	
		<?php endif ?>
		<div class="hr" style="height:1px;border-bottom:2px solid #0094D4;clear: both;">&nbsp;</div><br>
		<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:left;"> 
			Message:
		</div><br>
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;"> 
			<?php echo $user_data['customer_message']; ?><br><br>
			<a href="mailto:<?php echo $user_email; ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Contact User Directly</a><br><br>
		</div>

		<?php
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-footer-admin.php');	
		
		$message = ob_get_contents();
		ob_end_clean();
		
		return $message;
		
	}
	/*
	 * @param array string $user_data The users data
	 * Changes the WP contact form 7 default message to user shoplync custom emails
	 *
	 */
	public function user_contact_us_notify($user_data){
		$user_email = $user_data['user_email'];
		
		ob_start();
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-header.php');
		?>
		
		<div class="title" style="font-family:Noto Sans, sans-serif;font-size:18px;font-weight:600;color:#0094D4;text-align:center;">
			<?php echo $user_data['subject_type']; ?> - Message Received
		</div><br>
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;text-align:center;color:#333333"> 
			Thank you for your message. One of our <?php echo strtolower( explode(' ', trim( $user_data['subject_type'] ) )[0] ); ?> representatives will email you back as soon as possible. <br><br>
		</div>
		<div class="hr" style="height:1px;border-bottom:2px solid #0094D4;clear: both;">&nbsp;</div><br>
		<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:center;"> 
		  
		</div><br>
		<?php if( username_exists($user_email) || email_exists($user_email) ): ?>
		<?php 
		$user = get_user_by( 'email', $user_email );
		//User information for email
		$first_name = get_user_meta( $user->ID, 'first_name', true ); 
		$last_name = get_user_meta( $user->ID, 'last_name', true ); 
		$user_subscribed = get_user_meta( $user_id, 'user_subscribed', true);
		?>
		<?php $approved = get_the_author_meta( 'user-status', $user->ID); ?>
		<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:center;"> 
			Log Into Your Shoplync Account
		</div><br>
		
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			<strong style="color:#333333!important;">Full Name: <?php echo $first_name.' '.$last_name; ?> </strong><br><br>
			<strong style="color:#333333!important;">Company Name: <?php echo esc_attr( get_the_author_meta( 'company_name', $user->ID ) ); ?> </strong><br><br>
			<strong style="color:#333333!important;">E-mail address: <?php echo $user->user_email; ?> </strong><br><br>
			<strong style="color:#333333!important;">E-mail Subscription: <?php if($user_subscribed !== '' && (int)$user_subscribed == 1){ echo '<span style="color:#1ed44e!important;">Subscribed</span>'; }else{ echo '<span style="color:#e31d27!important;">Not Subscribed</span>'; } ?></strong><br><br>
			<strong style="color:#333333!important;">User Status: <?php if($approved !== '' && (int)$approved == 1){ echo '<span style="color:#1ed44e!important;">Approved</span>'; }else{ echo '<span style="color:#e31d27!important;">Not Approved</span>'; } ?> </strong><br><br>
			<a href="<?php echo home_url('/customers/'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">My Account</a>
			<br><br>
		</div>			
		<?php else: ?>
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			<strong style="color:#333333!important;">Full Name: <?php echo $user_data['full_name']; ?> </strong><br><br>
			<strong style="color:#333333!important;">E-mail address: <?php echo $user_email; ?> </strong><br><br>
			<strong style="color:#333333!important;">User Status: <?php echo '<span style="color:#ff9a52!important;">Not Registered</span>'; ?> </strong><br><br>
			<?php  $full_name = explode(' ', trim( $user_data['full_name'] ) );?>
			<a href="<?php echo home_url('/customers/?login=register'); echo '&email='.$user_email.'&first_name='.$full_name[0].'&last_name='.$full_name[1]; ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Create An Account</a><br><br>
		</div>	
		<?php endif ?>
		<div class="hr" style="height:1px;border-bottom:2px solid #0094D4;clear: both;">&nbsp;</div><br>
		<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:left;"> 
			Your Message:
		</div><br>
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;"> 
			<?php echo $user_data['customer_message']; ?>
		</div>
		<?php
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-footer.php');	
		
		$message = ob_get_contents();
		ob_end_clean();
		
		return $message;
	}
	
	/*
	 * @param array string $user_data The users data
	 * Changes the WP contact form 7 default message to user shoplync custom emails
	 *
	 */
	public function change_user_email_updated( $email_change_email, $original_userdata, $new_userdata ){
		
		
		ob_start();
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-header.php');
		?>
		
		<div class="title" style="font-family:Noto Sans, sans-serif;font-size:18px;font-weight:600;color:#0094D4;text-align:center;">
			Hello, <?php echo $original_userdata['first_name'].' '.$original_userdata['last_name']; ?>
		</div>
		<br>

		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;text-align:center;color:#333333"> 
			The email address assocciated with your account has been changed. <br><br>
		</div>
		<div class="hr" style="height:1px;border-bottom:2px solid #0094D4;clear: both;">&nbsp;</div><br>
		
		<div class="subtitle" style="font-family:Noto Sans, sans-serif;font-size:16px;font-weight:600;color:#0094D4;text-align:center;"> 
			Profile Information Updated
		</div><br>
		
		<div class="body-text" style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			<strong style="color:#333333!important;">New E-mail address: <?php echo $new_userdata['user_email']; ?> </strong><br><br>
			<a href="<?php echo home_url('/customers/'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Log In</a><br><br>
			<br>
			<p style="font-family:Noto Sans, sans-serif;font-size:14px;line-height:20px;color:#333333;text-align:center;">
			*If you did not request this email change please 
			<a href="<?php echo home_url('/contact/'); ?>" style="border:none;outline:none;text-decoration:none;color:#0094D4 !important;">Contact Us</a>.
			</p>
		</div>	
		
		<?php
		include(plugin_dir_path( dirname( __FILE__ ) ) . 'mails/_partials/email-footer.php');	
		
		$message = ob_get_contents();
		ob_end_clean();
		
		$email_change_email['subject'] = 'Shoplync - Your Email Address Has Been Updated';
		$email_change_email['message'] = $message;
		
		return $email_change_email;
		
	}

}