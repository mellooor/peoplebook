<a href="{{ route('status', $statusLike->status->id) }}">
    <div class="card">
        <div class="card-body">
            @if ($statusLike->liker->activeProfilePictureThumbnail)
                <img src="{{ $statusLike->liker->activeProfilePictureThumbnail->getFullURL() }}"/>
            @else
                <img src="../images/default_profile_picture-25x25.png"/>
            @endif
            {{ $statusLike->liker->first_name }} {{ $statusLike->liker->last_name }} liked your status. <small>{{ $statusLike->activity->createdAtDuration() }}</small>
        </div>
    </div>
</a>