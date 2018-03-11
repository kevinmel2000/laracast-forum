<div class="panel panel-default">
    <div class="panel-heading">
        {{ $reply->created_at->diffForHumans() }} by 
        
        <a href="#">
            {{ $reply->owner->name }}
        </a>
    </div>

    <div class="panel-body">
        {{ $reply->body }}
    </div>
</div>