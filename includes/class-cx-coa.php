<?php
defined( 'ABSPATH' ) || exit;

/**
 * Main Class to load.
 */
final class CX_COA {

    /**
     * The single instance of the class.
     *
     * @var CX_COA
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main instance
     * @return class object
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
	   self::init();

        /**
         * Init.
         *
         * @since 1.0.0
         */
        do_action( 'cx_coa_init' );
    }

    /**
     * Initialiseeee
     */
    public static function init() {

        self::define_constants(); //Define the constants.

        self::includes(); // Include relevant files.
	
        // Load the textdomain.
		add_action( 'init', array( __CLASS__, 'load_textdomain' ) );

	    // Add Block Editor compatibility.
        add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_assets' ) );

		// Enqueue required js and css.
		add_filter( 'wp_enqueue_scripts', array( __CLASS__, 'load_scripts' ) );

    }

    /**
     * Constants define
     */
    private static function define_constants() {
        self::define( 'CX_COA_ABSPATH', dirname( CX_COA_PLUGIN_FILE ) . '/' );
        self::define( 'CX_COA_PLUGIN_FILE', plugin_basename( CX_COA_PLUGIN_FILE ) );
		self::define( 'CX_COA_PLUGIN_VERSION', '1.0.0' );
    }

    /**
     * 
     * @param string $name
     * @param mixed $value
     */
    private static function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Check request
     * @param string $type
     * @return bool
     */
    private static function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined( 'DOING_AJAX' );
            case 'cron' :
                return defined( 'DOING_CRON' );
            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

    /**
     * load plugin files
     */
    public static function includes() {
		include_once CX_COA_ABSPATH . 'includes/class-cx-coa-co-authors.php';
		include_once CX_COA_ABSPATH . 'includes/class-cx-coa-ads.php';

        // Admin side.
        if ( self::is_request( 'admin' ) ) {
			include_once CX_COA_ABSPATH . 'includes/admin/meta-boxes/class-cx-coa-post-meta.php';
		}
    }
     /**
     * Plugin url
     *
     * @return string path
     */
    public static function plugin_url() {
        return untrailingslashit( plugins_url( '/', CX_COA_PLUGIN_FILE ) );
    }
	
    /**
     * Load Localisation files.
     *
	 * @since  1.0.0
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'cx-coa', false, plugin_basename( dirname( CX_COA_PLUGIN_FILE ) ) . '/languages' );
    }


	/**
	 * Load Script.
	 *
	 * @since 1.0.0
	 */
	public static function load_scripts() {

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_script( 'cx-coa-co-authors-js', self::plugin_url() . '/assets/js/co-authors' . $suffix . '.js', array( 'jquery' ), CX_COA_PLUGIN_VERSION );

	}

	/*-----------------------------------------------------------------------------------*/
	/* Block Editor Functions */
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Load Script in Block Editor.
	 * 
	 * @since 1.0.0
	 * @param string $hook the name of the page we're on in the WP admin.
	 */
	public static function enqueue_assets( $hook ) {
		// Add styles and scripts for block editor.
    	wp_enqueue_script( 'cx-coa-gutenberg-sidebar', self::plugin_url() . '/assets/js/dist/post-sidebar.js', array( 'wp-plugins', 'wp-edit-post', 'wp-i18n', 'wp-element' ), CX_COA_PLUGIN_VERSION );
	}

}
