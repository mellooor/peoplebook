@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <h2>{{ $user->first_name }}'s Photos</h2>

            <a class="btn btn-primary" href="{{ route('user', $user->id) }}">Back</a>
            <br>
            @if (count($user->paginatedPhotos(9)) > 0)
                <div id="gallery">
                    @foreach ($user->paginatedPhotos(9) as $photo)
                        <img class="gallery-image" src="{{ $photo->getUrl() }}" data-toggle="modal" data-target="#photo-lightbox">
                    @endforeach
                </div>

                <br>
                <div id="photo-pagination">
                    {{ $user->paginatedPhotos(9)->links() }}
                </div>
            @else
            <h2>No Images to Show!</h2>
            @endif
        </div>

        @include("inc/sidebar")
        @include("inc/photo-lightbox")
        @include("inc/likes-modal")
    </div>
@endsection