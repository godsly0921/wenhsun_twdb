<?php

class TsdbService extends \CComponent
{

    function __construct()
    {

    }

    public static function writeTimeSeriesInfo($static_code)
    {
        $YiiExecute = Yii::getPathOfAlias('application') . "/yiic";
      
        $domain = TsdbTool::getDomain();//取得目前P用戶所使用網址 ex:demo.luckywave.com.tw or www.yahoo.com.tw
        $ip = TsdbTool::getIPAddress();//取得目前PUBLIC
        $action_type = TsdbTool::getControllerName();//取得目前API Controller
        $action_name = TsdbTool::getActionName();//取得目前API版本 Action NAME
        $function_name = TsdbTool::getFunctionName();
        $executeTime = microtime(true) - _BEGIN_TIME;
        $executeTime = sprintf('%f', $executeTime);
        $locationInfo = TsdbTool::getLocationInfo();
        $country = $locationInfo['country'];
        $city = $locationInfo['city'];


        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN'){
            $load = sys_getloadavg();
        }else{
            $load[0] = 0;
        }
           
        $pad = rand(1, 999);
        $pad1 = rand(1, 999);
        $expandNanoTime = sprintf("%-019s", time() . "{$pad}{$pad1}", true);
        
      
        $tsdbParams = [
            'policy_name'   => 'three_year_usage_data',
            'measurement'   => 'api_actions',
            'value'         => floor($executeTime * 1000), //ms 執行秒數
            'tags'          => [
                'domain'  => $domain,//呼叫我們的哪個網站 demo.luckywave.com.tw or www.yahoo.com.tw
                'ip'  => $ip,//用戶的PUBLIC
                'api_type'  => $action_type,
                'api_name'  => $action_name,
                'function_name'  => $function_name,
                'country'  => $country,
                'city'  => $city,
                'static_code' => $static_code,
            ],
            'fields'        => [],
            'time'          => $expandNanoTime,
        ];


        $tsdbParams = TsdbTool::urlsafe_b64encode(json_encode($tsdbParams));

        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            $command = "{$YiiExecute} Tsdb SendTsdbMeasurement --params={$tsdbParams}  --silent=true  | at now ";
        } else {
            $command = "{$YiiExecute}.bat Tsdb SendTsdbMeasurement --params={$tsdbParams}  --silent=true  ";
        }
        shell_exec($command);
    }

}