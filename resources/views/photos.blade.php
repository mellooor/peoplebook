@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            @if (session()->has('updated'))
                <div class="alert alert-info">
                    {{ session()->get('updated') }}
                </div>
            @elseif (session()->has('not-updated'))
                <div class="alert alert-danger">
                    {{ session()->get('not-updated') }}
                </div>
            @endif

            @if ($user->id === Auth::user()->id)
                <h2>Your Photos</h2>
            @else
                <h2>{{ $user->first_name }}'s Photos</h2>
            @endif

            <a class="btn btn-primary" href="{{ route('user', $user->id) }}">Back</a>

            @if ($user->id === Auth::user()->id)
                <button id="add-photos-modal-btn" class="btn btn-primary" data-toggle="modal" data-target="#add-photos-modal"><i class="fas fa-upload"></i> Add Photos</button>
            @endif

            <br>
            @if (count($user->paginatedThumbnailPhotos(9)) > 0)
                <div id="gallery">
                    @foreach ($user->paginatedThumbnailPhotos(9) as $photo)
                        <img class="gallery-image" src="{{ $photo->getFullURL() }}" data-toggle="modal" data-target="#photo-lightbox" data-photo-id="{{ $photo->id }}">
                    @endforeach
                </div>

                <br>
                <div id="photo-pagination">
                    {{ $user->paginatedThumbnailPhotos(9)->links() }}
                </div>
            @else
            <h2>No Images to Show!</h2>
            @endif
        </div>

        @include("inc/sidebar")
        @include("inc/photo-lightbox")
        @include("inc/likes-modal")
        @include("inc/add-photos-modal")
    </div>
@endsection