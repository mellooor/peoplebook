@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <h2>User 1's Photos</h2>

            <a class="btn btn-primary" href="{{ route('user') }}">Back</a>
            <br>
            <!-- If there's any images to show -->
            {{--<div id="gallery">--}}
                {{--<img class="gallery-image" src="/images/sky-earth-galaxy-universe.jpg" data-toggle="modal" data-target="#photo-lightbox">--}}
            {{--</div>--}}

            <!-- else -->
            <h2>No Images to Show!</h2>
        </div>

        @include("inc/sidebar")
        @include("inc/photo-lightbox")
        @include("inc/likes-modal")
    </div>
@endsection