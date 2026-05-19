=== Astrology Suite for FreeAstroAPI ===
Contributors: omegab
Tags: astrology, natal chart, horoscope, gutenberg, blocks
Requires at least: 6.6
Tested up to: 6.9
Requires PHP: 8.1
Stable tag: 0.2.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add interactive astrology blocks with natal charts, reports, and simple sign widgets.

== Description ==

Astrology Suite for FreeAstroAPI helps you publish astrology experiences that feel fast, clean, and easy to use:

* Add interactive blocks directly in Gutenberg.
* Capture birth details with a built-in form (city autocomplete included).
* Render natal chart, natal report, or both.
* Offer simple sign widgets for Sun, Moon, Rising, and Midheaven.
* Customize styling and typography from plugin settings.
* Reuse recent requests with a "Past inputs" dropdown (stored in browser local storage).

Included blocks:

* `openastro/chart-only`
* `openastro/report-only`
* `openastro/chart-report`
* `openastro/simple-sign-sun`
* `openastro/simple-sign-moon`
* `openastro/simple-sign-rising`
* `openastro/simple-sign-midheaven`

== External services ==

Astrology Suite for FreeAstroAPI connects to FreeAstroAPI (`https://www.freeastroapi.com/`) to autocomplete cities and generate astrology calculations used by the included blocks.

What is sent and when:

* When someone types at least two characters into the city field, the plugin sends the typed city query and optional country filter to FreeAstroAPI over HTTPS so matching cities and timezones can be returned.
* When a logged-in user who can edit posts submits a natal chart, report, or simple-sign form, the plugin sends the submitted birth data and calculation options to FreeAstroAPI over HTTPS. This may include the optional name field, birth date, birth time, city name, latitude, longitude, timezone, interpretation settings, enabled extra bodies, the requested calculation mode, and whether minor aspects were requested.
* For protected calculation requests, the plugin also sends the site's configured FreeAstroAPI key in the server-to-server request header so FreeAstroAPI can authenticate the request.
* The plugin does not store visitor birth data in WordPress by default. The optional “Past inputs” feature stores recent successful requests only in the visitor's local browser storage.

FreeAstroAPI Terms of Service: https://www.freeastroapi.com/terms
FreeAstroAPI Privacy Policy: https://www.freeastroapi.com/privacy

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`, or install via WordPress plugin upload.
2. Activate **Astrology Suite for FreeAstroAPI** in the Plugins screen.
3. Get a FreeAstroAPI key and add it in **Settings > Astrology Suite**.
4. Add Astrology Suite blocks in the block editor.

== Frequently Asked Questions ==

= Do I need an API key? =

Yes. Add your key in **Settings > Astrology Suite**.

= Who can submit calculation requests? =

For security, natal chart, report, and simple-sign calculations are limited to logged-in users who can edit posts on the site. City autocomplete remains public.

= Is user birth data stored by default? =

No server-side storage is performed by default. The “Past inputs” list is kept in the visitor's local browser storage.

= Can I style the widgets? =

Yes. Use plugin settings for typography/colors and target documented CSS classes for theme-level customization.

== Changelog ==

= 0.2.2 =
* Renamed the plugin to Astrology Suite for FreeAstroAPI for WordPress.org review.
* Updated the text domain and approval-ready plugin slug to astrology-suite-freeastroapi.

= 0.2.1 =
* Documented the required FreeAstroAPI external service usage, data sent, and terms/privacy links.
* Moved upstream requests to the documented FreeAstroAPI API domain.
* Restricted API-key-backed calculation routes to authenticated users who can edit posts.
* Added privacy-policy helper text for site owners.

= 0.2.0 =
* Added Astrology Suite for FreeAstroAPI unified widget blocks and settings improvements.
* Improved input and display customization options.
* Added and refined simple sign widgets.

== Upgrade Notice ==

= 0.2.2 =
Naming and text-domain update for WordPress.org review.

= 0.2.1 =
Security and documentation update for FreeAstroAPI service disclosure and protected calculation routes.
