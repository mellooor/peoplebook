<div class="card user-request">
    <div class="card-body d-flex">
        <div class="my-auto">You received a relationship request from <a href="{{ route('user', $relationshipRequest->nonCurrentUser->id) }}">{{ $relationshipRequest->nonCurrentUser->first_name }} {{ $relationshipRequest->nonCurrentUser->last_name }}</a></div>

        <div class="ml-auto my-auto d-flex">
            <form class="options" action="{{ route('accept-relationship-request') }}" method="post">
                @csrf
                <input type="hidden" name="relationship-request-id" value="{{ $relationshipRequest->id }}"/>
                <input type="hidden" name="target-user-id" value="{{ $relationshipRequest->nonCurrentUser->id }}"/>
                <button type="submit" name="accept" class="btn btn-success mx-auto my-auto">Accept</button>
            </form>
            <form class="options" action="{{ route('decline-relationship-request') }}" method="post">
                @csrf
                @method('DELETE')
                <input type="hidden" name="relationship-request-id" value="{{ $relationshipRequest->id }}"/>
                <input type="hidden" name="target-user-id" value="{{ $relationshipRequest->nonCurrentUser->id }}"/>
                <button type="submit" data-target="{{ $relationshipRequest->id }}" name="decline" class="btn btn-danger mx-auto my-auto">Decline</button>
            </form>
        </div>
    </div>
</div>