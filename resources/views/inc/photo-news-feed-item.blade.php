<div class="card photo">
    <div class="card-header d-flex">
        <a href="{{ route('user', $photo->uploader_id) }}">
            {{ $photo->uploader->first_name }} {{ $photo->uploader->last_name }}
        </a>
        @if ($newsFeedItem->isUploadedPhoto())
            &nbsp;uploaded a new photo.
        @elseif ($newsFeedItem->isChangedProfilePicture())
            &nbsp;updated their profile picture.
        @endif

        <div class="dropdown ml-auto">
            @if ($data['user']->id === $photo->uploader_id)
                {{--If picture isn't a profile picture, allow its privacy to be changed.--}}
                @if (!$photo->hasProfilePicture())
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Privacy
                    </button>
                    <form method="post" action="{{ route('update-photo-privacy') }}" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="photo-id" value="{{ $photo->id }}"/>
                        <button type="submit" name="privacy-type" value="public" class="dropdown-item">
                            Show to All Users
                            @if ($photo->privacy->visibility === 'public')
                                <i class="fas fa-check"></i>
                            @endif
                        </button>
                        <button type="submit" name="privacy-type" value="friends-of-friends" class="dropdown-item">
                            Show to Friends and Friends of Friends
                            @if ($photo->privacy->visibility === 'friends-of-friends')
                                <i class="fas fa-check"></i>
                            @endif
                        </button>
                        <button type="submit" name="privacy-type" value="friends-only" class="dropdown-item">
                            Show to Friends Only
                            @if ($photo->privacy->visibility === 'friends-only')
                                <i class="fas fa-check"></i>
                            @endif
                        </button>
                    </form>
                @endif
                <button class="btn btn-danger" data-toggle="modal" data-target="#photo-delete-confirm" data-photo-id="{{ $photo->id }}"><i class="fas fa-trash-alt"></i></button>
            @endif
        </div>

    </div>
    <div class="card-body">
        <a href="{{ route('photo', $photo->id) }}">
            <img src="{{ $photo->getAssociatedPhoto('thumbnail')->getFullURL() }}"/>
        </a>

        <small><b>{{ $photo->timeUploadedDuration() }}</b></small>

        <div class="row">
            <button class="btn btn-link" data-toggle="modal" data-target="#likes-modal">
                {{ count($photo->likes) }}
                {{ (count($photo->likes) == 1) ? 'Like' : 'Likes'}}
            </button>
            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-{{ $loop->index }}" aria-expanded="true" aria-controls="collapseOne">
                {{ count($photo->comments) }}
                {{ (count($photo->comments) == 1) ? 'Comment' : 'Comments' }}
            </button>
        </div>
        <div class="row">
            @if ($photo->likes->contains('user_id', $data['user']->id))
                <form action="{{ route('unlike-photo') }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="photo-like-id" value="{{ $photo->likes->firstWhere('user_id', $data['user']->id)->id }}"/>
                    <button type="submit" class="btn btn-link">Unlike</button>
                </form>
            @else
                <form action="{{ route('like-photo') }}" method="post">
                    @csrf
                    <input type="hidden" name="photo-id" value="{{ $photo->id }}"/>
                    <button type="submit" class="btn btn-link">Like</button>
                </form>
            @endif
        </div>
        @if (count($photo->comments) > 0)
            @foreach ($photo->comments as $comment)
                <div id="collapse-{{ $loop->parent->index }}" class="row comments collapse">
                    <div class="card comment">
                        <div class="card-body">
                            <a href="{{ route('user', $comment->author_id) }}">
                                @if ($comment->author->activeProfilePictureThumbnail)
                                    <img src="{{ $comment->author->activeProfilePictureThumbnail->getFullURL() }}"/>
                                @else
                                    <img src="../images/default_profile_picture-25x25.png"/>
                                @endif
                            </a>
                            <p class="card-text">{{ $comment->content }}</p>
                            <b>({{ count($comment->likes) }} like)</b>

                            @if ( $comment->likes->contains('user_id', $data['user']->id))
                                <form action="{{ route('unlike-photo-comment') }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="photo-comment-id" value="{{ $comment->id }}"/>
                                    <input type="hidden" name="photo-comment-like-id" value="{{ $comment->likes->firstWhere('user_id', $data['user']->id)->id }}"/>
                                    <button type="submit" class="btn btn-link">Unlike</button>
                                </form>
                            @else
                                <form action="{{ route('like-photo-comment') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="photo-comment-id" value="{{ $comment->id }}"/>
                                    <button type="submit" class="btn btn-link">Like</button>
                                </form>
                            @endif

                            @if ($comment->author_id === $data['user']->id)
                                <button class="btn btn-link photo-comment-edit-btn" data-toggle="modal" data-target="#photo-comment-edit-modal" data-photo-id="{{ $photo->id }}" data-comment-id="{{ $comment->id }}">Edit</button>
                                <button class="btn btn-link photo-comment-delete-btn" data-toggle="modal" data-target="#photo-comment-delete-confirm" data-photo-id="{{ $photo->id }}" data-comment-id="{{ $comment->id }}">Delete</button>
                            @endif
                            <small>{{ $comment->createdAtDuration() }}</small>
                            @if ($comment->updated_at)
                                <small>(<b>Edited:{{ $comment->updatedAtDuration() }}</b>)</small>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        <form action="{{ route('add-photo-comment') }}" method="post">
            @csrf
            <input type="hidden" name="photo-id" value="{{ $photo->id }}"/>
            <textarea class="w-100 comment-text-input" name="comment" placeholder="Enter Comment..."></textarea>
        </form>
    </div>
</div>