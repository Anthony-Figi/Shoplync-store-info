<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.anthonygfigueroa.ca
 * @since      1.0.0
 *
 * @package    Shoplync_Store_Info
 * @subpackage Shoplync_Store_Info/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Shoplync_Store_Info
 * @subpackage Shoplync_Store_Info/public
 * @author     Anthony Figueroa <mail@anthonygfigueroa.ca>
 */
class Shoplync_Store_Info_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->load_dependencies();
		//gets options data from backend;
		$this->store_info_options = get_option($this->plugin_name);

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
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/shoplync-store-info-public.css', array(), $this->version, 'all' );

	}
	/**
	 * Add a <script> tag to the head of the html document
	 *
	 */
	 public function enqueue_wp_head_scripts(){
		 ?>
		 <script type="text/javascript">
			var ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
			var ajax_nonce = '<?php echo wp_create_nonce( "generate_new_passcode" ); ?>';
		 </script>
		 <?php
	 }
	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/shoplync-store-info-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'_ajax', plugin_dir_url( __FILE__ ) . 'js/shoplync-login-ajax.js', array( 'jquery' ), $this->version, false );

	}
	public function reset_sms_passcode(){
		// This is a secure process to validate if this request comes from a valid source.
		check_ajax_referer( 'generate_new_passcode', 'security' );
		
		if ( empty( $_POST["user_id"] ) ) {
			echo "Insert your name please";
			wp_die();
		}

		$current_user_id = get_current_user_id();
		$new_passcode = Shoplync_Store_Info_Helpers::random_str(10);
		
		update_user_meta( $current_user_id, 'sms-password', $new_passcode );
		
		echo $_POST["user_id"];
		wp_die();
	}
	public function show_store_cta(){
		/*if(!empty($this->store_info_options['tel']) && !empty($this->store_info_options['email']) ){
			if(!is_admin()){
				include_once( 'partials/shoplync-store-info-admin-display.php' );
			}
		}*/
	}	
	public function show_store_contact(){
		$is_enabled = ( isset($this->store_info_options['topbar']) && !empty($this->store_info_options['topbar'])  ? $options['topbar'] : 0 );
		
		if($is_enabled && !empty($this->store_info_options['tel']) && !empty($this->store_info_options['email']) ){
			if(!is_admin()){
				include_once( 'partials/shoplync-store-contact.php' );
			}
		}
	}
	
	public function shoplync_contact_shortcode($params){
		shortcode_atts(array(
			'type' => 1,
	    ), $params, 'shoplync_contact');
		
		$type = (isset($params['type']) ? (int) $params['type'] : 1);
		
		if(!empty($this->store_info_options['tel']) && !empty($this->store_info_options['email']) ){
			if($type == 1){//wrapper in <span> <a></a> </span>
				return '<span>Call Us: <a href="tel:'.$this->store_info_options['tel'].'">'.$this->store_info_options['tel'].'</a></span><br><span>Email Us: <a href="mailto:'.$this->store_info_options['email'].'">'.$this->store_info_options['email'].'</a></span>';
			}else if ($type == 2){//Only the email and phone info is wrapped in anchor tags
				return '<a href="tel:'.$this->store_info_options['tel'].'">'.$this->store_info_options['tel'].'</a><br><a href="mailto:'.$this->store_info_options['email'].'">'.$this->store_info_options['email'].'</a>';
			}else if ($type == 3){//Phone Number Only: Outputs an anchor link
				return '<a href="tel:'.$this->store_info_options['tel'].'">'.$this->store_info_options['tel'].'</a>';
			}else if ($type == 4){//Email: Outputs an anchor link
				return '<a href="mailto:'.$this->store_info_options['email'].'">'.$this->store_info_options['email'].'</a>';
			}else if ($type == 5){//Raw: Contact info is displayed as raw text
				return $this->store_info_options['tel'].' '.$this->store_info_options['email'];
			}else if ($type == 6){//Raw Phone Number: displayed as raw text
				return $this->store_info_options['tel'];
			}else if ($type == 7){//Raw Email: displayed as raw text
				return $this->store_info_options['email'];
			}
		}else {
			return '<span class="shop-email"><i class="fa fa-envelope"></i><a href="'.$base_url.'/contact">Email Us</a></span>';
		}

	}
	public function shoplync_shortcodes() {
		add_shortcode('shoplync_contact', array($this, 'shoplync_contact_shortcode'));
	}

}
