<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.anthonygfigueroa.ca
 * @since      1.0.0
 *
 * @package    Shoplync_Store_Info
 * @subpackage Shoplync_Store_Info/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Shoplync_Store_Info
 * @subpackage Shoplync_Store_Info/includes
 * @author     Anthony Figueroa <mail@anthonygfigueroa.ca>
 */
class Shoplync_Store_Info {
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Shoplync_Store_Info_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SHOPLYNC_STORE_INFO_VERSION' ) ) {
			$this->version = SHOPLYNC_STORE_INFO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'shoplync-store-info';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

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

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shoplync-store-info-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shoplync-store-info-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-shoplync-store-info-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shoplync-store-info-public.php';

		/**
		 * The file for defining pluggable functions
		 * that override the default core functions in wordpress.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shoplync-store-info-pluggable.php';

		$this->loader = new Shoplync_Store_Info_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Shoplync_Store_Info_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Shoplync_Store_Info_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Shoplync_Store_Info_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );
		
		// Plugin love.
		$this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'footer_link', 10, 2 );
		// Save/Update our plugin options
		$this->loader->add_action('admin_init', $plugin_admin, 'store_info_update');
		
		$this->loader->add_action('admin_init', $plugin_admin, 'export_mail_list');		
		
		
		
		
		//Allows us to define a users company name, company website, and phone number
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'user_extra_fields');
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'user_extra_fields' );
		$this->loader->add_action( "user_new_form", $plugin_admin, "user_extra_fields" );
		//Saves the users company name, company website, and phone number information
		$this->loader->add_action( 'user_register', $plugin_admin, 'save_user_extra_fields');
		$this->loader->add_action( 'personal_options_update', $plugin_admin, 'save_user_extra_fields' );
		$this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'save_user_extra_fields' );
		
		
		//Allows us to define clients custom username/login
		$this->loader->add_action('show_user_profile', $plugin_admin, 'user_sms_login');
		$this->loader->add_action('edit_user_profile', $plugin_admin, 'user_sms_login');
		$this->loader->add_action( "user_new_form", $plugin_admin, "user_sms_login" );
		//Saves the user_sms_login information
		$this->loader->add_action( 'user_register', $plugin_admin, 'save_user_sms_login');
		$this->loader->add_action( 'personal_options_update', $plugin_admin, 'save_user_sms_login' );
		$this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'save_user_sms_login' );

		
		//Allows us to approve or deny users via check box inside 'edit user'
		$this->loader->add_action('show_user_profile', $plugin_admin, 'user_status');
		$this->loader->add_action('edit_user_profile', $plugin_admin, 'user_status');
		$this->loader->add_action( "user_new_form", $plugin_admin, "user_status" );
		//Saves the user_sms_login information
		$this->loader->add_action( 'user_register', $plugin_admin, 'save_user_status');
		$this->loader->add_action( 'personal_options_update' ,$plugin_admin, 'save_user_status' );
		$this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'save_user_status' );
		
		
		//Shows users status in a column within the 'Manage Users" admin page.
		$this->loader->add_filter( 'manage_users_columns', $plugin_admin, 'user_status_table_column' );
		$this->loader->add_filter( 'manage_users_custom_column', $plugin_admin, 'user_status_table_row', 10, 3 );
		
		//When user is created they are automatically added to our mailing list
		$this->loader->add_action( 'user_register', $plugin_admin, 'save_user_to_mailinglist');
		
		//Edits the default outgoing sender profile and content type.
		$this->loader->add_filter( 'wp_mail_from_name', $plugin_admin, 'change_wp_email_name_outgoing' );
		$this->loader->add_filter( 'wp_mail_from', $plugin_admin, 'change_wp_email_outgoing' );
		$this->loader->add_filter( 'wp_mail_content_type', $plugin_admin, 'change_wp_email_content_type' );
		//Changes the default password reset subject line
		$this->loader->add_filter ("retrieve_password_title", $plugin_admin, "change_retrieve_password_subject");
		$this->loader->add_filter ("retrieve_password_message", $plugin_admin, "change_retrieve_password_message", 10, 4);
		
		//Changes the default password reset subject line
		$this->loader->add_filter ("wp_new_user_notification_email_admin", $plugin_admin, "change_admin_new_user_notify", 10, 3);
		
		//Changes the contact form 7 emails to use custom template
		$this->loader->add_action('wpcf7_before_send_mail', $plugin_admin, 'change_wpcf7_send_mail',1);
		
		//Changes the default email, when a user updates its email address
		$this->loader->add_filter( 'email_change_email', $plugin_admin, 'change_user_email_updated', 10, 3 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Shoplync_Store_Info_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'enqueue_wp_head_scripts' );
		
		$this->loader->add_action( 'wp_ajax_reset_sms_passcode', $plugin_public, 'reset_sms_passcode' );

		$this->loader->add_action('shoplync_store_contact', $plugin_public, 'show_store_contact');
		$this->loader->add_action('init', $plugin_public, 'shoplync_shortcodes');
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Shoplync_Store_Info_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
