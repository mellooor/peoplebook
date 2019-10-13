<div class="card status">
    <div class="card-body">
        <p class="card-text">
            <a href="{{  route('user', $newFriendship->user1->id) }}">{{ $newFriendship->user1->first_name }} {{ $newFriendship->user1->last_name }}</a>
                became friends with
            <a href="{{ route('user', $newFriendship->user2->id) }}">{{ $newFriendship->user2->first_name }} {{ $newFriendship->user2->last_name }}</a>
        </p>
        <small><b>{{ $newFriendship->createdAtDuration() }}</b></small>
    </div>
</div>