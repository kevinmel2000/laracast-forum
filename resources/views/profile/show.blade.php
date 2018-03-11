@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>
                {{ $userProfile->name }}
    
                <small>Since: {{ $userProfile->created_at->diffForHumans() }}</small>
            </h1>
        </div>

        @if ( ! $userThreads->isEmpty() )
            @foreach ($userThreads as $thread)
                <div class="panel panel-default">
                    <div class="panel-heading level">
                        <h4 class="flex">
                            <a href="{{ $thread->path() }}">
                                {{ $thread->title }}
                            </a>

                            <small>{{ $thread->created_at->diffForHumans() }}</small>
                        </h4>

                        <a href="{{ $thread->path() }}">
                            {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}
                        </a>
                    </div>

                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>
            @endforeach

            {{ $userThreads->links() }}
        @else
            <h3 class="text-center">No thread by this user, yet.</h3>
        @endif
    </div>
@endsection
