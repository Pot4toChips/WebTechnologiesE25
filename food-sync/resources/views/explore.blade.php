@extends('layouts.app')

@section('title', 'Food Sync - Explore')

@section('css')
    <link rel="stylesheet" href="css/explore.css">
@endsection

@section('content')
    <div class="container my-3">
        <input id="recipe-search" class="form-control" type="search" placeholder="Search recipes by title..." aria-label="Search recipes">
    </div>

    <!-- Feed: posts will be appended into #feed by js/explore.js or replaced by search -->
    <div id="feed" class="container-fluid p-0 d-flex flex-column justify-content-center align-items-center grid">
    </div>

    <!-- Loader and sentinel used by the infinite scroll script -->
    <div id="loader" class="my-3 d-none text-center">
        <div class="spinner-border text-secondary" role="status" aria-hidden="true"></div>
        <span class="visually-hidden">Loading</span>
    </div>

    <div id="sentinel" class="text-center text-secondary mb-4">Scroll to load more</div>
@endsection

@section('js')
    <script src="js/explore.js" type="module"></script>
    <script src="js/recipe_search.js"></script>
@endsection
