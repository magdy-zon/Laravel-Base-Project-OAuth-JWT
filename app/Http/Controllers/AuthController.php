<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ToolsController;

use Carbon\Carbon;
use App\User;
use AuthValidator;

class AuthController extends Controller
{
    public function __construct() {
        $this->version = \Request::header('version');
        $this->idiom  = \Request::header('lang');
    }

      /**
        * Login user (JWT OAuth)
        *
        * @return [string] message
        **/
    public function login(Request $request)
    {
        $validate = new AuthValidator();
        $validate_data = $validate->validate_data_login($request->all());
        if(!$validate_data)
            return ToolsController::returnResponse("login_no_data", 0, null, 200, $this->idiom);

        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return ToolsController::returnResponse("login_fail", 1, null, 200, $this->idiom);

        $user         = $request->user();
        $tokenResult  = $user->createToken('Personal Access Token');
        $token        = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $json = [
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ];

        return ToolsController::returnResponse("login_success", 1, $json, 200, $this->idiom);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
      * Default message when unauthenticated
      */
    public function no_login() {
        return ToolsController::returnResponse("no_login", 1, null, 200, $this->idiom);
    }
}
