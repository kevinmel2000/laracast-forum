@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                            <div class="flex">
                                <h3>{{ $thread->title }}</h3>
                            </div>

                            @can ('update', $thread)
                                <form action="{{ $thread->path() }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}

                                    <button type="submit" class="btn btn-link">
                                        Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>

                <h4 class="text-center">&mdash; Replies &mdash;</h4>
                
                @foreach ($replies as $reply)
                    @include('thread.replies')
                @endforeach

                {{ $replies->links() }}

                @if (auth()->check())
                    <form action="{{ route('thread.add-reply', [$thread->channel, $thread]) }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" rows="5" 
                                placeholder="Have a reply"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Post</button>
                    </form>
                @else
                    <p class="text-center">
                        Please <a href="{{ route('login') }}">sign in</a> to participate in this thread
                    </p>
                @endif
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>This thread was published {{ $thread->created_at->diffForHumans() }}.</p>
                        
                        <p>
                            Posted by 
                            <a href="{{ route('profile', $thread->author) }}">
                                {{ $thread->author->name }}
                            </a>.
                        </p>
                        
                        <p>Currently has {{ $thread->replies_count }} 
                            {{ str_plural('comment', $thread->replies_count) }}.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
