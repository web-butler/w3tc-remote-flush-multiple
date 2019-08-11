<?php
/*
 * Basic security to access the flashing functionality
 * Developed by webButler (https://web-butler.ch)
 */
ignore_user_abort( false );

/* Login password */
$password = 'password';

/* Cookie name */
$cookie_name = 'wB_flush_cache_admin';
/* Cookie value (assign password set above) */
$cookie_value = $password;

// login function
function showLogin() {
    echo "<html>
    <head>
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
    <style>
    a { color: #fff; }
    a:hover { color: #fff; }
    body { color: #ccc; background: linear-gradient(to right,#000000,#4d4d4d 80%); }
    .logo { width: 150px; margin-left: 10px; }
    h1 { margin-top: 10px; font-size: 1.5rem; color: #fff; }
    h2 { margin-top: 5px; font-size: 1.2rem; color: #dedede; }
    input[type=password] { padding: 10px 16px; border: none; background-color: #353535; color: #fff; border-radius: 4px; margin: 5px 0; }
    input[type=submit] { background-color: #353535; border: none; color: white; padding: 10px 16px; text-decoration: none; margin: 5px 0; cursor: pointer; font-size: 1rem; border-radius: 4px; }
    @media screen and (min-width: 600px) {
        h1 { margin-top: 10px; font-size: 1.6rem; }
        h2 { margin-top: 5px; font-size: 1.3rem; }
    }
    @media screen and (min-width: 900px) {
        h1 { margin-top: 10px; font-size: 1.9rem; }
        h2 { margin-top: 5px; font-size: 1.5rem; }
    }
    @media screen and (min-width: 1200px) {
        h1 { margin-top: 10px; font-size: 2.3rem; }
        h2 { margin-top: 5px; font-size: 1.8rem; }
    }
    </style>
    </head>
    <body>
    <div class='container' id='main-content'>
        <h1>Flush W3TC Cache remotely</h1>
        <p>..powered by:  <a href='https://web-butler.ch/' target='_blank'>webButler</a></p>
        <h2>You have to enter the password to view this content!</h2>
        <form action=''>
            <input type='password' name='password'>
            <br />
            <input type='submit' value='Login'>
        </form>
    </div>
    </body>
    </html>
    ";
};

// 1: if the cookie is correct and the password is delivered by url, reload without password in the url (will go to 2)
if (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == $password && isset($_GET['password']) && $_GET['password'] == $password) {
    header("Location:control-panel-access.php");
    exit;
// 2: if the cookie is correct, load admin main
} elseif (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == $password) {
    include("../w3tc-remote-flush-multiple/w3tc-remote-flush-multiple.php");
    exit;
// 3: if the password is correct, reset and then set the cookie right and reload the page (will go to 2)
} elseif (isset($_GET['password']) && $_GET['password'] == $password) {
    unset($_COOKIE[$cookie_name]);
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/'); // 86400 = 1 day
    header("Location:control-panel-access.php");
    exit;
// if the cookie is wrong or there's no pw or the wrong pw
} elseif ($_COOKIE[$cookie_name] !== $password || !isset($_GET['password']) || $_GET['password'] !== $password) {
    showLogin();
    exit;
} else {
    echo "Smth's odd here ...";
    echo "Please send the admin all info on the screen, especially the following:";
    echo "Cookie: ".$_COOKIE[$cookie_name];
    echo "PW: ".$_GET['password'];
};
