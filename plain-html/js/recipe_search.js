// Client-side search for the plain-html demo page
// debug: indicate script loaded
console.debug('[plain-html] recipe_search.js loaded');

(function () {
  const input = document.getElementById('recipe-search');
  if (!input) return;

  const feed = document.getElementById('feed');
  let timer = null;
  let currentQuery = '';

  function normalize(s) {
    return (s || '').toString().toLowerCase().trim();
  }

  function ensureNoResultsEl() {
    let noEl = document.getElementById('no-results');
    if (!noEl) {
      noEl = document.createElement('div');
      noEl.id = 'no-results';
      noEl.className = 'text-center text-secondary my-4';
      noEl.textContent = 'No results.';
    }
    return noEl;
  }

  function removeNoResultsEl() {
    const noEl = document.getElementById('no-results');
    if (noEl && noEl.parentNode) noEl.parentNode.removeChild(noEl);
  }

  function filterPosts(q) {
    currentQuery = q || '';
    const posts = feed.querySelectorAll('.recipe-post');

    // If there are no posts yet, do nothing — MutationObserver will reapply
    if (!posts || posts.length === 0) return;

    const query = normalize(q);
    if (!query) {
      posts.forEach(p => p.style.display = 'flex');
      removeNoResultsEl();
      return;
    }

    let anyVisible = false;
    posts.forEach(p => {
      const titleEl = p.querySelector('.recipe-post-header p');
      const title = titleEl ? normalize(titleEl.textContent) : '';
      const authorEl = p.querySelector('.recipe-post-header a');
      const author = authorEl ? normalize(authorEl.textContent) : '';
      if (title.includes(query) || author.includes(query)) {
        p.classList.remove('hidden-by-search');
        anyVisible = true;
      } else {
        p.classList.add('hidden-by-search');
      }
    });

    if (!anyVisible) {
      const noEl = ensureNoResultsEl();
      // insert after feed if not already present
      if (!document.getElementById('no-results')) {
        feed.parentNode.insertBefore(noEl, feed.nextSibling);
      }
    } else {
      removeNoResultsEl();
    }
  }

  function debounce(q) {
    if (timer) clearTimeout(timer);
    timer = setTimeout(() => filterPosts(q), 250);
  }

  input.addEventListener('input', (e) => {
    debounce(e.target.value);
  });

  // Reapply current filter whenever feed children change (posts loaded asynchronously)
  const mo = new MutationObserver(() => {
    try {
      filterPosts(currentQuery);
    } catch (err) {
      console.error('Filter mutation error', err);
    }
  });
  mo.observe(feed, { childList: true, subtree: true });

  // Attempt an initial filter in case posts are already present
  document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => filterPosts(input.value || ''), 50);
  });

  // Expose a helper for debugging in the console
  window.__plainRecipeSearch = {
    filter: (q) => filterPosts(q),
    getQuery: () => currentQuery,
  };

})();
