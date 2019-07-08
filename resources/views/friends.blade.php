@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            @if ($data['user']->id === Auth::user()->id)
                <h2>Friends</h2>
            @else
                <h2>{{ $data['user']->first_name }}'s Friends</h2>
            @endif

            <br>

            @if (session()->has('removed'))
                <div class="alert alert-danger">
                    {{ session()->get('removed') }}
                </div>
            @endif

            @if (count($data['friendships']) > 0)
                @foreach ($data['friendships'] as $friendshipID => $friend)
                    <div class="card user">
                        <div class="card-body d-flex">
                            <button class="btn user-btn"><a href="{{ route('user', $friend->id) }}" class="user-link"><img src="/images/default_profile_picture-50x50.png"/>{{ $friend->first_name }} {{ $friend->last_name }}</a></button>
                            <button name="remove-friend" data-toggle="modal" data-target="#remove-friend-confirm" data-friendship-id="{{ $friendshipID }}" data-target-user-id="{{ $friend->id }}" class="btn btn-danger my-auto ml-auto remove-friend-btn">Remove Friend</button>
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