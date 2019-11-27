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
    <h2>Edit Name</h2>

    <form action="{{ route('update-name') }}" method="post">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="edit-first-name">First Name:</label>
            <input type="text" name="first-name" id="edit-first-name" value="{{ $user->first_name }}"/>
        </div>

        <div class="form-group">
            <label for="edit-last-name">Last Name:</label>
            <input type="text" name="last-name" id="edit-last-name" value="{{ $user->last_name }}"/>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection