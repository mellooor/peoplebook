<a href="{{ route('user', $relationshipRequestAccepted->nonCurrentUser()->id) }}">
    <div class="card">
        <div class="card-body">
            @if ($relationshipRequestAccepted->nonCurrentUser()->activeProfilePictureThumbnail)
                <img src="{{ $relationshipRequestAccepted->nonCurrentUser()->activeProfilePictureThumbnail->getFullURL() }}"/>
            @else
                <img src="../images/default_profile_picture-25x25.png"/>
            @endif
            {{ $relationshipRequestAccepted->nonCurrentUser()->first_name }} {{ $relationshipRequestAccepted->nonCurrentUser()->last_name }} accepted your relationship request. <small>{{ $relationshipRequestAccepted->relationshipRequestAcceptedActivity->createdAtDuration() }}</small>
        </div>
    </div>
</a>