<?php
/**
 * Select Field.
 *
 * @package     CoraFramework/Fields
 * @author      Omar Badran
 * @version     1.0.0
 */

class cora_select_field {

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

		wp_enqueue_style( 'cora-select-field', $this->url."index.css" );
		wp_enqueue_script( 'cora-select-field', $this->url."index.js" );

        # select2
		wp_enqueue_script( 'select2', $this->url."assets/select2/select2.min.js" , ['jquery'] );
		wp_enqueue_style( 'select2', $this->url."assets/select2/select2.min.css" );

    }
}

new cora_select_field();