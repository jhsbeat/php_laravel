<?php

namespace App\Http\Controllers\Api\v1;

class SessionsController extends \App\Http\Controllers\SessionsController
{
    protected function respondCreated($token)
    {
        return response()->json([
            'token' => $token,
        ], 201, [], JSON_PRETTY_PRINT);
    }

}
