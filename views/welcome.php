<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Setup Framework Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Welcome' ) ) {
  class CSF_Welcome{

    private static $instance = null;

    public function __construct() {

      if( CSF::$premium && ( ! CSF::is_used_as_plugin() || apply_filters( 'csf_welcome_page', true ) === false ) ) { return; }

      add_action( 'admin_menu', array( &$this, 'add_about_menu' ), 0 );
      add_filter( 'plugin_action_links', array( &$this, 'add_plugin_action_links' ), 10, 5 );
      add_filter( 'plugin_row_meta', array( &$this, 'add_plugin_row_meta' ), 10, 2 );

      $this->set_demo_mode();

    }

    // instance
    public static function instance() {
      if ( is_null( self::$instance ) ) {
        self::$instance = new self();
      }
      return self::$instance;
    }

    public function add_about_menu() {
      add_management_page( 'Codestar Framework', 'Codestar Framework', 'manage_options', 'csf-welcome', array( &$this, 'add_page_welcome' ) );
    }

    public function add_page_welcome() {

      $section = ( ! empty( $_GET['section'] ) ) ? $_GET['section'] : '';

      CSF::include_plugin_file( 'views/header.php' );

      // safely include pages
      switch ( $section ) {

        case 'quickstart':
          CSF::include_plugin_file( 'views/quickstart.php' );
        break;

        case 'documentation':
          CSF::include_plugin_file( 'views/documentation.php' );
        break;

        case 'relnotes':
          CSF::include_plugin_file( 'views/relnotes.php' );
        break;

        case 'support':
          CSF::include_plugin_file( 'views/support.php' );
        break;

        case 'free-vs-premium':
          CSF::include_plugin_file( 'views/free-vs-premium.php' );
        break;

        default:
          CSF::include_plugin_file( 'views/about.php' );
        break;

      }

      CSF::include_plugin_file( 'views/footer.php' );

    }

    public static function add_plugin_action_links( $links, $plugin_file ) {

      if( $plugin_file === 'codestar-framework/codestar-framework.php' && ! empty( $links ) ) {
        $links['csf--welcome'] = '<a href="'. admin_url( 'tools.php?page=csf-welcome' ) .'">Settings</a>';
        if( ! CSF::$premium ) {
          $links['csf--upgrade'] = '<a href="http://codestarframework.com/">Upgrade</a>';
        }
      }

      return $links;

    }

    public static function add_plugin_row_meta( $links, $plugin_file ) {

      if( $plugin_file === 'codestar-framework/codestar-framework.php' && ! empty( $links ) ) {
        $links['csf--docs'] = '<a href="http://codestarframework.com/documentation/" target="_blank">Documentation</a>';
      }

      return $links;

    }

    public function set_demo_mode() {

      $demo_mode = get_option( 'csf_demo_mode', false );

      if( ! empty( $_GET['csf-demo'] ) ) {
        $demo_mode = ( $_GET['csf-demo'] === 'activate' ) ? true : false;
        update_option( 'csf_demo_mode', $demo_mode );
      }

      if( ! empty( $demo_mode ) ) {
        CSF::include_plugin_file( 'samples/options.samples.php' );

        if( CSF::$premium ) {

          CSF::include_plugin_file( 'samples/customize-options.samples.php' );
          CSF::include_plugin_file( 'samples/metabox.samples.php'           );
          CSF::include_plugin_file( 'samples/shortcoder.samples.php'        );
          CSF::include_plugin_file( 'samples/taxonomy-options.samples.php'  );
          CSF::include_plugin_file( 'samples/widgets.samples.php'           );

        }

      }

    }

  }

  CSF_Welcome::instance();
}
