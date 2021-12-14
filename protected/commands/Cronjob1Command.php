<?php
class Cronjob1Command extends CConsoleCommand{
	
	public function run($today = null){
      $start_time = date("Y-m-d H:i:s");
      $res = new CronService();
      $cronLog = new CronlogService();
      $log = $res->today_record($today);
      $end_time = date("Y-m-d H:i:s");
      $cronLog->WriteLog("Cronjob1", $start_time, $end_time, json_encode($log,JSON_UNESCAPED_UNICODE));
      
	}

}

?>