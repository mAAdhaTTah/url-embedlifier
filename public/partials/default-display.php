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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="mceItemEmbedly">
	<div class="embedly">
		<?php echo get_post_meta( $post->ID, 'embedlified_html', true ); ?>
		<!-- Support for this post type still needs to be implemented -->
	</div>
	<div class="embedly-clear"></div>
	<div class="embedly-powered">
		<a target="_blank" href="http://embed.ly?src=anywhere" title="Powered by Embedly">
			<img src="http://static.embed.ly/images/logos/embedly-powered-small-light.png" alt="Embedly Powered" />
		</a>
	</div>
	<div class="media-attribution">
		<span>via </span>
		<a href="<?php echo get_post_meta( get_the_ID(), 'embedlified_provider_url', true ); ?>" class="media-attribution-link" target="_blank">
			<?php echo get_post_meta( get_the_ID(), 'embedlified_provider_name', true ); ?>
		</a>
	</div>
	<div class="embedly-clear"></div>
</div>
