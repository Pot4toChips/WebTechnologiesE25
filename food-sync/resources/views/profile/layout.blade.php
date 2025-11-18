@extends('layouts.app')

@section('content')
      <!-- MAIN CONTENT -->
      <div class="col-md-10 col-12 d-flex justify-content-center">
  <div class="d-flex flex-column align-items-center overflow-auto w-100">
  <div class="content-wrap w-100" style="max-width:980px; padding:24px;">

          <!-- Profile panel -->
          <section id="profile-panel" class="panel">

            <!-- Username Row -->
            <div class="row w-100 mb-3 align-items-center">
              <div class="col">
                @yield("name1")
                @yield('profile_description') 
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
                @yield('image')
                <!--<div><button id="subscribe-btn" class="subscribe-button mt-2" type="button">Subscribe</button></div>--> <!-- YOU SHOULDNT SUBSCRIBE TO YOUR OWN PROFILE-->
              </div>
 
              <div class="col">
                <div class="stats-compact">
                  <div class="stat-item">
                    <div class="h5 mb-0" id="stat-posts">120</div>
                    <small>Posts</small>
                  </div>

                  <div class="stat-item">
                    <div class="h5 mb-0" id="stat-followers">1,500</div>
                    <small>Followers</small>
                  </div>

                  <div class="stat-item">
                    <div class="h5 mb-0" id="stat-following">300</div>
                    <small>Following</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="thin-sep" aria-hidden="true"></div>

            <!-- About -->
            <div class="row mb-0">
              <div class="col">
                <h5 class="mb-1">About</h5>
                 @yield('bio')
              </div>
            </div>

          </section>

          <!-- Posts panel -->
          <section id="posts-panel" class="panel mt-4">
            <div class="row align-items-center mb-3">
              <div class="col">
                 @yield('name2')    
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
    </div>

@endsection

