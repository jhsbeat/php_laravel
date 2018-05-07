<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocsController extends Controller
{
    protected $docs;

    public function __construct(\App\Documentation $docs){
        $this->docs = $docs;
    }

    public function show($file = null){

        // 120 : minutes
        $index = \Cache::remember('docs.index', 120, function(){
            return markdown($this->docs->get());
        });

        $content = \Cache::remember("docs.{$file}", 120, function() use ($file){
            return markdown($this->docs->get($file ?: 'installation.md'));
        });

        return view('docs.show', compact('index', 'content'));
    }

    public function image($file){
        $reqEtag = \Request::getEtags();
        $genEtag = $this->docs->etag($file);

        if(isset($reqEtag[0])){
            if($reqEtag[0] == $genEtag){
                return response('', 304);
            }
        }

        $image = $this->docs->image($file);
        return response($image->resize(600, null, function($constraint){
            $constraint->aspectRatio();})->encode('png'), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=0',
            'Etag' => $genEtag,
        ]);
    }
}
