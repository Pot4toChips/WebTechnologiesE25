@extends('layouts.profileLayout')

@section('name')
  <h3 id="profile-username" class="fw-bold mb-0">{{$name}}</h3>
@endsection


@section('css')
    <link rel="stylesheet" href="css/profile.css">
@endsection

@section('posts')
<div id="posts-container">
    @foreach($posts as $post)
        <div class="post-card" id="post-{{ $post->id }}" data-post-id="{{ $post->id }}">
            <div class="row g-0">
                <div class="col-12 col-md-5">
                    <img src="{{ $post->image }}" alt="{{ $post->title }}" class="post-media">
                </div>
                <div class="col-12 col-md-7">
                    <div style="padding:14px;">
                        <h5 class="mb-1">{{ $post->title }}</h5>
                        <p class="text-muted mb-3">{{ $post->hh }}</p>

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
                                <a href="/post/{{ $post->id }}" class="btn btn-sm btn-outline-secondary">View</a>
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