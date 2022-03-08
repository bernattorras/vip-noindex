<?php
/**
 * Plugin Name: VIP noindex non-production sites.
 * Description: Prevent WordPress to index non-production sites.
 * Author: Automattic
 * Version: 1.0
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if ( defined( 'VIP_GO_APP_ENVIRONMENT' ) && 'production' !== VIP_GO_APP_ENVIRONMENT ) {
	$is_public = get_option( 'blog_public', '1' );

	if ( '1' === $is_public ) {
		// Disable the 'Search engine visibility' option if the blog is public.
		update_option( 'blog_public', '0' );
	}

	// Show an admin notification.
	add_action( 'admin_notices', 'admin_indexable_notice' );
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
