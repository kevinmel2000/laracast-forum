<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <h5 class="flex">
                {{ $reply->created_at->diffForHumans() }} by 

                <a href="#">
                    {{ $reply->owner->name }}
                </a>
            </h5>
            <div>
                <form method="post" action="{{ route('reply.favorite', $reply) }}">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-default" 
                            {{ $reply->isFavorited() ? 'disabled' : '' }}>
                        👍 {{ $reply->favorites_count }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="panel-body">
        {{ $reply->body }}
    </div>
</div>