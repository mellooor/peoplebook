@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <h2>Friend Requests</h2>
            <br>

            @if (session()->has('accepted'))
                <div class="alert alert-success">
                    {{ session()->get('accepted') }}
                </div>
            @elseif (session()->has('declined'))
                <div class="alert alert-danger">
                    {{ session()->get('declined') }}
                </div>
            @endif

            @if (count($requests) > 0)
                @foreach ($requests as $request)
                    <div class="card user-request">
                        <div class="card-body d-flex">
                            <button class="btn"><a href="{{ route('user', $request["user"]->id) }}"><img src="images/default_profile_picture-50x50.png"/>{{ $request["user"]->first_name }} {{ $request["user"]->last_name }}</a></button>
                            <div class="ml-auto my-auto d-flex">
                                <form class="options" action="{{ route('accept-friend-request') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="friend-request-id" value="{{ $request['id'] }}"/>
                                    <input type="hidden" name="target-user-id" value="{{ $request['user']->id }}"/>
                                    <button type="submit" name="accept" class="btn btn-success mx-auto my-auto">Accept</button>
                                </form>
                                <form class="options" action="{{ route('decline-friend-request') }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="friend-request-id" value="{{ $request['id'] }}"/>
                                    <input type="hidden" name="target-user-id" value="{{ $request['user']->id }}"/>
                                    <button type="submit" data-target="{{ $request["id"] }}" name="decline" class="btn btn-danger mx-auto my-auto">Decline</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <h2>No Friend Requests!</h2>
            @endif

        </div>
        @include('inc/sidebar')
    </div>
@endsection