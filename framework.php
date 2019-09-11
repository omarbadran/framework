<?php
/**
 * Cora Framework
 *
 * Long Description
 *
 * @package      CoraDashboard
 * @subpackage   CoraFramework
 * @author       Omar Badran
 */

# Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


# Vendors
require "vendor/scssphp/scss.inc.php";
use ScssPhp\ScssPhp\Compiler as SCSSCompiler;



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
         * Class constructor.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function __construct( $config ) {

            global $pagenow;

            # Define paths
            $this->dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
            $this->url = site_url( str_replace( str_replace( '\\', '/', ABSPATH ), '', $this->dir ) );
            
            
            # Configuration
            $this->config = wp_parse_args( $config, array(
                'page_title' => 'example',
                'menu_title' => 'example',
                'capability' => 'manage_options',
                'menu_icon' => '',
                'menu_position' => 99,
                'render_page' => array( $this, 'render_page' )
            ));
            
            # Translation
            $this->translation = include $this->dir . 'translation.php';

            # Add admin page
            add_action( '_admin_menu', array( $this  , "add_admin_page" ));
            # Ajax save
            add_action( 'wp_ajax_cora_save', array( $this  , "save") );


            # Load assets only in settings page
            if ( $pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === $this->config['id']) {

                # Compile SCSS
                add_action( 'admin_head', array( $this  , "style" ) );
                # Enqueue styles
                add_action( 'admin_enqueue_scripts', array( $this  , "styles" ) );
                # Enqueue scripts
                add_action( 'admin_enqueue_scripts', array( $this  , "scripts" ) );
                # Localize data to be handeld by vue
                add_action( 'admin_enqueue_scripts', array( $this  , "app_data") );

                # Autoload Fields
                foreach( glob($this->dir . 'includes/fields/*')  as $folder ) {
                    require_once $folder. '/index.php';
                }

            }

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
            add_menu_page( $page_title, $menu_title, $capability, $id, $render_page, $menu_icon, $menu_position );
            
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
         * Compile SCSS.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
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
                $this->url."/assets/js/app.js",
                array('vue'),
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
        
            wp_localize_script( 'cora-framework', 'CoraFrameworkData', array(
                'nonce' => wp_create_nonce('cora-framework-nonce'),
                'config' => $this->config,
                'sections' => $this->sections,
                'fields' => $this->fields,
                'values' => json_decode(json_encode( (object) $this->getValues())),
                'translation' => $this->translation,
                'url' => $this->url
            ));
        
        }

        /**
         * API: Add section
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function add_section( $section ) {
            
            $this->sections[] = $section;
            
            if ( !isset($this->values[ $section['id'] ]) ) {
                $this->values[$section['id']] = array();
            }
        }

        /**
         * API: Add field
         *
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
         * API: Get settings values.
         *
         * @since       1.0.0
         * @access      public
         * @return      array
         */
        public function getValues() {
            $values = get_option($this->config['id']);
            $sections = array();

            if($values) {

                foreach ($this->sections as $section) {
                    $sections[ $section['id']] = array();
                }
        
                $values = wp_parse_args($values , $sections);    

                return $values;
            }

            # Option is not in the database, get default values.
            return $this->values;
        }
        
    } # Class end

}