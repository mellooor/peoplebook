<div class="modal fade" id="remove-friend-confirm" tabindex="-1" role="dialog" aria-labelledby="removeFriendConfirmLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove Friend</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <form id="remove-friend-form" class="options ml-auto" action="{{ route('remove-friend') }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="friendship-id" value=""/>
                    <input type="hidden" name="target-user-id" value=""/>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <btn class="btn btn-secondary" data-dismiss="modal">Cancel</btn>
            </div>
        </div>
    </div>
</div>