<?php
/**
 * Plugin Name:       Quiet Consent
 * Plugin URI:        https://oneoffboss.com/
 * Description:       A lightweight, privacy-first cookie consent banner that respects user choice.
 * Version:           1.0.0
 * Author:            LBridges, OneOffBoss
 * Author URI:        https://oneoffboss.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       quiet-consent
 *
 * @package           QuietConsent
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'QUIET_CONSENT_VERSION', '1.0.0' );

/**
 * Enqueue scripts and styles for the front-end banner.
 */
function qc_enqueue_assets() {
	// Only enqueue if we have a GA ID set in the options.
	$ga_id = get_option( 'qc_ga_id' );
	if ( empty( $ga_id ) ) {
		return;
	}

	wp_enqueue_style(
		'quiet-consent-styles',
		plugin_dir_url( __FILE__ ) . 'assets/css/quiet-consent.css',
		array(),
		QUIET_CONSENT_VERSION,
		'all'
	);

	wp_enqueue_script(
		'quiet-consent-script',
		plugin_dir_url( __FILE__ ) . 'assets/js/quiet-consent.js',
		array(),
		QUIET_CONSENT_VERSION,
		true
	);

	// Pass the Google Analytics ID to our JavaScript file securely.
	wp_localize_script(
		'quiet-consent-script',
		'quietConsent',
		array(
			'gaId' => esc_js( $ga_id ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'qc_enqueue_assets' );

/**
 * Add the banner HTML to the site footer.
 */
function qc_render_banner_html() {
	// Only render the banner if a GA ID is set and consent has not yet been given.
	$ga_id = get_option( 'qc_ga_id' );
	if ( empty( $ga_id ) ) {
		return;
	}
	?>
	<!-- Quiet Consent Banner -->
	<div id="quiet-consent-banner" class="qc-banner" role="dialog" aria-live="polite" aria-label="Cookie Consent Banner" style="display: none;">
		<div class="qc-banner-content">
			<p class="qc-banner-text">This site uses cookies for analytics. By clicking "Accept", you agree to this use.</p>
			<div class="qc-banner-buttons">
				<button id="quiet-consent-accept" class="qc-button qc-button-accept">Accept</button>
				<button id="quiet-consent-decline" class="qc-button qc-button-decline">Decline</button>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'qc_render_banner_html' );


/**
 * SETTINGS PAGE LOGIC
 * =============================================================================
 */

/**
 * Add the top-level admin menu page.
 */
function qc_add_admin_menu() {
	add_menu_page(
		'Quiet Consent Settings',      // Page Title
		'Quiet Consent',               // Menu Title
		'manage_options',              // Capability
		'quiet-consent-settings',      // Menu Slug
		'qc_render_settings_page',     // Callback function to render the page
		'dashicons-shield-alt',        // Icon
		20                             // Position
	);
}
add_action( 'admin_menu', 'qc_add_admin_menu' );

/**
 * Register the settings fields for our settings page.
 */
function qc_register_settings() {
	register_setting(
		'qc_settings_group', // Option group
		'qc_ga_id'           // Option name
	);

	add_settings_section(
		'qc_main_section',
		'Analytics Settings',
		null,
		'quiet-consent-settings'
	);

	add_settings_field(
		'qc_ga_id',
		'Google Analytics ID',
		'qc_ga_id_callback',
		'quiet-consent-settings',
		'qc_main_section'
	);
}
add_action( 'admin_init', 'qc_register_settings' );

/**
 * Callback function to render the Google Analytics ID input field.
 */
function qc_ga_id_callback() {
	$ga_id = get_option( 'qc_ga_id' );
	printf(
		'<input type="text" id="qc_ga_id" name="qc_ga_id" value="%s" class="regular-text" placeholder="G-XXXXXXXXXX" />',
		isset( $ga_id ) ? esc_attr( $ga_id ) : ''
	);
	echo '<p class="description">Enter your Google Analytics Measurement ID (e.g., G-XXXXXXXXXX).</p>';
}

/**
 * Callback function to render the main settings page HTML.
 */
function qc_render_settings_page() {
	?>
	<div class="wrap">
		<h1>Quiet Consent Settings</h1>
		<form method="post" action="options.php">
			<?php
				settings_fields( 'qc_settings_group' );
				do_settings_sections( 'quiet-consent-settings' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

