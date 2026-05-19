# Astrology Suite for FreeAstroAPI

Install this plugin to get Astrology Suite for FreeAstroAPI in one package.

## Includes

- Shared Astrology Suite settings page (`Settings > Astrology Suite`)
- REST proxy endpoints:
  - `GET /wp-json/openastro/v1/geo/search`
  - `POST /wp-json/openastro/v1/natal/calculate`
  - `POST /wp-json/openastro/v1/natal/chart-svg`
  - `POST /wp-json/openastro/v1/western/simple-signs`
- Gutenberg blocks (each includes the input form):
  - `openastro/chart-only`
  - `openastro/report-only`
  - `openastro/chart-report`
  - `openastro/simple-sign-sun`
  - `openastro/simple-sign-moon`
  - `openastro/simple-sign-rising`
  - `openastro/simple-sign-midheaven`

## Notes

- `Chart only` does not render a legend.
- Report rendering can be customized in plugin settings.
- Typography, colors, and border can be customized in plugin settings.
- Input and button visuals are customizable (radius, border, colors, placeholder, labels, focus ring).
- Forms include a local “Past inputs…” dropdown (last 20 successful requests, browser-local only).
- For security, natal chart, report, and simple-sign requests are limited to logged-in users who can edit posts on the site. City autocomplete remains public.
- CSS class reference:
  - [`docs/CSS-CLASSES.md`](docs/CSS-CLASSES.md)
- Visitor birth data is not persisted by default.

## External services

Astrology Suite connects to [FreeAstroAPI](https://www.freeastroapi.com/) to autocomplete cities and generate astrology calculations.

- City autocomplete sends the typed city query and optional country filter over HTTPS when someone types into the city field.
- Natal chart, report, and simple-sign requests send the submitted birth data and calculation options over HTTPS when a logged-in user who can edit posts submits the form.
- Protected calculation requests also include the site's configured FreeAstroAPI key in the server-to-server request header.
- The plugin does not store visitor birth data in WordPress by default. The optional “Past inputs” feature stores recent successful requests in the visitor's browser local storage only.

Links:

- Terms of Service: [https://www.freeastroapi.com/terms](https://www.freeastroapi.com/terms)
- Privacy Policy: [https://www.freeastroapi.com/privacy](https://www.freeastroapi.com/privacy)

## Migration

- Old `freeastro/*` block names are not registered anymore.
- Use `Settings > Astrology Suite > Run Block Migrator` once to convert legacy content to `openastro/*`.
- Existing `freeastro_*` options are auto-migrated to `openastro_*` on plugin init.
