<div class="modal fade" id="status-comment-edit-modal" tabindex="-1" role="dialog" aria-labelledby="statusCommentEditModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="status-comment-edit-form" action="{{ route('update-comment') }}" method="post" class="d-flex flex-column">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status-id" value=""/>
                    <input type="hidden" name="comment-id" value=""/>
                    <div class="form-group">
                        <textarea name="comment" class="form-control" id="status-comment-edit-body"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success ml-auto">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>