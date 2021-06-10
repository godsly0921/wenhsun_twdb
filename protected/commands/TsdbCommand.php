<?php

class TsdbCommand extends CConsoleCommand
{
    public function actionSendTsdbMeasurement($params,$silent=false)
    {
        
        $params=TsdbTool::urlsafe_b64decode($params);
        $params= @json_decode($params,true);
        
        var_dump($params);
        die();
        
        if ( $params )
        {
            require_once(__DIR__."/../../vendor/autoload.php");
            try 
            {
                
                $influxdbClient=new InfluxDB\Client(INFLUXDB_HOST, INFLUXDB_PORT,'','',false,false,2,2);
                $database = $influxdbClient->selectDB(INFLUXDB_DB);
                
                $retentionPolicy=isset($params['policy_name'])?$params['policy_name']:null;
                
                $tsdbParams[]=new InfluxDB\Point(
                    $params['measurement'],
                    $params['value'],
                    $params['tags'],
                    $params['fields'],
                    $params['time']
                );
                
                //debug($tsdbParams);
                
                //die();
                
                $result = $database->writePoints($tsdbParams, InfluxDB\Database::PRECISION_NANOSECONDS,$retentionPolicy);
                //debug($result);
            }
            catch ( Exception $e )
            {
                $eNow=date('Y-m-d H:i:s');
                $fp=fopen(TSDB_FILE_SAVE_PATH."write_tsdb_error.log","a+");
                fwrite($fp,print_r($tsdbParams,true));
                fwrite($fp,print_r($e->getMessage(),true));
                fwrite($fp,PHP_EOL.$eNow.PHP_EOL.PHP_EOL);
                fclose($fp);
            }
        }
        else
        {
            echo ('bad params');
        }
    }
}
