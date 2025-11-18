
const SUPABASE_URL = "https://vvtmkzsrflnaqphsxxal.supabase.co";
const SUPABASE_BUCKET = "recipe_post_images";
import { timeSince } from './scripts.js'
 
(function () {
  const feed = document.getElementById('feed');
  const sentinel = document.getElementById('sentinel');
  const loading = document.getElementById('loader');


  const PAGE_SIZE = 9;
  let page = 0;
  let isLoading = false;
  let done = false;

  function makePostNode(post) {
    let recipePost = document.createElement("article");
    recipePost.className = "recipe-post content-card d-flex flex-column align-items-start justify-content-start m-1";
    recipePost.innerHTML = `
      <div class="recipe-post-header d-flex flex-row align-items-center justify-content-between m-0 w-100">
        <p class="m-0">${post.title}</p>
        <div class="d-flex flex-row align-items-center justify-content-center">
          <a class="text-secondary m-0">${post.author}</a>
          <p class="text-secondary m-0 mx-1">•</p>
          <p class="text-secondary m-0">${post.time}</p>
        </div>
      </div>
      <hr class="border-2 w-100 my-2">
      ${post.image_url? `
      <div class="d-flex flex-row align-items-start justify-content-start mt-2 w-100 justify-content-center image-container">
        <img class="w-100 rounded" src="${post.image_url}" alt="Image of ${post.title}" style="height:280px;object-fit:cover;" 
          onerror="console.error('Image load error', this.src); this.classList.add('img-error'); this.insertAdjacentHTML('afterend', '<div class=\'text-danger small mt-2\'>Image failed to load</div>');"
          onload="console.debug('Image loaded', this.src);"
        >
      </div>
      ` : `<div class="small text-muted mt-2">No image</div>`}
    `;
    return recipePost;
  }

  async function fetchPosts(pageNumber, pageSize) {
    // Try to load posts from the backend API once and page client-side
  if (window.__explorePostsCache) {
    const cache = window.__explorePostsCache;
    const total = cache.length;

  // If not enough posts to fill the page → loop the posts
    if (total > 0) {
      const results = [];
      for (let i = 0; i < pageSize; i++) {
        const index = (pageNumber * pageSize + i) % total; 
        results.push(cache[index]);
      }
      return results;
    }

    return []; // no posts at all
}


    try {
      const res = await fetch('/api/recipe-posts/get-recipe-posts', {
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      if (!res.ok) throw new Error('Network response was not ok');
      const data = await res.json();
      console.debug('Explore: fetched posts from API', data);

      
      const mapped = data.map((r, i) => {
        const cleanPath = (r.image_url || "")
          .replace(/^\/+/, "")         
          .replace(/^images\//, "")    
          .replace(/\/{2,}/g, "/");   
         return {
             id: r.id ?? `post-${i + 1}`,
             title: r.title ?? `Recipe ${i + 1}`,
             author: r.author ?? r.name ?? 'Unknown',
             time: timeSince(r.time ?? r.updated_at ?? r.updatedAt ?? new Date().toISOString()),

             image_url: cleanPath
              ? `${SUPABASE_URL}/storage/v1/object/public/${SUPABASE_BUCKET}/${cleanPath}`
             : null,
             };
        });

      window.__explorePostsCache = mapped;
      console.debug('Explore: mapped posts', mapped.slice(0, 10));
      const start = pageNumber * pageSize;
      return mapped.slice(start, start + pageSize);
    } catch (err) {
      console.warn('Failed to fetch posts from API, falling back to local generator', err);

      // Fallback to client-side generated posts (keeps UX functional)
      return new Promise((resolve) => {
        setTimeout(() => {
          if (pageNumber >= 5) {
            resolve([]);
            return;
          }

          const authorNames = [
            "@richardtivolt", "@pauldonici", "@hubageller", "@romanteren", "@foodiequeen", "@chefmax", "@sarahcooks", "@tastytom", "@veggievibe", "@spicyjane", "@bakerbob", "@grillguy", "@saucysue", "@noodleking", "@sweetpea"
          ];

          const items = Array.from({ length: pageSize }, (_, i) => {
            const n = pageNumber * pageSize + i + 1;
            const author = authorNames[Math.floor(Math.random() * authorNames.length)];
            const title = `Recipe ${n}`;
            return {
              id: `post-${n}`,
              title,
              author,
              time: `${(n % 60) + 1}m`,
            };
          });

          resolve(items);
        }, 600 + Math.random() * 400);
      });
    }
  }

  async function loadMore() {
    if (isLoading || done) return;
    isLoading = true;
    loading.classList.remove('d-none');

    try {
      const feedEl = document.getElementById('feed');
      const isGrid = feedEl && feedEl.classList.contains('grid');
      const placeholderNodes = [];
      for (let i = 0; i < PAGE_SIZE; i++) {
        const ph = document.createElement(isGrid ? 'div' : 'article');
        if (isGrid) {
          ph.className = 'tile skeleton';
        } else {
          ph.className = 'post recipe-post content-card skeleton';
          ph.style.height = '200px';
        }
        feedEl.appendChild(ph);
        placeholderNodes.push(ph);
      }

      const posts = await fetchPosts(page, PAGE_SIZE);
      if (!posts || posts.length === 0) {
        placeholderNodes.forEach(n => n.remove());
        return;
      } else {
        for (let i = 0; i < posts.length; i++) {
          const p = posts[i];
          const node = makePostNode(p);
          const placeholder = placeholderNodes[i];
          if (placeholder && placeholder.parentNode) placeholder.parentNode.replaceChild(node, placeholder);
          else feed.appendChild(node);
        }
        page += 1;
      }
    } catch (err) {
      console.error('Failed to load posts', err);
    } finally {
      isLoading = false;
      loading.classList.add('d-none');
    }
  }

  let observer = null;

  function createObserver(root) {
    const cb = (entries) => {
      for (const entry of entries) {
        if (entry.isIntersecting) {
          loadMore();
        }
      }
    };

    observer = new IntersectionObserver(cb, {
      root: root || null,
      rootMargin: '200px',
      threshold: 0.1,
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    if (!feed || !sentinel || !loading) return;

    let root = null;
    let node = sentinel.parentElement;
    while (node && node !== document.body) {
      const style = getComputedStyle(node);
      const overflowCombined = (style.overflow + ' ' + style.overflowY + ' ' + style.overflowX).toLowerCase();
      if (/auto|scroll|overlay/.test(overflowCombined)) {
        root = node;
        break;
      }
      node = node.parentElement;
    }

    createObserver(root);
    observer.observe(sentinel);
    let last = 0;
    function onScrollFallback() {
      const now = Date.now();
      if (now - last < 150) return;
      last = now;
      const rect = sentinel.getBoundingClientRect();
      let rootRect;
      if (root && root.getBoundingClientRect) rootRect = root.getBoundingClientRect();
      else rootRect = { top: 0, bottom: window.innerHeight };
      const distance = rect.top - rootRect.bottom;
      if (distance < 400) loadMore();
    }
    const scrollTarget = root || window;
    scrollTarget.addEventListener('scroll', onScrollFallback, { passive: true });

    const COLUMNS = 3;
    const FALLBACK_TILE = 200; // px

    function initialLoadIfNeeded() {
      const rootHeight = root ? root.clientHeight : window.innerHeight;
      const feedWidth = feed.clientWidth || (document.documentElement.clientWidth - 40);
      const tileWidth = feedWidth / COLUMNS || FALLBACK_TILE;
      const rowsThatFit = Math.ceil(rootHeight / tileWidth);
      let initialNeeded = rowsThatFit * COLUMNS + 3; 
      if (initialNeeded < PAGE_SIZE) initialNeeded = PAGE_SIZE;

      const placeholderNodes = [];
      for (let i = 0; i < initialNeeded; i++) {
        const ph = document.createElement('div');
        ph.className = 'tile skeleton';
        feed.appendChild(ph);
        placeholderNodes.push(ph);
      }

      fetchPosts(0, initialNeeded).then(posts => {
        if (!posts || posts.length === 0) {
          done = true;
          sentinel.textContent = 'No more posts';
          placeholderNodes.forEach(n => n.remove());
          return;
        }

        for (let i = 0; i < posts.length; i++) {
          const p = posts[i];
          const node = makePostNode(p);
          const placeholder = placeholderNodes[i];
          if (placeholder && placeholder.parentNode) placeholder.parentNode.replaceChild(node, placeholder);
          else feed.appendChild(node);
        }

        page = Math.ceil(initialNeeded / PAGE_SIZE);
      }).catch(err => {
        console.error('Initial load failed', err);
        placeholderNodes.forEach(n => n.remove());
      }).finally(() => {
        loading.classList.add('d-none');
      });
    }

    initialLoadIfNeeded();
  });
})();
