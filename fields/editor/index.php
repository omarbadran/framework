<?php
/**
 * Editor Field.
 *
 * @package     CoraFramework/Fields
 * @author      Omar Badran
 * @version     1.0.0
 */

class cora_editor_field {

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

		wp_enqueue_style( 'cora-editor-field', $this->url."index.css" );
        wp_enqueue_script( 'cora-editor-field', $this->url."index.js" );
        
        wp_enqueue_editor();
    }
}

new cora_editor_field();