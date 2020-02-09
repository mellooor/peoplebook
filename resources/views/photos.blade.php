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

            @if ($data['isCurrentUser'])
                <h2>Your Photos</h2>
            @else
                <h2>{{ $data['user']->first_name }}'s Photos</h2>
            @endif

            <a class="btn btn-primary" href="{{ route('user', $data['user']->id) }}">Back</a>

            @if ($data['isCurrentUser'])
                <button id="add-photos-modal-btn" class="btn btn-primary" data-toggle="modal" data-target="#add-photos-modal"><i class="fas fa-upload"></i> Add Photos</button>
            @endif

            <br>
            @if ($data['photos']->total() > 0)
                <div id="gallery">
                    @foreach ($data['photos'] as $photo)
                        <a href="{{ route('photo', $photo->id) }}"><img class="gallery-image" src="{{ $photo->getFullURL() }}"></a>
                    @endforeach
                </div>

                <br>
                <div id="photo-pagination">
                    {{ $data['photos']->links() }}
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