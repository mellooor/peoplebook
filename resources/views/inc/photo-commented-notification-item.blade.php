<a href="{{ route('photo', $photoComment->rawPhoto->id) }}">
    <div class="card">
        <div class="card-body">
            @if ($photoComment->author->activeProfilePictureThumbnail)
                <img src="{{ $photoComment->author->activeProfilePictureThumbnail->getFullURL() }}"/>
            @else
                <img src="../images/default_profile_picture-25x25.png"/>
            @endif
            {{ $photoComment->author->first_name }} {{ $photoComment->author->last_name }} commented on your photo. <small>{{ $photoComment->activity->createdAtDuration() }}</small>
        </div>
    </div>
</a>