<?php
/**
 * Plugin Name: VIP noindex non-production sites.
 * Description: Prevent WordPress to index non-production sites.
 * Author: Automattic
 * Version: 1.0
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if ( 'production' !== VIP_GO_APP_ENVIRONMENT || false !== strpos( $_SERVER['HTTP_HOST'] ?? '', '.go-vip.' ) ) {
	// Discourage search engine indexing.
	add_filter( 'option_blog_public', '__return_zero' );

	// Show an admin notice to warn that the site visibility has been turned off.
	add_action( 'admin_notices', 'admin_indexable_notice' );

	// Disable the 'blog_public' checkbox.
	add_action( 'admin_footer', 'disable_blog_public_option' );
}


/**
 * Show an admin notice to inform that search engine visibility has been turned off.
 *
 * @return void
 */
function admin_indexable_notice() {
	?>
	<div class="notice notice-warning is-dismissible">
		<p><?php esc_html_e( 'Search engine visibility has been turned off to prevent indexing this non-production site.', 'vip-mu-noindex' ); ?></p>
	</div>
	<?php
}

/**
 * Disables the 'blog_public' checkbox in Settings > Reading.
 *
 * @return void
 */
function disable_blog_public_option() {
	if ( 'options-reading' !== get_current_screen()->id ) {
		return;
	}
	?>
	<script>
	jQuery('#blog_public').attr("disabled", true);
	</script>
	<?php
}
