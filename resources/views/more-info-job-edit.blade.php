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

    <h1>Edit Jobs</h1>

    <form action="{{ route('update-job') }}" method="post">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="job-title">Job Title:</label>
            <input type="text" class="form-control" name="job-title" id="job-title"/>
        </div>
        <div class="form-group">
            <label for="job-employer">Employer:</label>
            <select class="custom-select" name="employer-id">
                @if (count($data['employers']) > 0)
                    <option>Choose Employer...</option>
                    @foreach($data['employers'] as $employer)
                        <option value="{{ $employer->id }}">{{ $employer->name }}</option>
                    @endforeach
                @else
                    <option>No employers found</option>
                @endif
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>

    <hr>

    <form action="{{ route('add-employer') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="employer-name">If your employer cannot be found, add it to the list:</label>
            <input type="text" class="form-control" name="employer-name"/>
            <br>
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>

    <hr>

    <h4>Current Job:</h4>
    <!-- If User has a job -->
    @if ($data['user']->current_job_id)
        <p>{{ $data['user']->currentJob->job_title }} at {{ $data['user']->currentJob->employer->name }}</p>
    <!-- Else -->
    @else
        <p>Not Set</p>
    @endif
    <!-- End if -->
@endsection