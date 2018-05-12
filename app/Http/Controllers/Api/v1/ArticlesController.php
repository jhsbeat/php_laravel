<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ArticlesController as ParentController;

class ArticlesController extends ParentController
{
    public function __construct(){
    }

    public function tags(){
        return \App\Tag::all();
    }

    protected function respondCollection(\Illuminate\Contracts\Pagination\LengthAwarePaginator $articles)
    {
        return $articles->toJson(JSON_PRETTY_PRINT);
    }

    protected function respondCreated(\App\Article $article)
    {
        // Will be failed because of the missing authentication.
        return response()->json([
            'success' => 'created'
        ], 201, ['Location' => route('api.v1.articles.show', $article->id)], JSON_PRETTY_PRINT);
    }
}
