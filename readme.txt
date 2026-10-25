=== Astrology Suite for FreeAstroAPI ===
Contributors: omegab
Tags: astrology, natal chart, horoscope, vedic astrology, blocks
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 8.1
Stable tag: 0.5.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Publish Western and Vedic charts, reports, horoscopes, ephemerides, practitioner tools, and an astrology bot with FreeAstroAPI.

== Description ==

Astrology Suite for FreeAstroAPI is a WordPress toolkit for publishing interactive astrology without exposing API credentials to visitors. The site owner connects one FreeAstroAPI key, sets the request-per-second limit for that key's current plan, and publishes responsive blocks or shortcodes.

= Western astrology =

* Render a natal wheel, interpretation report, or combined chart and report.
* Offer focused Sun, Moon, Rising, and Midheaven widgets.
* Publish monthly ephemeris tables with body filters, tropical or sidereal mode, optional local points, and CSV download.
* Add daily sign and personalized horoscope experiences.

= Vedic astrology =

* Display D1, D9, and other supported vargas in North, South, or East Indian layouts.
* Give astrologers a detailed workbench with grahas, Vimshottari dashas, yogas and doshas, Panchang, Shadbala, Ashtakavarga, Avastha, and practitioner presets.
* Control Vedic chart layout, labels, node type, colors, sizing, titles, and saved defaults from dedicated settings.
* Style Vedic result panels, summary tiles, tables, and badges inside the consolidated Vedic Astrology tab with an instant no-request preview.

= Built for WordPress site owners =

* Use Gutenberg blocks or equivalent shortcodes in classic content and templates.
* Keep FreeAstroAPI credentials in server-side WordPress settings; visitors never enter or receive the saved key.
* Respect different FreeAstroAPI plans with a configurable site-wide request queue, short jittered retries, and a safe 1 RPS default.
* Capture birth details with city autocomplete, explicit 24-hour time controls, and an optional browser-local Past inputs list.
* Choose one supported Western house system for reports, chart wheels, previews, focused angle calculations, and Western bot context.
* Choose True Node, Mean Node, or no lunar node without allowing duplicate node variants in one natal calculation.
* Let Western natal users mark birth time as unknown; exact birth time remains required for Vedic calculations.
* Localize frontend labels and select the natal interpretation response language.
* Choose built-in chart themes, edit chart request bodies, or use a custom chart_config from the FreeAstroAPI Chart Designer.
* Allow public calculations or restrict them to logged-in editors.
* Add a Western/Vedic astrology bot powered by FreeAstroAPI tools and, on WordPress 7.0+, a provider connected under Settings > Connectors.

Included blocks:

* `astrology-suite/chart-only`
* `astrology-suite/report-only`
* `astrology-suite/chart-report`
* `astrology-suite/simple-sign-sun`
* `astrology-suite/simple-sign-moon`
* `astrology-suite/simple-sign-rising`
* `astrology-suite/simple-sign-midheaven`
* `astrology-suite/ephemeris`
* `astrology-suite/daily-horoscope`
* `astrology-suite/personalized-horoscope`
* `astrology-suite/vedic-chart`
* `astrology-suite/vedic-workbench`
* `astrology-suite/astrology-bot`

Included shortcodes:

* `[astrology_suite mode="chart-report"]`
* `[astrology_suite mode="chart-report" language="vi"]`
* `[astrology_suite_chart_only]`
* `[astrology_suite_report_only]`
* `[astrology_suite_chart_report]`
* `[astrology_suite_simple_sign_sun include_interpretation="true" interpretation_style="improved"]`
* `[astrology_suite_simple_sign_moon include_interpretation="false"]`
* `[astrology_suite_simple_sign_rising include_interpretation="true" interpretation_style="poetic"]`
* `[astrology_suite_simple_sign_midheaven]`
* `[astrology_suite_ephemeris]`
* `[astrology_suite_daily_horoscope]`
* `[astrology_suite_personalized_horoscope]`
* `[astrology_suite_vedic_chart]`
* `[astrology_suite_vedic_workbench]`
* `[astrology_suite_vedic_workbench preset="custom" layout="south_indian" divisions="1,9,10" modules="yogas,panchang,shadbala"]`
* `[astrology_suite_astrology_bot]`

== External services ==

Astrology Suite for FreeAstroAPI connects to FreeAstroAPI (`https://www.freeastroapi.com/`) to autocomplete cities and generate astrology calculations used by the included blocks. A standard chart/report installation only needs a FreeAstroAPI key. The optional Astrology Bot uses Google Gemini for its answer layer.

What is sent and when:

* When someone types at least two characters into the city field, the plugin sends the typed city query and optional country filter to FreeAstroAPI over HTTPS so matching cities and timezones can be returned.
* When a visitor submits a natal chart, report, simple-sign, ephemeris, horoscope, Vedic chart/workbench, or astrology bot form while public calculations are enabled, or when an authenticated editor submits while public calculations are disabled, the plugin sends the submitted astrology data and calculation options to FreeAstroAPI over HTTPS. This may include the optional name field, birth date, birth time, city name, latitude, longitude, timezone, interpretation settings, selected bodies, zodiac type, Vedic vargas/modules/layout, horoscope dates, chat message/history/profile, the requested calculation mode, and whether minor aspects were requested.
* Astrology bot requests may send the chat message, recent chat history, selected Western/Vedic system, and submitted birth profile to FreeAstroAPI MCP and Google Gemini over HTTPS. On WordPress 7.0+, Gemini access is managed by the Google provider connected under Settings > Connectors.
* Calculation requests also include the site's configured FreeAstroAPI key in the server-to-server request header so FreeAstroAPI can authenticate the request. The API key is not sent to the visitor's browser.
* On WordPress versions before 7.0, Astrology bot answer requests use the legacy Gemini key saved in plugin settings. On WordPress 7.0+, the WordPress AI Client uses the Google connector, and the legacy key is ignored.
* The plugin does not store visitor birth data in WordPress by default. The optional “Past inputs” feature stores recent successful requests only in the visitor's local browser storage.

FreeAstroAPI Terms of Service: https://www.freeastroapi.com/terms
FreeAstroAPI Privacy Policy: https://www.freeastroapi.com/privacy
FreeAstroAPI Chart Designer: https://www.freeastroapi.com/dashboard/chart-designer
FreeAstroAPI Ephemeris Docs: https://www.freeastroapi.com/docs/western/ephemeris
FreeAstroAPI Daily Sign Horoscope Docs: https://www.freeastroapi.com/docs/western/daily-sign
FreeAstroAPI Daily Personal Horoscope Docs: https://www.freeastroapi.com/docs/western/daily-personal
FreeAstroAPI Vedic Visual Chart Docs: https://www.freeastroapi.com/docs/vedic/visual-chart
FreeAstroAPI Full Vedic Calculate Docs: https://www.freeastroapi.com/docs/vedic/calculate
FreeAstroAPI Western Natal Docs: https://www.freeastroapi.com/docs/western/natal
FreeAstroAPI Astrology Bot: https://www.freeastroapi.com/astrology-bot
Google Gemini API Key Docs for legacy WordPress versions: https://ai.google.dev/gemini-api/docs/api-key

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`, or install via WordPress plugin upload.
2. Activate **Astrology Suite for FreeAstroAPI** in the Plugins screen.
3. Get a FreeAstroAPI key and add it in **Settings > Astrology Suite**.
4. Set **API requests per second** to match the saved key's FreeAstroAPI plan. The safe default is 1.
5. Add Astrology Suite blocks in the block editor.
6. Or add Astrology Suite shortcodes in shortcode-ready content.
7. Optional: To use the Astrology Bot on WordPress 7.0+, connect Google under **Settings > Connectors**.

== Frequently Asked Questions ==

= Do I need an API key? =

Yes. Add your key in **Settings > Astrology Suite**.

The WordPress site owner supplies one key for the site. Visitors use the published forms and never need to enter the key; authenticated API requests are proxied server-side.

= Which API rate setting should I use? =

Set **API requests per second** to the current requests-per-second allowance published for the site owner's FreeAstroAPI plan. The setting accepts different and future plan limits and defaults conservatively to 1. FreeAstroAPI still enforces the plan's daily or monthly quota.

= Who can submit calculation requests? =

By default, public visitors can submit astrology calculation forms. Site admins can disable public calculations in **Settings > Astrology Suite > API** to restrict natal chart, report, simple-sign, ephemeris, daily horoscope, personalized horoscope, and astrology bot calculations to logged-in users who can edit posts. City autocomplete remains public.

= Is user birth data stored by default? =

No server-side storage is performed by default. The “Past inputs” list is kept in the visitor's local browser storage.

= Can I style the widgets? =

Yes. Use **Style**, **Vedic Astrology**, **Ephemeris Style**, and **Typography** for visual controls and live previews. The documented CSS classes support theme-level customization.

= Which Western house system and lunar node are used? =

Placidus is the default. In **Settings > Astrology Suite > Objects**, choose Placidus, Whole Sign, Equal, Koch, Regiomontanus, Porphyry, or Campanus. The same choice is applied to reports, chart wheels, previews, focused Rising/Midheaven calculations, and Western bot natal context.

The same tab lets you choose True Node, Mean Node, or no lunar node. True and Mean Node cannot be requested together, which prevents duplicate North Node positions and self-aspects.

= Why do frontend settings look stale after I save them? =

Full-page caches can retain the inline frontend configuration generated before the settings change. Purge WordPress, host, CDN, and browser caches after changing Report, Objects, Style, or Custom Chart settings. If those settings change frequently, exclude the exact chart page from full-page caching. The plugin calculation routes use POST requests under `/wp-json/astrology-suite/v1/` and should also be bypassed by any reverse proxy that caches API traffic.

= What is the difference between Vedic Visual Chart and Vedic Astrology Workbench? =

Vedic Visual Chart is the focused Kundli view for one or more vargas such as D1 and D9. Vedic Astrology Workbench combines chart output with practitioner references including grahas, dashas, yogas, Panchang, strength modules, and divisional analysis.

= Why does an admin chart preview say it is waiting or unavailable? =

Preview requests share the same site-wide FreeAstroAPI queue as frontend calculations. Leave the settings page open briefly so previews can respect the configured plan limit, then retry only if the message remains after the queue has had time to run.

== Screenshots ==

1. Vedic Visual Chart with guided defaults, regional layout, divisional-chart selection, and generated output.
2. Consolidated Vedic Astrology settings with presets, chart defaults, and explicit ayanamsha/house-system guidance.
3. Vedic result appearance preview with live local styling and no settings-page API request.
4. Vedic Astrology Workbench with summary cards, section navigation, dashas, yogas, Panchang, strength, Ashtakavarga, and varga references.

== Changelog ==

= 0.5.0 =
* Reorganized all Vedic defaults, chart controls, workbench modules, result styling, and previews into one Vedic Astrology settings tab with five internal sections.
* Added Guided, Practitioner, and Custom Vedic presets for site defaults and individual blocks.
* Added per-block and shortcode overrides for Vedic preset, Kundli layout, divisional charts, and result modules.
* Expanded Vedic visual support to the additional divisional charts documented by FreeAstroAPI.
* Added a section-based Vedic workbench with keyboard-accessible Grahas, Dashas, Yogas, Panchang, Strength, Ashtakavarga, and Varga views.
* Added documented title, subtitle, brand, house, and badge chart color controls under an advanced disclosure.
* Changed the generated Vedic settings preview to load only when requested, reducing API usage while editing settings.

= 0.4.4 =
* Tested with WordPress 7.1.3.
* Uses the WordPress 7.0+ AI Client and Connectors screen for Astrology Bot answers, with the existing direct provider retained only for older WordPress versions.
* Hardened upstream requests by disabling redirects, requiring safe public URLs, and limiting response sizes.
* Tightened REST payload validation, calendar-date checks, text limits, and visitor-wide rate limiting.
* Hardened SVG sanitization against external resource references and oversized or deeply nested documents.
* Prevented API credentials from being autoloaded and added no-store headers to API responses.

= 0.4.3 =
* Added a Western house-system selector for Placidus, Whole Sign, Equal, Koch, Regiomontanus, Porphyry, and Campanus.
* Synchronized the selected house system across natal reports, chart wheels, previews, Rising/Midheaven calculations, and Western bot natal context.
* Replaced independent True Node and Mean Node toggles with one exclusive node selector to prevent duplicate North Node positions and self-aspects.
* Added a persistent localized 24-hour birth-time label to frontend forms.
* Prevented custom chart request bodies from overriding managed house-system, zodiac, node, and object settings.
* Fixed object settings so disabling optional objects no longer silently re-enables ASC and MC, and chart display settings now pass MC explicitly.

= 0.4.2 =
* Added real frontend and settings screenshots for Vedic Visual Chart, Vedic Astrology Workbench, Vedic Chart settings, and the Vedic style preview.
* Reworked the plugin description and expanded Help with balanced guidance for Western, Vedic, ephemeris, horoscope, and bot experiences, plus setup, styling, rate-limit, privacy, and production guidance.
* Added Vedic result styling controls for workbench surfaces, text, borders, accents, summary tiles, table headers, and badges.
* Added a representative live Vedic preview to the Style settings tab; changing a color updates it instantly without using an API request.
* Applied saved Vedic styling to both Vedic Visual Chart and Vedic Astrology Workbench frontend results.
* Isolated the Vedic preview table from WordPress admin responsive table rules so its columns remain compact and readable at narrow widths or browser zoom.

= 0.4.1 =
* Added a configurable site-wide FreeAstroAPI requests-per-second limit with a safe 1 RPS default for plan-aware traffic control.
* Added short, jittered frontend retries for local and upstream RPS responses so multi-request widgets remain usable on the Free plan.
* Added upstream 429 classification and retry timing without retrying exhausted daily or monthly quotas.
* Added idempotency keys to authenticated FreeAstroAPI POST requests.
* Hardened visitor throttling so untrusted `X-Forwarded-For` and user-agent changes cannot bypass the per-visitor limit.
* Documented the bundled public-domain StarFont Sans license and provenance.
* Fixed Custom Chart preview cards so they wait for site-wide RPS slots instead of failing when loaded together.
* Added a Vedic Chart settings tab with persistent frontend defaults, custom colors, sizing overrides, and a generated D1/D9 preview.
* Kept H1-H12 house labels permanently hidden from Vedic visual charts.

= 0.4.0 =
* Added Vedic Visual Chart and Vedic Astrology Workbench blocks and shortcodes.
* Added server-side proxies for the V2 visual chart and full Vedic calculate endpoints.
* Added North, South, and East Indian layouts, varga selection, practitioner presets, dasha depth, yogas, Panchang, Shadbala, Ashtakavarga, Avastha, and node controls.
* Added responsive Vedic result views for grahas, active dashas, yoga evidence, strength, and varga reference data.

= 0.3.2 =
* Added `astrology-suite/astrology-bot` block and `[astrology_suite_astrology_bot]` shortcode for the FreeAstroAPI Western/Vedic astrology bot experience.
* Added `/wp-json/astrology-suite/v1/astrology-bot/chat` REST proxy using the saved FreeAstroAPI key for MCP tool calls and the saved Gemini key for chat answers.
* Added API settings for Gemini API key/model and natal interpretation response language.
* Added Astrology Bot style controls for shell, header, screen, accent, and chat bubble colors.
* Added multilingual natal/simple-sign interpretation request support using FreeAstroAPI's supported language values.

= 0.3.1 =
* Added `astrology-suite/daily-horoscope` block and `[astrology_suite_daily_horoscope]` shortcode using the FreeAstroAPI Daily Sign Horoscope endpoint.
* Added `astrology-suite/personalized-horoscope` block and `[astrology_suite_personalized_horoscope]` shortcode using the FreeAstroAPI Daily Personal Horoscope endpoint.
* Added configurable planet aspect links that open FreeAstroAPI aspect interpretation blocks in a modal.
* Added optional aspect glyph display and thematic interpretation settings.
* Improved astrology glyph mappings for ASC, MC, Lilith, and aspect table rows.
* Improved report headings so missing house data no longer displays as "House -".

= 0.3.0 =
* Added `astrology-suite/ephemeris` block and `[astrology_suite_ephemeris]` shortcode.
* Added `/wp-json/astrology-suite/v1/western/ephemeris` REST proxy for FreeAstroAPI monthly table grids.
* Added ephemeris frontend with month navigation, body selectors, optional city-based local points, tropical/sidereal mode, CSV download, and plugin-matched table styling.
* Added Ephemeris Style settings for table grid, header, badge, today row, retrograde, ingress, and station colors.

= 0.2.9 =
* Added an API setting to allow public visitor calculations while keeping the FreeAstroAPI key server-side.
* Changed the API key field to "leave blank to keep current key" so the saved key is not echoed into admin page HTML.
* Split settings saves by tab to avoid hidden-field key exposure and accidental cross-tab option persistence.
* Optimized frontend requests so chart-only calls only chart SVG, report-only calls only natal calculation, and chart-report calls both.
* Sanitized upstream SVG chart responses before returning them to the frontend.
* Added explicit "Customize this body" and "Reset to preset" controls for per-theme chart API bodies.

= 0.2.8 =
* Added per-theme FreeAstroAPI chart request body editors for chart themes.
* Chart preview PNGs and frontend SVG chart requests now use the selected theme body.
* Updated Custom Chart help text for SVG/PNG chart body fields such as display_settings, fixed_stars, and chart_config.

= 0.2.7 =
* Added a Custom Chart settings tab with chart theme presets and FreeAstroAPI-generated PNG preview cards.
* Added an advanced custom chart_config JSON textarea for configs generated in FreeAstroAPI Chart Designer.
* Chart SVG requests now use the selected preset or custom chart_config.

= 0.2.6 =
* Added shortcode support for all Astrology Suite widgets.
* Added generic `[astrology_suite mode="..."]` shortcode plus named `[astrology_suite_*]` shortcodes.
* Added legacy `[openastro]`, `[openastro_*]`, `[freeastro]`, and `[freeastro_*]` shortcode aliases.

= 0.2.5 =
* Added `astrology-suite/*` block names and `/wp-json/astrology-suite/v1/*` REST routes.
* Kept `openastro/*` blocks and `/wp-json/openastro/v1/*` routes as legacy compatibility aliases.
* Updated Help and CSS docs to use Astrology Suite naming.

= 0.2.4 =
* Improved the city autocomplete dropdown with richer rows, country filters, and keyboard selection.

= 0.2.3 =
* Updated natal chart SVG generation to use FreeAstroAPI's current `/api/v1/natal/chart/` endpoint.
* Improved default widget form styling for cleaner inputs, buttons, and past-input controls.

= 0.2.2 =
* Renamed the plugin to Astrology Suite for FreeAstroAPI for WordPress.org review.
* Updated the text domain and approval-ready plugin slug to astrology-suite-freeastroapi.

= 0.2.1 =
* Documented the required FreeAstroAPI external service usage, data sent, and terms/privacy links.
* Moved upstream requests to the documented FreeAstroAPI API domain.
* Added authenticated-user protection for API-key-backed calculation routes.
* Added privacy-policy helper text for site owners.

= 0.2.0 =
* Added Astrology Suite for FreeAstroAPI unified widget blocks and settings improvements.
* Improved input and display customization options.
* Added and refined simple sign widgets.

== Upgrade Notice ==

= 0.5.0 =
Adds a consolidated Vedic Astrology workspace, audience presets, per-block Vedic controls, expanded divisional charts, and a shorter section-based workbench.

= 0.4.4 =
Adds WordPress 7.1 compatibility and security hardening for API requests, REST input, SVG output, rate limiting, and API credentials.

= 0.4.3 =
Keeps Western house and node calculations consistent between reports and chart wheels, and clarifies 24-hour birth-time entry.

= 0.4.2 =
Adds dedicated Vedic result styling controls and a no-request live preview in plugin settings.

= 0.4.1 =
Adds plan-aware site-wide API throttling, safe short retries, stronger visitor identification, and production packaging fixes.

= 0.4.0 =
Adds Vedic visual-chart and full workbench experiences for users and professional astrologers.

= 0.3.2 =
Adds the Astrology Bot block/shortcode, Gemini key setting, bot style controls, and multilingual natal response language support.

= 0.3.1 =
Adds aspect interpretation modals, optional aspect glyphs, thematic interpretation groups, and cleaner report headings.

= 0.3.0 =
Adds the ephemeris block/shortcode, monthly table REST proxy, and Ephemeris Style controls.

= 0.2.9 =
Adds public calculation controls, safer API key handling, sanitized chart SVG responses, and mode-specific API calls.

= 0.2.8 =
Adds per-theme SVG/PNG chart request body editors and applies those bodies to preview PNGs and frontend chart SVGs.

= 0.2.7 =
Adds Custom Chart settings for chart SVG presets, previews, and custom chart_config JSON.

= 0.2.6 =
Adds shortcode support for all widget modes while keeping block behavior unchanged.

= 0.2.5 =
Adds Astrology Suite block and REST namespaces while keeping existing OpenAstro aliases compatible.

= 0.2.3 =
Fixes natal chart SVG rendering by using the current FreeAstroAPI chart endpoint.

= 0.2.2 =
Naming and text-domain update for WordPress.org review.

= 0.2.1 =
Security and documentation update for FreeAstroAPI service disclosure and API-key-backed calculation routes.
