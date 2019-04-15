@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="card status">
                <div class="card-header">
                    <a href="{{ route('user') }}"><img src="/images/default_profile_picture-25x25.png"/> User 3</a>
                </div>
                <div class="card-body">
                    <p class="card-text">So Random! <small>27 minutes ago</small></p>
                    <div class="row">
                        <button class="btn btn-link" data-toggle="modal" data-target="#likes-modal">0 Likes</button>
                    </div>
                    <div class="row">
                        <a href="#">Like</a>
                    </div>
                    <div class="row comments">
                        <div class="card comment">
                            <div class="card-body">
                                <a href="{{ route('user') }}"><img src="/images/default_profile_picture-25x25.png"/></a> Hello World! <b>(1 like)</b> <a href="#">Like</a> <small>10 minutes ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include("inc/sidebar")
        @include("inc/likes-modal")
    </div>

@endsection