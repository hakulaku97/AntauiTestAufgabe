<?php
namespace App;
require '../../src/autoload.php';

use Test\controllers\UserController;
use Test\helpers\ConfigHelper;
use Test\helpers\SecurityHelper;

define('SALT_KEY', SecurityHelper::getSaltKeyFromEnvironment());


ConfigHelper::checkForLoginCookie([
        'redirect_on_success' => '',
        'redirect_on_failure' => 'Location: ../index.php',
]);


$greeting_data = UserController::getGreetingData();
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Greeting Page</title>
    <link rel='stylesheet' href='../assets/css/theme.css'>
</head>
<body>
<div class='greeting-div'>
    <h2>Hello, <?php echo htmlspecialchars($greeting_data['username']); ?>!</h2>
    <h2>Time elapsed since last login: <?php echo $greeting_data['elapsed_time']; ?></h2>
    <h3>Your Activity</h3>
    <table class='log-table'>
        <thead>
        <tr>
            <th>Username</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($greeting_data['logs'] as $log): ?>
        <tr>
            <td><?php echo $log['username'] ?></td>
            <td><?php echo $log['date'] ?></td>
            <td><?php echo $log['action'] ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <form action='logoutHelper.php' method='POST'>
        <button type='submit' class='logout-button'>Logout</button>
    </form>
</div>
</body>
</html>