<?php
require_once __DIR__.'/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('client_secrets.json');
$client->setAccessType("offline");        // offline access
$client->setIncludeGrantedScopes(true);   // incremental auth*/
$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
$client->addScope(Google_Service_AndroidPublisher::ANDROIDPUBLISHER);

/*
 * 判斷access_token有無設定，如果沒有設定導向oauth2callback進行驗證
 */
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);

      if ($client->isAccessTokenExpired()) {
        session_destroy();
        header('Location: http://www.isgoodtime.com.tw');
      }

      $packageName = 'fusulina_alpha.plugin';
      $productId = 'fusulina_vip_iap_00';
      $token = 'bfebhdlefddpmlfncnmmcmcf.AO-J1Owx9hpGV3FhG3juScj4sE4wSZ_pc9xIxcqNaMVIMS_bFICmKsjKgg_lw9QUR3e5Xzqv2PuzFxgfqexjzQX3pd-mWvSXKPOImDusoXMtIMkGG5aai-BHkmAMuD3wMU6xNKDnTblG';

      //$url = 'https://www.googleapis.com/androidpublisher/v2/applications/'.$packageName.'/purchases/products/'.$productId.'/tokens/'.$token;
      #$receipt = ['packageName'=>$packageName,'productId'=>$productId,'token'=>$token,'url'=>$url];


    try {
        $service = new Google_Service_AndroidPublisher($client);
        $results = $service->purchases_products->get($packageName,$productId,$token,array());

    } catch (Exception $e) {
        echo json_encode(array("result"=>"false","msg"=>$e->getMessage()));
        exit();
    }




   /* print_r ($results); //This object has all the data about the subscription
    echo "expiration: " . $results->expiryTimeMillis;
    exit;*/



} else {
  $redirect_uri = 'http://' . 'phptest.isgoodtime.com.tw' . '/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
?>
