<?php
namespace App;
require '../../src/autoload.php';

use Test\controllers\UserController;

$greetingData = UserController::getGreetingData();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greeting Page</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
</head>
<body>
<div class="dashboard-container">
    <h2>Hello, <?php echo htmlspecialchars($greetingData['username']); ?>!</h2>
    <h2>Time elapsed since last login: <?php echo $greetingData['elapsedTime']; ?></h2>
    <h3>Your Activity</h3>
    <table class="dashboard-table">
        <thead>
        <tr>
            <th>Username</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($greetingData['logs'] as $log): ?>
        <tr>
            <td><?php echo $log['username'] ?></td>
            <td><?php echo $log['date'] ?></td>
            <td><?php echo $log['action'] ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <form action="logoutHelper.php" method="POST">
        <button type="submit" class="logout-button">Logout</button>
    </form>
</div>
</body>
</html>