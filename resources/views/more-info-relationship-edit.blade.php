@extends('layouts/app')

@section('content')
    <a class="btn btn-dark" href="{{ route('my-profile-more-info') }}">Back</a>
    <hr>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @elseif (session()->has('fail'))
        <div class="alert alert-danger">
            {{  session()->get('fail') }}
        </div>
    @endif

    <h2>Edit Relationship Status</h2>

    <form action="{{ route('update-relationship') }}" method="post">
        @csrf
        @method('PUT')
        <select class="custom-select" name="relationship-status">
            <option value="single">Select relationship type...</option>
            @if (count($data['relationshipTypes']) > 0)
                @foreach($data['relationshipTypes'] as $relationshipType)
                    <option value="{{ $relationshipType->id }}">{{ $relationshipType->type }}</option>
                @endforeach
            @else
                <option value="null">No relationship types found.</option>
            @endif
        </select>

        <select class="custom-select" name="other-user-id">
            <option>Select other user...</option>
            @if (count($data['otherUsers']) > 0)
                @foreach($data['otherUsers'] as $otherUser)
                    <option value="{{ $otherUser->id }}">{{ $otherUser->first_name }} {{ $otherUser->last_name }}</option>
                @endforeach
            @else
                <option>No other users found.</option>
            @endif
        </select>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>

    <h3>Current Relationship Status:</h3>
    <!-- If User has a Relationship Status -->
    @if ($data['user']->relationship())
        <p>{{ ucfirst($data['user']->relationship()->relationshipType->type) }}</p> <!-- Relationship type with first letter capitalised. -->
        @if ($data['user']->relationship()->relationshipType->type !== 'single' && !$data['user']->relationship()->is_request)
             with <p>{{ $data['user']->relationship()->otherUser($data['user']->id)->first_name }} {{ $data['user']->relationship()->otherUser($data['user']->id)->last_name }}</p>
        @endif
    <!-- Else -->
    @else
        <p>Not set.</p>
    @endif
    <!-- End if -->
@endsection