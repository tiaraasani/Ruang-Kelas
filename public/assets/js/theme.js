/**
 * Applies the saved colour theme before the page paints, to avoid a flash of the
 * wrong theme. Loaded synchronously in <head>. Kept in a separate file because the
 * Content-Security-Policy forbids inline scripts.
 */
(function () {
    try {
        var saved = localStorage.getItem('rk-theme');
        if (saved === 'light' || saved === 'dark') {
            document.documentElement.setAttribute('data-theme', saved);
        }
    } catch (e) {
        /* localStorage unavailable (private mode, blocked cookies); fall back to system theme. */
    }
})();
