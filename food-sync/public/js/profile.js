import {sendAPIRequest} from './scripts.js'
//     // Render posts into the #posts-container using Bootstrap
//     function renderPosts(list) {
//       const container = document.getElementById('posts-container');
//       container.innerHTML = '';
//       list.forEach(post => {
//         const wrapper = document.createElement('div');
//         wrapper.className = 'post-card';
//         wrapper.id = `post-${post.id}`;
//         wrapper.dataset.postId = post.id;

//         wrapper.innerHTML = `
//           <div class="row g-0">
//             <div class="col-12 col-md-5">
//               <img src="${post.image}" alt="${post.title}" class="post-media">
//             </div>
//             <div class="col-12 col-md-7">
//               <div style="padding:14px;">
//                 <h5 class="mb-1">${post.title}</h5>
//                 <p class="text-muted mb-3">${post.description}</p>

//                 <div class="d-flex align-items-center">
//                   <div>
//                     <button class="btn btn-sm btn-outline-success" type="button" data-action="upvote" data-id="${post.id}" aria-label="Upvote ${post.title}">↑</button>
//                     <span class="votes" id="votes-${post.id}">${post.votes}</span>
//                     <button class="btn btn-sm btn-outline-danger" type="button" data-action="downvote" data-id="${post.id}" aria-label="Downvote ${post.title}">↓</button>
//                   </div>

//                   <div class="ms-auto">
//                     <a href="/post/${post.id}" class="btn btn-sm btn-outline-secondary">View</a>
//                   </div>
//                 </div>
//               </div>
//             </div>
//           </div>
//         `;
//         container.appendChild(wrapper);
//       });
//     }
    // Vote handler (updates data + UI)
    function handleVote(postId, delta) {
      const p = posts.find(x => x.id === postId);
      if (!p) return;
      p.votes = (p.votes || 0) + delta;
      const el = document.getElementById(`votes-${postId}`);
      if (el) el.textContent = p.votes;
    }

    // upvote/downvote
    document.addEventListener('click', (ev) => {
      const btn = ev.target.closest('button[data-action]');
      if (!btn) return;
      const action = btn.dataset.action;
      const id = Number(btn.dataset.id);
      if (action === 'upvote') handleVote(id, 1);
      if (action === 'downvote') handleVote(id, -1);
    });

    // Sorting
    function sortPosts(mode) {
      let sorted;
      if (mode === 'top') {
        sorted = [...posts].sort((a, b) => (b.votes || 0) - (a.votes || 0));
      } else if (mode === 'recent') {
        sorted = [...posts].sort((a, b) => b.id - a.id); // recent: newest first
      } else {
        sorted = [...posts].sort((a, b) => (b.votes || 0) - (a.votes || 0));
      }
      renderPosts(sorted);
    }

    // Wire sort labels to sorting function
    document.querySelectorAll('label[data-sort]').forEach(label => {
      label.addEventListener('click', () => sortPosts(label.dataset.sort));
    });

    // Edit profile (placeholder)
    document.getElementById('edit-profile-btn').addEventListener('click', () => {
      window.location.href = '/profile/edit';
    });

    // Delete post
    document.querySelectorAll('.delete-post-btn').forEach(button => {
        button.addEventListener('click', async () => {

          const post_id = button.id;

          const response = await sendAPIRequest("recipe-posts/delete-recipe-post", "POST", {
            id: Number(post_id)
          });
          
          if (response.error) {
              alert(response.error);
              return;
          }
          
          document.getElementById(`post-${post_id}`).remove(); // DELETE FROM THE UI
    
      });
    });

    // Subscribe (placeholder)
    //document.getElementById('subscribe-btn').addEventListener('click', () => {
    //  alert('Subscribe action — implement backend call');
    //});

    // Load more (placeholder)
    document.getElementById('load-more-btn').addEventListener('click', () => {
      alert('Load more — implement paging');
    });

    // Initial render - Recent by default
    window.addEventListener('load', () => {
      sortPosts('recent');
    });