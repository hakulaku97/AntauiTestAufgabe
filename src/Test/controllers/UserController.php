<?php
namespace Test\controllers;

use DateTime;
use Test\models\User;

class UserController {
    public static function validateLogin($username, $password): array {
        $errors = [];

        $user = User::getUserByName($username);

        if (empty($user)) {
            $errors[] = "Invalid username";

            return $errors;
        }

        if ($user[0]['blocked'] === '1') {
            $errors[] = "User as blocked due to 3 failed login attempts";

            return $errors;
        }

        if ($user[0]['password'] !== $password) {
            $errors[] = "Invalid password";
            $newFailureCount = ((int) $user[0]['failed']) + 1;

            if ($newFailureCount >= 3) {
                $saved = User::blockUser($user[0]['username']);

                if (!$saved) {
                    trigger_error('failed to save block for user ', E_USER_WARNING);
                }

                LogController::saveLog([
                    'username' => $user[0]['username'],
                    'date' => date('Y-m-d H:i:s'),
                    'action' => 'blocked user',
                ]);

                return $errors;
            }

            $saved = User::updateLoginFailure($user[0]['username'], $newFailureCount);

            if (!$saved) {
                trigger_error("Could not save failure count");
            }

            return $errors;
        }

        return $errors;
    }

    public static function getGreetingData(): array {
        $currentLoginTime = date('Y-m-d H:i:s');
        $username = $_COOKIE['auth_user'];
        $user = User::getUserByName($username);
        $logs = LogController::getLogs($username);
        $lastLoginTime = $user[0]['lastlogin'];

        LogController::saveLog([
            'username' => $username,
            'date' => $currentLoginTime,
            'action' => 'login'
        ]);

        $saved = User::updateLastLogin($username, $currentLoginTime);

        if (!$saved) {
            trigger_error('Unable to update last login', E_USER_ERROR);
        }

        try {
            $elapsedTime = (new DateTime($currentLoginTime))
                ->diff(new DateTime($lastLoginTime))
                ->format('%a days, %h hours, %i minutes, %s seconds');
        } catch (\Exception $e) {
            $elapsedTime = "";
        }

        return [
            'username' => $username,
            'elapsedTime' => $elapsedTime,
            'logs' => $logs,
        ];
    }
}
