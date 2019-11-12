<?php
/**
 * Cora Framework
 *
 * Simple & Flexible WordPress options framework.
 *
 * @package     CoraFramework
 * @author      Omar Badran <engineer.o.badran@gmail.com>
 * @access      public
 */

 
if ( ! class_exists('CoraFramework') ) {

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
        public $config;
        
        /**
         * Registered Sections.
         *
         * @since 1.0.0
         *
         * @var array
         */
        public $sections = [];

        /**
         * Registered fields.
         *
         * @since 1.0.0
         *
         * @var array
         */
        public $fields = [];
        
        /**
         * Fields values.
         *
         * @since 1.0.0
         *
         * @var string
         */
        private $values = [];

        /**
         * Translated strings.
         *
         * @since 1.0.0
         *
         * @var string
         */
        private $translation = [];


        /**
         * Class constructor.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function __construct( $config ) {

            # Define paths
            $this->dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
            $this->url = site_url( str_replace( str_replace( '\\', '/', ABSPATH ), '', $this->dir ) );
            
            
            # Instance Configuration
            $this->config = wp_parse_args( $config, [
                'page_title' => 'example',
                'menu_title' => 'example',
                'capability' => 'manage_options',
                'menu_icon' => '',
                'menu_position' => 99,
            ]);
            
            # Translation
            $this->translation = include $this->dir . 'translation.php';

            # Currently in view
            if ( $this->in_view() ) {

                # Enqueue styles
                add_action( 'admin_enqueue_scripts', [ $this  , "styles" ] );
                # Enqueue scripts
                add_action( 'admin_enqueue_scripts', [ $this  , "scripts" ] );
                # Localize data to be handeld by vue
                add_action( 'admin_enqueue_scripts', [ $this  , "app_data" ] );

                # Autoload Fields
                foreach( glob($this->dir . 'includes/fields/*')  as $field ) {
                    require_once $field. '/index.php';
                }

            }

            # Add admin page
            add_action( '_admin_menu', [ $this  , "add_admin_page" ] );
            # Ajax save
            add_action( 'wp_ajax_cora_save', [ $this  , "save" ] );

        }

        /**
         * Add admin page.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function add_admin_page() {

            extract($this->config);

            # Add Menu page
            add_menu_page( $page_title, $menu_title, $capability, $id, [ $this, 'render_page' ], $menu_icon, $menu_position );
            
        }

        /**
         * Render page.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render_page() {

            include( 'view.html' );

        }

        /**
         * Determine if currently in view.
         *
         * @since       1.0.0
         * @access      public
         * @return      boolean
         */
        public function in_view() {

            global $pagenow;
            
            return $pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === $this->config['id'];
        
        }

        /**
         * Enqueue styles.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function styles() {

            # Material icons
            wp_enqueue_style( 
                'material-icons',
                $this->url."/assets/vendor/material-icons/material-icons.css"
            );

            # Main
            wp_enqueue_style( 
                'cora-framework',
                $this->url."/assets/css/style.css"
            );
        }

        /**
         * Enqueue scripts.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function scripts() {

            # Vue
            wp_enqueue_script( 
                'vue',
                $this->url."/assets/vendor/vue/vue.js"
            );
            
            # App
            wp_enqueue_script(
                'cora-framework',
                $this->url."/assets/js/app.min.js",
                ['vue'],
                $this->version,
                true
            );
        }

        /**
         * Localize data to be handeld by vue.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function app_data() {


            wp_localize_script( 'cora-framework', 'CoraFrameworkData', [
                'nonce' => wp_create_nonce('cora-framework-nonce'),
                'config' => $this->config,
                'sections' => $this->sections,
                'fields' => $this->fields,
                'values' => $this->get_values(),
                'translation' => $this->translation,
                'url' => $this->url
            ]);
        
        }

        /**
         * Add section
         * 
         * @api
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function add_section( $section ) {
            
            $this->sections[] = $section;
            
            if ( !isset($this->values[ $section['id'] ]) ) {

                $this->values[$section['id']] = ['_empty' => null];

            }

        }

        /**
         * Add field
         * 
         * @api
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function add_field( $field ) {
            
            $this->fields[] = $field;

            if( isset( $field['default'] ) ) {

                $this->values[$field['section']][$field['id']] = $field['default'];

            }

        }

        /**
         * Ajax Save.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function save() {
            
            # Check nonce
            if ( ! check_ajax_referer( 'cora-framework-nonce', 'security') ){
                wp_die();
            }

            # Check user capability
            if ( ! current_user_can($this->config['capability']) ){
                wp_die();
            }

            # Save the data
            update_option( $this->config['id'], $_POST['data'] );

            wp_die();
            
        }

        /**
         * Get all values.
         * 
         * @api
         * @since       1.0.0
         * @access      public
         * @return      array
         */
        public function get_values() {

            $values = get_option($this->config['id']);
        
            if( $values ) {
                $sections = [];
                        
                $values = wp_parse_args($values , $this->values);

                foreach ( $this->sections as $section ) {
                    $values[ $section['id'] ]['_empty'] = null;
                }

                return wp_unslash( json_decode( json_encode($values), true) );
            }

            return $this->values;

        }
        
        /**
         * Get field value.
         * 
         * @api
         * @since       1.0.0
         * @access      public
         * @return      array
         */
        public function get_value($sectionID, $fieldID, $default = NULL) {

            $section = $this->get_values()[$sectionID];

            if( isset($section[$fieldID]) ) {
            
                return $section[$fieldID];
            
            }

            return $default;

        }

        /**
         * Update field value.
         * 
         * @api
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function update_value($sectionID, $fieldID, $value) {

            $values = $this->get_values();
            
            $values[$sectionID][$fieldID] = $value;
            
            update_option( $this->config['id'], $values );

        }

    }

}