<?php
namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Models\User;
use Umb\Mentorship\Models\Notification;
use Umb\Mentorship\Models\UserCategory;
use Umb\Mentorship\Controllers\Controller;
use Umb\Mentorship\Controllers\Utils\Utility;

class UsersController extends Controller{

    public function createUser($data){
        try {
            $attributes = ['first_name', 'middle_name', 'last_name', 'email', 'phone_number', 'facility_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            if($data['password'] != ''){
                $data['password'] = md5($data['password']);
            } else unset($data['password']);
            $data['username'] = substr($data['first_name'], 0, 1) . $data['last_name'];
            $data['created_by'] = $this->user->id;
            $data['active'] = 1;
            User::create($data);
            self::response(SUCCESS_RESPONSE_CODE, "User created successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateUser($id, $data){
        try {
            $attributes = ['first_name', 'middle_name', 'last_name', 'email', 'phone_number', 'password', 'facility_id'];
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

    public function createUserCategory($data){
        try {
            $attributes = ["access_level", "name", "description", "permissions"];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $data['created_by'] = $this->user->id;
            UserCategory::create($data);
            self::response(SUCCESS_RESPONSE_CODE, "The user category created successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateUserCategory($id, $data){
        try {
            $attributes = ["access_level", "name", "description", "permissions"];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $userCategory = UserCategory::findOrFail($id);
            $userCategory->update($data);
            self::response(SUCCESS_RESPONSE_CODE, "The user category updated successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function getNotifications($id)
    {
        $notifications = Notification::where('user_id', $id)->where('read', 0)->get();
        $this->response(SUCCESS_RESPONSE_CODE, "User notifications", $notifications);
    }

    public function markAsRead($data)
    {
        try {
            $attributes = ['id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $notification = Notification::findOrFail($data['id']);
            $notification->read = 1;
            $notification->save();
            $this->getNotifications($notification->user_id);
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            self::response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function markAllAsRead($data)
    {
        try {
            $attributes = [ 'user_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            Notification::where('user_id', $data['user_id'])->where('read', 0)
                ->update(['read' => 1]);
            $this->getNotifications($data['user_id']);
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }



}
