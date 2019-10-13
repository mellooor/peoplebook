@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-9" style="background-color: #bbb; border: 1px solid;">
            @if (session()->has('deleted'))
                <div class="alert alert-info">
                    {{ session()->get('deleted') }}
                </div>
            @elseif (session()->has('not-deleted'))
                <div class="alert alert-danger">
                    {{ session()->get('not-deleted') }}
                </div>

            @endif

            @if (session()->has('created'))
                    <div class="alert alert-info">
                        {{ session()->get('created') }}
                    </div>
            @elseif (session()->has('not-created'))
                    <div class="alert alert-danger">
                        {{ session()->get('not-created') }}
                    </div>
            @endif

            <br>
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#status-create-modal">Create Status</button>
            @if (count($data['newsFeedItems']) > 0)
                @foreach($data['newsFeedItems'] as $newsFeedItem)
                    @if ($status = $newsFeedItem->isCreatedStatus())
                        @include('inc/status-news-feed-item')
                    @elseif ($photo = $newsFeedItem->isUploadedPhoto())
                            @include('inc/photo-news-feed-item')
                    @elseif ($photo = $newsFeedItem->isChangedProfilePicture())
                        @include('inc/photo-news-feed-item')
                    @elseif ($newFriendship = $newsFeedItem->isNewFriendship())
                        @include('inc/new-friendship-news-feed-item')
                    @endif
                    <br>
                @endforeach
            @else
                <h2>No Statuses to Show!</h2>
            @endif
        </div>
        @include("inc/sidebar")
        @include("inc/status-create-modal")
        @include("inc/status-delete-confirm")
        @include("inc/status-edit-modal")
        @include("inc/likes-modal")
        @include("inc/status-comment-edit-modal")
        @include("inc/status-comment-delete-confirm")
    </div>
@endsection
