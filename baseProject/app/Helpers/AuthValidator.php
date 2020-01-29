<?php
namespace App\Helpers;

use App\User;
/**
 *
 */
class AuthValidator
{
    // Register validate existence data
    public function validate_data_signup($request) {
        if(!isset($request['name']) | !isset($request['email']) | !isset($request['password']) | !isset($request['password_confirmation']))
            return false;

        return true;
    }

    // Register validator for user existents
    public function validate_user($request) {
        $user = User::where('email', $request['email'])->get();
        if($user->count() > 0)
            return false;

        return true;
    }

    //
    public function validate_pass($request) {
        if(!isset($request['password']) || !isset($request['password_confirmation']))
            return false;
        if($request['password'] != $request['password_confirmation'])
            return false;

        return true;
    }

    // Login validate all date existence
    public function validate_data_login($request) {
        if(!isset($request['email']) | !isset($request['password']))
            return false;

        return true;
    }

}
