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
        			"request"=>json_decode($value["request"],true),
        			"respond"=>json_decode($value["respond"],true),
        			"start_time"=>$value["start_time"],
        			"end_time"=>$value["end_time"],
        			"total_time"=>$value["total_time"],
        		);
        	}
        }return $data;
	}

	function ApimanageList($status=array()){
		$data = array();
		if(empty($status)){
			$sql = "SELECT * FROM api_manage WHERE status in(0,1)";
		}else{
			$sql = "SELECT * FROM api_manage WHERE status in(".implode(',',$status).")";
		}
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}
	function apimanage_create($input){
		try {
		 	$create_msg = "";
            $operationlogService = new OperationlogService();
            if(empty($input["id"])){
            	$model = new Apimanage();
            	$model->api_key = $input['api_key'];
            	$model->api_password = $input['api_password'];
            	$model->createtime = date("Y-m-d H:i:s");
            }else{
            	$model = Apimanage::model()->findByPk($input["id"]);
            }
            $model->status = $input['status'];
            $model->remark = $input['remark'];
            $model->isNeedToken = $input['isNeedToken'];
            if (!$model->validate()) {
                return $input;
            }

            if (!$model->hasErrors()) {
                if( $model->save() ){
                	if(empty($input["id"])){
	                    $motion = "建立 API KEY";
	                    $log = "成功 建立 API KEY = " .$input['api_key'];
	                }else{
	                	$motion = "修改 API KEY";
	                    $log = "修改 建立 API KEY = " .$input['api_key'];
	                }
                    $operationlogService->create_operationlog( $motion, $log );
                }else{
                    if(empty($input["id"])){
	                    $motion = "建立 API KEY";
	                    $log = "成功 建立 API KEY = " .$input['api_key'];
	                }else{
	                	$motion = "修改 API KEY";
	                    $log = "修改 建立 API KEY = " .$input['api_key'];
	                }
                    $operationlogService->create_operationlog( $motion, $log, 0 );
                    foreach ($model->getErrors () as $attribute => $error){
	                    foreach ($error as $message){
	                        $create_msg.": ".$message;
	                    }
	                }
                    return array("status"=>false,'msg'=>$create_msg,'data'=>$input);
                }
            }
            return array("status"=>true, 'msg'=> '成功','data'=>$input);

        } catch (Exception $e) {
            return array("status"=>false,'msg'=>$e->getMessage(),'data'=>$input);
        }
	}

	function apimanage_delete($id){
		try {
			$create_msg = "";
			$operationlogService = new OperationlogService();
			$model = Apimanage::model()->findByPk($id);
			$api_key = $model->api_key;
			if(!empty($model)){
            	$model->status=99;
            	if( $model->save() ){
            		$motion = "刪除 API KEY";
	                $log = "刪除 API KEY = " .$api_key;
                    $operationlogService->create_operationlog( $motion, $log );
            	}else{
            		foreach ($model->getErrors () as $attribute => $error){
	                    foreach ($error as $message){
	                        $create_msg.": ".$message;
	                    }
	                }
                    return array("status"=>false,'msg'=>$create_msg);
            	}
            }else{
                return array("status"=>false,'msg'=>"編號：" . $id . "資料不存在");
            }
			return array("status"=>true, 'msg'=> '成功');
		} catch (Exception $e) {
            return array("status"=>false,'msg'=>$e->getMessage());
        }
	}
	function api_download_delete($id){
		try {
			$create_msg = "";
			$operationlogService = new OperationlogService();
			$model = Apidownload::model()->findByPk($id);
			if(!empty($model)){
            	$model->status=99;
            	if( $model->save() ){
            		$motion = "刪除 下載記錄";
	                $log = "刪除 下載記錄 = " .$id;
                    $operationlogService->create_operationlog( $motion, $log );
            	}else{
            		foreach ($model->getErrors () as $attribute => $error){
	                    foreach ($error as $message){
	                        $create_msg.": ".$message;
	                    }
	                }
                    return array("status"=>false,'msg'=>$create_msg);
            	}
            }else{
                return array("status"=>false,'msg'=>"編號：" . $id . "資料不存在");
            }
			return array("status"=>true, 'msg'=> '成功');
		} catch (Exception $e) {
            return array("status"=>false,'msg'=>$e->getMessage());
        }
	}
	function api_getimage_list($id){
		try {
			$create_msg = "";
			$operationlogService = new OperationlogService();
			$model = Apilogrecord::model()->findByPk($id);
			if(!empty($model)){
            	$model->status=99;
            	if( $model->save() ){
            		$motion = "刪除 圖資請記錄";
	                $log = "刪除 圖資請記錄 = " .$id;
                    $operationlogService->create_operationlog( $motion, $log );
            	}else{
            		foreach ($model->getErrors () as $attribute => $error){
	                    foreach ($error as $message){
	                        $create_msg.": ".$message;
	                    }
	                }
                    return array("status"=>false,'msg'=>$create_msg);
            	}
            }else{
                return array("status"=>false,'msg'=>"編號：" . $id . "資料不存在");
            }
			return array("status"=>true, 'msg'=> '成功');
		} catch (Exception $e) {
            return array("status"=>false,'msg'=>$e->getMessage());
        }
	}
	function Log_list($log_format=array()){
		ini_set('memory_limit', '256M');
		$sql = "SELECT al.id,al.log_format,al.api_token,al.request,al.start_time,am.api_key FROM api_log_record al LEFT JOIN api_manage am ON al.api_manage_id=am.id WHERE al.status<>99";
		if(!empty($log_format)){
			$sql .=" AND al.log_format IN ( ";
			foreach ($log_format as $key => $value) {
				$sql .= "'" . $value . "'";
				if(end($log_format) != $value) $sql .= ",";
			}
			$sql .= ")";
		}
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}
	function Api_download_list(){
		ini_set('memory_limit', '256M');
		$sql = "SELECT al.*,am.api_key FROM api_download al LEFT JOIN api_manage am ON al.api_manage_id=am.id WHERE al.status=1";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}

	function findAuthorByKeyword($keyword, $limit, $page){
		$data = array();
		$total_result = 0;
		$conditions = "";
		if(!empty($keyword)){
			$conditions = " WHERE name LIKE '%" . $keyword . "%' OR original_name LIKE '" . $keyword . "'";
		}
		$sql = "SELECT * FROM book_author" . $conditions;
		if(!empty($limit) && !empty($page)){
			$sql .= " LIMIT " . ($page-1)*$limit . "," . $limit;
		}
		$total_row_sql = "SELECT COUNT(*) as total_result FROM book_author" . $conditions;
		$total_row = Yii::app()->db->createCommand($total_row_sql)->queryRow();
		if($total_row){
			$total_result = $total_row["total_result"];
		}
		$data = Yii::app()->db->createCommand($sql)->queryAll();

		return array("total_result" => $total_result, "data" => $data);
	}

	function findAuthorEvent($author_id){
		$data = array();
		if(!empty($author_id)){
			$sql = "SELECT * FROM book_author_event WHERE author_id='" . $author_id . "'";
			$data = Yii::app()->db->createCommand($sql)->queryAll();
		}
		return $data;
	}

	function findAuthorBook($author_id){
		$data = array();
		if(!empty($author_id)){
			$sql = "SELECT * FROM book WHERE author_id='" . $author_id . "'";
			$data = Yii::app()->db->createCommand($sql)->queryAll();
		}
		return $data;
	}
	function findAuthorById($author_id){
		$data = array();
		if(!empty($author_id)){
			$sql = "SELECT * FROM book_author WHERE author_id='" . $author_id . "'";
			$data = Yii::app()->db->createCommand($sql)->queryRow();
		}
		return $data;
	}
}
?>