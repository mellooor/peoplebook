@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="notifs-container">
                <a href="{{ route('status', 1) }}">
                    <div class="card">
                        <div class="card-body">
                            <img src="images/default_profile_picture-25x25.png"/> User 3 liked your comment: "Why hello there". <small>59 minutes ago</small>
                        </div>
                    </div>
                </a>
                <a href="{{ route('status', 1) }}">
                    <div class="card">
                        <div class="card-body">
                            <img src="images/default_profile_picture-25x25.png"/> User 1 replied to your comment: "LOL". <small>Tuesday 11:23</small>
                        </div>
                    </div>
                </a>
                <a href="{{ route('friendRequests') }}">
                    <div class="card">
                        <div class="card-body">
                            <img src="images/default_profile_picture-25x25.png"/> User 5 sent you a friend request. <small>2 Months Ago</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection