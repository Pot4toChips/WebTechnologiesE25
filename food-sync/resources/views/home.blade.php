@extends('layouts.app')

@section('title', 'Food Sync - Home')

@section('css')
    <link rel="stylesheet" href="css/home.css">
@endsection

@section('content')
    <div id="recipe-posts" class="container-fluid p-0 d-flex flex-column justify-content-center align-items-center"></div>
@endsection

@section('js')
    <script src="js/home.js" type="module"></script>
@endsection