@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            @if ($data['user']->activeProfilePicture())
                <img src="{{ $data['user']->activeProfilePicture()->getFullURL() }}"/>
            @else
                <img src="/images/default_profile_picture-320x320.png"/>
            @endif

            <!-- If userID matches current user -->
            <button class="btn btn-warning" data-toggle="modal" data-target="#add-profile-pic-modal"><i class="fas fa-pencil-alt"></i></button>
            <!-- end if -->
            <h2>{{ $data['user']->first_name }} {{ $data['user']->last_name }}</h2>

            <!-- If not My Account -->
            {{--<form class="options">--}}
                {{--<button type="submit" name="accept" class="btn btn-primary">Friends</button>--}}
            {{--</form>--}}

            <div id="user-menu">
                <div class="card">
                    @if ($data['user']->id === Auth::user()->id)
                        <a class="btn" href="{{ route('my-profile-more-info') }}"><img class="card-img-top" src="/images/ellipsis-icon_256x256.png" alt="Card image cap"></a>
                        <a class="btn" href="{{ route('my-profile-more-info') }}">
                    @else
                        <a class="btn" href="{{ route('user-more-info', $data['user']->id) }}"><img class="card-img-top" src="/images/ellipsis-icon_256x256.png" alt="Card image cap"></a>
                        <a class="btn" href="{{ route('user-more-info', $data['user']->id) }}">
                    @endif
                        <div class="card-body">
                            <p class="card-text">More Information</p>
                        </div>
                    </a>
                </div>
                <div class="card">
                    @if ($data['user']->id === Auth::user()->id)
                        <a class="btn" href="{{ route('my-photos') }}"><img class="card-img-top" src="/images/picture-icon_256x256.png" alt="Card image cap"></a>
                        <a class="btn" href="{{ route('my-photos') }}">
                            <div class="card-body">
                                <p class="card-text">Photos</p>
                            </div>
                        </a>
                    @else
                        <a class="btn" href="{{ route('photos', $data['user']->id) }}"><img class="card-img-top" src="/images/picture-icon_256x256.png" alt="Card image cap"></a>
                        <a class="btn" href="{{ route('photos', $data['user']->id) }}">
                            <div class="card-body">
                                <p class="card-text">Photos</p>
                            </div>
                        </a>
                    @endif


                </div>
                <div class="card">
                    <a class="btn" href="{{ route('friends', $data['user']->id) }}"><img class="card-img-top" src="/images/multiple-users-icon_256x256.png" alt="Card image cap"></a>
                    <a class="btn" href="{{ route('friends', $data['user']->id) }}">
                        <div class="card-body">
                            <p class="card-text">Friends</p>
                        </div>
                    </a>
                </div>
            </div>
            <br>

            <div id="statuses">
                @if (count($data['newsFeedItems']) > 0)
                    @foreach ($data['newsFeedItems'] as $newsFeedItem)
                        @if ($status = $newsFeedItem->isCreatedStatus())
                            @include('inc/status-news-feed-item')
                        @elseif ($photo = $newsFeedItem->isUploadedPhoto())
                            @include('inc/photo-news-feed-item')
                        @elseif ($photo = $newsFeedItem->isChangedProfilePicture())
                            @include('inc/photo-news-feed-item')
                        @elseif ($newFriendship = $newsFeedItem->isNewFriendship())
                            @include('inc/new-friendship-news-feed-item')
                        @elseif ($newRelationship = $newsFeedItem->isNewRelationship())
                            @include('inc/new-relationship-news-feed-item')
                        @endif
                        <br>
                    @endforeach
                    {{ $data['newsFeedItems']->links() }}
                @else
                    <h2>No Statuses to Show!</h2>
                @endif
            </div>
        </div>

        @include("inc/sidebar")
        @include("inc/status-delete-confirm")
        @include("inc/status-edit-modal")
        @include("inc/photo-delete-confirm")
        @include("inc/likes-modal")
        @include("inc/status-comment-edit-modal")
        @include("inc/status-comment-delete-confirm")
        @include("inc/photo-comment-edit-modal")
        @include("inc/photo-comment-delete-confirm")
    </div>
@endsection