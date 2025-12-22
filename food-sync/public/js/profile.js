import { sendAPIRequest } from './scripts.js'

let posts = [];

// Render posts into the #posts-container
function renderPosts(list) {
  const container = document.getElementById('posts-container');
  container.innerHTML = '';
  list.forEach(post => {
    const wrapper = document.createElement('div');
    wrapper.className = 'post-card';
    wrapper.id = `post-${post.id}`;
    wrapper.dataset.postId = post.id;

    const ingredientsHTML = post.ingredients ? post.ingredients.map(i => `<li>${i}</li>`).join('') : '';
    const instructionsHTML = post.instructions ? post.instructions.map(i => `<li>${i}</li>`).join('') : '';

    wrapper.innerHTML = `
      <div class="row g-0">
        <div class="col-12 col-md-5">
          <img src="${post.image_url}" alt="${post.title}" class="post-media">
        </div>
        <div class="col-12 col-md-7">
          <div style="padding:14px;">
            <h5 class="mb-1">${post.title}</h5>
            ${ingredientsHTML ? `<h6 class="fw-bold mt-3">Ingredients</h6><ul class="mb-3">${ingredientsHTML}</ul>` : ''}
            ${instructionsHTML ? `<h6 class="fw-bold mt-3">Instructions</h6><ol class="mb-3">${instructionsHTML}</ol>` : ''}
            <div class="d-flex align-items-center">
              <div class="ms-auto">
                <button class="btn btn-sm btn-outline-danger delete-post-btn" id="${post.id}">Delete Post</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
    container.appendChild(wrapper);
  });
}

// Fetch and render posts on load
async function loadPosts() {
  try {
    const data = await sendAPIRequest(`recipe-posts/get-recipe-posts?user_id=${window.userId}`, 'GET');
    posts = data || [];
    renderPosts(posts);
  } catch (error) {
    console.error('Error loading posts:', error);
  }
}

// Delete post with event delegation
document.addEventListener('click', async (ev) => {
  const btn = ev.target.closest('.delete-post-btn');
  if (!btn) return;

  const post_id = btn.id;

  const response = await sendAPIRequest("recipe-posts/delete-recipe-post", "POST", {
    id: Number(post_id)
  });

  if (response.error) {
    alert(response.error);
    return;
  }

  document.getElementById(`post-${post_id}`).remove(); // DELETE FROM THE UI
});

// Edit profile
document.getElementById('edit-profile-btn').addEventListener('click', () => {
  window.location.href = '/profile/edit';
});

// Load posts on page load
window.addEventListener('load', loadPosts);