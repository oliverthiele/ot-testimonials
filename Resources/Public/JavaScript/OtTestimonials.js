(function () {
  'use strict';

  const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
  const desktopQuery = window.matchMedia('(min-width: 992px)');

  class OtTestimonialsSlider {
    #element;
    #track;
    #slides;
    #btnPrev;
    #btnNext;
    #btnPause;
    #progressBar;
    #indicator;
    #currentIndex = 0;
    #intervalMs;
    #timer = null;
    #isPlaying = false;
    // Hover-pause: active while cursor is over the slide track — does not affect button state
    #hoverActive = false;
    // User-pause: set explicitly via pause button, prev/next, or keyboard — survives hover
    #userPaused = false;
    #indicatorType = 'none';

    constructor(element) {
      this.#element = element;
      this.#track = element.querySelector('.ot-testimonials-track');
      this.#slides = Array.from(element.querySelectorAll('.ot-testimonials-slide'));
      this.#btnPrev = element.querySelector('[data-js="testimonialPrev"]');
      this.#btnNext = element.querySelector('[data-js="testimonialNext"]');
      this.#btnPause = element.querySelector('[data-js="testimonialPause"]');
      this.#progressBar = element.querySelector('[data-js="testimonialProgressBar"]');
      this.#indicator = element.querySelector('[data-js="testimonialIndicator"]');
      this.#intervalMs = parseInt(element.dataset.interval, 10) || 7000;
      this.#indicatorType = element.dataset.indicator || 'none';

      if (this.#slides.length === 0) return;

      element.style.setProperty('--ot-testimonials-interval', this.#intervalMs + 'ms');
      this.#goTo(0, false);
      this.#bindEvents();
      this.#bindResize();

      const autoplayEnabled = element.dataset.autoplay === '1';
      if (autoplayEnabled && !reducedMotionQuery.matches) {
        this.#startAutoplay();
      }

      reducedMotionQuery.addEventListener('change', () => {
        if (reducedMotionQuery.matches) this.#stopTimer();
      });
    }

    // --- Navigation ---

    #goTo(index, announce = true) {
      const total = this.#slides.length;
      this.#currentIndex = (index + total) % total;

      const isDesktop = desktopQuery.matches;
      const visibleCount = isDesktop ? 2 : 1;
      const pageStart = isDesktop
        ? Math.floor(this.#currentIndex / 2) * 2
        : this.#currentIndex;

      // offsetLeft gives the exact pixel offset of the slide within the track,
      // correctly accounting for gap and any padding — no manual calculation needed.
      const firstSlide = this.#slides[pageStart];
      if (firstSlide) {
        this.#track.style.transform = `translateX(-${firstSlide.offsetLeft}px)`;
      }

      this.#slides.forEach((slide, slideIndex) => {
        const visible = slideIndex >= pageStart && slideIndex < pageStart + visibleCount;
        slide.setAttribute('aria-hidden', visible ? 'false' : 'true');
      });

      if (announce) {
        this.#track.setAttribute('aria-live', 'polite');
      }

      this.#resetProgress();
      this.#updateIndicator();
    }

    #next() {
      const step = desktopQuery.matches ? 2 : 1;
      this.#goTo(this.#currentIndex + step);
    }

    #prev() {
      const step = desktopQuery.matches ? 2 : 1;
      this.#goTo(this.#currentIndex - step);
    }

    // --- Autoplay ---

    #startAutoplay() {
      if (this.#isPlaying || this.#hoverActive || this.#userPaused) return;
      this.#isPlaying = true;
      this.#track.setAttribute('aria-live', 'off');
      this.#resetProgress();
      this.#timer = setInterval(() => this.#next(), this.#intervalMs);
    }

    #stopTimer() {
      if (!this.#isPlaying) return;
      this.#isPlaying = false;
      clearInterval(this.#timer);
      this.#timer = null;
      this.#track.setAttribute('aria-live', 'polite');
      this.#pauseProgress();
    }

    #onHoverEnter() {
      this.#hoverActive = true;
      this.#stopTimer();
    }

    #onHoverLeave() {
      this.#hoverActive = false;
      if (!this.#userPaused) {
        const autoplayEnabled = this.#element.dataset.autoplay === '1';
        if (autoplayEnabled && !reducedMotionQuery.matches) {
          this.#startAutoplay();
        }
      }
    }

    #toggleUserPause() {
      this.#userPaused = !this.#userPaused;
      if (this.#userPaused) {
        this.#stopTimer();
      } else if (!this.#hoverActive) {
        const autoplayEnabled = this.#element.dataset.autoplay === '1';
        if (autoplayEnabled && !reducedMotionQuery.matches) {
          this.#startAutoplay();
        }
      }
      this.#updatePauseButton();
    }

    #updatePauseButton() {
      if (!this.#btnPause) return;
      this.#btnPause.setAttribute('aria-pressed', this.#userPaused ? 'true' : 'false');
    }

    // --- Progress bar ---

    #resetProgress() {
      if (!this.#progressBar) return;
      this.#progressBar.classList.remove('is-active', 'is-paused');
      void this.#progressBar.offsetWidth;
      if (this.#isPlaying) {
        this.#progressBar.classList.add('is-active');
      }
    }

    #pauseProgress() {
      if (!this.#progressBar) return;
      this.#progressBar.classList.add('is-paused');
    }

    // --- Indicator ---

    #getPageCount() {
      return desktopQuery.matches
        ? Math.ceil(this.#slides.length / 2)
        : this.#slides.length;
    }

    #getCurrentPage() {
      return desktopQuery.matches
        ? Math.floor(this.#currentIndex / 2)
        : this.#currentIndex;
    }

    #updateIndicator() {
      if (!this.#indicator || this.#indicatorType === 'none') return;

      const pageCount = this.#getPageCount();
      const currentPage = this.#getCurrentPage();
      const labelPage = this.#element.dataset.labelPage || 'Page {0} of {1}';
      const labelCurrent = this.#element.dataset.labelCurrent || 'Current page';

      this.#indicator.innerHTML = '';

      if (this.#indicatorType === 'dots') {
        for (let i = 0; i < pageCount; i++) {
          const button = document.createElement('button');
          button.type = 'button';
          button.className = 'ot-testimonials-dot' + (i === currentPage ? ' is-active' : '');
          button.setAttribute(
            'aria-label',
            labelPage.replace('{0}', i + 1).replace('{1}', pageCount)
          );
          if (i === currentPage) {
            button.setAttribute('aria-current', 'true');
            button.setAttribute('aria-label',
              labelCurrent + ' — ' + labelPage.replace('{0}', i + 1).replace('{1}', pageCount)
            );
          }
          button.addEventListener('click', () => {
            this.#userPaused = true;
            this.#stopTimer();
            this.#updatePauseButton();
            this.#goTo(desktopQuery.matches ? i * 2 : i);
          });
          this.#indicator.appendChild(button);
        }
      } else if (this.#indicatorType === 'counter') {
        const span = document.createElement('span');
        span.className = 'ot-testimonials-counter';
        span.textContent = `${currentPage + 1} / ${pageCount}`;
        this.#indicator.appendChild(span);
      }
    }

    // --- Resize handling ---

    #bindResize() {
      let resizeTimer = null;

      // ResizeObserver detects size changes regardless of what caused them
      // (viewport resize, scrollbar appearing, zoom, etc.).
      new ResizeObserver(() => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          // Reposition without CSS transition to avoid a visible snap/jump
          this.#track.classList.add('no-transition');
          this.#goTo(this.#currentIndex, false);
          // Re-enable transition after the browser has painted the new position
          requestAnimationFrame(() => this.#track.classList.remove('no-transition'));
        }, 50);
      }).observe(this.#element);
    }

    // --- Events ---

    #bindEvents() {
      this.#btnPrev?.addEventListener('click', () => {
        this.#userPaused = true;
        this.#stopTimer();
        this.#updatePauseButton();
        this.#prev();
      });

      this.#btnNext?.addEventListener('click', () => {
        this.#userPaused = true;
        this.#stopTimer();
        this.#updatePauseButton();
        this.#next();
      });

      this.#btnPause?.addEventListener('click', () => this.#toggleUserPause());

      // Hover-pause only on the slide track, not on the controls or indicator
      this.#track.addEventListener('mouseenter', () => this.#onHoverEnter());
      this.#element.addEventListener('mouseleave', () => this.#onHoverLeave());

      // Keyboard navigation
      this.#element.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') {
          event.preventDefault();
          this.#userPaused = true;
          this.#stopTimer();
          this.#updatePauseButton();
          this.#prev();
        } else if (event.key === 'ArrowRight') {
          event.preventDefault();
          this.#userPaused = true;
          this.#stopTimer();
          this.#updatePauseButton();
          this.#next();
        } else if (event.key === 'Escape') {
          this.#userPaused = true;
          this.#stopTimer();
          this.#updatePauseButton();
          const focusTarget = this.#element.closest('[tabindex]') || document.body;
          focusTarget.focus({ preventScroll: true });
        }
      });

      // Recalculate when the lg breakpoint is crossed (layout switches between 1-up and 2-up)
      desktopQuery.addEventListener('change', () => {
        this.#track.classList.add('no-transition');
        this.#goTo(this.#currentIndex, false);
        requestAnimationFrame(() => this.#track.classList.remove('no-transition'));
      });
    }
  }

  document.querySelectorAll('[data-js="testimonialSlider"]').forEach(element => {
    new OtTestimonialsSlider(element);
  });
})();
