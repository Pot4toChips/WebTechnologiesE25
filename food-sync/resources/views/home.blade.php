@extends('layouts.app')

@section('title', 'Food Sync - Home')

@section('css')
    <link rel="stylesheet" href="css/home.css">
@endsection

@section('content')
    <div id="recipe-post-editor" class="content-card sticky-top accordion accordion-flush w-100 mb-3 p-0">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Share your Recipes
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#recipe-post-editor">
                <div class="accordion-body">
                    <form method="POST" action="">
                        @csrf

                        <div class="mb-3">
                            <label for="recipe-title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="recipe-title" required>
                        </div>

                        <div class="mb-3">
                            <label for="recipe-ingredients" class="form-label">Ingredients</label>
                            <textarea type="text" class="form-control" name="recipe-ingredients" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="recipe-instructions" class="form-label">Instructions</label>
                            <textarea type="text" class="form-control" name="recipe-instructions" required></textarea>
                        </div>

                        <x-input-error :messages="$errors->get('email')" />
                        <x-input-error :messages="$errors->get('password')" />

                        <button type="submit" class="btn btn-primary px-4">Share</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="recipe-posts" class="container-fluid p-0 d-flex flex-column justify-content-center align-items-center"></div>
@endsection

@section('js')
    <script src="js/home.js" type="module"></script>
@endsection