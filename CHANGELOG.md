# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.0] — 2026-06-02

### Initial release

#### Content element

- Extbase plugin (`ottestimonials_testimonials`) with dedicated domain model table
- FlexForm: storage folder picker (per content element)
- Domain model fields: quote (RTE), author name, position/role, company name, company logo (FAL)
- Sorting by `sorting` field (drag-and-drop in TYPO3 backend)

#### Slider

- CSS `transform: translateX` animation — smooth slide transition, no layout shift
- All slides remain in the DOM; `overflow: hidden` on the container clips off-screen slides
- `offsetLeft`-based offset calculation — correct with any CSS gap or padding, no manual math
- `ResizeObserver` with 50ms debounce recalculates position on any element size change
- Breakpoint-change handler (`window.matchMedia`) switches between 1-up (mobile) and 2-up (desktop, ≥lg)
- Transition temporarily disabled during resize/breakpoint change via `.no-transition` class

#### Autoplay

- Configurable interval via SiteSet setting `otTestimonials.slider.interval` (default: 7000 ms)
- Enable/disable via SiteSet setting `otTestimonials.slider.autoplay`
- Two independent pause states: hover-pause (`#hoverActive`) and user-pause (`#userPaused`)
- Hover-pause scoped to the slide track only — buttons do not trigger hover-pause
- Pause button toggles `#userPaused`; button state (`aria-pressed`) reflects user-pause only, not hover state
- Prev/Next buttons and keyboard navigation set `#userPaused = true`

#### Accessibility (WCAG 2.1 AA)

- `role="region"` with translated `aria-label` on the slider container
- `role="group"` with `aria-label="Testimonial X of Y"` on each slide
- `aria-live="off"` during autoplay; `aria-live="polite"` on manual navigation
- `aria-hidden="true"` on off-screen slides (screen readers skip them)
- `aria-pressed` on the pause button reflects user-pause state
- `ArrowLeft` / `ArrowRight` on the slider region navigate between slides
- `Escape` releases focus from the slider to the next focusable ancestor (WCAG SC 2.1.2)
- `prefers-reduced-motion`: autoplay and CSS transitions disabled when set

#### Page indicator

- Configurable via SiteSet setting `otTestimonials.slider.indicator`: `none`, `dots`, `counter`
- Dots: one `<button>` per page, clickable, keyboard-accessible, `aria-current` on active dot
- Counter: plain `X / Y` text, `aria-hidden="true"` (informational only)
- Indicator regenerated on resize and breakpoint change (page count differs between 1-up and 2-up)

#### Structured data

- JSON-LD `schema.org/ItemList` containing one `schema.org/Review` per testimonial
- Each `Review`: `reviewBody` (stripped HTML), `author` (`Person` with `name`, `jobTitle`, `worksFor`)
- `itemReviewed`: `Organization` with name from SiteSet setting `otTestimonials.itemReviewedName`
- Output via `<f:format.raw>` in the Fluid template

#### SiteSet configuration (TYPO3 v14)

- `settings.definitions.yaml` with categories `OtTestimonials.slider` and `OtTestimonials.structuredData`
- `labels.xlf` / `de.labels.xlf` in XLIFF 2.0 format — auto-resolved by TYPO3 (no `LLL:` in YAML required)
- Enum values for `indicator` setting use explicit `LLL:` references
- `setup.typoscript` maps SiteSet constants to Extbase `plugin.tx_ottestimonials.settings.*`

#### SiteKit integration

- `Configuration/SiteKit.yaml` registers `ottestimonials_testimonials` in `group_content_wide`
- `minCols: 12`, `requiresFullWidth: true`
