<a href="{{ route('user', $newFriendship->nonCurrentUser()->id) }}">
    <div class="card">
        <div class="card-body">
            @if ($newFriendship->nonCurrentUser()->activeProfilePictureThumbnail)
                <img src="{{ $newFriendship->nonCurrentUser()->activeProfilePictureThumbnail->getFullURL() }}"/>
            @else
                <img src="../images/default_profile_picture-25x25.png"/>
            @endif
            {{ $newFriendship->nonCurrentUser()->first_name }} {{ $newFriendship->nonCurrentUser()->last_name }} accepted your friend request. <small>{{ $newFriendship->activity->createdAtDuration() }}</small>
        </div>
    </div>
</a>