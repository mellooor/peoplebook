@extends('layouts/app')

@section('content')
    <a class="btn btn-dark" href="{{ route('my-profile-more-info') }}">Back</a>
    <hr>
    @if ($data['type'] === 'home')
        <h2>Edit Home Town</h2>
    @elseif ($data['type'] === 'current')
        <h2>Edit Current Town</h2>
    @endif

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @elseif (session()->has('fail'))
        <div class="alert alert-danger">
            {{  session()->get('fail') }}
        </div>
    @endif

    <h3>Existing Towns:</h3>
    <ul class="list-group">
        @if (count($data['places']) > 0)
            @foreach($data['places'] as $place)
                <li class="list-group-item list-group-item-action">
                    <form
                    @if ($data['type'] === 'home')
                        action = "{{ route('update-town', $data['type']) }}"
                    @elseif ($data['type'] === 'current')
                        action = "{{ route('update-town', $data['type']) }}"
                    @endif
                    method="post">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="place-name-id" value="{{ $place->id }}"/>
                        <button type="submit" class="btn btn-link">{{ $place->name }}</button>
                    </form>
                </li>
            @endforeach
        @else
            <li class="list-group-item list-group-item-action">No places found.</li>
        @endif
    </ul>

    <br>

    <form action="{{ route('add-place-name') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="add-place">If your town cannot be found, add it to the list:</label>
            <input type="text" class="form-control" name="place-name" id="add-place"/>
            <br>
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>

    <hr>

    @if ($data['type'] === 'home')
        <h3>Current Home Town:</h3>
        <!-- If User has a Home Town -->
        @if ($data['user']->home_town_id)
            <p>{{ $data['user']->homeTown->placeName->name }}</p>
        <!-- else -->
        @else
            <p>Not Set</p>
        @endif
        <!-- End if -->

    @elseif ($data['type'] === 'current')
        <h3>Current Town:</h3>
        <!-- If User has a Current Town -->
        @if ($data['user']->current_town_id)
            <p>{{ $data['user']->currentTown->placeName->name }}</p>
            <!-- else -->
        @else
            <p>Not Set</p>
        @endif
        <!-- End if -->
    @endif
@endsection