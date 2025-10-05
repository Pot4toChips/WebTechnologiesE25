
(function () {
  const feed = document.getElementById('feed');
  const sentinel = document.getElementById('sentinel');
  const loading = document.getElementById('loader');

  // Simulated image pool 
  const images = [
    'images/chicken_alfredo.png',
    'images/chicken_alfredo.png',
    'images/chicken_alfredo.png'
  ];

  const PAGE_SIZE = 6;
  let page = 0;
  let isLoading = false;
  let done = false;

  function makePostNode(post) {
    const tile = document.createElement('div');
    tile.className = 'tile';
    tile.tabIndex = 0; 
    const img = document.createElement('img');
    
    img.alt = post.alt || '';
    img.loading = 'lazy';
    img.src = post.image;
    tile.appendChild(img);
    return tile;
  }

  function fetchPosts(pageNumber, pageSize) {
    return new Promise((resolve) => {
      setTimeout(() => {
        if (pageNumber >= 5) {
          resolve([]);
          return;
        }

        const items = Array.from({ length: pageSize }, (_, i) => {
          const n = pageNumber * pageSize + i + 1;
          return {
            id: `post-${n}`,
            title: `Recipe ${n}`,
            author: `user${(n % 10) + 1}`,
            time: `${(n % 60) + 1}m`,
            image: images[n % images.length],
            alt: `Photo of recipe ${n}`,
          };
        });
        resolve(items);
      }, 600 + Math.random() * 400);
    });
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
        done = true;
        sentinel.textContent = 'No more posts';
        observer.unobserve(sentinel);
        placeholderNodes.forEach(n => n.remove());
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
    const FALLBACK_TILE = 200; 

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
