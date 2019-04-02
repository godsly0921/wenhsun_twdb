<?php
class Cronjob2Command extends CConsoleCommand{
	
	public function run($today = null){

      $res = new CronService();
      $f   = $res->dev_today_record($today);
      
	}

}

?>