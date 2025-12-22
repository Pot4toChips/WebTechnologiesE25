@extends('profile.layout')
 
@php
$storageURL = "https://vvtmkzsrflnaqphsxxal.supabase.co/storage/v1/object/public";
$imageURL = $storageURL . '/profile_images/' . $userProfile->image;
@endphp 

<script>
    window.userId = {{ auth()->id() }};
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
    <!-- Posts will be loaded here via AJAX -->
</div>
@endsection


@section('js')
    <script src="js/profile.js" type="module"></script>
@endsection