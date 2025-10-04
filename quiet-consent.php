<?php
/**
 * Plugin Name:       Quiet Consent
 * Plugin URI:        https://oneoffboss.com/
 * Description:       A lightweight, privacy-first cookie consent banner that respects user choice.
 * Version:           1.0.0
 * Author:            OneOffBoss
 * Author URI:        https://oneoffboss.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       quiet-consent
 *
 * @package           QuietConsent
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main plugin class
 */
class Quiet_Consent {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'render_banner_html' ] );
	}

	/**
	 * Add top-level admin menu
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'Quiet Consent', 'quiet-consent' ),
			__( 'Quiet Consent', 'quiet-consent' ),
			'manage_options',
			'quiet-consent',
			[ $this, 'settings_page_html' ],
			'dashicons-lock',
			30
		);
	}

	/**
	 * Register plugin settings
	 */
	public function register_settings() {
		register_setting( 'qc_settings_group', 'qc_ga_id' );
		register_setting( 'qc_settings_group', 'qc_banner_style' );

		add_settings_section(
			'qc_general_section',
			__( 'General Settings', 'quiet-consent' ),
			'__return_false',
			'quiet-consent'
		);

		add_settings_field(
			'qc_ga_id',
			__( 'Google Analytics ID', 'quiet-consent' ),
			[ $this, 'ga_id_render' ],
			'quiet-consent',
			'qc_general_section'
		);
		
		add_settings_field(
			'qc_banner_style',
			__( 'Banner Style', 'quiet-consent' ),
			[ $this, 'banner_style_render' ],
			'quiet-consent',
			'qc_general_section'
		);
	}

	/**
	 * Render the GA ID input field
	 */
	public function ga_id_render() {
		$ga_id = get_option( 'qc_ga_id' );
		echo '<input type="text" name="qc_ga_id" value="' . esc_attr( $ga_id ) . '" class="regular-text" placeholder="G-XXXXXXXXXX">';
		echo '<p class="description">' . __( 'Enter your Google Analytics Measurement ID.', 'quiet-consent' ) . '</p>';
	}
	
	/**
	 * Render the Banner Style radio buttons
	 */
	public function banner_style_render() {
		$style = get_option( 'qc_banner_style', 'bar' );
		?>
		<fieldset>
			<label>
				<input type="radio" name="qc_banner_style" value="bar" <?php checked( $style, 'bar' ); ?>>
				<span><?php esc_html_e( 'Bottom Bar (Default)', 'quiet-consent' ); ?></span>
			</label>
			<br>
			<label>
				<input type="radio" name="qc_banner_style" value="floating" <?php checked( $style, 'floating' ); ?>>
				<span><?php esc_html_e( 'Floating Box', 'quiet-consent' ); ?></span>
			</label>
			<p class="description"><?php esc_html_e( 'Choose the appearance of the consent banner.', 'quiet-consent' ); ?></p>
		</fieldset>
		<?php
	}


	/**
	 * HTML for the settings page
	 */
	public function settings_page_html() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'qc_settings_group' );
				do_settings_sections( 'quiet-consent' );
				submit_button( __( 'Save Settings', 'quiet-consent' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue_scripts() {
		$ga_id = get_option( 'qc_ga_id' );
		if ( empty( $ga_id ) ) {
			return;
		}

		wp_enqueue_style( 'quiet-consent-css', plugin_dir_url( __FILE__ ) . 'assets/css/quiet-consent.css', [], '1.1.0' );
		wp_enqueue_script( 'quiet-consent-js', plugin_dir_url( __FILE__ ) . 'assets/js/quiet-consent.js', [], '1.1.0', true );

		// Pass the GA ID to the JavaScript file
		wp_localize_script( 'quiet-consent-js', 'quietConsent', [ 'gaId' => esc_js( $ga_id ) ] );
	}

	/**
	 * Render the banner HTML in the footer
	 */
	public function render_banner_html() {
		$ga_id = get_option( 'qc_ga_id' );
		if ( empty( $ga_id ) ) {
			return;
		}
		
		$style = get_option( 'qc_banner_style', 'bar' );
		$style_class = 'qc-banner--style-' . esc_attr( $style );
		?>
		<!-- Quiet Consent Banner -->
		<div id="quiet-consent-banner" class="qc-banner <?php echo $style_class; ?>" role="dialog" aria-live="polite" aria-label="Cookie Consent Banner">
			<div class="qc-banner-content">
				<p class="qc-banner-text">This site uses cookies for analytics. By clicking "Accept", you agree to this use.</p>
				<div class="qc-banner-buttons">
					<button id="quiet-consent-decline" class="qc-button qc-button-decline">Decline</button>
					<button id="quiet-consent-accept" class="qc-button qc-button-accept">Accept</button>
				</div>
			</div>
		</div>
		<?php
	}
}

new Quiet_Consent();

