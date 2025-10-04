# Quiet Consent
## A lightweight, privacy-first WordPress cookie consent plugin that respects user choice.

### What It Is

Quiet Consent is a simple, theme-agnostic cookie banner designed for site owners who believe in a privacy-first web. Unlike complex consent management platforms, this plugin has one job: to prevent analytics scripts (like Google Analytics) from loading until a user gives their explicit, informed consent.

It's built on the principle that user trust is paramount. The banner is clean, the choices are clear, and the user's decision is always respected.

### Features
Privacy-First: No analytics scripts are loaded by default. Tracking only begins after a user clicks "Accept".

- Explicit Opt-In: The user must take an affirmative action to consent.

- Lightweight: Minimal CSS and a single, efficient JavaScript file. No bloat.

- Theme-Agnostic: Hooks into the WordPress footer and uses its own styles, so it works with any theme.

- Simple Configuration: A single settings page to add your Google Analytics ID.

### Installation

Download the quiet-consent.zip file from the latest release.

In your WordPress dashboard, go to Plugins > Add New > Upload Plugin.

Upload the zip file and activate the plugin.

### Setup

After activating, a new menu item called "Quiet Consent" will appear in your WordPress admin menu.

Navigate to the "Quiet Consent" settings page.

Enter your Google Analytics Measurement ID (e.g., G-XXXXXXXXXX) into the text field.

Click "Save Changes".

The consent banner will now appear on the front-end of your site for all new visitors.

### The Philosophy

This plugin was born from the idea that consent shouldn't be a dark pattern. For a site built on trust, a confusing cookie banner that tracks users by default is a non-starter. This plugin is an alternative for developers and site owners who want to align their technical implementation with their brand's values.

### Roadmap (Future Plans)

This is the first version, but here are some ideas for future enhancements:

[ ] Support for other analytics providers (Matomo, Plausible, etc.).

[ ] Options to customize the banner text directly from the settings page.

[ ] Color customization options.

[ ] A "Revisit Consent" button or shortcode for users to change their minds.

Built with ❤️ by One Off Boss.
