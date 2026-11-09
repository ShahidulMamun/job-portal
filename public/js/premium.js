/* LivejobsBD – premium front-end behaviour. Vanilla JS, no dependencies. */
(function () {
    'use strict';

    var $ = function (s, r) { return (r || document).querySelector(s); };
    var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function el(tag, cls, text) {
        var n = document.createElement(tag);
        if (cls) n.className = cls;
        if (text != null) n.textContent = text;
        return n;
    }
    function icon(cls) { var i = el('i'); i.className = cls; return i; }

    /* ---------------------------------------------------------------- Header */
    (function header() {
        var h = $('#siteHeader'), t = $('#navToggle');
        if (!h) return;
        var onScroll = function () { h.classList.toggle('is-solid', window.scrollY > 16); };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        if (!t) return;
        t.addEventListener('click', function () {
            var open = h.classList.toggle('is-open');
            t.setAttribute('aria-expanded', String(open));
            h.classList.add('is-solid');
        });
        $$('#mainNav a').forEach(function (a) {
            a.addEventListener('click', function () { h.classList.remove('is-open'); t.setAttribute('aria-expanded', 'false'); });
        });
    })();

    /* ------------------------------------------- Scroll UX (progress, mini search, to-top) */
    (function scrollUx() {
        var header = $('#siteHeader'), bar = $('#scrollProgress'), top = $('#toTop');
        var mainBox = $('main form[data-search]');
        var ticking = false;

        function update() {
            ticking = false;
            var y = window.scrollY, max = document.documentElement.scrollHeight - window.innerHeight;
            if (bar && max > 0) bar.style.transform = 'scaleX(' + Math.min(y / max, 1) + ')';
            if (top) top.classList.toggle('is-visible', y > 900);
        }
        window.addEventListener('scroll', function () {
            if (!ticking) { ticking = true; requestAnimationFrame(update); }
        }, { passive: true });
        update();

        if (top) top.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' }); });

        // When the big search box scrolls out of view, move a compact search into the header
        if (header && mainBox && 'IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) {
                var e = entries[0];
                header.classList.toggle('show-search', !e.isIntersecting && e.boundingClientRect.top < 0);
            }, { threshold: 0 }).observe(mainBox);
        }
    })();

    /* ------------------------------------------------------- CV drop zone */
    (function cvDrop() {
        var zone = $('#cvDrop'), input = $('#cvFile');
        if (!zone || !input) return;
        var idle = $('.cvdrop-idle', zone), done = $('.cvdrop-done', zone), err = $('.cvdrop-error', zone), nameEl = $('[data-cv-name]', zone);
        var MAX = 2 * 1024 * 1024, OK = /\.(pdf|docx?)$/i;

        function fail(msg) { err.textContent = msg; err.hidden = false; }
        function accept(file) {
            err.hidden = true;
            if (!file) return;
            if (!OK.test(file.name)) { fail('Please choose a PDF, DOC or DOCX file.'); return; }
            if (file.size > MAX) { fail('That file is over 2 MB. Please choose a smaller one.'); return; }
            nameEl.textContent = file.name;
            idle.hidden = true; done.hidden = false;
        }
        function reset() { input.value = ''; err.hidden = true; done.hidden = true; idle.hidden = false; }

        zone.addEventListener('click', function (e) { if (e.target.closest('a, [data-cv-reset]')) return; if (!done.hidden) return; input.click(); });
        zone.addEventListener('keydown', function (e) {
            if ((e.key === 'Enter' || e.key === ' ') && e.target === zone && done.hidden) { e.preventDefault(); input.click(); }
        });
        input.addEventListener('change', function () { accept(input.files[0]); });
        ['dragenter', 'dragover'].forEach(function (n) { zone.addEventListener(n, function (e) { e.preventDefault(); zone.classList.add('is-over'); }); });
        ['dragleave', 'drop'].forEach(function (n) { zone.addEventListener(n, function (e) { e.preventDefault(); zone.classList.remove('is-over'); }); });
        zone.addEventListener('drop', function (e) { accept(e.dataTransfer && e.dataTransfer.files[0]); });
        $('[data-cv-reset]', zone).addEventListener('click', function (e) { e.stopPropagation(); reset(); });
    })();

    /* ---------------------------------------------------------------- Reveal */
    (function reveal() {
        var items = $$('.reveal');
        if (!items.length) return;
        if (!('IntersectionObserver' in window) || reduceMotion) { items.forEach(function (n) { n.classList.add('is-in'); }); return; }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); } });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        // light stagger for siblings in grids
        $$('.cat-grid, .city-grid, .company-grid, .steps').forEach(function (g) {
            $$('.reveal', g).forEach(function (n, i) { n.style.setProperty('--d', (i % 4) * 70 + 'ms'); });
        });
        items.forEach(function (n) { io.observe(n); });
    })();

    /* ---------------------------------------------------------- Count-up stats */
    (function counters() {
        var nums = $$('[data-count]');
        if (!nums.length || !('IntersectionObserver' in window) || reduceMotion) return;
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;
                io.unobserve(e.target);
                var node = e.target, end = parseInt(node.getAttribute('data-count'), 10) || 0, t0 = null, dur = 1100;
                if (end < 2) return;
                (function tick(ts) {
                    if (t0 === null) t0 = ts;
                    var p = Math.min((ts - t0) / dur, 1), eased = 1 - Math.pow(1 - p, 3);
                    node.textContent = Math.round(end * eased).toLocaleString('en-US');
                    if (p < 1) requestAnimationFrame(tick);
                })(performance.now());
            });
        }, { threshold: 0.6 });
        nums.forEach(function (n) { io.observe(n); });
    })();

    /* ------------------------------------------------------------- Job tabs */
    (function tabs() {
        var bar = $('#jobTabs'), grid = $('#jobGrid');
        if (!bar || !grid) return;
        var cards = $$('.job-card', grid), empty = $('#tabEmpty');
        bar.addEventListener('click', function (e) {
            var b = e.target.closest('button[data-tab]');
            if (!b) return;
            $$('button', bar).forEach(function (x) { x.classList.toggle('is-active', x === b); });
            var tab = b.getAttribute('data-tab'), shown = 0;
            cards.forEach(function (c) {
                var ok = tab === 'all' ||
                    (tab === 'featured' && c.dataset.featured === '1') ||
                    (tab === 'remote' && c.dataset.remote === '1') ||
                    c.dataset.type === tab;
                c.hidden = !ok;
                if (ok) shown++;
            });
            if (empty) empty.hidden = shown > 0;
        });
    })();

    /* ---------------------------------------------------- Mobile filter drawer */
    (function drawer() {
        var open = $('[data-open-filters]');
        if (!open) return;
        var set = function (v) { document.body.classList.toggle('filters-open-state', v); };
        open.addEventListener('click', function () { set(true); });
        $$('[data-close-filters]').forEach(function (b) { b.addEventListener('click', function () { set(false); }); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') set(false); });
    })();

    /* Auto-submit filter/sort forms when a control changes */
    $$('form[data-autosubmit]').forEach(function (f) {
        var timer;
        f.addEventListener('change', function () {
            // on mobile the drawer has an explicit "Show jobs" button; desktop applies instantly
            if (f.closest('.filters') && window.matchMedia('(max-width: 1080px)').matches) return;
            clearTimeout(timer);
            timer = setTimeout(function () { f.submit(); }, 150);
        });
    });

    /* ===================================================== Smart search box */
    var CITIES = ['Dhaka', 'Chattogram', 'Sylhet', 'Rajshahi', 'Khulna', 'Barishal', 'Rangpur', 'Mymensingh',
        'Gazipur', 'Narayanganj', 'Cumilla', "Cox's Bazar", 'Bogura', 'Jashore', 'Savar', 'Tongi'];
    var RECENT_KEY = 'lj_recent_searches';

    function readRecent() {
        try { var v = JSON.parse(localStorage.getItem(RECENT_KEY) || '[]'); return Array.isArray(v) ? v : []; }
        catch (e) { return []; }
    }
    function writeRecent(list) { try { localStorage.setItem(RECENT_KEY, JSON.stringify(list.slice(0, 6))); } catch (e) { /* storage unavailable */ } }
    function pushRecent(q, loc) {
        if (!q && !loc) return;
        var list = readRecent().filter(function (r) { return !(r.q === q && r.loc === loc); });
        list.unshift({ q: q, loc: loc });
        writeRecent(list);
    }

    /** Append text with the matched part wrapped in <mark> (DOM only, no innerHTML). */
    function highlight(target, text, q) {
        var i = q ? text.toLowerCase().indexOf(q.toLowerCase()) : -1;
        if (i < 0) { target.textContent = text; return; }
        target.appendChild(document.createTextNode(text.slice(0, i)));
        target.appendChild(el('mark', null, text.slice(i, i + q.length)));
        target.appendChild(document.createTextNode(text.slice(i + q.length)));
    }

    function Dropdown(root, input, onPick) {
        var rows = [], idx = -1;

        function render(sections, query) {
            root.textContent = '';
            rows = []; idx = -1;
            sections.forEach(function (sec) {
                if (!sec.items.length) return;
                var h = el('div', 'sdrop-h');
                h.appendChild(el('span', null, sec.title));
                if (sec.onClear) {
                    var clr = el('button', null, 'Clear');
                    clr.type = 'button';
                    clr.addEventListener('mousedown', function (e) { e.preventDefault(); sec.onClear(); });
                    h.appendChild(clr);
                }
                root.appendChild(h);
                sec.items.forEach(function (it) {
                    var row = el('div', 'sdrop-row' + (it.action ? ' is-action' : ''));
                    row.setAttribute('role', 'option');
                    var ico = el('span', 'sdrop-ico'); ico.appendChild(icon(it.icon || 'fa-solid fa-briefcase'));
                    var txt = el('span', 'sdrop-txt');
                    var main = el('strong'); highlight(main, it.label, it.hl ? query : '');
                    txt.appendChild(main);
                    if (it.sub) txt.appendChild(el('small', null, it.sub));
                    row.appendChild(ico); row.appendChild(txt);
                    if (it.onRemove) {
                        var rm = el('span', 'sdrop-rm'); rm.title = 'Remove'; rm.appendChild(icon('fa-solid fa-xmark'));
                        rm.addEventListener('mousedown', function (e) { e.preventDefault(); e.stopPropagation(); it.onRemove(); });
                        row.appendChild(rm);
                    } else {
                        row.appendChild(icon('fa-solid fa-arrow-right sdrop-go'));
                    }
                    row.addEventListener('mousedown', function (e) { e.preventDefault(); onPick(it); });
                    row.addEventListener('mousemove', function () { setActive(rows.indexOf(entry)); });
                    var entry = { el: row, item: it };
                    rows.push(entry);
                    root.appendChild(row);
                });
            });
            show(rows.length > 0);
        }
        function show(v) { root.hidden = !v; input.setAttribute('aria-expanded', String(v)); }
        function setActive(i) {
            idx = i;
            rows.forEach(function (r, n) { r.el.classList.toggle('is-active', n === idx); });
            if (rows[idx]) rows[idx].el.scrollIntoView({ block: 'nearest' });
        }
        return {
            render: render,
            close: function () { show(false); idx = -1; },
            isOpen: function () { return !root.hidden; },
            move: function (d) { if (rows.length) setActive((idx + d + rows.length) % rows.length); },
            pickActive: function () { if (idx >= 0 && rows[idx]) { onPick(rows[idx].item); return true; } return false; }
        };
    }

    function enhanceSearch(form) {
        var qIn = $('input[name=q]', form), locIn = $('input[name=location]', form);
        var dropQ = $('[data-drop-q]', form), dropL = $('[data-drop-l]', form);
        var clearBtn = $('[data-clear]', form), micBtn = $('[data-mic]', form), langBtn = $('[data-voice-lang]', form);
        var url = form.getAttribute('data-suggest-url'), trending = [];
        try { trending = JSON.parse(form.getAttribute('data-trending') || '[]'); } catch (e) { trending = []; }
        if (!qIn || !dropQ) return;

        var cache = {}, ctrl = null, timer = null, lastQuery = '';

        /* ---- keyword dropdown ---- */
        var dq = Dropdown(dropQ, qIn, function (it) {
            if (it.href) { window.location.href = it.href; return; }
            qIn.value = it.value != null ? it.value : it.label;
            if (it.loc != null) locIn.value = it.loc;
            dq.close(); toggleClear(); form.requestSubmit ? form.requestSubmit() : form.submit();
        });

        function showIdle() {
            var recent = readRecent(), secs = [];
            if (recent.length) {
                secs.push({
                    title: 'Recent searches',
                    onClear: function () { writeRecent([]); showIdle(); },
                    items: recent.map(function (r) {
                        return {
                            icon: 'fa-solid fa-clock-rotate-left', label: r.q || 'All jobs', sub: r.loc ? 'in ' + r.loc : '',
                            value: r.q, loc: r.loc || '',
                            onRemove: function () { writeRecent(readRecent().filter(function (x) { return !(x.q === r.q && x.loc === r.loc); })); showIdle(); }
                        };
                    })
                });
            }
            if (trending.length) {
                secs.push({
                    title: 'Popular right now',
                    items: trending.slice(0, 5).map(function (t) { return { icon: 'fa-solid fa-arrow-trend-up', label: t, value: t }; })
                });
            }
            dq.render(secs, '');
        }

        function showSuggestions(q, data) {
            var secs = [{ title: 'Search', items: [{ icon: 'fa-solid fa-magnifying-glass', label: 'Search jobs for “' + q + '”', value: q, action: true }] }];
            secs.push({ title: 'Jobs', items: (data.jobs || []).map(function (j) {
                return { icon: 'fa-solid fa-briefcase', label: j.title, sub: [j.company, j.location].filter(Boolean).join(' · '), href: j.url, hl: true };
            }) });
            secs.push({ title: 'Categories', items: (data.categories || []).map(function (c) {
                return { icon: 'fa-solid fa-layer-group', label: c.name, sub: 'Browse category', href: c.url, hl: true };
            }) });
            secs.push({ title: 'Companies', items: (data.companies || []).map(function (c) {
                return { icon: 'fa-regular fa-building', label: c.name, sub: c.jobs + (c.jobs === 1 ? ' open role' : ' open roles'), href: c.url, hl: true };
            }) });
            secs.push({ title: 'Locations', items: (data.locations || []).map(function (l) {
                return { icon: 'fa-solid fa-location-dot', label: l.name, sub: 'Jobs in this location', href: l.url, hl: true };
            }) });
            dq.render(secs, q);
        }

        function fetchSuggestions(q) {
            if (cache[q]) { showSuggestions(q, cache[q]); return; }
            if (ctrl) ctrl.abort();
            ctrl = ('AbortController' in window) ? new AbortController() : null;
            fetch(url + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' }, signal: ctrl ? ctrl.signal : undefined })
                .then(function (r) { return r.ok ? r.json() : null; })
                .then(function (data) {
                    if (!data) return;
                    cache[q] = data;
                    if (qIn.value.trim() === q && document.activeElement === qIn) showSuggestions(q, data);
                })
                .catch(function () { /* aborted or offline: keep the simple search working */ });
        }

        function onType() {
            toggleClear();
            var q = qIn.value.trim();
            clearTimeout(timer);
            if (q.length < 2) { lastQuery = ''; showIdle(); return; }
            // instant "search for…" row while the network request is in flight
            if (q !== lastQuery) { lastQuery = q; showSuggestions(q, cache[q] || {}); }
            timer = setTimeout(function () { fetchSuggestions(q); }, 170);
        }

        qIn.addEventListener('focus', function () { qIn.value.trim().length < 2 ? showIdle() : onType(); });
        qIn.addEventListener('input', onType);
        qIn.addEventListener('blur', function () { dq.close(); });
        qIn.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowDown') { e.preventDefault(); if (!dq.isOpen()) onType(); dq.move(1); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); dq.move(-1); }
            else if (e.key === 'Enter') { if (dq.isOpen() && dq.pickActive()) e.preventDefault(); }
            else if (e.key === 'Escape') { dq.close(); }
        });

        var kbd = $('[data-kbd]', form);
        function toggleClear() {
            if (clearBtn) clearBtn.hidden = qIn.value.length === 0;
            if (kbd) kbd.classList.toggle('is-hidden', qIn.value.length > 0);
        }
        if (clearBtn) clearBtn.addEventListener('click', function () { qIn.value = ''; toggleClear(); qIn.focus(); showIdle(); });
        toggleClear();

        /* ---- location dropdown ---- */
        if (locIn && dropL) {
            var dl = Dropdown(dropL, locIn, function (it) { locIn.value = it.label; dl.close(); });
            var renderLoc = function () {
                var v = locIn.value.trim().toLowerCase();
                var list = CITIES.filter(function (c) { return !v || c.toLowerCase().indexOf(v) > -1; }).slice(0, 8);
                var items = list.map(function (c) { return { icon: 'fa-solid fa-city', label: c, hl: true }; });
                if (!v || 'remote'.indexOf(v) === 0) items.unshift({ icon: 'fa-solid fa-house-laptop', label: 'Remote', sub: 'Work from anywhere', hl: true });
                dl.render([{ title: v ? 'Matching places' : 'Popular places', items: items }], locIn.value.trim());
            };
            locIn.addEventListener('focus', renderLoc);
            locIn.addEventListener('input', renderLoc);
            locIn.addEventListener('blur', function () { dl.close(); });
            locIn.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowDown') { e.preventDefault(); if (!dl.isOpen()) renderLoc(); dl.move(1); }
                else if (e.key === 'ArrowUp') { e.preventDefault(); dl.move(-1); }
                else if (e.key === 'Enter') { if (dl.isOpen() && dl.pickActive()) e.preventDefault(); }
                else if (e.key === 'Escape') { dl.close(); }
            });
        }

        /* ---- remember searches ---- */
        form.addEventListener('submit', function () { pushRecent(qIn.value.trim(), locIn ? locIn.value.trim() : ''); });

        /* ---- voice search (Chrome, Edge, Safari) ---- */
        var SR = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (SR && micBtn && langBtn) {
            micBtn.hidden = false; langBtn.hidden = false;
            var lang = 'en-US';
            try { lang = localStorage.getItem('lj_voice_lang') || 'en-US'; } catch (e) { /* ignore */ }
            var paintLang = function () { langBtn.textContent = lang === 'bn-BD' ? 'বাং' : 'EN'; };
            paintLang();
            langBtn.addEventListener('click', function () {
                lang = lang === 'bn-BD' ? 'en-US' : 'bn-BD'; paintLang();
                try { localStorage.setItem('lj_voice_lang', lang); } catch (e) { /* ignore */ }
            });
            var rec = null;
            micBtn.addEventListener('click', function () {
                if (rec) { rec.stop(); return; }
                rec = new SR(); rec.lang = lang; rec.interimResults = true; rec.maxAlternatives = 1;
                micBtn.classList.add('is-listening'); qIn.placeholder = 'Listening…';
                rec.onresult = function (ev) {
                    var text = '';
                    for (var i = ev.resultIndex; i < ev.results.length; i++) text += ev.results[i][0].transcript;
                    qIn.value = text.trim(); toggleClear();
                    if (ev.results[ev.results.length - 1].isFinal && qIn.value) { form.requestSubmit ? form.requestSubmit() : form.submit(); }
                };
                var done = function () { micBtn.classList.remove('is-listening'); qIn.placeholder = 'Job title, skill or company'; rec = null; };
                rec.onend = done; rec.onerror = done;
                try { rec.start(); } catch (e) { done(); }
            });
        }
    }
    $$('form[data-search]').forEach(enhanceSearch);

    /* Press "/" anywhere to jump to the search box */
    document.addEventListener('keydown', function (e) {
        if (e.key !== '/' || e.ctrlKey || e.metaKey || e.altKey) return;
        var t = e.target, tag = t && t.tagName;
        if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || (t && t.isContentEditable)) return;
        var q = $('form[data-search] input[name=q]');
        if (q) { e.preventDefault(); q.focus(); window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' }); }
    });
})();
