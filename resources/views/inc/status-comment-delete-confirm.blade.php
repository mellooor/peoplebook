<div class="modal fade" id="status-comment-delete-confirm" tabindex="-1" role="dialog" aria-labelledby="statusCommentDeleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2>Are you sure you want to delete this comment?</h2>
            </div>
            <div class="modal-footer">
                <form id="delete-status-comment-form" action="{{ route('delete-comment') }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="status-id" value=""/>
                    <input type="hidden" name="comment-id" value=""/>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <btn class="btn btn-secondary" data-dismiss="modal">Cancel</btn>
            </div>
        </div>
    </div>
</div>