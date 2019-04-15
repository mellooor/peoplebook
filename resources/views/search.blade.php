@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-statuses-tab" data-toggle="tab" href="#nav-statuses" role="tab" aria-controls="nav-statuses" aria-selected="true">Statuses</a>
                    <a class="nav-item nav-link" id="nav-users-tab" data-toggle="tab" href="#nav-users" role="tab" aria-controls="nav-users" aria-selected="false">Users</a>
                    <a class="nav-item nav-link" id="nav-pages-tab" data-toggle="tab" href="#nav-pages" role="tab" aria-controls="nav-pages" aria-selected="false">Pages</a>
                </div>
            </nav>
            <br>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-statuses" role="tabpanel" aria-labelledby="nav-statuses-tab">
                    <h2>2 Statuses found for "{{ $term }}"</h2>
                    <br>
                    <div class="card status">
                        <div class="card-header">
                            <a href="user.php"><img src="../images/default_profile_picture-25x25.png"/> User 3</a>
                        </div>
                        <div class="card-body">
                            <p class="card-text">So Random!</p>
                            <div class="row">
                                0 Likes
                                1 Comment
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="card status">
                        <div class="card-header">
                            <a href="user.php"><img src="../images/default_profile_picture-25x25.png"/> User 2</a>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Home.</p>
                            <a href="#"><img src="../images/sky-earth-galaxy-universe.jpg"/></a>
                            <div class="row">
                                4 Likes
                                3 Comments
                            </div>
                            <div class="row">
                                <a href="#">Liked</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab">
                    <h2>1 User found for "{{ $term }}"</h2>
                    <br>
                    <div class="card user">
                        <div class="card-body d-flex">
                            <button class="btn"><a href="user.php"><img src="../images/default_profile_picture-50x50.png"/>User 2</a></button>
                            <button class="btn btn-primary ml-auto my-auto">Add</button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-pages" role="tabpanel" aria-labelledby="nav-pages-tab">
                    <h2>3 Pages found for "{{ $term }}"</h2>
                    <br>
                    <div class="card page">
                        <div class="card-body d-flex">
                            <button class="btn"><a href="page.php"><img src="../images/default_profile_picture-50x50.png"/>Page 1</a></button>
                            <button class="btn btn-primary ml-auto my-auto">Like</button>
                        </div>
                    </div>
                    <div class="card page">
                        <div class="card-body d-flex">
                            <button class="btn"><a href="page.php"><img src="../images/default_profile_picture-50x50.png"/>Page 6</a></button>
                            <button class="btn btn-primary ml-auto my-auto">Like</button>
                        </div>
                    </div>
                    <div class="card page">
                        <div class="card-body d-flex">
                            <button class="btn"><a href="page.php"><img src="../images/default_profile_picture-50x50.png"/>Page 11</a></button>
                            <button class="btn btn-primary ml-auto my-auto">Like</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include("inc/sidebar")
    </div>
@endsection