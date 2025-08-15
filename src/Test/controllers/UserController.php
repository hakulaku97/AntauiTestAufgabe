<?php
namespace Test\controllers;

use DateTime;
use Test\models\User;

class UserController {

    /**
     * Validates if the given credentials are correct
     *
     * @param $username
     * @param $password
     *
     * @return array
     */
    public static function validateLogin($username, $password): array {
        $errors = [];

        $user = User::getUserByName($username);

        if (empty($user)) {
            $errors[] = "Username was not found in the database";

            return $errors;
        }

        if ($user[0]['blocked'] === '1') {
            $errors[] = "User was blocked due to 3 failed login attempts";

            return $errors;
        }

        if ($user[0]['password'] !== $password) {
            $errors[] = "Invalid password for User {$username}";
            $new_failure_count = ((int) $user[0]['failed']) + 1;

            if ($new_failure_count >= 3) {
                $saved = User::blockUser($user[0]['username']);

                if (!$saved) {
                    trigger_error('failed to save block for user ', E_USER_ERROR);
                }

                LogController::saveLog([
                    'username' => $user[0]['username'],
                    'date' => date('Y-m-d H:i:s'),
                    'action' => 'blocked user',
                ]);

                return $errors;
            }

            $saved = User::updateLoginFailure($user[0]['username'], $new_failure_count);

            if (!$saved) {
                trigger_error("Could not save failure count", E_USER_ERROR);
            }

            return $errors;
        }

        return $errors;
    }

    /**
     * Gets the data used for the greeting page for the current logged-in User
     *
     * @return array
     */
    public static function getGreetingData(): array {
        $current_login_time = date('Y-m-d H:i:s');
        $username = $_COOKIE['auth_user'];
        $user = User::getUserByName($username);
        $logs = LogController::getLogsWithPagination($username, 1, 10);
        $last_login_time = $user[0]['lastlogin'];

        /*
         * In my opinion a login is not only technical but also if the user considers this action as a login even while
         * already being logged in via cookie.
         *
         * Therefore I made the choice to count the revisit while already having cookies set as another login
         */
        LogController::saveLog([
            'username' => $username,
            'date' => $current_login_time,
            'action' => 'login'
        ]);

        $saved = User::updateLastLogin($username, $current_login_time);

        if (!$saved) {
            trigger_error('Unable to update last login', E_USER_ERROR);
        }

        try {
            $elapsed_time = (new DateTime($current_login_time))
                ->diff(new DateTime($last_login_time))
                ->format('%a days, %h hours, %i minutes, %s seconds');
        } catch (\Exception $e) {
            $elapsed_time = "Undefined";
        }

        return [
            'username' => $username,
            'elapsed_time' => $elapsed_time,
            'logs' => $logs,
        ];
    }
}
