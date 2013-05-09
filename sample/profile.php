<?php
require_once '../vendor/autoload.php';

session_name('linkedin');
session_start();

$redirect_uri = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
$api_key    = "";
$api_secret = "";

$client = new \Splash\LinkedIn\Client($api_key, $api_secret);

if (isset($_GET['logout'])) {
    unset($_SESSION['creds']);
    echo "<h1>Reset Session</h1>";
} elseif (isset($_GET['error'])) {
    echo "<h1>ERROR</h1> <p>{$_GET['error_description']}</p>";
} elseif (isset($_GET['code']) && !isset($_SESSION['creds'])) {

    $access_token = $client->fetchAccessToken($_GET['code'], $redirect_uri);

    $_SESSION['creds'] = $access_token;

} elseif (!isset($_SESSION['creds'])) {
    $url = $client->getAuthorizationUrl($redirect_uri);

    echo "Redirect to... <a href='$url'>$url</a>";
}


if (isset($_SESSION['creds'])) {
    $client->setAccessToken($_SESSION['creds']['access_token']);

    $response = $client->fetch('/v1/people/~:(firstName,lastName)');

    echo "<pre>";
    var_export($response);
    echo "</pre>";
}