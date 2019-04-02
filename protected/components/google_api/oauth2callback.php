<?php
date_default_timezone_set("Asia/Taipei");
require_once __DIR__.'/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfigFile('client_secrets.json');
$client->setRedirectUri('http://' . 'phptest.isgoodtime.com.tw' . '/oauth2callback.php');
$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
$client->addScope(Google_Service_AndroidPublisher::ANDROIDPUBLISHER);

/*
 * 判斷code是否存在，如果不存在產生驗證連結網址
 */
if (!isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = 'http://' .'phptest.isgoodtime.com.tw' . '/';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
