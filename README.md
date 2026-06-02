# Testimonials — TYPO3 Accessible Testimonials Slider

A TYPO3 v14 testimonials slider built for **WCAG 2.1 AA compliance**: autoplay with pause control, keyboard
navigation, hover-pause scoped to the slide area, `prefers-reduced-motion` support, and `schema.org/Review`
structured data for SEO.

[![TYPO3](https://img.shields.io/badge/TYPO3-14.3-orange.svg)](https://typo3.org/)
[![Packagist Version](https://img.shields.io/packagist/v/oliverthiele/ot-testimonials.svg)](https://packagist.org/packages/oliverthiele/ot-testimonials)
[![PHP](https://img.shields.io/packagist/dependency-v/oliverthiele/ot-testimonials/php.svg)](https://php.net/)
[![License](https://img.shields.io/packagist/l/oliverthiele/ot-testimonials.svg)](LICENSE)
[![Changelog](https://img.shields.io/badge/Changelog-CHANGELOG.md-blue.svg)](CHANGELOG.md)

---

## Features

- **WCAG 2.1 AA accessible** — `role="region"`, `aria-live`, `role="group"` per slide, full keyboard support
- **Autoplay with pause control** — explicit pause button; hover-pause scoped to slides only (not controls)
- **Keyboard navigation** — `ArrowLeft` / `ArrowRight` navigate, `Escape` releases focus from the slider
- **`prefers-reduced-motion`** — autoplay and CSS transitions disabled automatically when set by the visitor
- **CSS transform slider** — smooth `translateX` animation, no layout shift (all slides remain in the DOM)
- **Responsive** — 2 slides per page on desktop (≥lg), 1 on mobile; `ResizeObserver` recalculates on any resize
- **Optional page indicator** — dots (clickable, keyboard-accessible) or text counter (e.g. `1 / 3`)
- **schema.org/Review structured data** — JSON-LD `ItemList` of `Review` objects with author, job title, company
- **SiteSet configuration** — TYPO3 v14 SiteSet; autoplay, indicator and structured data name configurable site-wide
- **FlexForm** — storage folder selectable per content element

---

## Requirements

| Requirement | Version |
|-------------|---------|
| TYPO3       | 14.3+   |
| PHP         | 8.4+    |
| Bootstrap   | 5.x     |

---

## Installation

```bash
composer require oliverthiele/ot-testimonials
```

Then run the TYPO3 setup:

```bash
vendor/bin/typo3 extension:setup -e ot_testimonials
# or via DDEV:
ddev exec typo3 extension:setup -e ot_testimonials
```

Run the database schema update to create the testimonials table:

```bash
vendor/bin/typo3 database:updateschema
```

---

## Configuration

### 1. Add SiteSet

Add the extension as a SiteSet dependency in your site configuration (`config/sites/yoursite/config.yaml`):

```yaml
dependencies:
    - oliverthiele/ot-testimonials
```

### 2. Add SCSS

Import the source SCSS into your project build. The file uses Bootstrap 5 variables and mixins, so it must be
imported after Bootstrap is set up:

```scss
@import 'path/to/vendor/oliverthiele/ot-testimonials/Resources/Private/Scss/OtTestimonials';
```

### 3. Load TypoScript

If your project does not rely exclusively on SiteSets for TypoScript, import the setup file in your sitepackage:

```typoscript
@import 'EXT:ot_testimonials/Configuration/TypoScript/setup.typoscript'
```

### 4. Create testimonial records

Create a sysfolder in the TYPO3 page tree and add testimonial records there. Each record has:

- **Quote** (RTE)
- **Author name**
- **Position / Role**
- **Company name**
- **Company logo** (FAL image)

### 5. Add content element

Insert a **Testimonials** content element on any page, set the storage folder in the FlexForm, and the slider
renders automatically.

---

## SiteSet Settings

All settings are configurable via the TYPO3 backend site wizard or TypoScript constants.

### Slider

| Setting | Default | Description |
|---------|---------|-------------|
| `otTestimonials.slider.interval` | `7000` | Autoplay interval in milliseconds. |
| `otTestimonials.slider.autoplay` | `1` | Enable autoplay (`1` = on, `0` = off). Always disabled when visitor has `prefers-reduced-motion` set. |
| `otTestimonials.slider.indicator` | `none` | Page indicator below the slider: `none`, `dots` (clickable), `counter` (e.g. `1 / 3`). |

### Structured Data

| Setting | Default | Description |
|---------|---------|-------------|
| `otTestimonials.itemReviewedName` | `` | Name of the organisation being reviewed in `schema.org/Review` structured data (`itemReviewed`). Example: `Acme Corporation`. |

---

## FlexForm Options (per content element)

| Option | Description |
|--------|-------------|
| **Storage folder** | One or more TYPO3 sysfolders containing the testimonial records to display. |

---

## Accessibility

The slider is built around WCAG 2.1 AA requirements:

| Criterion | Implementation |
|-----------|----------------|
| SC 1.3.1 Info and Relationships | `role="region"`, `role="group"` with `aria-label` per slide |
| SC 2.1.1 Keyboard | `ArrowLeft` / `ArrowRight` navigate; all controls are standard `<button>` elements |
| SC 2.1.2 No Keyboard Trap | `Escape` moves focus out of the slider region |
| SC 2.2.2 Pause, Stop, Hide | Pause/Resume button; hover-pause on slide area; `prefers-reduced-motion` disables autoplay |
| SC 4.1.2 Name, Role, Value | `aria-pressed` on pause button; `aria-current` on active dot |

`aria-live="off"` during autoplay (prevents screen reader interruptions); switches to `aria-live="polite"` on
manual navigation.

---

## Template Customization

Override template paths in your sitepackage TypoScript:

```typoscript
plugin.tx_ottestimonials {
    view {
        templateRootPaths.20 = EXT:your_sitepackage/Resources/Private/Templates/
        partialRootPaths.20  = EXT:your_sitepackage/Resources/Private/Partials/
        layoutRootPaths.20   = EXT:your_sitepackage/Resources/Private/Layouts/
    }
}
```

### Template variables

| Variable | Type | Description |
|----------|------|-------------|
| `{testimonials}` | `QueryResultInterface` | All testimonial records from the configured storage folder |
| `{structuredData}` | `string` | Ready-to-output `<script type="application/ld+json">` block |
| `{settings}` | `array` | All resolved SiteSet + FlexForm settings |

### Data attributes (slider root element)

The `<section data-js="testimonialSlider">` element carries configuration for the JavaScript:

| Attribute | Source | Description |
|-----------|--------|-------------|
| `data-interval` | `settings.interval` | Autoplay interval in ms |
| `data-autoplay` | `settings.autoplay` | `1` or `0` |
| `data-indicator` | `settings.indicator` | `none`, `dots`, or `counter` |
| `data-label-page` | `locallang.xlf` | Translated aria-label pattern for dot buttons |
| `data-label-current` | `locallang.xlf` | Translated "current page" label for active dot |

---

## SiteKit Integration (optional)

If your project uses [OT SiteKit Base](https://github.com/oliverthiele/ot-sitekit-base), the extension ships with a
`Configuration/SiteKit.yaml` that registers the content element with the SiteKit grid system:

```yaml
elements:
    - ctype: ottestimonials_testimonials
      groups: [group_content_wide]
      grid: { minCols: 12, requiresFullWidth: true }
```

Without SiteKit installed, this file is ignored.

---

## License

GPL-2.0-or-later — see [LICENSE](LICENSE)

## Author

Oliver Thiele — [oliver-thiele.de](https://www.oliver-thiele.de)
