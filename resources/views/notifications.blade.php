@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @elseif (session()->has('fail'))
                <div class="alert alert-danger">
                    {{ session()->get('fail') }}
                </div>
            @endif

            <div id="notifs-container">

                @if (count($notifications) > 0)
                    @foreach ($notifications as $notification)
                        @if ($notification->notificationType->type === 'status-commented') <!-- status-commented -->
                            @include('inc/status-commented-notification-item', ['statusComment' => $notification->activity->statusComment])
                        @elseif ($notification->notificationType->type === 'status-liked') <!-- status-liked -->
                            @include('inc/status-liked-notification-item', ['statusLike' => $notification->activity->statusLike])
                        @elseif ($notification->notificationType->type === 'friend-request-accepted') <!-- friend-request-accepted -->
                            @include('inc/friend-request-accepted-notification-item', ['newFriendship' => $notification->activity->newFriendship])
                        @elseif ($notification->notificationType->type === 'comment-liked') <!-- comment-liked -->
                            @include('inc/comment-liked-notification-item', ['commentLike' => $notification->activity->statusCommentLike])
                        @elseif ($notification->notificationType->type === 'relationship-request')
                            @include('inc/relationship-request-notification-item', ['relationshipRequest' => $notification->activity->relationshipRequest])
                        @elseif ($notification->notificationType->type === 'relationship-request-accepted')
                                @include('inc/relationship-request-accepted-notification-item', ['relationshipRequestAccepted' => $notification->activity->relationshipRequestAccepted])
                        @elseif ($notification->notificationType->type === 'photo-commented')
                            @include('inc/photo-commented-notification-item', ['photoComment' => $notification->activity->photoComment])
                        @elseif ($notification->notificationType->type === 'photo-liked')
                            @include('inc/photo-liked-notification-item', ['photoLike' => $notification->activity->photoLike])
                        @elseif ($notification->notificationType->type === 'photo-comment-liked')
                            @include('inc/photo-comment-liked-notification-item', ['photoCommentLike' => $notification->activity->photoCommentLike])
                        @endif
                    @endforeach
                @else
                        <h2>You currently have no notifications.</h2>
                @endif
            </div>
        </div>
    </div>
@endsection