<?php

namespace Umb\Mentorship\Controllers;

use Umb\Mentorship\Controllers\Utils\Utility;
use Umb\Mentorship\Models\Notification;
use Umb\Mentorship\Models\User;

class Controller
{
    protected User $user;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION[$_ENV['SESSION_APP_NAME']])) {
            http_response_code(401);
            Utility::logError(401, "User not authenticated,..");
            echo "First";
            die(401);
        }
        $sessionData = $_SESSION[$_ENV['SESSION_APP_NAME']];
        if (!isset($sessionData['expires_at'])) {
            http_response_code(401);
            Utility::logError(401, "User not authenticated,..");
            echo "Second";
            die(401);
        } else {
            if (time() > $sessionData['expires_at']) {
                session_unset();
                session_destroy();
                Utility::logError(401, "Session expired");
                http_response_code(401);
                die(401);
            } else {
                $this->user = $sessionData['user'];
                $sessionData['expires_at'] = time() + ($_ENV['SESSION_DURATION'] * 60);
                $_SESSION[$_ENV['SESSION_APP_NAME']] = $sessionData;
            }
        }
    }

    public static function response($code, $message, $data = null)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "code" => $code,
            "message" => $message,
            "data" => $data
        ]);
    }

    /*******
     * Creates a notification for the user whose id is passed.
     *
     * @param $userId int id for the user;
     * @param $message string The naughty-fication message
     *
     */
    public function createNotification(int $userId, string $message)
    {
        Notification::create([
            "user_id" => $userId,
            "message" => $message
        ]);
    }

    public function returnFile($fileName)
    {
        if (file_exists($fileName)) {

            //Define header information
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: 0");
            header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
            header('Content-Length: ' . filesize($fileName));
            header('Pragma: public');

            //Clear system output buffer
            flush();

            //Read the size of the file
            readfile($fileName);
            unlink($fileName);
        }
    }
}
