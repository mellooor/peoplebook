<a href="{{ route('photo', $photoCommentLike->photoComment->rawPhoto->id) }}">
    <div class="card">
        <div class="card-body">
            @if ($photoCommentLike->liker->activeProfilePictureThumbnail)
                <img src="{{ $photoCommentLike->liker->activeProfilePictureThumbnail->getFullURL() }}"/>
            @else
                <img src="../images/default_profile_picture-25x25.png"/>
            @endif
            {{ $photoCommentLike->liker->first_name }} {{ $photoCommentLike->liker->last_name }} liked your comment. <small>{{ $photoCommentLike->activity->createdAtDuration() }}</small>
        </div>
    </div>
</a>