<div class="modal fade" id="status-create-modal" tabindex="-1" role="dialog" aria-labelledby="statusCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Status...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('create-status') }}" enctype="multipart/form-data" method="post" class="d-flex flex-column">
                    @csrf
                    <div class="form-group">
                        <textarea name="body" class="form-control" rows="3"></textarea>
                    </div>
                    <input type="file" name="status_images[]" accept="image/jpg, image/jpeg, image/png" multiple/>
                    <button type="submit" class="btn btn-success ml-auto">Share</button>
                </form>
            </div>
        </div>
    </div>
</div>