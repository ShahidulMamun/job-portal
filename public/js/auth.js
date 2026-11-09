/* LivejobsBD – login / register behaviour. Vanilla JS. */
(function () {
    'use strict';
    var $ = function (s, r) { return (r || document).querySelector(s); };
    var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

    /* ---- role switch (job seeker / employer) ---- */
    var card = $('.auth-card[data-role]');
    var form = card && $('form[data-action-candidate]', card);
    function setRole(role, fromUser) {
        if (!card) return;
        card.setAttribute('data-role', role);
        $$('[data-role-btn]', card).forEach(function (b) {
            var on = b.getAttribute('data-role-btn') === role;
            b.setAttribute('aria-selected', String(on));
            b.tabIndex = on ? 0 : -1;
        });
        if (form) form.action = role === 'employer' ? form.getAttribute('data-action-employer') : form.getAttribute('data-action-candidate');
        $$('[data-role-input]', card).forEach(function (i) { i.value = role; });
        $$('[data-employer-only]', card).forEach(function (i) { i.required = role === 'employer'; i.disabled = role !== 'employer'; });
        if (fromUser) { try { history.replaceState(null, '', location.pathname + '?role=' + role); } catch (e) { /* ignore */ } }
    }
    if (card) {
        setRole(card.getAttribute('data-role'), false);
        $$('[data-role-btn]', card).forEach(function (b) {
            b.addEventListener('click', function () { setRole(b.getAttribute('data-role-btn'), true); });
            b.addEventListener('keydown', function (e) {
                if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
                var other = $('[data-role-btn]:not([data-role-btn="' + b.getAttribute('data-role-btn') + '"])', card);
                other.focus(); other.click();
            });
        });
    }

    /* ---- show / hide password ---- */
    $$('[data-eye]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = $('input', btn.parentNode), show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.setAttribute('aria-pressed', String(show));
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            var i = $('i', btn); i.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        });
    });

    /* ---- Caps Lock warning ---- */
    $$('[data-caps]').forEach(function (input) {
        var hint = $('[data-caps-hint]', input.closest('.field'));
        if (!hint) return;
        var check = function (e) { if (e.getModifierState) hint.hidden = !e.getModifierState('CapsLock'); };
        input.addEventListener('keydown', check); input.addEventListener('keyup', check);
        input.addEventListener('blur', function () { hint.hidden = true; });
    });

    /* ---- password strength + confirm match ---- */
    var pw = $('[data-strength]'), meter = $('[data-meter]'), label = $('[data-meter-label]');
    var LABELS = ['Use 8 or more characters with letters and numbers.', 'Too weak. Add more characters.', 'Fair. Mix upper and lower case.', 'Good. Add a number or symbol for more.', 'Strong password.'];
    function score(v) {
        if (!v) return 0;
        var s = 0;
        if (v.length >= 8) s++;
        if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++;
        if (/\d/.test(v)) s++;
        if (/[^A-Za-z0-9]/.test(v) || v.length >= 12) s++;
        return Math.max(1, s);
    }
    var confirm = $('[data-confirm]'), match = $('[data-match]');
    function checkMatch() {
        if (!confirm || !match) return;
        if (!confirm.value) { match.hidden = true; return; }
        var ok = pw ? confirm.value === pw.value : true;
        match.hidden = false;
        match.className = 'hint ' + (ok ? 'is-ok' : 'is-bad');
        match.textContent = ok ? 'Passwords match.' : 'Passwords do not match yet.';
    }
    if (pw && meter) {
        pw.addEventListener('input', function () {
            var s = score(pw.value);
            meter.setAttribute('data-s', String(s));
            if (label) label.textContent = LABELS[s];
            checkMatch();
        });
    }
    if (confirm) confirm.addEventListener('input', checkMatch);

    /* ---- captcha refresh (no page reload) ---- */
    var rb = $('[data-captcha-refresh]');
    if (rb) rb.addEventListener('click', function () {
        rb.classList.add('is-spinning');
        fetch(rb.getAttribute('data-url'), { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                $('#captchaNum1').textContent = d.num1; $('#captchaNum2').textContent = d.num2;
                var a = $('#captchaAnswer'); a.value = ''; a.focus();
            })
            .catch(function () { /* keep the current question */ })
            .then(function () { rb.classList.remove('is-spinning'); });
    });

    /* ---- stop double submits, show a spinner ---- */
    $$('form').forEach(function (f) {
        var btn = $('[data-submit]', f);
        if (!btn) return;
        f.addEventListener('submit', function () {
            if (btn.classList.contains('is-loading')) return;
            requestAnimationFrame(function () { btn.classList.add('is-loading'); });
        });
    });
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) $$('[data-submit]').forEach(function (b) { b.classList.remove('is-loading'); });
    });
})();
