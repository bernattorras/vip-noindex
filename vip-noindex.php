<?php
/**
 * Plugin Name: VIP noindex non-production sites.
 * Description: Prevent WordPress to index non-production sites.
 * Author: Automattic
 * Version: 1.0
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// By default, the 'vip_index_noprod_domains' filter returns an empty array (to prevent indexing any non-production sites).
$index_noprod_domains = apply_filters( 'vip_index_noprod_domains', '__return_empty_array' );

// Don't "no-index" the site if the filter 'vip_index_noprod_domains' returns true or contains the current domain in a string.
if ( true === $index_noprod_domains || in_array( $_SERVER['SERVER_NAME'], $index_noprod_domains, true ) ) {
	return;
}

// Block the indexing of non-production sites.
if ( 'production' !== VIP_GO_APP_ENVIRONMENT || false !== strpos( $_SERVER['HTTP_HOST'] ?? '', '.go-vip.' )
) {

	// Discourage search engine indexing.
	add_filter( 'option_blog_public', '__return_zero' );

	// Show an admin notice to warn that the site visibility has been turned off.
	add_action( 'admin_notices', 'admin_indexable_notice' );

	// Disable the 'blog_public' checkbox.
	add_action( 'admin_footer', 'disable_blog_public_option' );

	// Disalow site in robots.txt.
	add_action( 'do_robotstxt', 'disallow_all_user_agents' );
}

/**
 * Disallow all user-agents for the site in robots.txt
 *
 * @return void
 */
function disallow_all_user_agents() {
	echo 'User-agent: *' . PHP_EOL;
	echo 'Disallow: /' . PHP_EOL;
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
