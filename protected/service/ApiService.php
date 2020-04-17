<?php
class ApiService{

	function LogRecord($log_format,$request,$respond){
		try{
			$api_manage_id = "";
			if(!empty($request["token"])){
				$sql = "SELECT * FROM api_manage WHERE api_token = '" . $request["token"] . "'";
				$api_data = Yii::app()->db->createCommand($sql)->queryAll();
				if(!empty($api_data)){
					$api_manage_id = $api_data[0]["id"];
				}
			}
			if(!empty($request["api_key"])){
				$sql = "SELECT * FROM api_manage WHERE api_key = '" . $request["api_key"] . "'";
				$api_data = Yii::app()->db->createCommand($sql)->queryAll();
				if(!empty($api_data)){
					$api_manage_id = $api_data[0]["id"];
				}
			}

			$end_time = microtime(true);
			$model = new Apilogrecord();
			$model->log_format = $log_format;
			$model->api_token = $request["token"];
			$model->api_manage_id = $api_manage_id;
			$model->request = json_encode($request,JSON_UNESCAPED_UNICODE);
			$model->respond = json_encode($respond,JSON_UNESCAPED_UNICODE);
			$model->start_time = $request['start'];
			$model->end_time = DateTime::createFromFormat('U.u', $end_time)->format("Y-m-d H:i:s.u");
			$model->total_time =  $end_time-$request['start_microtime'];
			$model->save();
		} catch (Exception $e) {
			var_dump($e->getMessage());exit();
		}
	}
	function ApiDownloadLog($api_manage_id,$image_id,$size_type,$zip_filename){
		$model = new Apidownload();
		$model->api_manage_id = $api_manage_id;
		$model->image_id = $image_id;
		$model->size_type = $size_type;
		$model->filename = $zip_filename;
		$model->createtime = date("Y-m-d H:i:s");
		$model->save();
	}
	function FindLogByTokenAndLogFormat($token,$log_format){
		$sql = "SELECT * FROM api_log_record WHERE api_token = '" . $token . "' AND log_format='".$log_format."' LIMIT 30";
		$lot_data = Yii::app()->db->createCommand($sql)->queryAll();
		$data = array();
        if(!empty($lot_data)){
        	foreach ($lot_data as $key => $value) {
        		$data[]=array(
        			"request"=>$value["request"],
        			"respond"=>$value["respond"],
        			"start_time"=>$value["start_time"],
        			"end_time"=>$value["end_time"],
        			"total_time"=>$value["total_time"],
        		);
        	}
        }return $data;
	}
}
?>