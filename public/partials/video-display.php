<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://jamesdigioia.com/url-embedlifier/
 * @since      1.0.0
 *
 * @package    URL_Embedlifier
 * @subpackage URL_Embedlifier/public/partials
 */

// Set the thumbnail URL
if ( has_post_thumbnail() ) {
	$thumb_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
	$thumb_url = $thumb_src[0];
} else {
	$thumb_url = get_post_meta( get_the_ID(), 'embedlified_thumbnail_url', true );
}
?>
<div class="url-embedlified">

	<div class="url-embedlified-title">
		<h1><a href="<?php echo get_post_meta( get_the_ID(), 'embedlified_url' , true ); ?>" title="<?php echo get_post_meta( get_the_ID(), 'embedlified_title', true ); ?>"><?php echo get_post_meta( get_the_ID(), 'embedlified_title', true ); ?></a></h1>
	</div>

	<div class="url-embedlified-content">
		<?php
			echo wp_oembed_get(
				get_post_meta( get_the_ID(), 'embedlified_url' , true ),
				array(
					'width' => get_post_meta( $post->ID, 'embedlified_width', true ),
					'height' => get_post_meta( $post->ID, 'embedlified_height', true )
				)
			);
		?>
	</div>

	<div class="url-embedlified-clear"></div>

	<div class="url-embedlified-footer">
		<div class="url-embedlified-media-attribution">
			<span>via </span><a href="<?php echo get_post_meta( get_the_ID(), 'embedlified_provider_url', true ); ?>" class="media-attribution-link" target="_blank"><?php echo get_post_meta( get_the_ID(), 'embedlified_provider_name', true ); ?></a>
		</div>
	</div>

	<div class="clear"></div>

</div>
