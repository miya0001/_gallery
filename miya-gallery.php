<?php
/**
 * Plugin Name:     Miya Gallery
 * Plugin URI:      https://github.com/miya0001/miya-gallery
 * Description:     Grid style gallery plugin.
 * Author:          Takayuki Miyauchi
 * Author URI:      https://miya.io/
 * Version:         0.1.1
 *
 * @package         Masonry_Gallery
 */

require_once( 'lib/GH_Auto_Update.php' );

class Masonry_Gallery
{
	public function __construct()
	{
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'init', array( $this, 'autoupdate' ) );
	}

	function autoupdate()
	{
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH.'wp-admin/includes/plugin.php';
		}
		$plugin = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );

		// set auto-update params
		$plugin_current_version = $plugin[ basename( __FILE__ ) ]['Version'];
		$gh_user                = 'miya0001';
		$gh_repo                = 'miya-gallery';
		$plugin_slug            = plugin_basename( __FILE__ );

		new GH_Auto_Update( $plugin_current_version, $gh_user, $gh_repo, $plugin_slug );
	}

	public function plugins_loaded()
	{
		add_filter( 'post_gallery', array( $this, 'post_gallery' ), 10, 3 );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
	}

	public function wp_enqueue_scripts()
	{
		wp_enqueue_style(
			'masonry-gallery',
			plugins_url( 'css/masonry-gallery.css', __FILE__ ),
			array(),
			filemtime( dirname( __FILE__ ) . '/css/masonry-gallery.css' )
		);
	}

	public function post_gallery( $output = null, $attr, $instance )
	{
		$post = get_post();

		if ( ! empty( $attr['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attr['orderby'] ) ) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		$html5 = current_theme_supports( 'html5', 'gallery' );
		$atts = shortcode_atts( array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post ? $post->ID : 0,
			'columns'    => 3,
			'size'       => 'medium',
			'include'    => '',
			'exclude'    => '',
			'link'       => 'file'
		), $attr, 'gallery' );

		$id = intval( $atts['id'] );

		if ( ! empty( $atts['include'] ) ) {
			$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( ! empty( $atts['exclude'] ) ) {
			$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		} else {
			$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		}

		if ( empty( $attachments ) ) {
			return '';
		}

		$columns = intval( $atts['columns'] );

		$output = "<div class='masonry-gallery' style='column-count: {$columns}; -moz-column-count: {$columns};'>";

		foreach ( $attachments as $id => $attachment ) {
			if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
				$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false );
			} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
				$image_output = wp_get_attachment_image( $id, $atts['size'], false );
			} else {
				$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false );
			}

			$output .= "<figure class='masonry-gallery-item'>";
			$output .= $image_output;
			$output .= "</figure>";
		}

		$output .= "</div>";

		return $output;
	}
}

$masonry_gallery = new Masonry_Gallery();
