<?php
/*
 * This File contains the API code for the pagination of the user action logs
 */
namespace App;
require '../../src/autoload.php';

use Test\helpers\SecurityHelper;
use Test\controllers\LogController;

define('SALT_KEY', SecurityHelper::getSaltKeyFromEnvironment());

header('Content-Type: application/json');

$username = $_COOKIE['auth_user'];
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;

$logs = LogController::getLogsWithPagination($username, $page, 10);

echo json_encode([
    'data' => $logs['data'],
    'total_pages' => $logs['total_pages'],
    'current_page' => $page
]);