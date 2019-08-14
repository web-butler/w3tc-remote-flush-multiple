<?php
/*
 * Basic security to access the flashing functionality using a browser cookie
 * Developed by webButler (https://web-butler.ch)
 */
ignore_user_abort(false);

$pathToFlushControl = "../w3tc-remote-flush-multiple.php";
$correctPassword = 'password';
$cookieName = 'WEB_BUTLER_W3TC_FLUSH';
$browserCookie = '';
$passwordFromGet = '';
$authenticated = false;

if (isset($_COOKIE[$cookieName])) {
    $browserCookie = $_COOKIE[$cookieName];
}
if (isset($_POST['password'])) {
    $passwordFromGet = $_POST['password'];
}

// 1: if the cookie is correct, authenticate
if ($browserCookie === $correctPassword) {
    $authenticated = true;
// 2: if the password is correct but there is no cookie, reset the cookie correctly and reload the page
} elseif ($passwordFromGet === $correctPassword) {
    // "unset" cookie by assigning no value and putting the expiration date in the past
    setcookie($cookieName, '', 1);
    setcookie($cookieName, $correctPassword, time()+3600); // 3600s = 1 hour
    header("Location:".basename(__FILE__));
// if either, the cookie or pw is wrong, show the login form again
} elseif ($browserCookie !== $correctPassword || $passwordFromGet !== $correctPassword) {
    $authenticated = false;
} else { // otherwise, print the browser cookie and password from GET
    echo "Smth's odd here ...";
    echo "The Browser's Cookie Value: ".$browserCookie;
    echo "The password received is: ".$passwordFromGet;
    exit;
};

function showLoginForm() {
    echo "<h2>You have to enter the password to view this content!</h2>
    <form action='' method='post'>
        <input type='password' name='password'>
        <br />
        <input type='submit' value='Login'>
    </form>
    ";
}
?>

<html>
<head>
<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
<link rel='stylesheet' type='text/css' href='./style.css'/>
</head>
<body>
<div class='container' id='main-content'>
    <h1>Flush W3TC Cache remotely</h1>
    <p>..powered by:  <a href='https://web-butler.ch/' target='_blank'>webButler</a></p>
    <?php
    if ($authenticated) {
        include($pathToFlushControl);
    } else {
        showLoginForm();
    }
    ?>
</div>
</body>
</html>
