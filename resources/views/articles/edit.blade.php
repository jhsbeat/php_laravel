@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>포럼<small> / 글 수정 / {{ $article->title }}</small></h1>
        <hr />

        <form action="{{ route('articles.update', $article->id) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field('put') !!}

            @include('articles.partial.form')

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    수정하기
                </button>
            </div>
        </form>
    </div>
@stop