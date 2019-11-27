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
    <h2>Edit Date of Birth</h2>

    <form action="{{ route('update-DOB') }}" method="post">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="date-of-birth">Date:</label>
            <input type="date" class="form-control" name="date-of-birth" value="{{ $user->date_of_birth->format('Y-m-d') }}"/>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection