<?php

namespace App\Transformers;

use App\Article;
use Appkr\Api\TransformerAbstract;
use League\Fractal\ParamBag;

class ArticleTransformer extends TransformerAbstract
{

    /**
     * Transform single resource.
     *
     * @param  \App\Article $article
     * @return  array
     */
    public function transform(Article $article)
    {
        $obfuscatedId = optimus($article->id);
        $payload = [
            'id' => $obfuscatedId,
            'title' => $article->title,
            'content' => $article->content,
            'content_html' => markdown($article->content),
            'author'       => [
                'name'   => $article->user->name,
                'email'  => $article->user->email,
                'avatar' => 'http:' . gravatar_profile_url($article->user->email),
            ],
            'tags'         => $article->tags->pluck('slug'),
            'view_count' => (int) $article->view_count,
            'created' => $article->created_at->toIso8601String(),
            'attachments'  => (int) $article->attachments->count(),
//            'comments'     => (int) $article->comments->count(),
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('api.v1.articles.show', $obfuscatedId),
                ],
                [
                    'rel' => 'api.v1.articles.attachments.index',
                    'href' => route('api.v1.articles.attachments.index', $obfuscatedId),
                ],
//                [
//                    'rel' => 'api.v1.articles.comments.index',
//                    'href' => route('api.v1.articles.comments.index', $article->id),
//                ],
            ],
        ];

        if ($fields = $this->getPartialFields()) {
            $payload = array_only($payload, $fields);
        }

        return $payload;
    }

}
