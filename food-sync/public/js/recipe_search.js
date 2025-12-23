// Simple fetch-based search with debounce (~250ms)
(() => {
    const input = document.getElementById('recipe-search');
    if (!input) return;

    const resultsContainer = document.getElementById('feed');
    let timer = null;

    function debounceFetch(q) {
        if (timer) clearTimeout(timer);
        timer = setTimeout(() => doFetch(q), 250);
    }

    async function doFetch(q) {
        try {
            const url = '/recipe-posts/search?q=' + encodeURIComponent(q || '');
            const resp = await fetch(url, { method: 'GET', credentials: 'same-origin' });
            if (!resp.ok) {
                console.error('Search request failed', resp.status);
                return;
            }
            const html = await resp.text();
            // Replace results container content with returned partial
            resultsContainer.innerHTML = html;
        } catch (err) {
            console.error('Search error', err);
        }
    }

    input.addEventListener('input', (e) => {
        const q = e.target.value.trim();
        debounceFetch(q);
    });

    // Optionally trigger initial load (empty query) to populate feed
    // doFetch('');
})();
