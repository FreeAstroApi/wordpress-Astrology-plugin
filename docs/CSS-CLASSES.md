# Astrology Suite for FreeAstroAPI CSS Class Reference

Use these classes in your theme CSS (`Appearance > Customize > Additional CSS`) to restyle blocks.

## Container / Layout

- `.wp-block-astrology-suite-widget`
  - Purpose: Main block wrapper.
  - Rendered in: all `astrology-suite/*` blocks.
- `.astrology-suite-no-border`
  - Purpose: Removes wrapper border when border setting is disabled.
  - Rendered in: all blocks.
- `.astrology-suite-output`
  - Purpose: Results/output container.
  - Rendered in: all blocks.
- `.astrology-suite-ephemeris`
  - Purpose: Ephemeris workbench wrapper.
  - Rendered in: `astrology-suite/ephemeris`.

Legacy `.wp-block-openastro-widget` and `.openastro-*` classes remain supported for existing pages and custom CSS.

Example:

```css
.wp-block-astrology-suite-widget {
  max-width: 900px;
  margin: 0 auto;
}
```

## Form / Input Controls

- `.astrology-suite-form-header`
  - Purpose: Top row containing quick actions like history dropdown.
  - Rendered in: all blocks.
- `.astrology-suite-history-label`
  - Purpose: Label for saved-input selector.
  - Rendered in: all blocks.
- `.astrology-suite-history-select`
  - Purpose: Dropdown to load past inputs.
  - Rendered in: all blocks.
- `.astrology-suite-load-recent-wrap`
  - Purpose: Saved-input selector wrapper.
  - Rendered in: all blocks.
- `.astrology-suite-load-recent-label`
  - Purpose: Saved-input selector label.
  - Rendered in: all blocks.
- `.astrology-suite-load-recent-select`
  - Purpose: Saved-input select control.
  - Rendered in: all blocks.
- `.astrology-suite-load-recent-panel`
  - Purpose: Saved-input selector panel.
  - Rendered in: all blocks.
- `.astrology-suite-form`
  - Purpose: Input form grid.
  - Rendered in: all blocks.
- `.astrology-suite-input`
  - Purpose: Text/date/time/city fields.
  - Rendered in: all blocks.
- `.astrology-suite-city-field`
  - Purpose: City autocomplete field wrapper and suggestion popover anchor.
  - Rendered in: all blocks.
- `.astrology-suite-city-input`
  - Purpose: City autocomplete text field.
  - Rendered in: all blocks.
- `.astrology-suite-city-loading`
  - Purpose: City autocomplete loading, empty, error, and selected-state message.
  - Rendered in: all blocks.
- `.astrology-suite-inline`
  - Purpose: Inline checkbox row (minor aspects).
  - Rendered in: natal blocks.
- `.astrology-suite-btn`
  - Purpose: Primary submit button.
  - Rendered in: all blocks.
- `.astrology-suite-status`
  - Purpose: Helper/status text.
  - Rendered in: all blocks.
- `.astrology-suite-results`
  - Purpose: City autocomplete list wrapper.
  - Rendered in: all blocks.
- `.astrology-suite-result-btn`
  - Purpose: City suggestion item.
  - Rendered in: all blocks.
- `.astrology-suite-result-main`, `.astrology-suite-result-primary`, `.astrology-suite-result-name`, `.astrology-suite-result-country`, `.astrology-suite-result-meta`, `.astrology-suite-result-meta-item`
  - Purpose: City suggestion row text, country badge, metadata row, and metadata items.
  - Rendered in: all blocks.

Example:

```css
.astrology-suite-input {
  border-radius: 8px;
  border-color: #d6d6d6;
}
```

## Report / Cards

- `.astrology-suite-report-title`
  - Purpose: Main report title (`h2`).
  - Rendered in: `astrology-suite/report-only`, `astrology-suite/chart-report`.
- `.astrology-suite-card`
  - Purpose: Shared report/sign/chart card panel.
  - Rendered in: report, chart, and simple-sign outputs.
- `.astrology-suite-access-note`
  - Purpose: Access-state message shown when public calculations are disabled and the current visitor cannot use calculation routes.
  - Rendered in: all blocks when public calculations are disabled and the viewer is not logged in with content-editing permission.
- `.astrology-suite-planet-item`
  - Purpose: Per-planet interpretation item.
  - Rendered in: report modes.

Example:

```css
.astrology-suite-card {
  border-radius: 12px;
  border-color: #e8e8e8;
}
```

## Simple Signs

- `.astrology-suite-simple-sign-card`
  - Purpose: Simple-sign result card.
  - Rendered in: all simple-sign blocks.
- `.astrology-suite-simple-sign-value`
  - Purpose: Sign + degree line.
  - Rendered in: all simple-sign blocks.
- `.astrology-suite-simple-sign-interpretation`
  - Purpose: Interpretation text section.
  - Rendered when interpretation is enabled.

Example:

```css
.astrology-suite-simple-sign-value {
  color: #1f4a8a;
}
```

## Horoscope

- `.astrology-suite-horoscope-form`
  - Purpose: Sign/date/timezone or birth-data horoscope form wrapper.
  - Rendered in: `astrology-suite/daily-horoscope`, `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-card`
  - Purpose: Horoscope result card.
  - Rendered in: `astrology-suite/daily-horoscope`, `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-theme-row`
  - Purpose: Wrapper for the returned theme and keyword chips.
  - Rendered in: `astrology-suite/daily-horoscope`, `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-theme`
  - Purpose: Horoscope theme badge.
  - Rendered in: `astrology-suite/daily-horoscope`, `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-keywords`
  - Purpose: Keyword chip list returned by the horoscope endpoint.
  - Rendered in: `astrology-suite/daily-horoscope`, `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-summary`
  - Purpose: Main forecast paragraph.
  - Rendered in: `astrology-suite/daily-horoscope`, `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-scores`
  - Purpose: Overall/love/career/money/health score grid.
  - Rendered in: `astrology-suite/daily-horoscope`, `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-score-factors`
  - Purpose: Expandable "Why these scores?" section built from score factor explanations.
  - Rendered in: `astrology-suite/daily-horoscope`, `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-personal`
  - Purpose: Personalized timing and transit context wrapper.
  - Rendered in: `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-personal-meta`
  - Purpose: Focus area and confidence chips for personalized horoscope results.
  - Rendered in: `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-transits`
  - Purpose: Personalized top-transits grid.
  - Rendered in: `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-transit-meta`
  - Purpose: Score/exact-time metadata for a personalized transit card.
  - Rendered in: `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-timing`
  - Purpose: Timing context panel for next shifts, exact hits, active windows, and dominant topics.
  - Rendered in: `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-lucky`
  - Purpose: Lucky attributes such as color, number, time, and direction.
  - Rendered in: `astrology-suite/daily-horoscope`, `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-guidance`
  - Purpose: Do, avoid, and supporting insight lists.
  - Rendered in: `astrology-suite/daily-horoscope`, `astrology-suite/personalized-horoscope`.
- `.astrology-suite-horoscope-astro`
  - Purpose: Moon phase/sign and sky highlight chips.
  - Rendered in: `astrology-suite/daily-horoscope`, `astrology-suite/personalized-horoscope`.

Horoscope CSS variables:

- `--astrology-suite-horoscope-card-bg`
- `--astrology-suite-horoscope-card-text`
- `--astrology-suite-horoscope-card-border`
- `--astrology-suite-horoscope-theme-bg`
- `--astrology-suite-horoscope-theme-text`
- `--astrology-suite-horoscope-chip-bg`
- `--astrology-suite-horoscope-chip-text`
- `--astrology-suite-horoscope-chip-border`
- `--astrology-suite-horoscope-tile-bg`
- `--astrology-suite-horoscope-tile-text`
- `--astrology-suite-horoscope-tile-border`
- `--astrology-suite-horoscope-accent`

## Chart / Print / Glyphs

- `.astrology-suite-chart-card`
  - Purpose: Natal wheel card wrapper.
  - Rendered in: `astrology-suite/chart-only`, `astrology-suite/chart-report`.
- `.astrology-suite-chart`
  - Purpose: SVG chart container.
  - Rendered in: chart modes.
- `.astrology-suite-print-btn`
  - Purpose: Chart print button.
  - Rendered when print option is enabled.
- `.astrology-suite-astro-glyph`
  - Purpose: Astrological glyph span using AstrologySuiteStarFont.
  - Rendered in report/simple-sign output.

Example:

```css
.astrology-suite-print-btn {
  border-radius: 999px;
}
```

## Ephemeris

- `.astrology-suite-ephemeris-toolbar`
  - Purpose: Month/year, zodiac, city, refresh, and download toolbar.
  - Rendered in: `astrology-suite/ephemeris`.
- `.astrology-suite-ephemeris-filter`
  - Purpose: Body selector panel.
  - Rendered in: `astrology-suite/ephemeris`.
- `.astrology-suite-ephemeris-chip`
  - Purpose: Selectable body chip.
  - Rendered in: `astrology-suite/ephemeris`.
- `.astrology-suite-ephemeris-table-card`
  - Purpose: Ephemeris table frame.
  - Rendered in: `astrology-suite/ephemeris`.
- `.astrology-suite-ephemeris-table`
  - Purpose: Monthly grid table.
  - Rendered in: `astrology-suite/ephemeris`.
- `.astrology-suite-ephemeris-table-meta`
  - Purpose: Time basis label in the ephemeris table header.
  - Rendered in: `astrology-suite/ephemeris`.
- `.astrology-suite-ephemeris-row-badge`
  - Purpose: Table row count badge.
  - Rendered in: `astrology-suite/ephemeris`.
- `.astrology-suite-ephemeris-row`
  - Purpose: Table row; current day also receives `.is-today`.
  - Rendered in: `astrology-suite/ephemeris`.
- `.astrology-suite-ephemeris-cell`
  - Purpose: Planet/body table value; retrograde, ingress, and station cells also receive `.is-retrograde`, `.is-ingress`, or `.is-station`.
  - Rendered in: `astrology-suite/ephemeris`.
- `.astrology-suite-ephemeris-glyph`
  - Purpose: Planet/point glyph inside chips and headers.
  - Rendered in: `astrology-suite/ephemeris`.

Example:

```css
.astrology-suite-ephemeris-table {
  min-width: 80rem;
}
```

## Vedic Chart and Workbench

- `.astrology-suite-vedic`, `.astrology-suite-vedic-intro`, `.astrology-suite-vedic-form`
  - Purpose: Main Vedic experience, heading, and birth/options form.
  - Rendered in: `astrology-suite/vedic-chart` and `astrology-suite/vedic-workbench`.
- `.astrology-suite-vedic-options`, `.astrology-suite-vedic-advanced`, `.astrology-suite-vedic-control-grid`, `.astrology-suite-vedic-division-grid`
  - Purpose: Varga selection, calculation modules, practitioner controls, and responsive option grids.
- `.astrology-suite-vedic-chart-card`, `.astrology-suite-vedic-chart`
  - Purpose: Sanitized FreeAstroAPI Vedic SVG chart frame.
- `.astrology-suite-vedic-report`, `.astrology-suite-vedic-summary-grid`
  - Purpose: Workbench overview and Lagna, Chandra, Surya, and Sade Sati summary.
- `.astrology-suite-vedic-result-tabs`, `.astrology-suite-vedic-result-tab`, `.astrology-suite-vedic-result-panels`, `.astrology-suite-vedic-result-panel`
  - Purpose: Keyboard-accessible workbench navigation for Grahas, Dashas, Yogas, Panchang, Strength, Ashtakavarga, and Vargas. The tab row scrolls horizontally on narrow screens.
- `.astrology-suite-vedic-section`, `.astrology-suite-vedic-table-wrap`
  - Purpose: Expandable professional reference sections and responsive tables.
- `.astrology-suite-vedic-dasha-grid`, `.astrology-suite-vedic-progress`
  - Purpose: Active Vimshottari periods and progress indicators.
- `.astrology-suite-vedic-yoga-grid`, `.astrology-suite-vedic-panchang-grid`
  - Purpose: Active yoga/dosha evidence and birth Panchang tiles.
- `.astrology-suite-vedic-strength`, `.astrology-suite-vedic-ashtaka-grid`, `.astrology-suite-vedic-varga-grid`
  - Purpose: Shadbala assessments, Sarvashtakavarga points, and selected varga references.

Vedic result CSS variables:

- `--astrology-suite-vedic-surface-bg`
- `--astrology-suite-vedic-surface-text`
- `--astrology-suite-vedic-surface-border`
- `--astrology-suite-vedic-accent`
- `--astrology-suite-vedic-tile-bg`
- `--astrology-suite-vedic-tile-text`
- `--astrology-suite-vedic-tile-border`
- `--astrology-suite-vedic-table-header-bg`
- `--astrology-suite-vedic-table-header-text`
- `--astrology-suite-vedic-badge-bg`
- `--astrology-suite-vedic-badge-text`

## Astrology Bot

- `.astrology-suite-bot`
  - Purpose: Astrology bot shell wrapper.
  - Rendered in: `astrology-suite/astrology-bot`.
- `.astrology-suite-bot-phone`
  - Purpose: Framed chat surface.
  - Rendered in: `astrology-suite/astrology-bot`.
- `.astrology-suite-bot-header`, `.astrology-suite-bot-kicker`, `.astrology-suite-bot-status-pill`
  - Purpose: Bot title and current loading/online state.
  - Rendered in: `astrology-suite/astrology-bot`.
- `.astrology-suite-bot-contacts`, `.astrology-suite-bot-contact`
  - Purpose: Western/Vedic bot selector.
  - Rendered in: `astrology-suite/astrology-bot`.
- `.astrology-suite-bot-profile`, `.astrology-suite-bot-profile-grid`, `.astrology-suite-bot-field`, `.astrology-suite-bot-profile-save`, `.astrology-suite-bot-profile-status`
  - Purpose: Birth profile form and save status.
  - Rendered in: `astrology-suite/astrology-bot`.
- `.astrology-suite-bot-messages`, `.astrology-suite-bot-message`, `.astrology-suite-bot-message-assistant`, `.astrology-suite-bot-message-user`
  - Purpose: Chat history and assistant/user bubbles.
  - Rendered in: `astrology-suite/astrology-bot`.
- `.astrology-suite-bot-quick`, `.astrology-suite-bot-quick-btn`
  - Purpose: Suggested prompt buttons.
  - Rendered in: `astrology-suite/astrology-bot`.
- `.astrology-suite-bot-composer`, `.astrology-suite-bot-input`, `.astrology-suite-bot-send`, `.astrology-suite-bot-chat-status`
  - Purpose: Chat input, send button, and status text.
  - Rendered in: `astrology-suite/astrology-bot`.

Astrology Bot CSS variables:

- `--astrology-suite-bot-shell-bg`
- `--astrology-suite-bot-header-bg`
- `--astrology-suite-bot-screen-bg`
- `--astrology-suite-bot-accent`
- `--astrology-suite-bot-user-bubble-bg`
- `--astrology-suite-bot-user-bubble-text`
- `--astrology-suite-bot-assistant-bubble-bg`
- `--astrology-suite-bot-assistant-bubble-text`

## Typography Variables

Astrology Suite for FreeAstroAPI exposes CSS variables on `.wp-block-astrology-suite-widget`:

- `--astrology-suite-font-h2`
- `--astrology-suite-font-h3`
- `--astrology-suite-font-h4`
- `--astrology-suite-font-body`
- `--astrology-suite-font-table`
- `--astrology-suite-font-input`
- `--astrology-suite-font-button`
- `--astrology-suite-font-meta`
- `--astrology-suite-input-radius`
- `--astrology-suite-input-border-color`
- `--astrology-suite-input-bg`
- `--astrology-suite-input-text`
- `--astrology-suite-input-placeholder`
- `--astrology-suite-button-radius`
- `--astrology-suite-button-bg`
- `--astrology-suite-button-text`
- `--astrology-suite-button-border`
- `--astrology-suite-label-color`
- `--astrology-suite-label-weight`
- `--astrology-suite-focus-ring`

## Ephemeris Table Variables

The Ephemeris Style settings tab writes these variables on `.wp-block-astrology-suite-widget`:

- `--astrology-suite-ephemeris-table-bg`
- `--astrology-suite-ephemeris-table-text`
- `--astrology-suite-ephemeris-table-border`
- `--astrology-suite-ephemeris-header-bg`
- `--astrology-suite-ephemeris-header-text`
- `--astrology-suite-ephemeris-badge-bg`
- `--astrology-suite-ephemeris-badge-text`
- `--astrology-suite-ephemeris-today-bg`
- `--astrology-suite-ephemeris-today-accent`
- `--astrology-suite-ephemeris-retrograde`
- `--astrology-suite-ephemeris-ingress`
- `--astrology-suite-ephemeris-station`

Legacy `--openastro-*` variables remain supported as fallbacks.

Example:

```css
.wp-block-astrology-suite-widget {
  --astrology-suite-font-h2: 34px;
  --astrology-suite-font-body: 17px;
}
```
