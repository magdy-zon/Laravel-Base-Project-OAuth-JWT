<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Http\Controllers\ToolsController;

use AuthValidator;

class RegisterController extends Controller
{
    public function __construct() {
        $this->version = \Request::header('version');
        $this->idiom = \Request::header('lang');
    }

     /**
    * @OA\Post(
    *     path="/api/auth/signup",
    *     tags={"Register"},
    *     summary="Register for a new user",
    *     description="A sample greeting to test out the API",
    *     operationId="signup",
    *     @OA\Parameter(
    *         name="name",
    *         description="Name for the new user",
    *         required=true,
    *         in="query",
    *         @OA\Schema(
    *           type="string"
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="email",
    *         description="Email for register for the new user",
    *         required=true,
    *         in="query",
    *         @OA\Schema(
    *           type="string"
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="password",
    *         description="Password for register for the new user",
    *         required=true,
    *         in="query",
    *         @OA\Schema(
    *           type="string"
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="password_confirmation",
    *         description="Password confirmation for the new user",
    *         required=true,
    *         in="query",
    *         @OA\Schema(
    *           type="string"
    *         )
    *     ),
    *     @OA\Response(
    *       response=200,
    *       description="User registeder successfully",
    *       @OA\JsonContent(
    *         @OA\Property(
    *           property="success",
    *           type="integer",
    *           example=1
    *         ),
    *         @OA\Property(
    *           property="message",
    *           type="string",
    *           example="Usuario creado con éxito"
    *         ),
    *         @OA\Property(
    *             property="data",
    *             type="object",
    *             example=null
    *         )
    *       )
    *     ),
    *     @OA\Response(
    *       response=204,
    *       description="Email repeated",
    *       @OA\JsonContent(
    *         @OA\Property(
    *           property="success",
    *           type="integer",
    *           example=0
    *         ),
    *         @OA\Property(
    *           property="message",
    *           type="string",
    *           example="Correo electrónico ya registrado"
    *         ),
    *         @OA\Property(
    *             property="data",
    *             type="object",
    *             example=null
    *         )
    *       )
    *     ),
    *     @OA\Response(
    *       response=205,
    *       description="Passwords isn't equals",
    *       @OA\JsonContent(
    *         @OA\Property(
    *           property="success",
    *           type="integer",
    *           example=0
    *         ),
    *         @OA\Property(
    *           property="message",
    *           type="string",
    *           example="Los password no coinciden"
    *         ),
    *         @OA\Property(
    *             property="data",
    *             type="object",
    *             example=null
    *         )
    *       )
    *     )
    *   )
    */
    public function signup(Request $request)
    {
        $validate = new AuthValidator();
        $validate_data = $validate->validate_data_signup($request->all());
        if(!$validate_data)
            return ToolsController::returnResponse("register_no_data", 0, null, 200, $this->idiom);

        $valid_pass = $validate->validate_pass($request->all());
        if(!$valid_pass)
            return ToolsController::returnResponse("register_fail_pass", 0, null, 200, $this->idiom);

        $exists_user = $validate->validate_user($request->all());
        if(!$exists_user)
            return ToolsController::returnResponse("register_repetead", 0, null, 200, $this->idiom);

        $user = new User([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)
        ]);
        $user->save();

        return ToolsController::returnResponse("register_success", 1, null, 200, $this->idiom);
    }


}
