<?php
/**
 * Plugin Name:     _Gallery
 * Plugin URI:      https://github.com/miya0001/_gallery
 * Description:     Grid style gallery plugin.
 * Author:          Takayuki Miyauchi
 * Author URI:      https://miya.io/
 * Version:         nightly
 *
 * @package         Miya_Gallery
 */

require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

class _Gallery
{
	public function __construct()
	{
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'init', array( $this, 'auto_update' ) );
	}

	function auto_update()
	{
		$plugin_slug = plugin_basename( __FILE__ );
		$gh_user = 'miya0001';
		$gh_repo = '_gallery';
		new Miya\WP\GH_Auto_Updater( $plugin_slug, $gh_user, $gh_repo );
	}

	public function plugins_loaded()
	{
		add_filter( 'post_gallery', array( $this, 'post_gallery' ), 9999, 3 );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
	}

	public function wp_enqueue_scripts()
	{
		wp_enqueue_script(
			'underscore-gallery',
			plugins_url( 'js/script.min.js', __FILE__ ),
			array( 'jquery' ),
			filemtime( dirname( __FILE__ ) . '/js/script.min.js' ),
			true
		);
		wp_enqueue_style(
			'underscore-gallery',
			plugins_url( 'css/style.min.css', __FILE__ ),
			array(),
			filemtime( dirname( __FILE__ ) . '/css/style.min.css' )
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

		$output = "<div class='underscore-gallery' style='column-count: {$columns}; -moz-column-count: {$columns};'>";

		foreach ( $attachments as $id => $attachment ) {
			if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
				$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false );
			} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
				$image_output = wp_get_attachment_image( $id, $atts['size'], false );
			} else {
				$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false );
			}

			$output .= "<figure class='underscore-gallery-item'>";
			$output .= $image_output;
			$output .= "</figure>";
		}

		$output .= "</div>";

		return $output;
	}
}

$masonry_gallery = new _Gallery();
