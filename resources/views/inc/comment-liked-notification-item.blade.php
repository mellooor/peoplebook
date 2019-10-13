<a href="{{ route('status', $commentLike->comment->status->id) }}">
    <div class="card">
        <div class="card-body">
            @if ($commentLike->liker->activeProfilePictureThumbnail)
                <img src="{{ $commentLike->liker->activeProfilePictureThumbnail->getFullURL() }}"/>
            @else
                <img src="../images/default_profile_picture-25x25.png"/>
            @endif
            {{ $commentLike->liker->first_name }} {{ $commentLike->liker->last_name }} liked your comment. <small>59 minutes ago</small>
        </div>
    </div>
</a>