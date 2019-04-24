<div class="modal fade" id="status-edit-modal" tabindex="-1" role="dialog" aria-labelledby="statusEditModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="status-edit-form" action="{{ route('update-status') }}" method="post" class="d-flex flex-column">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status-id" value=""/>
                    <div class="form-group">
                        <textarea name="body" class="form-control" id="status-edit-body" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success ml-auto">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>