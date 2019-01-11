<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Passport\TokenRepository;
use App\Http\Controllers\Controller;

// This controller is used for password-based authentication via the REST API.
class ApiLoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * The token repository implementation.
     *
     * @var \Laravel\Passport\TokenRepository
     */
    protected $tokenRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Laravel\Passport\TokenRepository  $tokenRepository
     * @return void
     */
    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        $user = Auth::user();
        $token = $user->createToken('ElyssifClient')->accessToken;

        return response()->json(["token" => $token]);
    }

    /**
     * Revoke the token in use.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();

        if (is_null($token)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $token->revoke();
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
