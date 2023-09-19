<?php

namespace Umb\Mentorship\Controllers;

use Illuminate\Support\Facades\Password;
use Umb\Mentorship\Models\User;
use Umb\Mentorship\Models\Notification;
use Umb\Mentorship\Models\UserCategory;
use Umb\Mentorship\Controllers\Controller;
use Umb\Mentorship\Controllers\Utils\Utility;
use Umb\Mentorship\Models\PasswordReset;

class UsersController extends Controller
{

    public function createUser($data)
    {
        try {
            if (!hasPermission(PERM_USER_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['first_name', 'middle_name', 'last_name', 'email', 'phone_number', 'facility_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            if ($data['password'] != '') {
                $data['password'] = md5($data['password']);
            } else unset($data['password']);
            $data['username'] = substr($data['first_name'], 0, 1) . $data['last_name'];
            $data['created_by'] = $this->user->id;
            $data['active'] = 1;
            $data['facility_id'] = $data['facility_id'] == '' ? null : $data['facility_id'];
            User::create($data);
            self::response(SUCCESS_RESPONSE_CODE, "User created successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateUser($id, $data)
    {
        try {
            if (!hasPermission(PERM_USER_MANAGEMENT, $this->user)) throw new \Exception("Forbidden", 403);
            $attributes = ['first_name', 'middle_name', 'last_name', 'email', 'phone_number', 'password', 'facility_id'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $u = User::findOrFail($id);
            if ($data['password'] != '') {
                $data['password'] = md5($data['password']);
            } else unset($data['password']);
            $data['facility_id'] = $data['facility_id'] == '' ? null : $data['facility_id'];
            $u->update($data);
            self::response(SUCCESS_RESPONSE_CODE, "User updated successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function updateProfile($id, $data)
    {
        try {
            $attributes = ['first_name', 'middle_name', 'last_name', 'email', 'phone_number', 'password'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $u = User::findOrFail($id);
            if ($data['password'] != '') {
                $data['password'] = md5($data['password']);
            } else unset($data['password']);
            $data['facility_id'] = $data['facility_id'] == '' ? null : $data['facility_id'];
            $u->update($data);
            self::response(SUCCESS_RESPONSE_CODE, "User updated successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            $this->response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public function createUserCategory($data)
    {
        try {
            if (!hasPermission(PERM_SYSTEM_ADMINISTRATION, $this->user)) throw new \Exception("Forbidden", 403);
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

    public function updateUserCategory($id, $data)
    {
        try {
            if (!hasPermission(PERM_SYSTEM_ADMINISTRATION, $this->user)) throw new \Exception("Forbidden", 403);
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
            $attributes = ['user_id'];
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

    public static function requestResetPassword($data)
    {
        try {
            $attributes = ['email'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $user = User::where('email', $data['email'])->first();
            if ($user == null) throw new \Exception("Error: User not found...", 1);
            $passwordReset = PasswordReset::where('user_id', $user->id)->where('expires_at', '>', date('Y-m-d G:i:s'))->where('is_used', 0)->first();
            if ($passwordReset == null) {
                $token = md5(uniqid(json_encode($user), true));
                $currTime = new \DateTime();
                $currTime->add(new \DateInterval('PT' . 60 . 'M'));
                $expiresAt = $currTime->format('Y-m-d H:i:s');
                $passwordReset = PasswordReset::create([
                    'user_id' => $user->id,
                    'token' => $token,
                    'expires_at' => $expiresAt
                ]);
            }
            $receipient = [
                "address" => $user->email, "name" => $user->first_name . ' ' . $user->last_name
            ];
            $recipients[] = $receipient;
            $link = $_ENV['APP_URL'] . 'web/reset_password?t=' . $passwordReset->token;
            $message = " Hi {$user->first_name}, A password reset was requested for your account. Ignore this email if you did not make the request.
            Use this link to reset your account password <a href='$link' target='_blank'>Reset Password</a> ";
            $sent = Utility::sendMail($recipients, "Password Reset", $message);
            if(!$sent) throw new \Exception("Error sending mail", 1);
            self::response(SUCCESS_RESPONSE_CODE, "Reset link sent successfully.");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            self::response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }

    public static function resetPassword($data)
    {
        try {
            $attributes = ['token', 'password'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed : " . json_encode($missing)));
            $passwordReset = PasswordReset::where('token', $data['token'])->first();
            if($passwordReset == null) throw new \Exception("Error Processing Request", 1);
            $user = User::findOrFail($passwordReset->user_id);
            $user->password =  md5($data['password']);
            $user->save();
            $passwordReset->is_used = 1;
            $passwordReset->save();
            self::response(SUCCESS_RESPONSE_CODE, "Password reset successful...");
        } catch (\Throwable $th) {
            Utility::logError($th->getCode(), $th->getMessage());
            self::response(PRECONDITION_FAILED_ERROR_CODE, $th->getMessage());
        }
    }
}
