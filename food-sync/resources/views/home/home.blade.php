@extends('layouts.app')

@section('title', 'Food Sync - Home')

@section('css')
    <link rel="stylesheet" href="css/home.css">
@endsection

@section('content')
    @include('home.partials.recipe-post-creator')    

    <script type="text/template" id="recipe-post-template">
        @include('home.partials.recipe-post-template')
    </script>
    <div id="recipe-posts" class="container-fluid p-0 d-flex flex-column justify-content-center align-items-center"></div>
    
    @include('home.partials.recipe-post-placeholder')
@endsection

@section('js')
    <script src="js/home.js" type="module"></script>
@endsection