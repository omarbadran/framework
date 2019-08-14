<?php
/**
 * The plugin bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name:       Cora Framework
 * Plugin URI:        http://coradashboard.com
 * Description:       Simple Wordpress Options Framework.
 * Version:           1.0.0
 * Author:            Cora
 * Author URI:        http://coradashboard.com/
 */


# If this file is called directly, abort.
if ( ! defined( 'WPINC' ) )  die;





/**
 * Main class.
 *
 *
 * @since  1.0.0
 *
 * @package  CoraFramework
 * @access   public
 */
class CoraFramework {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * Plugin directory path.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $dir;

	/**
	 * Plugin directory url.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Current instance configuration.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $config;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $config ) {

		# Define paths
        $this->dir = dirname( __FILE__ ) . "/";
		$this->url = $this->dir_url();
		
		# Configuration
		$this->config = $config;
		
		# Add admin page
		add_action('admin_menu', array( $this  , "add_admin_page"), 100 );
		
	}

	/**
	 * Current dir url.
	 *
	 * @since 1.0.0
	 */
    public function dir_url() {

		$url = str_replace( "\\", "/", str_replace( str_replace( "/", "\\", WP_CONTENT_DIR ), "", __DIR__ ) );
		$url .= '/';
		if ( $url ){
			return content_url( $url );
		}
		
		return false;
		
	}

	/**
	 * Add admin page.
	 *
	 * @since 1.0.0
	 */
    public function add_admin_page() {

		$defaultArgs = array(
			'page_title' => 'example',
			'menu_title' => 'example',
			'capability' => 'manage_options',
			'menu_icon' => '',
			'menu_position' => 99
		);

		$args = wp_parse_args($this->config, $defaultArgs);

        add_menu_page( 
			$args['page_title'],
			$args['menu_title'],
			$args['capability'],
			$args['id'],
			function () {
				include 'view.html';
			},
			$args['menu_icon'],
			$args['menu_position']
		);
		
	}

}

// Testing Page
$exampleConfig = include 'example.config.php';
$exampleSettings = 	new CoraFramework( $exampleConfig );