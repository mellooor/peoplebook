@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <h2>Friends</h2>
            <br>

            @if (session()->has('removed'))
                <div class="alert alert-danger">
                    {{ session()->get('removed') }}
                </div>
            @endif

            @if (count($friendships) > 0)
                @foreach ($friendships as $friendship)
                    <div class="card user">
                        <div class="card-body d-flex">
                            <button class="btn user-btn"><a href="{{ route('user', $friendship['user']->id) }}" class="user-link"><img src="images/default_profile_picture-50x50.png"/>{{ $friendship['user']->first_name }} {{ $friendship['user']->last_name }}</a></button>
                            <button name="remove-friend" data-toggle="modal" data-target="#remove-friend-confirm" data-friendship-id="{{ $friendship['id'] }}" data-target-user-id="{{ $friendship['user']->id }}" class="btn btn-danger my-auto ml-auto remove-friend-btn">Remove Friend</button>
                        </div>
                    </div>
                @endforeach
            @else
                <h3>No Friends to Show!</h3>
            @endif
        </div>
        @include("inc/sidebar")
        @include("inc/remove-friend-confirm")
    </div>
@endsection