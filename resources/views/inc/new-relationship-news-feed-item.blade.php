<div class="card status">
    <div class="card-body">
        <p class="card-text">
            <a href="{{  route('user', $newRelationship->user1->id) }}">{{ $newRelationship->user1->first_name }} {{ $newRelationship->user1->last_name }}</a>
            is in a relationship with
            <a href="{{ route('user', $newRelationship->user2->id) }}">{{ $newRelationship->user2->first_name }} {{ $newRelationship->user2->last_name }}</a>
        </p>
        <small><b>{{ $newsFeedItem->createdAtDuration() }}</b></small>
    </div>
</div>