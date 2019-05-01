<?php
Class Mongo{
	private $mongo_connect;
	private $bulk;
	public function __construct(){
        $this->mongo = new MongoDB\Driver\Manager(MONGO);
    }

    public function insert_record( $datatable, $collection, $input ){
    	$this->bulk = new MongoDB\Driver\BulkWrite;
    	$db = $this->mongo_connect->$datatable;//選擇資料庫
        $teble = $db->$collection;//選擇文件集合
        $this->bulk->insert( $input );
        $this->mongo->executeBulkWrite( $datatable . "." . $collection, $this->bulk );
        return $mongo;
    }

    public function update_record($datatable, $collection, $find, $input){
    	$this->bulk = new MongoDB\Driver\BulkWrite;
    	$db = $this->mongo_connect->$datatable;//選擇資料庫
        $teble = $db->$collection;//選擇文件集合
        $this->bulk->update( $find, $input, array('multi' => true) );
        $this->mongo->executeBulkWrite( $datatable . "." . $collection, $this->bulk );
        return $mongo;
    }
    public function delete_record($datatable, $collection, $input){
    	$this->bulk = new MongoDB\Driver\BulkWrite;
    	$db = $this->mongo_connect->$datatable;//選擇資料庫
        $teble = $db->$collection;//選擇文件集合
        $this->bulk->delete( $input, array('multi' => true) );
        $this->mongo->executeBulkWrite( $datatable . "." . $collection, $this->bulk );
        return $mongo;
    }
}

?>