<?php
/*
 * Script flushing the W3TC Plugin's Cache entirely
 * Developed by webButler (https://web-butler.ch)
 * @package WordPress
 */
$password = $_GET['password'];
if($password !== 'password') {
  header('Location: /404');
}
ignore_user_abort(true);

include('wp-load.php');
w3tc_flush_all();
echo "Script executed";
