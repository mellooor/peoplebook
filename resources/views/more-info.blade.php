@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <img src="/images/default_profile_picture-320x320.png"/>
            <h2>User 1</h2>

            <a class="btn btn-primary" href="{{ route('user') }}">Back</a>

            <br>
            <!-- if user is current user -->
            <form>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name"/>
                </div>

                <div class="form-group">
                    <label for="date-of-birth">Date of Birth:</label>
                    <input type="date" class="form-control" name="date-of-birth" disabled/>
                </div>

                <div class="form-group">
                    <label for="home-town">Home Town:</label>
                    <input type="text" class="form-control" name="home-town"/>
                </div>

                <div class="form-group">
                    <label for="current-town">Current Town:</label>
                    <input type="text" class="form-control" name="current-town"/>
                </div>

                <div class="form-group">
                    <label for="school">School:</label>
                    <input type="text" class="form-control" name="school"/>
                </div>

                <div class="form-group">
                    <label for="job">Job:</label>
                    <input type="text" class="form-control" name="job"/>
                </div>

                <div class="form-group">
                    <label for="relationship-type">Relationship Status:</label>
                    <select class="form-control" name="relationship-type">
                        <option>Single</option>
                        <option>It's Complicated</option>
                        <option>In a Relationship</option>
                    </select>

                    <select class="form-control" name="relationship-person">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-primary">Save</button>
            </form>

            <!-- Else -->
            <ul class="list-group list-group-flush more-info-wrapper">
                <li class="list-group-item">
                    <b>Name:</b>
                    User 1
                    <!-- If userID matches current user -->
                    <button class="btn btn-warning"><i class="fas fa-pencil-alt"></i></button>
                    <!-- end if -->
                </li>
                <li class="list-group-item"><b>Date of Birth:</b> 12/12/2001</li>
                <li class="list-group-item">
                    <b>Home Town:</b>
                    Tumbridge Wells
                    <!-- If userID matches current user -->
                    <button class="btn btn-warning"><i class="fas fa-pencil-alt"></i></button>
                    <!-- end if -->
                </li>
                <li class="list-group-item">
                    <b>Current Town:</b>
                    Honolulu
                    <!-- If userID matches current user -->
                    <button class="btn btn-warning"><i class="fas fa-pencil-alt"></i></button>
                    <!-- end if -->
                </li>
                <li class="list-group-item">
                    <b>School:</b>
                    Jiju College
                    <!-- If userID matches current user -->
                    <button class="btn btn-warning"><i class="fas fa-pencil-alt"></i></button>
                    <!-- end if -->
                </li>
                <ul class="list-group-item"><b>Previous Schools:</b>
                    <li>Pereq High School</li>
                </ul>
                <li class="list-group-item">
                    <b>Job:</b>
                    Painter at Paintingtons
                    <!-- If userID matches current user -->
                    <button class="btn btn-warning"><i class="fas fa-pencil-alt"></i></button>
                    <!-- end if -->
                </li>
                <ul class="list-group-item"><b>Previous Jobs:</b>
                    <li>Bin Man at Topbin Mans</li>
                    <li>Teacher at Teachingtons</li>
                </ul>
                <li class="list-group-item">
                    <b>Relationship Status:</b>
                    In a Relationship with User 4
                    <!-- If userID matches current user -->
                    <button class="btn btn-warning"><i class="fas fa-pencil-alt"></i></button>
                    <!-- end if -->
                </li>
            </ul>
        </div>

        @include("inc/sidebar")
    </div>
@endsection