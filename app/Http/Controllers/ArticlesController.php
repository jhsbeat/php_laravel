<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function __construct(){
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug = null)
    {
        $query = $slug ? \App\Tag::whereSlug($slug)->firstOrFail()->articles() : new \App\Article;

        // Pagination
        $articles = $query->latest()->paginate(3);

        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $article = new \App\Article;
        return view('articles.create', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\ArticlesRequest $request)
    {
        $article = $request->user()->articles()->create($request->all());
        if(!$article){
            return back()->with('flash_message', '글이 저장되지 않았습니다.')->withInput();
        }
        $article->tags()->sync($request->input('tags'));

        // 첨부파일 연결
        $request->getAttachments()->each(function ($attachment) use ($article) {
            $attachment->article()->associate($article);
            $attachment->save();
        });

        event(new \App\Events\ArticlesEvent($article));

        return redirect(route('articles.index'))->with('flash_message', '작성하신 글이 저장되었습니다.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Article $article)
    {
//        $article = \App\Article::with('user')->findOrFail($id); // Route-model binding을 사용하였기 때문에 필요없음.
//        debug($article->toArray());
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param   \App\Article    $article
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Article $article)
    {
        $this->authorize('update', $article);
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ArticlesRequest  $request
     * @param  \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\ArticlesRequest $request, \App\Article $article)
    {
        $this->authorize('update', $article);
        $article->update($request->all());
        $article->tags()->sync($request->input('tags'));
        flash()->success('수정하신 내용을 저장했습니다.');

        return redirect(route('articles.show', $article->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Article $article)
    {
        debug('a@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 11111111');
        $this->authorize('delete', $article);
        debug('a@@@@@@@@@@@@@@@@@@@@@@@@@@@@');
        $article->delete();
        debug('a@@@@@@@@@@@@@@@@@@@@@@@@@@@@ 222');
        return response()->json([], 204);
    }
}
