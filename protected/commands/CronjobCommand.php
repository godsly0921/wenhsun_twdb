<?php
class CronjobCommand extends CConsoleCommand{
	
	public function run(){
      
      $res = new CronService();
      $f   = $res->today_record();
      
	}

}

?>