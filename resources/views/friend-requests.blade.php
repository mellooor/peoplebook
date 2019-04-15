@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <h2>Friend Requests</h2>
            <br>
            <div class="card user-request">
                <div class="card-body d-flex">
                    <button class="btn"><a href="{{ route('user') }}"><img src="images/default_profile_picture-50x50.png"/>User 12</a></button>
                    <form class="options ml-auto d-flex">
                        <button type="submit" name="accept" class="btn btn-success mx-auto my-auto">Accept</button>
                        <button type="submit" name="decline" class="btn btn-danger mx-auto my-auto">Decline</button>
                    </form>
                </div>
            </div>
        </div>
        @include('inc/sidebar')
    </div>
@endsection