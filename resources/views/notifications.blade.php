@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="notifs-container">

                @if (count($notifications) > 0)
                    @foreach ($notifications as $notification)
                        @if ($notification->type_id === 1) <!-- status-commented -->
                            @include('inc/status-commented-notification-item', ['statusComment' => $notification->activity->statusComment])
                        @elseif ($notification->type_id === 2) <!-- status-liked -->
                            @include('inc/status-liked-notification-item', ['statusLike' => $notification->activity->statusLike])
                        @elseif ($notification->type_id === 3) <!-- friend-request-accepted -->
                            @include('inc/friend-request-accepted-notification-item', ['newFriendship' => $notification->activity->newFriendship])
                        @elseif ($notification->type_id === 4) <!-- comment-liked -->
                            @include('inc/comment-liked-notification-item', ['commentLike' => $notification->activity->statusCommentLike])
                        @endif
                    @endforeach
                @else
                        <h2>You currently have no notifications.</h2>
                @endif
            </div>
        </div>
    </div>
@endsection