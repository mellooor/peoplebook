<div class="card status">
    <div class="card-header d-flex">
        <a href="{{ route('user', $photo->uploader_id) }}">
            {{ $photo->uploader->first_name }} {{ $photo->uploader->last_name }}
        </a>
        @if ($newsFeedItem->isUploadedPhoto())
            &nbsp;uploaded a new photo.
        @elseif ($newsFeedItem->isChangedProfilePicture())
            &nbsp;updated their profile picture.
        @endif

        {{--<div class="dropdown ml-auto">--}}
            {{--<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                {{--Privacy--}}
            {{--</button>--}}
            {{--<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
                {{--<a class="dropdown-item" href="#">Show to All Users <i class="fas fa-check"></i></a>--}}
                {{--<a class="dropdown-item" href="#">Show to Friends Only</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
    <div class="card-body">
        <a href="{{ $photo->getFullURL() }}">
            <img src="{{ $photo->getFullURL() }}"/>
        </a>

        <small><b>{{ $photo->timeUploadedDuration() }}</b></small>

        <form action="{{ route('add-comment') }}" method="post">
            @csrf
            <input type="hidden" name="status-id" value="{{ $photo->id }}"/>
            <textarea class="w-100 status-comment-text-input" name="comment" placeholder="Enter Comment..."></textarea>
        </form>
    </div>
</div>