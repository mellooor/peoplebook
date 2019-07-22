<div class="modal fade" id="add-profile-pic-modal" tabindex="-1" role="dialog" aria-labelledby="addProfilePicModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Profile Picture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-profile-pic-form" action="{{ route('add-profile-picture') }}" method="post" class="d-flex flex-column" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" name="profile_picture" accept="image/jpg, image/jpeg, image/png"/>
                    </div>
                    <button type="submit" class="btn btn-success ml-auto">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>