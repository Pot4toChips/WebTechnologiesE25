<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title> Profile </title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_box,explore,home,settings" />

  <!-- keep other global -->
  <link rel="stylesheet" href="css/styles.css">

  <!-- profile-specific styles -->
  {{-- <link rel="stylesheet" href="css/profile.css"> --}}
  @yield('css')
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <!-- NAVIGATION (left) -->
      <nav class="sidebar d-flex flex-column bg-light col-2 vh-100 p-4">
        <header class="d-flex flex-column align-items-start justify-content-start flex-grow-1 w-100">
          <h4 class="text-center fw-bolder mb-3 w-100">Food Sync</h4>
          <ul class="nav flex-column w-100">
            <li class="nav-item"><a class="nav-link" href="index.html">
                <span class="material-symbols-rounded nav-link-icon">home</span>Home</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="explore.html">
                <span class="material-symbols-rounded nav-link-icon">explore</span>Explore</a>
            </li>
            <li class="nav-item"><a class="nav-link active" href="profile.html">
                <span class="material-symbols-rounded nav-link-icon">account_box</span>Profile</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="settings.html">
                <span class="material-symbols-rounded nav-link-icon">settings</span>Settings</a>
            </li>
          </ul>
        </header>

        <footer class="d-flex flex-column align-items-start justify-content-end flex-grow-1 w-100">
          <ul class="nav flex-column w-100">
            <li class="nav-item"><a class="nav-link text-center" href="">Sign Out</a></li>
          </ul>
        </footer>
      </nav>

      <!-- MAIN CONTENT -->
      <main class="col-md-10 col-12 d-flex justify-content-center">
  <div class="d-flex flex-column align-items-center overflow-auto w-100">
  <div class="content-wrap w-100" style="max-width:980px; padding:24px;">

          <!-- Profile panel -->
          <section id="profile-panel" class="panel">

            <!-- Username Row -->
            <div class="row w-100 mb-3 align-items-center">
              <div class="col">
                @yield("name")
                <small id="profile-bio" class="text-muted">Short profile description</small>
              </div>

              <div class="col-auto text-end">
                <div class="dropdown">
                  <button id="profileMenuButton" class="btn btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Profile options">
                    &#8943;
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileMenuButton">
                    <li><button id="edit-profile-btn" class="dropdown-item" type="button">Edit Profile</button></li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="thin-sep" aria-hidden="true"></div>

            <!-- Stats row -->
            <div class="row align-items-center gy-3">
              <div class="col-auto text-center">
                <img id="profile-avatar" src="images/monke.png" alt="Profile avatar" class="rounded-circle profile-avatar">
                <div><button id="subscribe-btn" class="subscribe-button mt-2" type="button">Subscribe</button></div>
              </div>

              <div class="col">
                <div class="stats-compact">
                  <div class="stat-item">
                    <div class="h5 mb-0" id="stat-posts">120</div>
                    <small class="text-muted">Posts</small>
                  </div>

                  <div class="stat-item">
                    <div class="h5 mb-0" id="stat-followers">1,500</div>
                    <small class="text-muted">Followers</small>
                  </div>

                  <div class="stat-item">
                    <div class="h5 mb-0" id="stat-following">300</div>
                    <small class="text-muted">Following</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="thin-sep" aria-hidden="true"></div>

            <!-- About -->
            <div class="row mb-0">
              <div class="col">
                <h5 class="mb-1">About</h5>
                <p id="profile-about" class="text-muted mb-0">A short about text.</p>
              </div>
            </div>

          </section>

          <!-- Posts panel -->
          <section id="posts-panel" class="panel mt-4">
            <div class="row align-items-center mb-3">
              <div class="col">
                <h4 class="mb-0">User Posts</h4>
              </div>

              <div class="col-auto text-end">
                <div class="btn-group" role="group" aria-label="Sort posts" data-bs-toggle="buttons">
                  <input type="radio" class="btn-check" name="sort" id="recent-btn" autocomplete="off" checked>
                  <label class="btn btn-outline-primary" for="recent-btn" data-sort="recent">Recent</label>

                  <input type="radio" class="btn-check" name="sort" id="top-btn" autocomplete="off">
                  <label class="btn btn-outline-primary" for="top-btn" data-sort="top">Top</label>

                  <input type="radio" class="btn-check" name="sort" id="trending-btn" autocomplete="off">
                  <label class="btn btn-outline-primary" for="trending-btn" data-sort="trending">Trending</label>
                </div>
              </div>
            </div>

            <!-- posts Container row -->
            <div class="row mt-2">
              <div class="col" id="posts-container" aria-live="polite">
                 @yield('posts')    
              </div>
            </div>

            <div class="row">
              <div class="col text-center mt-3">
                <button id="load-more-btn" class="btn btn-sm btn-outline-secondary" type="button">Load more</button>
              </div>
            </div>
          </section>

        </div>
      </main>
    </div>
  </div>
  </div>


  <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
@yield('js')  
  
</body>
</html>
