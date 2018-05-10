<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UsersEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->last_login = \Carbon\Carbon::now();

        return $event->user->save();
    }

    public function subscribe(\Illuminate\Events\Dispatcher $events){
        $events->listen(
            \App\Events\UserCreated::class,
            __CLASS__ . '@onUserCreated'
        );

        $events->listen(
            \App\Events\PasswordRemindCreated::class,
            __CLASS__ . '@onPasswordRemindCreated'
        );
    }

    public function onUserCreated(\App\Events\UserCreated $event){
        $user = $event->user;

        \Mail::send('emails.auth.confirm', compact('user'), function($message) use ($user){
            $message->to($user->email);
            $message->subject(sprintf('[%s] 회원 가입을 확인해주세요.', config('app.name')));
        });
    }

    public function onPasswordRemindCreated(\App\Events\PasswordRemindCreated $event){
        $email = $event->email;
        $token = $event->token;

        \Mail::send('emails.passwords.reset', compact('token'), function($message) use ($email){
            $message->to($email);
            $message->subject(
                sprintf('[%s] 비밀번호를 초기화하세요.', config('app.name'))
            );
        });
    }
}
