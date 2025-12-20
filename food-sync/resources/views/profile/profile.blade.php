@extends('profile.layout')
 
@php
$storageURL = "https://vvtmkzsrflnaqphsxxal.supabase.co/storage/v1/object/public";
$imageURL = $storageURL . '/profile_images/' . $userProfile->image;
@endphp 

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('name1')
  <h3 id="profile-username" class="fw-bold mb-0">{{$name}}</h3>
@endsection

@section('profile_description')
     <small id="profile-bio">{{$userProfile->description}}</small>
@endsection

@section('image')
  <img id="profile-avatar" src="{{ $imageURL }}" alt="Profile avatar" class="rounded-circle profile-avatar">
@endsection

@section('bio')
      <p id="profile-about" class="mb-0">{{$userProfile->bio}}.</p>
@endsection

@section('edit_button')
@if($isOwner)
<div class="col-auto text-end">
    <div class="dropdown">
        <button
            id="profileMenuButton"
            class="btn btn-outline-secondary"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            aria-label="Profile options">
            &#8943;
        </button>

        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileMenuButton">
            <li>
                <button
                    id="edit-profile-btn"
                    class="dropdown-item"
                    type="button">
                    Edit Profile
                </button>
            </li>
        </ul>
    </div>
</div>
@endif
  @endsection

  @section('subscribe_button')
@if(!$isOwner)
    @if($isFollowing)
        <form action="{{ route('users.unfollow', $userProfile->user_id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-primary">Unsubscribe</button>
        </form>
    @else
        <form action="{{ route('users.follow', $userProfile->user_id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-primary">Subscribe</button>
        </form>
    @endif
@endif
@endsection 

@section('stats')
<div class="col">
    <div class="stats-compact">
        <div class="stat-item">
            <div class="h5 mb-0" id="stat-posts">{{ $postCount }}</div>
            <small>Posts</small>
        </div>

<div class="stat-item">
    <div class="h5 mb-0" id="stat-followers">
        <a href="{{ route('users.followers', $userProfile->user_id) }}">
            {{ $followersCount }}
        </a>
    </div>
    <small>Followers</small>
</div>

<div class="stat-item">
    <div class="h5 mb-0" id="stat-following">
        <a href="{{ route('users.following', $userProfile->user_id) }}">
            {{ $followingCount }}
        </a>
    </div>
    <small>Following</small>
</div>
    </div>
</div>
@endsection

@section('name2')
 <h4 class="mb-0">{{$name}}'s Posts</h4>
@endsection


@section('posts')
<div id="posts-container">
    @foreach($posts as $post)
    @php
   $recipeURL = $storageURL . '/recipe_post_images/' . $post->image;
    @endphp 

        <div class="post-card" id="post-{{ $post->id }}" data-post-id="{{ $post->id }}">
            <div class="row g-0">
                <div class="col-12 col-md-5">
                    <img src="{{ $recipeURL }}" alt="{{ $post->title }}" class="post-media">
                </div>
                <div class="col-12 col-md-7">
                    <div style="padding:14px;">
                        <h5 class="mb-1">{{ $post->title }}</h5>
                        <p class="mb-3">{{ $post->hh }}</p>

                        {{-- Ingredients Section --}}
                        @if(!empty($post->ingredients))
                            <h6 class="fw-bold mt-3">Ingredients</h6>
                            <ul class="mb-3">
                                @foreach($post->ingredients as $ingredient)
                                    <li>{{ $ingredient }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- Instructions Section --}}
                        @if(!empty($post->instructions))
                            <h6 class="fw-bold mt-3">Instructions</h6>
                            <ol class="mb-3">
                                @foreach($post->instructions as $step)
                                    <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                        @endif

                        <div class="d-flex align-items-center">
                            <div>
                            </div>
                             @if(@$isOwner)
                            <div class="ms-auto">
                                <button class="btn btn-sm btn-outline-danger delete-post-btn" id="{{ $post->id }}">Delete Post</button>
                            </div>

                            
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@if ($posts->hasPages())
    <div class="d-flex justify-content-center gap-2 mt-4">

        {{-- Previous --}}
        @if ($posts->onFirstPage())
            <button class="btn btn-sm btn-outline-secondary" disabled>
                ← Previous
            </button>
        @else
            <a href="{{ $posts->previousPageUrl() }}"
               class="btn btn-sm btn-outline-secondary">
                ← Previous
            </a>
        @endif

        {{-- Page indicator --}}
        <span class="align-self-center text-muted">
            Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}
        </span>

        {{-- Next --}}
        @if ($posts->hasMorePages())
            <a href="{{ $posts->nextPageUrl() }}"
               class="btn btn-sm btn-outline-secondary">
                Next →
            </a>
        @else
            <button class="btn btn-sm btn-outline-secondary" disabled>
                Next →
            </button>
        @endif

    </div>
@endif
@endsection


@section('js')
    <script src="{{ asset('js/profile.js') }}" type="module"></script>
@endsection