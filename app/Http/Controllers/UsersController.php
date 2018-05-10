<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct(){
        $this->middleware('guest');
    }

    public function create(){
        return view('users.create');
    }

    public function store(Request $request){
        $this->validate($request, [
           'name' => 'required|max:255',
           'email' => 'required|email|max:255|unique:users',
           'password' => 'required|confirmed|min:6'
        ]);

        $confirmCode = str_random(60);

        $user = \App\User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'confirm_code' => $confirmCode,
        ]);

        event(new \App\Events\UserCreated($user));

        return $this->respondCreated('가입하신 메일 계정으로 가입 확인 메일을 보내드렸습니다. 가입 확인하시고 로그인해주세요.');
    }

    public function confirm($code){
        $user = \App\User::whereConfirmCode($code)->first();

        if(!$user){
            flash('URL이 정확하지 않습니다.');
            return redirect('/');
        }

        $user->activated = 1;
        $user->confirm_code = null;
        $user->save();

        auth()->login($user);
        return $this->respondCreated(auth()->user()->name . '님. 환영합니다. 가입 확인되었습니다.');
    }

    protected function respondCreated($message) {
        flash($message);
        return redirect('/');
    }
}
