<?php
/**
 * Icon Field.
 *
 * @package     CoraFramework/Fields
 * @author      Omar Badran
 * @version     1.0.0
 */

class cora_icon_field {

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

		wp_enqueue_style( 'cora-icon-field', $this->url."index.css" );
		wp_register_script( 'cora-icon-field', $this->url."index.js", ['vue', 'jquery'], false, true );

        wp_enqueue_style( 'cora-icon-field-icons', $this->url."icons.css" );

        // Load the icons JSON file.

        $request = wp_remote_get( $this->url."icons.json" );

        if( is_wp_error( $request ) ) {
            return false;
        }
    
        $icons_file = wp_remote_retrieve_body( $request );
        $icons = json_decode( $icons_file );

        wp_localize_script( 'cora-icon-field', 'materialIconsList', $icons);
        wp_enqueue_script( 'cora-icon-field' );
    }
}

new cora_icon_field();