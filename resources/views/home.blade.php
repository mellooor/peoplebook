@extends('layouts.app')

@section('content')
    @auth
        <div class="row">
            <div class="col-md-9" style="background-color: #bbb; border: 1px solid;">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#status-create-modal">Create Status</button>
                <h2>No Statuses to Show!</h2>
            </div>
            @include("inc/sidebar")
            @include("inc/status-create-modal")
        </div>
    @else
        <h1>PeopleBook Homepage</h1>
    @endauth
@endsection
