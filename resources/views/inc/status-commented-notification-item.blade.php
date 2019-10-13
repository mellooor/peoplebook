<a href="{{ route('status', $statusComment->status->id) }}">
    <div class="card">
        <div class="card-body">
            @if ($statusComment->commenter->activeProfilePictureThumbnail)
                <img src="{{ $statusComment->commenter->activeProfilePictureThumbnail->getFullURL() }}"/>
            @else
                <img src="../images/default_profile_picture-25x25.png"/>
            @endif
            {{ $statusComment->commenter->first_name }} {{ $statusComment->commenter->last_name }} commented on your status. <small>59 minutes ago</small>
        </div>
    </div>
</a>