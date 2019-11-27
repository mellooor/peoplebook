@extends('layouts/app')

@section('content')
    <div class="card user-request">
        <div class="card-body d-flex">
            <button class="btn"><a href="{{ route('user', $data["otherUser"]->id) }}"><img src="images/default_profile_picture-50x50.png"/>{{ $data["otherUser"]->first_name }} {{ $data["otherUser"]->last_name }}</a></button>
            <div class="ml-auto my-auto d-flex">
                <form class="options" action="{{ route('accept-relationship-request') }}" method="post">
                    @csrf
                    <input type="hidden" name="relationship-request-id" value="{{ $data['relationshipRequest']->id }}"/>
                    <input type="hidden" name="target-user-id" value="{{ $data['otherUser']->id }}"/>
                    <button type="submit" name="accept" class="btn btn-success mx-auto my-auto">Accept</button>
                </form>
                <form class="options" action="{{ route('decline-relationship-request') }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="relationship-request-id" value="{{ $data['relationshipRequest']->id }}"/>
                    <input type="hidden" name="target-user-id" value="{{ $data['otherUser']->id }}"/>
                    <button type="submit" data-target="{{ $data["relationshipRequest"]->id }}" name="decline" class="btn btn-danger mx-auto my-auto">Decline</button>
                </form>
            </div>
        </div>
    </div>
@endsection