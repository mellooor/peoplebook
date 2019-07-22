<div class="modal fade" id="add-photos-modal" tabindex="-1" role="dialog" aria-labelledby="addPhotosModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Photos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-photos-form" action="{{ route('add-photos') }}" method="post" class="d-flex flex-column" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" name="photos[]" accept="image/jpg, image/jpeg, image/png" multiple/>
                    </div>
                    <button type="submit" class="btn btn-success ml-auto">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>