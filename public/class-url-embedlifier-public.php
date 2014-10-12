<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://jamesdigioia.com/url-embedlifier/
 * @since      1.0.0
 *
 * @package    URL_Embedlifier
 * @subpackage URL_Embedlifier/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    URL_Embedlifier
 * @subpackage URL_Embedlifier/admin
 * @author     James DiGioia <jamesorodig@gmail.com>
 */
class URL_Embedlifier_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery' ), $this->version, false );

	}

	public function add_embedly_to_content( $content ) {
		global $post;

		if ( $url = get_post_meta( $post->ID, 'embedlified_url' , true ) ) {
			// Set up some default, reused variables
			$embedly_clear = '<div class="embedly-clear"></div>';
			$div_open = '<div class="mceItemEmbedly"><div class="embedly">';
			if ( has_post_thumbnail() ) {
				$img_thumbnail = get_the_post_thumbnail( $post->ID, 'full', array( 'class' => 'thumb embedly-thumbnail-small' ) );
			} else {
				$img_thumbnail = '<img src="' . get_post_meta( $post->ID, 'embedlified_thumbnail_url', true ) . '" class="thumb embedly-thumbnail-small" />';
			}
			$media_attribution = '<div class="media-attribution"><span>via </span><a href="' . get_post_meta( $post->ID, 'embedlified_provider_url', true ) . '" class="media-attribution-link" target="_blank">' . get_post_meta( $post->ID, 'embedlified_provider_name', true ) . '</a></div>';
			$entry_meta = '<time class="published" datetime="' . get_the_time('c') . '">' . get_the_date() . ' </time>';
			$embedly_powered = '<p><span class="embedly-powered" style="float:right;display:block"><a target="_blank" href="http://embed.ly?src=anywhere" title="Powered by Embedly"><img src="http://static.embed.ly/images/logos/embedly-powered-small-light.png" alt="Embedly Powered" /></a></span></p>';
			$div_close = '</div>';

			// Append to content based on type
			if ( get_post_meta( $post->ID, 'embedlified_type', true ) == 'link' ) {
				$embedly_content = '<div class="embedly-content">' . get_post_meta( $post->ID, 'embedlified_description', true ) . '</div></div>';

				$content = $content . $div_open . $img_thumbnail . $entry_meta . $embedly_content . $embedly_clear . $embedly_powered . $media_attribution . $embedly_clear . $div_close;

			} elseif ( get_post_meta( $post->ID, 'embedlified_type', true ) == 'video' ) {

				if( ! is_single() ) {
					$embedly_content = '<div class="embedly-content">' . get_post_meta( $post->ID, 'embedlified_description', true ) . '</div></div>';

					$content = $content . $div_open . $img_thumbnail . $entry_meta . $embedly_content . $embedly_clear . $embedly_powered . $media_attribution . $embedly_clear . $div_close;

				} else {

					$height = get_post_meta( $post->ID, 'embedlified_height', true );
					$width = get_post_meta( $post->ID, 'embedlified_width', true );
					$oembed_args = array( 'width' => $width, 'height' => $height );
					$embedly_html = wp_oembed_get( $url, $oembed_args );
					$content = $content . $entry_meta . $height . ' ' . $width . $media_attribution . $embedly_html;

				}
			} else {
				$embedly_html = get_post_meta( $post->ID, 'embedlified_html', true ) . '<!-- Support for this post type still needs to be implemented -->';
				$content = $content . $embedly_html;
			}

		}
		return $content;
	}

}
