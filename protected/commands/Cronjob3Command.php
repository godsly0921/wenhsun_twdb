<?php
class Cronjob3Command extends CConsoleCommand{
	
	public function run(){

      $res = new CronService();
      $f   = $res->today_mysql_save();
      
	}

}

?>