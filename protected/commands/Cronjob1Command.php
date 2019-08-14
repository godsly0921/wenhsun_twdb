<?php
class Cronjob1Command extends CConsoleCommand{
	
	public function run($today = null){
      
      $res = new CronService();
      $f   = $res->today_record($today);
      
	}

}

?>