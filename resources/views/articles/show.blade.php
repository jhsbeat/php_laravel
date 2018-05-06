@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>
            {{ $article->title }}
            <small>{{ $article->user->name }}</small>
        </h1>
        <hr />
        <p>
            {{ $article->content }}
            <small>{{ $article->created_at }}</small>
        </p>
    </div>
@stop
