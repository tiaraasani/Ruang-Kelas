/**
 * Application behaviour. Kept in an external file so the Content Security Policy
 * can forbid inline scripts. Depends on jQuery + Bootstrap 3 (tabs, dropdowns, modals).
 */
(function ($) {
    'use strict';

    var body = document.body;
    var isMobile = function () { return window.matchMedia('(max-width: 767px)').matches; };

    /* ---- Sidebar toggle (off-canvas on mobile, collapse on desktop) ---- */
    function toggleSidebar() {
        if (isMobile()) {
            body.classList.toggle('sidebar-open');
        } else {
            body.classList.remove('sidebar-open');
            body.classList.toggle('sidebar-collapsed');
        }
    }
    $(document).on('click', '[data-sidebar-toggle]', function (e) {
        e.preventDefault();
        toggleSidebar();
    });
    $(document).on('click', '.sidebar-backdrop', function () {
        body.classList.remove('sidebar-open');
    });
    // Close the mobile drawer after tapping a link inside it.
    $(document).on('click', '.navbar-static-side a', function () {
        if (isMobile() && !$(this).closest('.has-submenu').length) {
            body.classList.remove('sidebar-open');
        }
    });

    /* ---- Collapsible sidebar submenu (replaces metisMenu) ---- */
    $(document).on('click', '.has-submenu > a', function (e) {
        e.preventDefault();
        $(this).closest('.has-submenu').toggleClass('open');
    });

    /* ---- Theme toggle (light / dark), persisted per browser ---- */
    function currentTheme() {
        var attr = document.documentElement.getAttribute('data-theme');
        if (attr) { return attr; }
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        try { localStorage.setItem('rk-theme', theme); } catch (e) { /* ignore */ }
        $('[data-theme-toggle] .fa')
            .removeClass('fa-moon-o fa-sun-o')
            .addClass(theme === 'dark' ? 'fa-sun-o' : 'fa-moon-o');
    }
    $(document).on('click', '[data-theme-toggle]', function (e) {
        e.preventDefault();
        applyTheme(currentTheme() === 'dark' ? 'light' : 'dark');
    });
    // Sync the toggle icon on load.
    applyTheme(currentTheme());

    /* ---- Confirm before destructive form submits (data-confirm="...") ---- */
    $(document).on('submit', 'form[data-confirm]', function (e) {
        if (!window.confirm(this.getAttribute('data-confirm'))) {
            e.preventDefault();
        }
    });

    /* ---- Fill a modal's form from the button that opened it ---- */
    $(document).on('click', '[data-modal-fill]', function () {
        var modal = document.querySelector(this.getAttribute('data-target'));
        if (!modal) { return; }

        var form = modal.querySelector('form');
        var action = this.getAttribute('data-form-action');
        if (form && action) { form.setAttribute('action', action); }

        Array.prototype.forEach.call(this.attributes, function (attribute) {
            if (attribute.name.indexOf('data-field-') !== 0) { return; }
            var field = form && form.querySelector('[name="' + attribute.name.substring(11) + '"]');
            if (field) { field.value = attribute.value; }
        });
    });

    /* ---- Keep the active tab in sync with the URL hash ---- */
    $(function () {
        var hash = window.location.hash;
        if (hash) {
            $('a[data-toggle="tab"][href="' + hash + '"]').tab('show');
        }
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (event) {
            if (window.history.replaceState) {
                window.history.replaceState(null, '', event.target.getAttribute('href'));
            }
        });
    });
})(jQuery);
