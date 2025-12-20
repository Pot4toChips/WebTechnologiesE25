@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>{{ $user->name }} is following</h3>

    @if($following->isEmpty())
        <p>No accounts yet.</p>
    @else
        <ul class="list-group">
            @foreach($following as $f)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('profile.show', $f->id) }}">{{ $f->name }}</a>

                    @if(Auth::check() && Auth::id() !== $f->id)
                        @php
                            $isFollowing = Auth::user()->following->contains($f->id);
                        @endphp
                        <form action="{{ $isFollowing ? route('users.unfollow', $f->id) : route('users.follow', $f->id) }}" method="POST">
                            @csrf
                            @if($isFollowing)
                                @method('DELETE')
                            @endif
                            <button type="submit" class="btn btn-sm btn-primary">
                                {{ $isFollowing ? 'Unsubscribe' : 'Subscribe' }}
                            </button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
