import {sendAPIRequest} from './scripts.js'

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

    Subscribe (placeholder)
    document.getElementById('subscribe-btn').addEventListener('click', () => {
     alert('Subscribe action — implement backend call');
    });