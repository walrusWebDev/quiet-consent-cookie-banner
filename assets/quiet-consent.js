(function () {
  'use strict';

  /**
   * Dynamically loads and initializes Google Analytics.
   * We only call this if the user gives consent.
   */
  function initializeGoogleAnalytics() {
    // Prevent this function from running more than once
    if (window.gaInitialized) {
      return;
    }

    // Get the GA ID passed from WordPress
    const gaId = quietConsent.gaId;
    if (!gaId) {
      console.warn('Quiet Consent: Google Analytics ID not found.');
      return;
    }

    // Dynamically create the Google Analytics script tag
    const gaScript = document.createElement('script');
    gaScript.async = true;
    gaScript.src = `https://www.googletagmanager.com/gtag/js?id=${gaId}`;
    document.head.appendChild(gaScript);

    // Initialize the dataLayer and configure gtag
    window.dataLayer = window.dataLayer || [];
    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', gaId);

    console.log('Quiet Consent: Google Analytics Initialized');
    window.gaInitialized = true; // Set a flag to show it has run
  }

  /**
   * Main consent logic, runs after the DOM is fully loaded.
   */
  document.addEventListener('DOMContentLoaded', () => {
    const banner = document.getElementById('quiet-consent-banner');
    const acceptBtn = document.getElementById('quiet-consent-accept');
    const declineBtn = document.getElementById('quiet-consent-decline');

    // If any of the required elements are missing, do nothing.
    if (!banner || !acceptBtn || !declineBtn) {
      return;
    }

    const consentValue = localStorage.getItem('quiet_consent');

    // If consent has already been given, initialize GA and exit.
    if (consentValue === 'accepted') {
      initializeGoogleAnalytics();
      return;
    }

    // If consent has been explicitly declined, do nothing.
    if (consentValue === 'declined') {
      return;
    }

    // If no choice has been made, show the banner.
    banner.style.display = 'block';

    // Event listener for the "Accept" button.
    acceptBtn.addEventListener('click', () => {
      localStorage.setItem('quiet_consent', 'accepted');
      banner.style.display = 'none';
      initializeGoogleAnalytics();
    });

    // Event listener for the "Decline" button.
    declineBtn.addEventListener('click', () => {
      localStorage.setItem('quiet_consent', 'declined');
      banner.style.display = 'none';
    });
  });

})();

