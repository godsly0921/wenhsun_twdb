<?php
class AuthorImportCommand extends CConsoleCommand{
	
	public function run($today = null){
      
      $res = new AuthorService();
      $res->Import();
      
	}

}

?>