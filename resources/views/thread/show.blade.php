@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>{{ $thread->title }}</h4>
                </div>

                <div class="panel-body">
                    {{ $thread->body }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($thread->replies as $reply)
            @include('thread.replies')
        @endforeach
    </div>

    @if (auth()->check())
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form action="{{ route('thread.add-reply', $thread) }}" method="post">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <textarea name="body" id="body" class="form-control" rows="5" placeholder="Have a reply"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Post</button>
                </form>
            </div>
        </div>
    @else
        <p class="text-center">
            Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion
        </p>
    @endif
</div>
@endsection
