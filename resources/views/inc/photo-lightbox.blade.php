<div class="modal fade" id="photo-lightbox" tabindex="-1" role="dialog" aria-labelledby="photoLightboxLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <img id="photo-lightbox-image" src="https://via.placeholder.com/500"/>
                        </div>
                        <div class="col-md-4">
                            <div class="card status">
                                <div class="card-header d-flex">
                                    <a href="{{ route('user', 1) }}"><img src="/images/default_profile_picture-25x25.png"/> User 1</a>
                                    @if (Auth::user()->id === $user->id)
                                        <div class="dropdown ml-auto">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Privacy
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#">Show to All Users <i class="fas fa-check"></i></a>
                                                <a class="dropdown-item" href="#">Show to Friends Only</a>
                                            </div>
                                        </div>

                                        <form id="photo-delete-form" method="post" action="{{ route('delete-photo') }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="thumbnail-photo-ID" value=""/>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    @endif

                                </div>
                                <div class="card-body">
                                    <p class="card-text">Hello World <small>27 minutes ago</small> <small><b>Edited</b> 10 minutes ago</small></p>
                                    <div class="row">
                                        <button class="btn btn-link" data-toggle="modal" data-target="#likes-modal">1 Like</button>
                                        @if (Auth::user()->id === $user->id)
                                            <form id="update-profile-picture-form" method="post" action="{{ route('update-profile-picture') }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="thumbnail-id" value="1"/>
                                                <button class="btn btn-link" type="submit">Make Profile Picture</button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <a href="#">Like</a>
                                    </div>
                                    <div class="row comments">
                                        <div class="card comment">
                                            <div class="card-body">
                                                <a href="{{ route('user', 1) }}"><img src="/images/default_profile_picture-25x25.png"/></a> Hello World! <b>(1 like)</b> <a href="#">Like</a> <small>10 minutes ago</small> <small><b>Edited</b> 7 minutes ago</small>
                                            </div>
                                        </div>
                                        <div class="card comment">
                                            <div class="card-body">
                                                <a href="{{ route('user', 1) }}"><img src="/images/default_profile_picture-25x25.png"/></a> Amazing. <b>(1 like)</b> liked <small>2 hours ago</small>
                                            </div>
                                        </div>
                                        <div class="card comment">
                                            <div class="card-body">
                                                <a href="{{ route('user', 1) }}"><img src="/images/default_profile_picture-25x25.png"/></a> Cor Blimey. <b>(1 like)</b> <a href="#">Like</a> <small>2 years ago</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>