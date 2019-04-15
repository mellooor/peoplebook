@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <h2>Friends</h2>
            <br>
            <div class="card user">
                <div class="card-body d-flex">
                    <button class="btn"><a href="{{ route('user') }}"><img src="images/default_profile_picture-50x50.png"/>User 2</a></button>
                    <form class="options ml-auto">
                        <button type="submit" name="remove-friend" class="btn btn-danger">Remove Friend</button>
                    </form>
                </div>
            </div>
            <div class="card user">
                <div class="card-body d-flex">
                    <button class="btn"><a href="{{ route('user') }}"><img src="images/default_profile_picture-50x50.png"/>User 4</a></button>
                    <form class="options ml-auto">
                        <button type="submit" name="remove-friend" class="btn btn-danger">Remove Friend</button>
                    </form>
                </div>
            </div>
            <div class="card user">
                <div class="card-body d-flex">
                    <button class="btn"><a href="{{ route('user') }}"><img src="images/default_profile_picture-50x50.png"/>User 7</a></button>
                    <form class="options ml-auto">
                        <button type="submit" name="remove-friend" class="btn btn-danger">Remove Friend</button>
                    </form>
                </div>
            </div>
        </div>
        @include("inc/sidebar")
    </div>
@endsection