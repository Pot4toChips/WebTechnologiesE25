	@extends('layouts.app')
 
@php
    $storageURL = "https://vvtmkzsrflnaqphsxxal.supabase.co/storage/v1/object/public";
    $imagePath = $userProfile->image; 
    $imagePath = ltrim($imagePath, '/'); // just in case
    $imageURL = "$storageURL/$imagePath";
@endphp 


	@section('title', 'Edit Profile')

	@section('css')
	<link rel="stylesheet" href="/css/styles.css">
    @endsection

	@section('content')
<div class="container" style="max-width: 700px;">
    <div class="card shadow-sm w-100">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Edit Profile</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea
                        name="description"
                        id="description"
                        class="form-control"
                        rows="3"
                    >{{ old('description', $userProfile->description) }}</textarea>
                </div>

                {{-- Bio --}}
                <div class="mb-3">
                    <label for="bio" class="form-label fw-semibold">Bio</label>
                    <textarea
                        name="bio"
                        id="bio"
                        class="form-control"
                        rows="5"
                    >{{ old('bio', $userProfile->bio) }}</textarea>
                </div>

{{-- Image --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Profile Image</label>
    <input type="file" name="image" class="form-control">

    @if($userProfile && $userProfile->image)
        <div class="mt-3">
            <p class="fw-semibold">Current Image:</p>
            <img src="{{ $imageURL }}"
                 class="img-thumbnail"
                 style="max-width: 150px;">
        </div>
    @endif
</div>



                <button type="submit" class="btn btn-primary w-100">
                    Save Changes
                </button>
            </form>

        </div>
    </div>
</div>
    @endsection
