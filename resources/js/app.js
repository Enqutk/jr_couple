import './bootstrap';

function initPortfolioFilters() {
    const root = document.querySelector('[data-portfolio-filter]');
    if (!root) return;

    const buttons = root.querySelectorAll('[data-filter]');
    const items = document.querySelectorAll('[data-category]');

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');

            buttons.forEach((btn) => btn.classList.remove('active'));
            button.classList.add('active');

            items.forEach((item) => {
                const category = item.getAttribute('data-category') || 'all';
                const show = filter === 'all' || category === filter;
                item.classList.toggle('is-hidden', !show);
            });
        });
    });
}

function initCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    if (!counters.length) return;

    const animate = (el) => {
        const target = Number(el.getAttribute('data-counter') || 0);
        const suffix = el.getAttribute('data-suffix') || '';
        const duration = 1200;
        const start = performance.now();

        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const value = Math.floor(progress * target);
            el.textContent = `${value}${suffix}`;
            if (progress < 1) requestAnimationFrame(tick);
        };

        requestAnimationFrame(tick);
    };

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                animate(entry.target);
                obs.unobserve(entry.target);
            });
        },
        { threshold: 0.4 }
    );

    counters.forEach((el) => observer.observe(el));
}

function initHeader() {
    const header = document.querySelector('[data-hz-header]');
    if (!header) return;

    const onScroll = () => {
        header.classList.toggle('is-scrolled', window.scrollY > 12);
    };

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    const collapse = document.getElementById('hzMainNav');
    const toggler = document.querySelector('.hz-toggler');
    if (collapse && toggler) {
        collapse.addEventListener('shown.bs.collapse', () => {
            toggler.setAttribute('aria-expanded', 'true');
        });
        collapse.addEventListener('hidden.bs.collapse', () => {
            toggler.setAttribute('aria-expanded', 'false');
        });
    }
}

function initScrollReveal() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const targets = document.querySelectorAll(
        'main .hz-section, main .hz-cta, main .hz-services, main .hz-store-teaser, main .hz-about, main .hz-page-hero'
    );

    if (!targets.length) return;

    let lastY = window.scrollY;
    let direction = 'down';

    window.addEventListener(
        'scroll',
        () => {
            const y = window.scrollY;
            if (Math.abs(y - lastY) < 2) return;
            direction = y < lastY ? 'up' : 'down';
            lastY = y;
        },
        { passive: true }
    );

    targets.forEach((el) => {
        el.classList.add('hz-reveal');
    });

    const markInView = (el, dir) => {
        el.classList.remove('hz-reveal-from-up', 'hz-reveal-from-down');
        el.classList.add(dir === 'up' ? 'hz-reveal-from-up' : 'hz-reveal-from-down');
        void el.offsetWidth;
        el.classList.add('is-inview');
    };

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                const el = entry.target;

                if (entry.isIntersecting) {
                    markInView(el, direction);
                    return;
                }

                el.classList.remove('is-inview', 'hz-reveal-from-up', 'hz-reveal-from-down');
            });
        },
        {
            threshold: 0.14,
            rootMargin: '0px 0px -6% 0px',
        }
    );

    targets.forEach((el) => {
        observer.observe(el);
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight * 0.92 && rect.bottom > 40) {
            markInView(el, 'down');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initHeader();
    initPortfolioFilters();
    initCounters();
    initScrollReveal();
});
