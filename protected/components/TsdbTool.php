<?php

class TsdbTool
{

  public static function urlsafe_b64encode($string)
  {
    $data = base64_encode($string);
    $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
    return $data;
  }

  public  static function urlsafe_b64decode($string)
  {
    $data = str_replace(array('-', '_'), array('+', '/'), $string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
      $data .= substr('====', $mod4);
    }
    return base64_decode($data);
  }

  public  static function getIPAddress()
  { 
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      //ip from share internet
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      //ip pass from proxy
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }

  public static function getActionName()
  {
    return  Yii::app()->controller->action->id;
  }

  public static function getControllerName()
  {
    return Yii::app()->controller->id;
  }

  public static function getFunctionName()
  {
    return ucfirst(Yii::app()->controller->id).'_'.ucfirst(Yii::app()->controller->action->id);
  }

  public static function getDomain()
  {
    return  $_SERVER['SERVER_NAME'];
  }
  public static function getLocationInfo()
  {
    $url = "https://www.ifreesite.com/ipaddress/address.php?q=" . TsdbTool::getIPAddress();


    $contents = file_get_contents($url);
    $locationInfo = array();

    if ($contents === 'alert("請檢查輸入的域名/IP地址是否完整!");') {
      $info = explode(",",$contents); 
      $locationInfo['ip']= TsdbTool::getIPAddress();
      $locationInfo['country']= 'Taiwan';
      $locationInfo['city']= 'Taipei';
    }else{
      $info = explode(",",$contents); 
      $locationInfo['ip']=$info[1];
      $locationInfo['country']=$info[4];
      $locationInfo['city']=$info[6];
    }

    return $locationInfo;

  }
}
