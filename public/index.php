<?php
namespace App;

require '../src/autoload.php';

use Test\controllers\UserController;
use Test\helpers\ConfigHelper;
use Test\helpers\SecurityHelper;

// define the SALT_KEY as global variable
define('SALT_KEY', SecurityHelper::getSaltKeyFromEnvironment());

ConfigHelper::checkForLoginCookie([
        'redirect_on_success' => 'Location: pages/greetingsPage.php',
        'redirect_on_failure' => '',
]);

// Process the submitted Login Credentials
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $errors = UserController::validateLogin($username, $password);

    if (empty($errors)) {
        ConfigHelper::setLoginCookies($username);
        header('Location: pages/greetingsPage.php');
    }
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Login</title>
    <link rel='stylesheet' href='assets/css/loginTheme.css'>
</head>
<body>
<div class='login-div'>
    <h2>Login</h2>
    <?php if (!empty($errors)): ?>
        <div class='error-list'>
            <?php foreach ($errors as $error): ?>
                <p class='error'><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form id='login-form' method='POST' action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>'>
        <div class='form-div'>
            <label for='username'>Username (user@host.domain)</label>
            <input type='text' id='username' name='username' value='<?php echo isset($_POST['username']) ?
                    htmlspecialchars($_POST['username']) : ''; ?>'
            >
        </div>
        <div class='form-div'>
            <label for='password'>Password</label>
            <input type='password' id='password' name='password'>
        </div>
        <button type='submit'>Login</button>
    </form>
</div>
<script src='assets/js/clientSideLoginValidation.js'></script>
</body>
</html>