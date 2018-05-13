<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ArticlesController as ParentController;

class ArticlesController extends ParentController implements \App\Http\Controllers\Cacheable
{
    use \App\EtagTrait;

    public function __construct(){
        $this->middleware('auth:api', ['except' => ['index', 'show', 'tags']]);
    }

    public function tags(){
        return \App\Tag::all();
    }

    protected function respondCollection(\Illuminate\Contracts\Pagination\LengthAwarePaginator $articles, $cacheKey)
    {
        $reqEtag = request()->getETags();
        $genEtag = $this->etags($articles, $cacheKey);

        if(config('project.etag') and isset($reqEtag[0]) and $reqEtag[0] === $genEtag){
            return json()->notModified();
        }
        return json()->setHeaders(['Etag' => $genEtag])->withPagination($articles, new \App\Transformers\ArticleTransformer);
    }

    protected function respondCreated(\App\Article $article)
    {
        // Will be failed because of the missing authentication.
        return response()->json([
            'success' => 'created'
        ], 201, ['Location' => route('api.v1.articles.show', $article->id)], JSON_PRETTY_PRINT);
    }

    protected function respondInstance(\App\Article $article){
        return (new \App\Transformers\ArticleTransformerBasic)->withItem($article);
    }

    /**
     * Specify the tags for caching.
     *
     * @return string
     */
    public function cacheTags()
    {
        return 'articles';
    }
}
