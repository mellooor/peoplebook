@extends('layouts/app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="accordion">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Profile Privacy
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                V
                            </button>
                        </h5>
                        <div id="collapseOne" class="collapse" data-parent="">
                            <p class="card-text">Choose how other users view your data.</p>
                            <form>
                                <div class="form-row">
                                    <div class="col">
                                        <label for=""><b>Statuses:</b></label>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="options" id="option1" autocomplete="off" checked> Show to All Users
                                            </label>
                                            <label class="btn btn-secondary">
                                                <input type="radio" name="options" id="option2" autocomplete="off"> Show to Friends Only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-row">
                                    <div class="col">
                                        <label for=""><b>Photos:</b></label>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="options" id="option1" autocomplete="off" checked> Show to All Users
                                            </label>
                                            <label class="btn btn-secondary">
                                                <input type="radio" name="options" id="option2" autocomplete="off"> Show to Friends Only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-row">
                                    <div class="col">
                                        <label for=""><b>Personal Details:</b></label>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="options" id="option1" autocomplete="off" checked> Show to All Users
                                            </label>
                                            <label class="btn btn-secondary">
                                                <input type="radio" name="options" id="option2" autocomplete="off"> Show to Friends Only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" name="submit" class="btn btn-primary">Save Settings</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
