<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordsController extends Controller
{
    public function __construct(){
        $this->middleware('guest');
    }

    public function getRemind(){
        return view('passwords.remind');
    }

    public function postRemind(Request $request){
        $this->validate($request, [
           'email' => 'required|email|exists:users'
        ]);
        $email = $request->get('email');
        $token = str_random(64);

        \DB::table('password_resets')->insert([
           'email' => $email,
           'token' => $token,
           'created_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        event(new \App\Events\PasswordRemindCreated($email, $token));

        return $this->respondSuccess('비밀번호를 바꾸는 방법을 담은 이메일을 발송했습니다. 메일박스를 확인해주세요.');
    }

    public function getReset($token = null){
        return view('passwords.reset', compact('token'));
    }

    public function postReset(Request $request){
        $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required|confirmed',
            'token' => 'required'
        ]);
        $token = $request->get('token');
        $email = $request->input('email');

        if (! \DB::table('password_resets')->whereEmail($email)->whereToken($token)->first()) {
            return $this->respondError(
                trans('auth.passwords.error_wrong_url')
            );
        }

        // 다른 사람의 비밀번호를 변경할 수도 있는 매우 위험한 코드.
        \App\User::whereEmail($email)->first()->update([
            'password' => bcrypt($request->input('password'))
        ]);

        \DB::table('password_resets')->whereToken($token)->delete();
        return $this->respondSuccess(
            trans('auth.passwords.success_reset')
        );
    }

    protected function respondSuccess($message) {
        flash($message);
        return redirect('/');
    }

    protected function respondError($message) {
        flash()->error($message);
        return back()->withInput();
    }
}
