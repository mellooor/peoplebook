<div class="modal fade" id="likes-modal" tabindex="-1" role="dialog" aria-labelledby="likesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Liked by...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card user">
                    <div class="card user-request">
                        <div class="card-body d-flex">
                            <button class="btn"><a href="{{ route('user') }}"><img src="/images/default_profile_picture-50x50.png"/>User 12</a></button>
                            <form class="options ml-auto d-flex">
                                <button type="submit" name="accept" class="btn btn-primary mx-auto my-auto">Friends</button>
                            </form>
                        </div>
                    </div>
                    <div class="card user-request">
                        <div class="card-body d-flex">
                            <button class="btn"><a href="{{ route('user') }}"><img src="/images/default_profile_picture-50x50.png"/>User 12</a></button>
                            <form class="options ml-auto d-flex">
                                <button type="submit" name="accept" class="btn btn-success mx-auto my-auto">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>