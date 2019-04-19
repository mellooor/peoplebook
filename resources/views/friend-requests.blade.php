@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <h2>Friend Requests</h2>
            <br>

            @if (count($requests) > 0)
                @foreach ($requests as $request)
                    @if ($request->is_request)
                        <div class="card user-request">
                            <div class="card-body d-flex">
                                <button class="btn" id="{{ $request->id }}"><a href="{{ route('user', $request->user2_id) }}"><img src="images/default_profile_picture-50x50.png"/>{{ $request->user->first_name }} {{ $request->user->last_name }}</a></button>
                                <form class="options ml-auto d-flex">
                                    <button type="submit" name="accept" class="btn btn-success mx-auto my-auto">Accept</button>
                                    <button type="submit" name="decline" class="btn btn-danger mx-auto my-auto">Decline</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <h2>No Friend Requests!</h2>
            @endif

        </div>
        @include('inc/sidebar')
    </div>
@endsection