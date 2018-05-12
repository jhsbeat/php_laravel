<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct(){
        $this->middleware('guest', ['except' => 'destroy']);
    }

    public function create(){
        return view('sessions.create');
    }

    public function store(Request $request){
        $this->validate($request, [
           'email' => 'required|email',
           'password' => 'required|min:6'
        ]);

        $token = is_api_domain()
            ? \Illuminate\Support\Facades\Auth::guard()->attempt($request->only('email', 'password'))
            : auth()->attempt($request->only('email', 'password'), $request->has('remember'));

        if(!$token){
            return $this->respondError('이메일 또는 비밀번호가 맞지 않습니다.');
        }

        if(!auth()->user()->activated){
            auth()->logout();
            return $this->respondError('가입을 확인해주세요.');
        }

        flash(auth()->user()->name . '님. 환영합니다.');

        return $this->respondCreated($token);
    }

    protected function respondCreated($token){
        return redirect()->intended('home');
    }

    public function destroy(){
        auth()->logout();
        flash('또 방문해주세요.');

        return redirect('/');
    }

    protected function respondError($message) {
        flash()->error($message);
        return back()->withInput();
    }
}
