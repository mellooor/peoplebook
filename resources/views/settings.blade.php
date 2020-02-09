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
                            <form method="post" action="{{ route('update-privacy-settings') }}">
                                @method('PUT')
                                @csrf
                                <div class="form-row">
                                    <div class="col">
                                        <label for=""><b>Statuses Visibility:</b></label>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary {{ ($user->defaultStatusPrivacy->visibility === 'public') ? 'active' : '' }}">
                                                <input type="radio" name="status-privacy" id="option1" autocomplete="off" value="public"> Public
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->defaultStatusPrivacy->visibility === 'friends-of-friends') ? 'active' : '' }}">
                                                <input type="radio" name="status-privacy" id="option2" autocomplete="off" value="friends-of-friends"> Friends of Friends
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->defaultStatusPrivacy->visibility === 'friends-only') ? 'active' : '' }}">
                                                <input type="radio" name="status-privacy" id="option3" autocomplete="off" value="friends-only"> Friends Only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-row">
                                    <div class="col">
                                        <label for=""><b>Photos Visibility:</b></label>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary {{ ($user->defaultPhotoPrivacy->visibility === 'public') ? 'active' : '' }}">
                                                <input type="radio" name="photo-privacy" id="option1" autocomplete="off" value="public"> Public
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->defaultPhotoPrivacy->visibility === 'friends-of-friends') ? 'active' : '' }}">
                                                <input type="radio" name="photo-privacy" id="option2" autocomplete="off" value="friends-of-friends"> Friends of Friends
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->defaultPhotoPrivacy->visibility === 'friends-only') ? 'active' : '' }}">
                                                <input type="radio" name="photo-privacy" id="option3" autocomplete="off" value="friends-only"> Friends Only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-row">
                                    <div class="col">
                                        <label for=""><b>Home Town Visibility:</b></label>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary {{ ($user->homeTownPrivacy->visibility === 'public') ? 'active' : '' }}">
                                                <input type="radio" name="home-town-privacy" id="option1" autocomplete="off" value="public"> Public
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->homeTownPrivacy->visibility === 'friends-of-friends') ? 'active' : '' }}">
                                                <input type="radio" name="home-town-privacy" id="option2" autocomplete="off" value="friends-of-friends"> Friends of Friends
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->homeTownPrivacy->visibility === 'friends-only') ? 'active' : '' }}">
                                                <input type="radio" name="home-town-privacy" id="option3" autocomplete="off" value="friends-only"> Friends Only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-row">
                                    <div class="col">
                                        <label for=""><b>Current Town Visibility:</b></label>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary {{ ($user->currentTownPrivacy->visibility === 'public') ? 'active' : '' }}">
                                                <input type="radio" name="current-town-privacy" id="option1" autocomplete="off" value="public"> Public
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->currentTownPrivacy->visibility === 'friends-of-friends') ? 'active' : '' }}">
                                                <input type="radio" name="current-town-privacy" id="option2" autocomplete="off" value="friends-of-friends"> Friends of Friends
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->currentTownPrivacy->visibility === 'friends-only') ? 'active' : '' }}">
                                                <input type="radio" name="current-town-privacy" id="option3" autocomplete="off" value="friends-only"> Friends Only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-row">
                                    <div class="col">
                                        <label for=""><b>Schools Visibility:</b></label>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary {{ ($user->schoolPrivacy->visibility === 'public') ? 'active' : '' }}">
                                                <input type="radio" name="school-privacy" id="option1" autocomplete="off" value="public"> Public
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->schoolPrivacy->visibility === 'friends-of-friends') ? 'active' : '' }}">
                                                <input type="radio" name="school-privacy" id="option2" autocomplete="off" value="friends-of-friends"> Friends of Friends
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->schoolPrivacy->visibility === 'friends-only') ? 'active' : '' }}">
                                                <input type="radio" name="school-privacy" id="option3" autocomplete="off" value="friends-only"> Friends Only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-row">
                                    <div class="col">
                                        <label for=""><b>Jobs Visibility:</b></label>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary {{ ($user->jobPrivacy->visibility === 'public') ? 'active' : '' }}">
                                                <input type="radio" name="job-privacy" id="option1" autocomplete="off" value="public"> Public
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->jobPrivacy->visibility === 'friends-of-friends') ? 'active' : '' }}">
                                                <input type="radio" name="job-privacy" id="option2" autocomplete="off" value="friends-of-friends"> Friends of Friends
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->jobPrivacy->visibility === 'friends-only') ? 'active' : '' }}">
                                                <input type="radio" name="job-privacy" id="option3" autocomplete="off" value="friends-only"> Friends Only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-row">
                                    <div class="col">
                                        <label for=""><b>Relationship Visibility:</b></label>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary {{ ($user->relationshipPrivacy->visibility === 'public') ? 'active' : '' }}">
                                                <input type="radio" name="relationship-privacy" id="option1" autocomplete="off" value="public"> Public
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->relationshipPrivacy->visibility === 'friends-of-friends') ? 'active' : '' }}">
                                                <input type="radio" name="relationship-privacy" id="option2" autocomplete="off" value="friends-of-friends"> Friends of Friends
                                            </label>
                                            <label class="btn btn-secondary {{ ($user->relationshipPrivacy->visibility === 'friends-only') ? 'active' : '' }}">
                                                <input type="radio" name="relationship-privacy" id="option3" autocomplete="off" value="friends-only"> Friends Only
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
