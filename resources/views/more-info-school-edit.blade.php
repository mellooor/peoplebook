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

    <h1>Edit School</h1>

    <h4>Existing Schools:</h4>
    <ul class="list-group">
        @if (count($data['schoolNames']) > 0)
            @foreach($data['schoolNames'] as $schoolName)
                <li class="list-group-item list-group-item-action">
                    <form action="{{ route('update-school') }}" method="post">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="school-name-id" value="{{ $schoolName->id }}"/>
                        <button type="submit" class="btn btn-link">{{ $schoolName->name }}</button>
                    </form>
                </li>
            @endforeach
        @else
            <li class="list-group-item">No schools found.</li>
        @endif
    </ul>

    <br>

    <form action="{{ route('add-school-name') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="school-name">If your school cannot be found, add it to the list:</label>
            <input type="text" class="form-control" name="school-name" id="school-name"/>
            <br>
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>

    <hr>

    <h4>Current School:</h4>
    <!-- If User has a School -->
    @if ($data['user']->current_school_id)
        <p>{{ $data['user']->currentSchool->schoolName->name }}</p>
    <!-- Else -->
    @else
        <p>Not Set</p>
    @endif
    <!-- End if -->
@endsection