<?php
/**
 * Cora Framework
 *
 * Simple & Flexible WordPress options framework.
 *
 * @package     CF
 * @author      Omar Badran <engineer.o.badran@gmail.com>
 * @access public
 */

 
if ( ! class_exists('CF') ) {

    /**
     * Main class.
     *
     *
     * @since  1.0.0
     *
     * @package  CF
     * @access   public
     */
    class CF {

        /**
         * Framework version, used for cache-busting of style and script file references.
         *
         * @since 1.0.0
         *
         * @var string
         */
        public $version = '1.0.0';

        /**
         * Instance configuration.
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
         * @var array
         */
        private $values = [];

        /**
         * Translated strings.
         *
         * @since 1.0.0
         *
         * @var array
         */
        private $translation = [];

        /**
         * Constructor.
         *
         * @since 1.0.0
         * @access public
         *  
    	 * @param array $args An array of information representing the instance.
         * 
         * @return void
         */
        public function __construct( $args ) {
            $defaults = [
                'page_title' => 'example',
                'menu_title' => 'example',
                'capability' => 'manage_options',
                'menu_icon' => '',
                'menu_position' => 99,
            ];

            $this->config = wp_parse_args( $args, $defaults );
            
            $this->translation = include $this->dir("translation.php");

            if ( $this->in_view() ) {

                # Enqueue styles
                add_action( 'admin_enqueue_scripts', [$this, "styles"] );
                # Enqueue scripts
                add_action( 'admin_enqueue_scripts', [$this, "scripts"] );
                # Localize data for the front-end
                add_action( 'admin_enqueue_scripts', [$this, "app_data"] );

                # Autoload fields
                foreach( glob( $this->dir("fields/*") ) as $field ) {
                    require_once $field . '/index.php';
                }

            }

            # Add admin page
            add_action( '_admin_menu', [$this, "add_admin_page"] );
            # Ajax save
            add_action( 'wp_ajax_cora_save', [$this, "save"] );
        }

        /**
         * Directory path.
         *
         * @since 1.0.0
         * @access public 
         * 
         * @return string The current directory path.
         */
        public function dir( $append = false ) {
            $dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );

            if ( $append ) {
                $dir .= $append;
            }

            return $this->clean_path($dir);
        }

        /**
         * Directory url.
         *
         * @since 1.0.0
         * @access public 
         *  
         * @return string The current directory url.
         */
        public function url( $append = false ) {
            $url = site_url( str_replace( str_replace( '\\', '/', ABSPATH ), '', $this->dir() ) ) . '/';

            if ( $append ) {
                $url .= $append;
            }

            return $this->clean_path($url);
        }

        /**
         * Clean any path used in the file system.
         *
         * @since 1.0.0
         * @access public 
         * 
         * @return void
         */
        public function clean_path( $path ) {
            $path = str_replace( '', '', str_replace( array( "\\", "\\\\" ), '/', $path ) );
            
            if ( $path[ strlen( $path ) - 1 ] === '/' ) {
                $path = rtrim( $path, '/' );
            }

            return $path;
        }

        /**
         * Add admin page.
         *
         * @since 1.0.0
         * @access public 
         * 
         * @return void
         */
        public function add_admin_page() {
            extract( $this->config );
            
            add_menu_page( $page_title, $menu_title, $capability, $id, [$this, 'render_page'], $menu_icon, $menu_position );
        }

        /**
         * Render page.
         *
         * @since 1.0.0
         * @access public 
         * 
         * @return void
         */
        public function render_page() {
            include $this->dir("views/view.php");
        }

        /**
         * Determine if currently in view.
         *
         * @since 1.0.0
         * @access public 
         * 
         * @return boolean
         */
        public function in_view() {            
            return $GLOBALS['pagenow'] === 'admin.php' && isset($_GET['page']) && $_GET['page'] === $this->config['id'];
        }

        /**
         * Enqueue styles.
         *
         * @since 1.0.0
         * @access public 
         * 
         * @return void
         */
        public function styles() {
            wp_enqueue_style( 
                'material-icons',
                $this->url("/assets/vendor/material-icons/material-icons.css")
            );

            wp_enqueue_style( 
                'cf',
                $this->url("/assets/css/style.css")
            );
        }

        /**
         * Enqueue scripts.
         *
         * @since 1.0.0
         * @access public 
         * 
         * @return void
         */
        public function scripts() {
            wp_enqueue_script( 
                'vue',
                $this->url("/assets/vendor/vue/vue.min.js")
            );
            
            wp_enqueue_script(
                'cf',
                $this->url("/assets/js/app.min.js"),
                ['vue', 'jquery'],
                $this->version,
                true
            );
        }

        /**
         * Localize data to be handeld by vue.
         *
         * @since 1.0.0
         * @access public 
         * 
         * @return void
         */
        public function app_data() {
            wp_localize_script( 'cf', 'CoraFrameworkData', [
                'nonce' => wp_create_nonce('cf-nonce'),
                'config' => $this->config,
                'sections' => $this->sections,
                'fields' => $this->fields,
                'values' => $this->get_values(),
                'translation' => $this->translation,
                'url' => $this->url()
            ]);
        }

        /**
         * Add section
         * 
         * @api
         * @since 1.0.0
         * @access public 
         * 
         * @return void
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
         * @since 1.0.0
         * @access public 
         * 
         * @return void
         */
        public function add_field( $field ) {
            $this->fields[] = $field;

            if( isset( $field['default'] ) ) {
                $this->values[$field['section']][$field['id']] = $field['default'];
            }
        }

        /**
         * Sanitize.
         *
         * @since 1.0.0
         * @access public 
         * 
         * @return void
         */
        public function sanitize($data) {
            if( is_string($data) ){
                $data = sanitize_text_field($data);
            }elseif( is_array($data) ){
                foreach ( $data as $key => &$value ) {
                    if ( is_array( $value ) ) {
                        $value = $this->sanitize($value);
                    }
                    else {
                        $value = sanitize_text_field( $value );
                    }
                }
            }
        
            return $data;
        }

        /**
         * Ajax Save.
         *
         * @since 1.0.0
         * @access public 
         * 
         * @return void
         */
        public function save() {
            # Check nonce
            if ( ! check_ajax_referer( 'cf-nonce', 'security') ){
                wp_die();
            }

            # Check user capability
            if ( ! current_user_can( $this->config['capability'] ) ){
                wp_die();
            }
            
            # Sanitize
            $data = $this->sanitize($_POST['data']);

            # Save the data
            update_option( $this->config['id'], $data );

            wp_send_json_success();

            wp_die();
        }

        /**
         * Get all values.
         * 
         * @since 1.0.0
         * @access public 
         * 
         * @return array
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
         * @since 1.0.0
         * @access public 
         * 
         * @return mixed
         */
        public function get_value( $sectionID, $fieldID, $default = NULL ) {
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
         * @since 1.0.0
         * @access public 
         * 
         * @return void
         */
        public function update_value( $sectionID, $fieldID, $value ) {
            $values = $this->get_values();
            
            $values[$sectionID][$fieldID] = $value;
            
            update_option( $this->config['id'], $values );
        }

    }

}
