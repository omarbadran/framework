<?php
/**
 * Color Field.
 *
 * @package     CoraFramework/Fields
 * @author      Omar Badran
 * @version     1.0.0
 */

class cora_color_field {

    /**
	 * Class constructor.
     * 
     * @since       1.0.0
     * @access      public
     * @return      void
	 */
	public function __construct() {

		# Define paths
        $this->dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
		$this->url = site_url( str_replace( str_replace( '\\', '/', ABSPATH ), '', $this->dir ) );
		
        # Enqueue assets
		add_action( 'admin_enqueue_scripts', [ $this  , "assets" ] );
    }

    /**
     * Enqueue assets.
     *
     * @since       1.0.0
     * @access      public
     * @return      void
     */
    public function assets() {

		wp_enqueue_style( 'cora-color-field', $this->url."index.css" );
		wp_enqueue_script( 'cora-color-field', $this->url."index.js" );

		# pickr
		wp_enqueue_script( 'pickr', $this->url."assets/pickr/pickr.min.js" , ['vue'] );
		wp_enqueue_style( 'pickr', $this->url."assets/pickr/nano.min.css" );
		
    }
}

new cora_color_field();