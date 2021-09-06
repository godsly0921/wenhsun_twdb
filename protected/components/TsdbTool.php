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
    return ucfirst(Yii::app()->controller->id) . '_' . ucfirst(Yii::app()->controller->action->id);
  }

  public static function getDomain()
  {
    return  $_SERVER['SERVER_NAME'];
  }
  public static function getLocationInfo()
  {
    $url = "http://ip-api.com/batch?fields=message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query";

    //$ip = ['39.9.75.112']; 
    $ip = TsdbTool::getIPAddress();  
    $endpoint = 'https://api.ip.sb/geoip/'.$ip; 
    $contents = file_get_contents($endpoint);
    $locationInfo = array();

    if (!isset($contents['country_code'])) {
      $locationInfo['ip'] = $ip;
      $locationInfo['country_code'] = 'TW';
      $locationInfo['country'] = 'Taiwan';
      $locationInfo['city'] = 'Taipei';
      $locationInfo['latitude'] = '23.5';
      $locationInfo['longitude'] = '121';
    } else {
      $locationInfo['ip'] =  $ip;
      $locationInfo['country_code'] = $contents['country_code'];
      $locationInfo['country'] = $contents['country'];
      $locationInfo['city'] =$contents['city'];
      $locationInfo['latitude'] = $contents['latitude'];
      $locationInfo['longitude'] = $contents['longitude'];
    }

    return $locationInfo;
  }
}
