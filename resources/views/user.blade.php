@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <img src="/images/default_profile_picture-320x320.png"/>
            <!-- If userID matches current user -->
            <button class="btn btn-warning"><i class="fas fa-pencil-alt"></i></button>
            <!-- end if -->
            <h2>User 1</h2>

            <!-- If not My Account -->
            {{--<form class="options">--}}
                {{--<button type="submit" name="accept" class="btn btn-primary">Friends</button>--}}
            {{--</form>--}}

            <div id="user-menu">
                <div class="card">
                    <a class="btn" href="{{ route('user-more-info', 1) }}"><img class="card-img-top" src="/images/ellipsis-icon_256x256.png" alt="Card image cap"></a>
                    <a class="btn" href="{{ route('user-more-info', 1) }}">
                        <div class="card-body">
                            <p class="card-text">More Information</p>
                        </div>
                    </a>
                </div>
                <div class="card">
                    <a class="btn" href="{{ route('photos', 1) }}"><img class="card-img-top" src="/images/picture-icon_256x256.png" alt="Card image cap"></a>
                    <a class="btn" href="{{ route('photos', 1) }}">
                        <div class="card-body">
                            <p class="card-text">Photos</p>
                        </div>
                    </a>
                </div>
                <div class="card">
                    <a class="btn" href="{{ route('friends') }}"><img class="card-img-top" src="/images/multiple-users-icon_256x256.png" alt="Card image cap"></a>
                    <a class="btn" href="{{ route('friends') }}">
                        <div class="card-body">
                            <p class="card-text">Friends</p>
                        </div>
                    </a>
                </div>
            </div>
            <br>
            <h2>No Statuses to Show!</h2>

            <div id="statuses">
                <div class="card status">
                    <div class="card-header d-flex">
                        <a href="user.php"><img src="../images/default_profile_picture-25x25.png"/> User 1</a>
                        <div class="dropdown ml-auto">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Privacy
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Show to All Users <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#">Show to Friends Only</a>
                            </div>
                            <button class="btn btn-warning edit-status"><i class="fas fa-pencil-alt"></i></button>
                            <button class="btn btn-danger" data-toggle="modal" data-target="#status-delete-confirm"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><span class="status-text-content">Hello World</span> <small>27 minutes ago</small> <small><b>Edited</b> 10 minutes ago</small></p>
                        <div class="row">
                            <button class="btn btn-link" data-toggle="modal" data-target="#likes-modal">1 Like</button>
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                0 Comments
                            </button>
                        </div>
                        <div class="row">
                            <a href="#">Like</a>
                        </div>
                        <div id="collapseOne" class="row comments collapse">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include("inc/sidebar")
        @include("inc/likes-modal")
        @include("inc/status-delete-confirm")
    </div>
@endsection