@extends('profile.layout')
 
@php
$storageURL = "https://vvtmkzsrflnaqphsxxal.supabase.co/storage/v1/object/public";
$imageURL = $storageURL . '/profile_images/' . $userProfile->image;
@endphp 

<script>
    console.log("Image URL:", @json($imageURL));
</script>


@section('css')
    <link rel="stylesheet" href="css/profile.css">
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
                                <button class="btn btn-sm btn-outline-success" type="button" data-action="upvote" data-id="{{ $post->id }}" aria-label="Upvote {{ $post->title }}">↑</button>
                                <span class="votes" id="votes-{{ $post->id }}">{{ $post->votes }}</span>
                                <button class="btn btn-sm btn-outline-danger" type="button" data-action="downvote" data-id="{{ $post->id }}" aria-label="Downvote {{ $post->title }}">↓</button>
                            </div>

                            <div class="ms-auto">
                                <button class="btn btn-sm btn-outline-danger delete-post-btn" id="{{ $post->id }}">Delete Post</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection


@section('js')
    <script src="js/profile.js" type="module"></script>
@endsection