<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\User;
use App\Http\Controllers\ToolsController;

use AuthValidator;
/**
 * @OA\Info(
 *    title="API Auth Usuarios",
 *    description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="magdiel@kokonutstudio.com"
 *      ),
 *    version="1.0")
 *
 * @OA\Server(
 *    url="http://localhost:8000"
 * )
 */
class AuthController extends Controller
{
    public function __construct() {
        $this->version = \Request::header('version');
        $this->idiom = \Request::header('lang');
    }

     /**
      * @OA\Post(
      *     path="/api/auth/login",
      *     tags={"Authentication"},
      *     summary="Login for new session",
      *     description="A sample greeting to test out the API",
      *     operationId="login",
      *     @OA\Parameter(
      *         name="email",
      *         description="Email for login user",
      *         required=true,
      *         in="query",
      *         @OA\Schema(
      *           type="string"
      *         )
      *     ),
      *     @OA\Parameter(
      *         name="password",
      *         description="Password for login the user",
      *         required=true,
      *         in="query",
      *         @OA\Schema(
      *           type="string"
      *         )
      *     ),
      *      @OA\Response(
      *       response=200,
      *       description="User logged successfully",
      *       @OA\JsonContent(
      *         @OA\Property(
      *           property="success",
      *           type="integer",
      *           example=1
      *         ),
      *         @OA\Property(
      *           property="message",
      *           type="string",
      *           example="Usuario logueado con éxito"
      *         ),
      *         @OA\Property(
      *             property="data",
      *             type="object",
      *             @OA\Property(property="access_token", type="string",example="eyJ0eXAiOiJKV1QiLC..."),
      *             @OA\Property(property="token_type", type="string",example="Bearer"),
      *             @OA\Property(property="expires_at", type="string",example="2021-01-29 18:07:25")
      *         )
      *       )
      *     ),
      *     @OA\Response(
      *       response=201,
      *       description="Error in credentials",
      *       @OA\JsonContent(
      *         @OA\Property(
      *           property="success",
      *           type="int",
      *           example=1
      *         ),
      *         @OA\Property(
      *           property="message",
      *           type="string",
      *           example="El correo o contraseña son erroneos"
      *         ),
      *         @OA\Property(
      *             property="data",
      *             type="object",
      *             example=null
      *         )
      *       )
      *     ),
      *     @OA\Response(
      *         response="202",
      *         description="Without data",
      *         @OA\JsonContent(
      *           @OA\Property(
      *             property="success",
      *             type="int",
      *             example=0
      *           ),
      *           @OA\Property(
      *             property="message",
      *             type="string",
      *             example="Te hace falta información para iniciar sesión"
      *           ),
      *           @OA\Property(
      *             property="data",
      *             type="object",
      *             example=null
      *           )
      *         )
      *       )
      *   )
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

     /**
      * @OA\Post(
      *     path="/api/auth/logout",
      *     tags={"Authentication"},
      *     summary="Logout for a logged user session",
      *     description="A sample greeting to test out the API",
      *     operationId="logout",
      *     @OA\Response(
      *         response=200,
      *         description="Logout with Bearer token "
      *     ),
      *     @OA\Response(
      *         response="default",
      *         description="Ha ocurrido un error."
      *     )
      * )
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
}
