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
$data = $greeting_data['logs']['data'];
$total_pages = $greeting_data['logs']['total_pages'];
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Greeting Page</title>
    <link rel='stylesheet' href='../assets/css/greetingPageTheme.css'>
</head>
<body>
<div class='greeting-div'>
    <h2>Hello, <?php echo htmlspecialchars($greeting_data['username']); ?>!</h2>
    <h2>Time elapsed since last login: <?php echo $greeting_data['elapsed_time']; ?></h2>
    <h3>Action Protocol</h3>
    <table id='log-table' data-headers='<?php echo json_encode(!empty($data) ?
            array_keys(array_filter(array_keys($data[0]), function($key) { return !is_numeric($key); })) : []); ?>'>
        <thead>
        <tr>
            <th>Username</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id='log-table-body'>
        <?php foreach ($data as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['username'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['date'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['action'] ?? ''); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($data)): ?>
        <p id='empty-logs' style='text-align: center;'>No logs found for page <?php echo $page; ?>.</p>
    <?php else: ?>
        <p id='empty-logs' style='text-align: center; display: none;'>No logs found.</p>
    <?php endif; ?>

    <div id='pagination' class='pagination'>
        <a id='prev-page' class="<?php echo $page <= 1 ? 'disabled' : ''; ?>"
           data-page='<?php echo max(1, $page - 1); ?>'>Previous
        </a>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a class="<?php echo $i === $page ? 'active' : ''; ?>" data-page='<?php echo $i; ?>'><?php echo $i; ?></a>
        <?php endfor; ?>
        <a id='next-page' class="<?php echo $page + 1 >= $total_pages || empty($data) ? 'disabled' : ''; ?>"
           data-page='<?php echo min($total_pages, $page + 1); ?>'
        >
            Next
        </a>
    </div>
    <div class='logout-div'>
        <form action='logout.php' method='POST'>
            <button type='submit' class='logout-button'>Logout</button>
        </form>
    </div>
    <script src='../assets/js/updateLogTablePage.js'></script>
</body>
</html>