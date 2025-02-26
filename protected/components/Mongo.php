<?php
Class Mongo{
	private $mongo_connect;
	private $bulk;
	public function __construct(){
        $this->mongo_connect = new MongoDB\Driver\Manager(MONGO);
    }

    public function search_record( $datatable, $collection, $filter, $options=array() ){
        $mongo_connection = $this->mongo_connect;
        $query = new MongoDB\Driver\Query( $filter, $options );
        $rows = $mongo_connection->executeQuery( $datatable . '.' . $collection, $query );
        return $rows;
    }
    public function insert_record( $datatable, $collection, $input ){
    	$this->bulk = new MongoDB\Driver\BulkWrite;
        $mongo_connection = $this->mongo_connect;
    	// $db = $mongo_connection->$datatable;//選擇資料庫
     //    $teble = $db->$collection;//選擇文件集合
        $this->bulk->insert( $input );
        $mongo_connection->executeBulkWrite( $datatable . "." . $collection, $this->bulk );
        return $mongo_connection;
    }

    public function update_record($datatable, $collection, $find, $input){
    	$this->bulk = new MongoDB\Driver\BulkWrite;
    	$mongo_connection = $this->mongo_connect;
        // $db = $mongo_connection->$datatable;//選擇資料庫
        // $teble = $db->$collection;//選擇文件集合
        $this->bulk->update( $find, $input, array('multi' => true) );
        $mongo_connection->executeBulkWrite( $datatable . "." . $collection, $this->bulk );
        return $mongo_connection;
    }
    public function delete_record($datatable, $collection, $input){
    	$this->bulk = new MongoDB\Driver\BulkWrite;
    	$mongo_connection = $this->mongo_connect;
        // $db = $mongo_connection->$datatable;//選擇資料庫
        // $teble = $db->$collection;//選擇文件集合
        $this->bulk->delete( $input, array('multi' => true) );
        $mongo_connection->executeBulkWrite( $datatable . "." . $collection, $this->bulk );
        return $mongo_connection;
    }
    /**
     * 执行MongoDB命令
     * @param array $param
     * @return \MongoDB\Driver\Cursor
     */
    function command( $datatable, array $param ){
        $mongo_connection = $this->mongo_connect;
        $cmd = new \MongoDB\Driver\Command($param);
        return $mongo_connection->executeCommand($datatable, $cmd);
    }
}

?>