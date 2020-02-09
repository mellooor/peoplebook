@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="card status">
                <div class="card-header d-flex">
                    <a href="{{ route('user', $data['photo']->uploader_id) }}">
                        @if ($data['photo']->uploader->activeProfilePictureThumbnail)
                            <img src="{{ $data['photo']->uploader->activeProfilePictureThumbnail->getFullURL() }}"/>
                        @else
                            <img src="../images/default_profile_picture-25x25.png"/>
                        @endif
                        {{ $data['photo']->uploader->first_name }} {{ $data['photo']->uploader->last_name }}
                    </a>
                </div>
                <div class="card-body">
                    {{--<p class="card-text">{{ Photo Caption }}</p> --}}
                    <img id="single-photo-page-img" src="{{ $data['photo']->getAssociatedPhoto('thumbnail')->getFullURL() }}" data-toggle="modal" data-target="#photo-lightbox" data-photo-id="{{ $data['photo']->id }}"/>

                    <small><b>{{ $data['photo']->timeUploadedDuration() }}</b></small>

                    <div class="row">
                        <button class="btn btn-link" data-toggle="modal" data-target="#likes-modal">
                            {{ count($data['photo']->likes) }}
                            {{ (count($data['photo']->likes) == 1) ? 'Like' : 'Likes'}}
                        </button>
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-1" aria-expanded="true" aria-controls="collapseOne">
                            {{ count($data['photo']->comments) }}
                            {{ (count($data['photo']->comments) == 1) ? 'Comment' : 'Comments' }}
                        </button>
                    </div>

                    <div class="row">
                        @if ($data['photo']->likes->contains('user_id', $data['currentUser']->id))
                            <form action="{{ route('unlike-photo') }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="photo-like-id" value="{{ $data['photo']->likes->firstWhere('user_id', $data['currentUser']->id)->id }}"/>
                                <button type="submit" class="btn btn-link">Unlike</button>
                            </form>
                        @else
                            <form action="{{ route('like-photo') }}" method="post">
                                @csrf
                                <input type="hidden" name="photo-id" value="{{ $data['photo']->id }}"/>
                                <button type="submit" class="btn btn-link">Like</button>
                            </form>
                        @endif
                    </div>

                    @if (count($data['photo']->comments) > 0)
                        @foreach ($data['photo']->comments as $photoComment)
                            <div id="collapse-1" class="row comments collapse">
                                <div class="card comment">
                                    <div class="card-body">
                                        <a href="{{ route('user', $photoComment->author_id) }}">
                                            @if ($photoComment->author->activeProfilePictureThumbnail)
                                                <img src="{{ $photoComment->author->activeProfilePictureThumbnail->getFullURL() }}"/>
                                            @else
                                                <img src="../images/default_profile_picture-25x25.png"/>
                                            @endif
                                        </a>
                                        <p class="card-text">{{ $photoComment->content }}</p>
                                        <b>({{ count($photoComment->likes) }} {{ (count($photoComment->likes) == 1) ? 'Like' : 'Likes' }})</b>

                                        @if ( $photoComment->likes->contains('user_id', $data['currentUser']->id))
                                            <form action="{{ route('unlike-photo-comment') }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="photo-comment-id" value="{{ $photoComment->id }}"/>
                                                <input type="hidden" name="photo-comment-like-id" value="{{ $photoComment->likes->firstWhere('user_id', $data['currentUser']->id) }}"/>
                                                <button type="submit" class="btn btn-link">Unlike</button>
                                            </form>
                                        @else
                                            <form action="{{ route('like-photo-comment') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="photo-comment-id" value="{{ $photoComment->id }}"/>
                                                <button type="submit" class="btn btn-link">Like</button>
                                            </form>
                                        @endif

                                        @if ($photoComment->author_id === $data['currentUser']->id)
                                            <button class="btn btn-link photo-comment-edit-btn" data-toggle="modal" data-target="#photo-comment-edit-modal" data-photo-id="{{ $data['photo']->id }}" data-comment-id="{{ $photoComment->id }}">Edit</button>
                                            <button class="btn btn-link photo-comment-delete-btn" data-toggle="modal" data-target="#photo-comment-delete-confirm" data-photo-id="{{ $data['photo']->id }}" data-comment-id="{{ $photoComment->id }}">Delete</button>
                                        @endif
                                        <small>{{ $photoComment->createdAtDuration() }}</small>
                                        @if ($photoComment->updated_at)
                                            <small>(<b>Edited:{{ $photoComment->updatedAtDuration() }}</b>)</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif


                    <form action="{{ route('add-photo-comment') }}" method="post">
                        @csrf
                        <input type="hidden" name="photo-id" value="{{ $data['photo']->id }}"/>
                        <textarea class="w-100 comment-text-input" name="comment" placeholder="Enter Comment..."></textarea>
                    </form>
                </div>
            </div>
        </div>
        @include("inc/sidebar")
        @include("inc/likes-modal")
        @include("inc/photo-lightbox")
    </div>

@endsection