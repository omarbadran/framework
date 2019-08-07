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
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		# Define paths
        $this->dir = dirname( __FILE__ ) . "/";
		$this->url = $this->get_url();

        var_dump($this->url);

	}

	/**
	 * Current dir url.
	 *
	 * @since 1.0.0
	 */
    public function get_url() {

		$url = str_replace( "\\", "/", str_replace( str_replace( "/", "\\", WP_CONTENT_DIR ), "", __DIR__ ) );
		$url .= '/';
		if ( $url ){
			return content_url( $url );
		}
		
		return false;
		
	}

}