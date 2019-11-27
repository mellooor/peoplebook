@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            @if ($user->activeProfilePicture())
                <img src="{{ $user->activeProfilePicture()->getFullURL() }}"/>
            @else
                <img src="/images/default_profile_picture-320x320.png"/>
            @endif

            <h2>{{$user->first_name}} {{$user->last_name}}</h2>

            <a class="btn btn-dark" href="{{ URL::previous() }}">Back</a>
            <hr>

            <br>
            <ul class="list-group list-group-flush more-info-wrapper">
                <li class="list-group-item">
                    <b>Name:</b>
                    {{ $user->first_name }} {{ $user->last_name }}
                    <!-- If userID matches current user -->
                    @if ($user->id === Auth::user()->id)
                    <a href="{{ route('edit-name-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                    @endif
                    <!-- end if -->
                </li>
                <li class="list-group-item">
                    <b>Date of Birth:</b>
                    {{ $user->formattedDateOfBirth() }}
                    <!-- If userID matches current user -->
                    @if ($user->id === Auth::user()->id)
                        <a href="{{ route('edit-dob-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                    @endif
                    <!-- end if -->
                </li>
                <li class="list-group-item">
                    <b>Home Town:</b>
                    @if ($user->homeTown)
                        {{ $user->homeTown->placeName->name }}
                    @else
                        Not Set
                    @endif

                    <!-- If userID matches current user -->
                    @if ($user->id === Auth::user()->id)
                        <a href="{{ route('edit-home-town-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                    @endif
                <!-- end if -->
                </li>
                <li class="list-group-item">
                    <b>Current Town:</b>
                    @if ($user->currentTown)
                        {{ $user->currentTown->placeName->name }}
                    @else
                        Not Set
                    @endif
                    <!-- If userID matches current user -->
                    @if ($user->id === Auth::user()->id)
                        <a href="{{ route('edit-current-town-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                    @endif
                    <!-- end if -->
                </li>
                <li class="list-group-item">
                    <b>School:</b>
                    @if ($user->currentSchool)
                        {{ $user->currentSchool->schoolName->name }}
                    @else
                        Not Set
                    @endif
                    <!-- If userID matches current user -->
                    @if ($user->id === Auth::user()->id)
                        <a href="{{ route('edit-school-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                    @endif
                    <!-- end if -->
                </li>

                <li class="list-group-item">
                    <b>Job:</b>
                    @if ($user->currentJob)
                        {{ $user->currentJob->job_title }} at {{ $user->currentJob->employer->name }}
                    @else
                        Not Set
                    @endif
                    <!-- If userID matches current user -->
                    @if ($user->id === Auth::user()->id)
                        <a href="{{ route('edit-job-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                    @endif
                    <!-- end if -->
                </li>
                <li class="list-group-item">
                    <b>Relationship Status:</b>
                    @if ($user->relationship())
                        @if ($user->relationship()->relationship_type_id === 1)
                            {{ $user->relationship()->relationshipType->type }}
                        @else
                            {{ $user->relationship()->relationshipType->type }} with {{ $user->relationship()->otherUser($user->id)->first_name }} {{ $user->relationship()->otherUser($user->id)->last_name }}
                        @endif
                    @else
                        Not Set
                    @endif
                    <!-- If userID matches current user -->
                    @if ($user->id === Auth::user()->id)
                        <a href="{{ route('edit-relationship-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                    @endif
                </li>
            </ul>
        </div>

        @include("inc/sidebar")
    </div>
@endsection