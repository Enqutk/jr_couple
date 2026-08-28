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

function initStorePage() {
    const root = document.querySelector('.hz-store-market');
    if (!root) return;

    const grid = root.querySelector('[data-store-grid]');
    const sortSelect = root.querySelector('[data-store-sort]');
    const sheet = root.querySelector('[data-store-sheet]');
    const sheetSort = root.querySelector('[data-store-sheet-sort]');

    const sortItems = (mode) => {
        if (!grid) return;

        const items = [...grid.querySelectorAll('[data-store-item]')];
        const compare = {
            recommended: (a, b) => Number(a.dataset.order) - Number(b.dataset.order),
            newest: (a, b) => Number(b.dataset.order) - Number(a.dataset.order),
            name_asc: (a, b) => a.dataset.name.localeCompare(b.dataset.name),
            name_desc: (a, b) => b.dataset.name.localeCompare(a.dataset.name),
        }[mode] || ((a, b) => Number(a.dataset.order) - Number(b.dataset.order));

        items.sort(compare).forEach((item) => grid.appendChild(item));
    };

    sortSelect?.addEventListener('change', () => {
        sortItems(sortSelect.value);
        sheetSort?.querySelectorAll('button').forEach((btn) => {
            btn.classList.toggle('active', btn.dataset.sort === sortSelect.value);
        });
    });

    sheetSort?.addEventListener('click', (event) => {
        const button = event.target.closest('button[data-sort]');
        if (!button) return;

        const mode = button.dataset.sort;
        sheetSort.querySelectorAll('button').forEach((btn) => btn.classList.remove('active'));
        button.classList.add('active');

        if (sortSelect) {
            sortSelect.value = mode;
        }

        sortItems(mode);
    });

    root.querySelectorAll('[data-store-category-link]').forEach((input) => {
        input.addEventListener('change', () => {
            if (!input.checked) return;
            window.location.href = input.dataset.storeCategoryLink;
        });
    });

    root.querySelectorAll('[data-filter-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            toggle.closest('[data-filter-section]')?.classList.toggle('is-open');
        });
    });

    const openSheet = () => {
        if (!sheet) return;
        sheet.hidden = false;
        document.body.style.overflow = 'hidden';
    };

    const closeSheet = () => {
        if (!sheet) return;
        sheet.hidden = true;
        document.body.style.overflow = '';
    };

    root.querySelector('[data-store-filter-open]')?.addEventListener('click', openSheet);
    root.querySelectorAll('[data-store-sheet-close]').forEach((el) => {
        el.addEventListener('click', closeSheet);
    });

    const savedKey = 'jr-store-saved';
    const readSaved = () => {
        try {
            return JSON.parse(localStorage.getItem(savedKey) || '[]');
        } catch {
            return [];
        }
    };

    const writeSaved = (ids) => {
        localStorage.setItem(savedKey, JSON.stringify(ids));
    };

    root.querySelectorAll('[data-store-save]').forEach((button) => {
        const card = button.closest('[data-store-item]');
        const title = card?.querySelector('.hz-bny-card-title')?.textContent?.trim();
        if (!title) return;

        if (readSaved().includes(title)) {
            button.classList.add('is-saved');
            button.querySelector('i')?.classList.replace('bi-heart', 'bi-heart-fill');
        }

        button.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();

            const saved = readSaved();
            const index = saved.indexOf(title);
            const icon = button.querySelector('i');

            if (index >= 0) {
                saved.splice(index, 1);
                button.classList.remove('is-saved');
                icon?.classList.replace('bi-heart-fill', 'bi-heart');
            } else {
                saved.push(title);
                button.classList.add('is-saved');
                icon?.classList.replace('bi-heart', 'bi-heart-fill');
            }

            writeSaved(saved);
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initHeader();
    initPortfolioFilters();
    initCounters();
    initScrollReveal();
    initStorePage();
});
