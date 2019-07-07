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
            @if (count($data['statuses']) > 0)
                @foreach($data['statuses'] as $status)
                    <div class="card status">
                        <div class="card-header d-flex">
                            <a href="{{ route('user', $status->author_id) }}"><img src="../images/default_profile_picture-25x25.png"/>{{ $status->author->first_name }} {{ $status->author->last_name }}</a>
                            <div class="dropdown ml-auto">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Privacy
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Show to All Users <i class="fas fa-check"></i></a>
                                    <a class="dropdown-item" href="#">Show to Friends Only</a>
                                </div>
                                @if ($data['currentUserID'] === $status->author->id)
                                    <button class="btn btn-warning edit-status-btn" data-toggle="modal" data-target="#status-edit-modal" data-status-id="{{ $status->id }}"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#status-delete-confirm" data-status-id="{{ $status->id }}"><i class="fas fa-trash-alt"></i></button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ $status->content }}</p>

                            @if (count($status->photos) > 0)
                                @foreach ($status->photos as $photo)
                                    <img src="{{ $photo->information->file_path }}"/>
                                @endforeach
                            @endif

                            <small><b>{{ $status->created_at }}</b></small>
                            @if ($status->updated_at)
                                <small>(Edited <b>{{ $status->updated_at }}</b>)</small>
                            @endif
                            <div class="row">
                                <button class="btn btn-link" data-toggle="modal" data-target="#likes-modal">
                                    {{ count($status->likes) }}
                                    {{ (count($status->likes) == 1) ? 'Like' : 'Likes'}}
                                </button>
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-{{ $loop->index }}" aria-expanded="true" aria-controls="collapseOne">
                                    {{ count($status->comments) }}
                                    {{ (count($status->comments) == 1) ? 'Comment' : 'Comments' }}
                                </button>
                            </div>
                            <div class="row">
                                @if ($status->likes->contains('user_id', $data['currentUserID']))
                                    <form action="{{ route('unlike-status') }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="status-like-id" value="{{ $status->likes->firstWhere('user_id', $data['currentUserID'])->id }}"/>
                                        <button type="submit" class="btn btn-link">Unlike</button>
                                    </form>
                                @else
                                     <form action="{{ route('like-status') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="status-id" value="{{ $status->id }}"/>
                                        <button type="submit" class="btn btn-link">Like</button>
                                     </form>
                                @endif
                            </div>
                            @if (count($status->comments) > 0)
                                @foreach ($status->comments as $comment)
                                    <div id="collapse-{{ $loop->parent->index }}" class="row comments collapse">
                                        <div class="card comment">
                                            <div class="card-body">
                                                <a href="{{ route('user', $comment->author_id) }}">
                                                    <img src="../images/default_profile_picture-25x25.png"/>
                                                </a>
                                                <p class="card-text">{{ $comment->content }}</p>
                                                <b>({{ count($comment->likes) }} like)</b>

                                                @if ( $comment->likes->contains('user_id', $data['currentUserID']))
                                                    <form action="{{ route('unlike-comment') }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="comment-id" value="{{ $comment->id }}"/>
                                                        <input type="hidden" name="comment-like-id" value="{{ $comment->likes->firstWhere('user_id', $data['currentUserID'])->id }}"/>
                                                        <button type="submit" class="btn btn-link">Unlike</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('like-comment') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="comment-id" value="{{ $comment->id }}"/>
                                                        <button type="submit" class="btn btn-link">Like</button>
                                                    </form>
                                                @endif

                                                @if ($comment->author_id === $data['currentUserID'])
                                                    <button class="btn btn-link status-comment-edit-btn" data-toggle="modal" data-target="#status-comment-edit-modal" data-status-id="{{ $status->id }}" data-comment-id="{{ $comment->id }}">Edit</button>
                                                    <button class="btn btn-link status-comment-delete-btn" data-toggle="modal" data-target="#status-comment-delete-confirm" data-status-id="{{ $status->id }}" data-comment-id="{{ $comment->id }}">Delete</button>
                                                @endif
                                                <small>{{ $comment->created_at }}</small>
                                                @if ($comment->updated_at)
                                                    <small>(<b>Edited:{{ $comment->updated_at }}</b>)</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <form action="{{ route('add-comment') }}" method="post">
                                @csrf
                                <input type="hidden" name="status-id" value="{{ $status->id }}"/>
                                <textarea class="w-100 status-comment-text-input" name="comment" placeholder="Enter Comment..."></textarea>
                            </form>
                        </div>
                    </div>
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
