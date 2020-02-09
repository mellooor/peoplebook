<a href="{{ route('photo', $photoLike->rawPhoto->id) }}">
    <div class="card">
        <div class="card-body">
            @if ($photoLike->liker->activeProfilePictureThumbnail)
                <img src="{{ $photoLike->liker->activeProfilePictureThumbnail->getFullURL() }}"/>
            @else
                <img src="../images/default_profile_picture-25x25.png"/>
            @endif
            {{ $photoLike->liker->first_name }} {{ $photoLike->liker->last_name }} liked your photo. <small>{{ $photoLike->activity->createdAtDuration() }}</small>
        </div>
    </div>
</a>