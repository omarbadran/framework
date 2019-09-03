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


# Require vendors
require "vendor/scssphp/scss.inc.php";

# Use Vendors
use ScssPhp\ScssPhp\Compiler as SCSSCompiler;



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
	private $dir;

	/**
	 * Plugin directory url.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Current instance configuration.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $config;
	
	/**
	 * Registered Sections.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $sections = array();

	/**
	 * Registered fields.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $fields = array();
	
	/**
	 * Fields values.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $values = array();

	/**
	 * Translated strings.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $translation = array();


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
		add_action( 'admin_menu', array( $this  , "add_admin_page" ) );
		# Compile SCSS
		add_action( 'admin_head', array( $this  , "style" ) );
		# Enqueue styles
		add_action( 'admin_enqueue_scripts', array( $this  , "styles" ) );
		# Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this  , "scripts" ) );
		# Localize data to be handeld by vue
		add_action( 'admin_enqueue_scripts', array( $this  , "app_data") );

	}

	/**
	 * Current dir url.
	 *
	 * @since 1.0.0
	 */
    public function dir_url() {

		$url = str_replace( "\\", "/", str_replace( str_replace( "/", "\\", WP_CONTENT_DIR ), "", __DIR__ ) );

		$url .=  '/';

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

		# Parse & extract default args
		extract( wp_parse_args( $this->config, array(
			'page_title' => 'example',
			'menu_title' => 'example',
			'capability' => 'manage_options',
			'menu_icon' => '',
			'menu_position' => 99
		)));

		# Add Menu page
        add_menu_page(
			$page_title,
			$menu_title,
			$capability,
			$id,
			array( $this, 'render_page' ),
			$menu_icon,
			$menu_position
		);
		
	}

	/**
	 * Render page.
	 *
	 * @since 1.0.0
	 */
    public function render_page() {

		include( 'view.html' );

	}

	/**
	 * Compile SCSS.
	 *
	 * @since 1.0.0
	 */
    public function style() {

		$compiler = new SCSSCompiler();
		$compiler->setImportPaths( $this->dir . "assets/scss/" );
		
		$file = file_get_contents( $this->dir . "assets/scss/style.scss" );
		$css = $compiler->compile( $file );	  
		
		echo "<style>$css</style>";
	
	}

	/**
	 * Enqueue styles.
	 *
	 * @since 1.0.0
	 */
    public function styles() {

		# Vue
		wp_enqueue_style( 'material-icons', $this->url."/assets/vendor/material-icons/material-icons.css" );
		# select2
		wp_enqueue_style( 'select2', $this->url."/assets/vendor/select2/select2.min.css");
		# pickr
		wp_enqueue_style( 'pickr', $this->url."/assets/vendor/pickr/nano.min.css");
		# Wordpress color picker
		wp_enqueue_style( 'wp-color-picker' ); 

	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 */
    public function scripts() {

		# Vue
		wp_enqueue_script( 'vue', $this->url."/assets/vendor/vue/vue.js" );
		# select2
		wp_enqueue_script( 'select2', $this->url."/assets/vendor/select2/select2.min.js" , array('jquery'));
		# slicksort
		wp_enqueue_script( 'vue-slicksort', $this->url."/assets/vendor/slicksort/vue-slicksort.min.js" , array('vue'));
		# pickr
		wp_enqueue_script( 'pickr', $this->url."/assets/vendor/pickr/pickr.min.js" , array('vue'));
		# Media
		wp_enqueue_media();

		# Custom Components
		foreach( glob($this->dir . 'assets/js/components/*.js')  as $file ) {
			$name = basename($file, '.js');
			$required = array('vue' , 'wp-color-picker', 'wp-color-picker', 'select2', 'vue-slicksort', 'pickr');

			wp_enqueue_script(
				$name,
				$this->url .'assets/js/components/'.$name . '.js',
				$required
			);
		}
		
		# App
		wp_enqueue_script(
			'cora-framework',
			$this->url."/assets/js/app.js",
			array('vue'),
			$this->version,
			true
		);
	}

	/**
	 * Localize data to be handeld by vue
	 *
	 * @since 1.0.0
	 */
    public function app_data () {
    
        wp_localize_script( 'cora-framework', 'CoraFrameworkData', array(
			'nonce' => wp_create_nonce('cora-framework-nonce'),
			'config' => $this->config,
			'sections' => $this->sections,
			'fields' => $this->fields,
			'values' => $this->values,
			'translation' => $this->translation,
			'url' => $this->url
		));
	
	}

	/**
	 * API : Add section
	 *
	 * @since 1.0.0
	 */
    public function add_section( $section ) {
		
		$this->sections[] = $section;
		
		if ( !isset($this->values[ $section['id'] ]) ) {
			$this->values[$section['id']] = array();
		}
	}

	/**
	 * API : Add field
	 *
	 * @since 1.0.0
	 */
    public function add_field( $field ) {
		
		$this->fields[] = $field;

		if( isset( $field['default'] ) ) {

			$this->values[$field['section']][$field['id']] = $field['default'];

		}

	}

} # Class end



// Sample instance
require_once 'sample.php';
