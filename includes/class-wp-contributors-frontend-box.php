<?php
/**
 * The User Contributors Box Display on the Frontend.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    wordpress_contributors
 * @subpackage wordpress_contributors/includes
 */

/**
 * The User Contributors Box Display on the Frontend.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wordpress_contributors
 * @subpackage wordpress_contributors/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Wp_Contibutors_Frontend_Boxes {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The single instance of the class.
	 *
	 * @var Wp_Contibutors_Frontend_Boxes
	 * @since 1.0.0
	 */
	protected static $instance = null;

	/**
	 * Main wordpress_contributors Instance.
	 *
	 * Ensures only one instance of wordpress_contributors is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Wp_Contibutors_Frontend_Boxes()
	 * @return Wp_Contibutors_Frontend_Boxes - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Wp Contributors front-end constructor.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since  1.0.0
	 */
	private function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_contributors_enqueue_scripts' ) );
		add_filter( 'the_content', array( $this, 'wp_contributors_display_author_box' ), 10, 1 );
	}

	/**
	 * Enqueue the JavaScript and CSS file.
	 *
	 * @since    1.0.0
	 */
	public function wp_contributors_enqueue_scripts() {
		wp_enqueue_style( 'wp_contributors', plugin_dir_url( __FILE__ ) . 'assets/wp-contributors.css', array(), true );
		wp_enqueue_script( 'wp_contributors', plugin_dir_url( __FILE__ ) . 'assets/wp-contributors.js', array( 'jquery' ), true );

	}
	/**
	 * Displays the post content.
	 *
	 * @param  string $content Post Text.
	 */
	public function wp_contributors_display_author_box( $content ) {
		global $post;
		$post_id            = $post->ID;
		$contributors_users = get_post_meta( $post_id, 'wp_contributors_users', true );
		$authors            = '';
		if ( ! empty( $contributors_users ) ) {
			$authors .= '<div class="wp_authors">';
			$authors .= '<h3>' . esc_html__( 'Contributors', ' wp-contributors' ) . '</h3>';
			$authors .= '<ul>';
			foreach ( $contributors_users as $contributors_user ) {
				$author   = get_userdata( $contributors_user );
				$authors .= '<li>';
				$authors .= '<a class="author-block " href=" ' . get_author_posts_url( $contributors_user ) . '">';
				$authors .= '<figure class="author-picture">';
				$authors .= get_avatar( $contributors_user, 30 );
				$authors .= '</figure>';
				$authors .= '</a>';
				$authors .= '<p>';
				$authors .= '<a class="author-block " href=" ' . get_author_posts_url( $contributors_user ) . '">';
				$authors .= $author->nickname;
				$authors .= '</a>';
				$authors .= '</p>';
				$authors .= '</li>';
			}
			$authors .= '</ul>';
			$authors .= '</div>';
		}
		return $content . $authors;
	}


}

/**
 * Main instance of Wp_Contibutors_Frontend_Boxes.
 *
 * Returns the main instance of Wp_Contibutors_Frontend_Boxes to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Wp_Contibutors_Frontend_Boxes
 */
Wp_Contibutors_Frontend_Boxes::instance();
