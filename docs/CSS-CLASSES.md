# Astrology Suite for FreeAstroAPI CSS Class Reference

Use these classes in your theme CSS (`Appearance > Customize > Additional CSS`) to restyle blocks.

## Container / Layout

- `.wp-block-openastro-widget`
  - Purpose: Main block wrapper.
  - Rendered in: all `openastro/*` blocks.
- `.openastro-no-border`
  - Purpose: Removes wrapper border when border setting is disabled.
  - Rendered in: all blocks.
- `.openastro-output`
  - Purpose: Results/output container.
  - Rendered in: all blocks.

Example:

```css
.wp-block-openastro-widget {
  max-width: 900px;
  margin: 0 auto;
}
```

## Form / Input Controls

- `.openastro-form-header`
  - Purpose: Top row containing quick actions like history dropdown.
  - Rendered in: all blocks.
- `.openastro-history-label`
  - Purpose: Label for saved-input selector.
  - Rendered in: all blocks.
- `.openastro-history-select`
  - Purpose: Dropdown to load past inputs.
  - Rendered in: all blocks.
- `.openastro-form`
  - Purpose: Input form grid.
  - Rendered in: all blocks.
- `.openastro-input`
  - Purpose: Text/date/time/city fields.
  - Rendered in: all blocks.
- `.openastro-inline`
  - Purpose: Inline checkbox row (minor aspects).
  - Rendered in: natal blocks.
- `.openastro-btn`
  - Purpose: Primary submit button.
  - Rendered in: all blocks.
- `.openastro-status`
  - Purpose: Helper/status text.
  - Rendered in: all blocks.
- `.openastro-results`
  - Purpose: City autocomplete list wrapper.
  - Rendered in: all blocks.
- `.openastro-result-btn`
  - Purpose: City suggestion item.
  - Rendered in: all blocks.

Example:

```css
.openastro-input {
  border-radius: 8px;
  border-color: #d6d6d6;
}
```

## Report / Cards

- `.openastro-report-title`
  - Purpose: Main report title (`h2`).
  - Rendered in: `openastro/report-only`, `openastro/chart-report`.
- `.openastro-card`
  - Purpose: Shared report/sign/chart card panel.
  - Rendered in: report, chart, and simple-sign outputs.
- `.openastro-access-note`
  - Purpose: Access-state message shown when the current visitor cannot use protected calculation routes.
  - Rendered in: all blocks when the viewer is not logged in with content-editing permission.
- `.openastro-planet-item`
  - Purpose: Per-planet interpretation item.
  - Rendered in: report modes.

Example:

```css
.openastro-card {
  border-radius: 12px;
  border-color: #e8e8e8;
}
```

## Simple Signs

- `.openastro-simple-sign-card`
  - Purpose: Simple-sign result card.
  - Rendered in: all simple-sign blocks.
- `.openastro-simple-sign-value`
  - Purpose: Sign + degree line.
  - Rendered in: all simple-sign blocks.
- `.openastro-simple-sign-interpretation`
  - Purpose: Interpretation text section.
  - Rendered when interpretation is enabled.

Example:

```css
.openastro-simple-sign-value {
  color: #1f4a8a;
}
```

## Chart / Print / Glyphs

- `.openastro-chart-card`
  - Purpose: Natal wheel card wrapper.
  - Rendered in: `openastro/chart-only`, `openastro/chart-report`.
- `.openastro-chart`
  - Purpose: SVG chart container.
  - Rendered in: chart modes.
- `.openastro-print-btn`
  - Purpose: Chart print button.
  - Rendered when print option is enabled.
- `.openastro-astro-glyph`
  - Purpose: Astrological glyph span using AstrologySuiteStarFont.
  - Rendered in report/simple-sign output.

Example:

```css
.openastro-print-btn {
  border-radius: 999px;
}
```

## Typography Variables

Astrology Suite for FreeAstroAPI exposes CSS variables on `.wp-block-openastro-widget`:

- `--openastro-font-h2`
- `--openastro-font-h3`
- `--openastro-font-h4`
- `--openastro-font-body`
- `--openastro-font-table`
- `--openastro-font-input`
- `--openastro-font-button`
- `--openastro-font-meta`
- `--openastro-input-radius`
- `--openastro-input-border-color`
- `--openastro-input-bg`
- `--openastro-input-text`
- `--openastro-input-placeholder`
- `--openastro-button-radius`
- `--openastro-button-bg`
- `--openastro-button-text`
- `--openastro-button-border`
- `--openastro-label-color`
- `--openastro-label-weight`
- `--openastro-focus-ring`

Example:

```css
.wp-block-openastro-widget {
  --openastro-font-h2: 34px;
  --openastro-font-body: 17px;
}
```
