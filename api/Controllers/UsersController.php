<?php
namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Models\User;
use Umb\Mentorship\Controllers\Controller;
use Umb\Mentorship\Controllers\Utils\Utility;

class UsersController extends Controller{

    public function createUser($data){
        try {
            $attributes = ['first_name', 'middle_name', 'last_name', 'email', 'phone_number', 'last_login', 'facility_id', 'active', 'created_by', 'program_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            if($data['password'] != ''){
                $data['password'] = md5($data['password']);
            } else unset($data['password']);
            User::create($data);
            self::response(SUCCESS_RESPONSE_CODE, "User created successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateUser($id, $data){
        try {
            $attributes = ['first_name', 'middle_name', 'last_name', 'email', 'phone_number', 'password', 'last_login', 'facility_id', 'active', 'created_by', 'program_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $u = User::findOrFail($id);
            if($data['password'] != ''){
                $data['password'] = md5($data['password']);
            } else unset($data['password']);
            $u->update($data);
            self::response(SUCCESS_RESPONSE_CODE, "User updated successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

}
