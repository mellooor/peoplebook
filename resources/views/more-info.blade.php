@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            @if ($data['user']->activeProfilePicture())
                <img src="{{ $data['user']->activeProfilePicture()->getFullURL() }}"/>
            @else
                <img src="/images/default_profile_picture-320x320.png"/>
            @endif

            <h2>{{$data['user']->first_name}} {{$data['user']->last_name}}</h2>

            <a class="btn btn-dark" href="{{ URL::previous() }}">Back</a>
            <hr>

            <br>
            <ul class="list-group list-group-flush more-info-wrapper">
                <li class="list-group-item">
                    <b>Name:</b>
                    {{ $data['user']->first_name }} {{ $data['user']->last_name }}
                    <!-- If userID matches current user -->
                    @if ($data['user']->id === Auth::user()->id)
                    <a href="{{ route('edit-name-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                    @endif
                    <!-- end if -->
                </li>

                @if ($data['displayDOB'] === true)
                    <li class="list-group-item">
                        <b>Date of Birth:</b>
                        {{ $data['user']->formattedDateOfBirth() }}
                        <!-- If userID matches current user -->
                        @if ($data['user']->id === Auth::user()->id)
                            <a href="{{ route('edit-dob-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                        @endif
                        <!-- end if -->
                    </li>
                @endif
                @if ($data['displayHomeTown'] === true)
                    <li class="list-group-item">
                        <b>Home Town:</b>
                        @if ($data['user']->homeTown)
                            {{ $data['user']->homeTown->placeName->name }}
                        @else
                            Not Set
                        @endif

                        <!-- If userID matches current user -->
                        @if ($data['user']->id === Auth::user()->id)
                            <a href="{{ route('edit-home-town-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                        @endif
                    <!-- end if -->
                    </li>
                @endif

                @if ($data['displayCurrentTown'] === true)
                    <li class="list-group-item">
                        <b>Current Town:</b>
                        @if ($data['user']->currentTown)
                            {{ $data['user']->currentTown->placeName->name }}
                        @else
                            Not Set
                        @endif
                        <!-- If userID matches current user -->
                        @if ($data['user']->id === Auth::user()->id)
                            <a href="{{ route('edit-current-town-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                        @endif
                        <!-- end if -->
                    </li>
                @endif

                @if ($data['displaySchool'] === true)
                    <li class="list-group-item">
                        <b>School:</b>
                        @if ($data['user']->currentSchool)
                            {{ $data['user']->currentSchool->schoolName->name }}
                        @else
                            Not Set
                        @endif
                        <!-- If userID matches current user -->
                        @if ($data['user']->id === Auth::user()->id)
                            <a href="{{ route('edit-school-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                        @endif
                        <!-- end if -->
                    </li>
                @endif

                @if ($data['displayJob'] === true)
                    <li class="list-group-item">
                        <b>Job:</b>
                        @if ($data['user']->currentJob)
                            {{ $data['user']->currentJob->job_title }} at {{ $data['user']->currentJob->employer->name }}
                        @else
                            Not Set
                        @endif
                        <!-- If userID matches current user -->
                        @if ($data['user']->id === Auth::user()->id)
                            <a href="{{ route('edit-job-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                        @endif
                        <!-- end if -->
                    </li>
                @endif

                @if ($data['displayRelationship'] === true)
                    <li class="list-group-item">
                        <b>Relationship Status:</b>
                        @if ($data['user']->relationship())
                            @if ($data['user']->relationship()->relationship_type_id === 1)
                                {{ $data['user']->relationship()->relationshipType->type }}
                            @else
                                {{ $data['user']->relationship()->relationshipType->type }} with {{ $data['user']->relationship()->otherUser($data['user']->id)->first_name }} {{ $data['user']->relationship()->otherUser($data['user']->id)->last_name }}
                            @endif
                        @else
                            Not Set
                        @endif
                        <!-- If userID matches current user -->
                        @if ($data['user']->id === Auth::user()->id)
                            <a href="{{ route('edit-relationship-page') }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                        @endif
                    </li>
                @endif
            </ul>
        </div>

        @include("inc/sidebar")
    </div>
@endsection