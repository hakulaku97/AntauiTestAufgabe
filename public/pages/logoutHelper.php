<?php
namespace App;
require '../../src/autoload.php';

use Test\controllers\LogController;
use Test\helpers\ConfigHelper;

LogController::saveLog([
    'username' => $_COOKIE['auth_user'],
    'date' => date('Y-m-d H:i:s'),
    'action' => 'logout'
]);

ConfigHelper::deleteLoginCookies();

header("Location: ../index.php");

exit;

