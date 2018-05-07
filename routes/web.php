<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@index');

Route::resource('articles', 'ArticlesController');

Route::get('auth/login', function(){
    $credentials = [
      'email' => 'john@example.com',
      'password' => 'password'
    ];

    if(!auth()->attempt($credentials)){
        return '로그인 정보가 정확하지 않습니다.';
    }

    return redirect('protected');
});

Route::get('protected', ['middleware' => 'auth', function(){
   dump(session()->all());

   if(!auth()->check()){
       return '누구세요?';
   }
   return '어서 오세요' . auth()->user()->name;
}]);

Route::get('auth/logout', function(){
   auth()->logout();
   return '또 봐요~';
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('mail', function(){
    $article = App\Article::with('user')->find(1);

    return Mail::send(
        'emails.articles.created',
        compact('article'),
        function($message) use ($article){
            $message->to('yours@domain');
            $message->subject('새 글이 등록되었습니다 - ' . $article->title);
        }
    );
});

Route::get('markdown', function(){
    $text =<<<EOT
# 마크다운 예제 1

이 문서는 [마크다운][1]으로 썼습니다. 화면에는 HTML로 변환되어 출력됩니다.

## 순서 없는 목록

- 첫 번째 항목
- 두 번째 항목[^1]

[1]: http://daringfireball.net/projects/markdown
[^1]: 두 번째 항목_ http://google.com
EOT;

    return app(ParsedownExtra::class)->text($text);
});

Route::get('docs/{file?}', 'DocsController@show');