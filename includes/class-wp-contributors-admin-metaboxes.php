<?php
/**
 * Metaboxes added on the post edit page.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    wordpress_contributors
 * @subpackage wordpress_contributors/includes
 */

/**
 * Metaboxes added on the post edit page.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wordpress_contributors
 * @subpackage wordpress_contributors/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Wp_Contibutors_Admin_Metaboxes {

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
	 * @var Wp_Contibutors_Admin_Metaboxes
	 * @since 1.0.0
	 */
	protected static $instance = null;

	/**
	 * Main Wp_Contibutors_Admin_Metaboxes Instance.
	 *
	 * Ensures only one instance of Wp_Contibutors_Admin_Metaboxes is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Wp_Contibutors_Admin_Metaboxes()
	 * @return Wp_Contibutors_Admin_Metaboxes - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Wp Contributors constructor.
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
		add_action( 'add_meta_boxes', array( $this, 'wp_contributors_register_metaboxes' ) );
		add_action( 'save_post', array( $this, 'wp_contributors_save_meta_box' ) );
	}

	/**
	 * Register meta box(es).
	 */
	public function wp_contributors_register_metaboxes() {
		add_meta_box( 'wp_contributors', __( 'Contributors', 'wp-contributors' ), array( $this, 'wp_contributors_metabox_callback' ), 'post', 'advanced', 'high' );
	}

	/**
	 * Meta box display callback.
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function wp_contributors_metabox_callback( $post ) {
		$post_id = $post->ID;
		wp_nonce_field( 'wp_contributors_metabox_nonce', 'wp_contributors_nonce' );
		$wp_users           = get_users( array( 'fields' => array( 'ID' ) ) );
		$contributors_users = get_post_meta( $post_id, 'wp_contributors_users', true );
		?>
		<fieldset>
			<?php
			foreach ( $wp_users as $wp_user ) {
				$author = get_userdata( $wp_user->ID );
				if ( ! empty( $contributors_users ) && in_array( $wp_user->ID, $contributors_users ) ) {
					$checked = 'Checked';
				} else {
					$checked = '';
				}
				?>
			<p>
				<input class="wp_contrbutors_users" type="checkbox" name="wp_contributors[]" value="<?php echo esc_attr( $wp_user->ID ); ?>" <?php echo esc_attr( $checked ); ?> />
				<label for="post-format-0" class="wp_contrbutors_label post-format-standard"><?php echo esc_html( $author->nickname ); ?></label>
			</p>
			<?php } ?>
		</fieldset>
		<?php
	}
	/**
	 * Save meta box content.
	 *
	 * @param int $post_id Post ID.
	 */
	public function wp_contributors_save_meta_box( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['wp_contributors_nonce'] ) ) {
			return $post_id;
		}
		$nonce = isset( $_POST['wp_contributors_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wp_contributors_nonce'] ) ) : '';
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'wp_contributors_metabox_nonce' ) ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$contributors_users = isset( $_POST['wp_contributors'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['wp_contributors'] ) ) : '';

		update_post_meta( $post_id, 'wp_contributors_users', $contributors_users );
	}
}

/**
 * Main instance of Wp_Contibutors_Admin_Metaboxes.
 *
 * Returns the main instance of Wp_Contibutors_Admin_Metaboxes to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Wp_Contibutors_Admin_Metaboxes
 */
Wp_Contibutors_Admin_Metaboxes::instance();
