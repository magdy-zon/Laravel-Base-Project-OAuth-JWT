<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ToolsController;

use App\User;

use AuthValidator;

class RegisterController extends Controller
{
    public function __construct() {
        $this->version = \Request::header('version');
        $this->idiom = \Request::header('lang');
    }

    /**
     * Register user (JWT OAuth)
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
