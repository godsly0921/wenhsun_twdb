<?php
class ApiController extends CController{
	public $page = 1;
	public $limit = 30;
	public function setresponse($params = array() , $result = array(), $debug = "",$log=1 ,$memberid = null) {
	    //設定header
	    //header 訊息修改
	    switch ($result['code']) {
	    	case '200': $headermsg = SUCCESS_GEL_EMSG; break;
	    	case '299': $headermsg = SUCCESS_LOGERR_EMSG; break;
	    	case '399': $headermsg = SUCCESS_EMPTYERR_EMSG; break;
	    	case '400': $headermsg = ERROR_WEB_POSTONLY_EMSG; break;
	    	case '401': $headermsg = ERROR_WEB_TOKEN_EMSG; break;
	    	case '308': $headermsg = ERROR_WEB_TOKENERR_EMSG; break; //2019.08.01 修改code為308
	    	case '404': $headermsg = ERROR_WEB_NOAPI_EMSG; break;
	    	case '410': $headermsg = ERROR_WEB_PARAMETERTYPE_EMSG; break;
	    	case '500': $headermsg = ERROR_SERVER_EMSG; break;
	    	
	    }
	    header("HTTP/1.1 ".$result['code']." ".$headermsg." ");
	    header('Content-Type: application/json; charset=utf-8');
	    // output format
	    $response = array(
	        "retResult" => $result['result'],
	        "retCode"   => $result['code'],
	        "retMsg"    => $result['msg'],
	        "debugMsg"  => $debug,
	        "content"   => $result['content']
	    );
	    $response = json_encode($response, JSON_UNESCAPED_UNICODE);
	    return $response;
	}
	public function checkToken($token){
		$sql = "SELECT * FROM api_manage WHERE api_token = '" . $token . "'";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		if(!empty($data)){
			return true;
		}else{
			return false;
		}
	}
	public function check_common($body = array()){
		if( !Yii::app()->request->isPostRequest ){
			$response = array(
				"result" => false, 
				"code" => ERROR_WEB_POSTONLY_NO, 
				"msg" => ERROR_WEB_POSTONLY_MSG, 
				"content" => array()
			);
		}else{
			if(!isset($body['token'])){
				$response = array(
					"result" => false, 
					"code" => ERROR_WEB_TOKEN_NO, 
					"msg" => ERROR_WEB_TOKEN_MSG, 
					"content" => array()
				);
			}else{
				if(!$this->checkToken($body['token'])){
					$response = array(
						"result" => false, 
						"code" => ERROR_WEB_TOKENERR_NO, 
						"msg" => ERROR_WEB_TOKENERR_MSG, 
						"content" => array()
					);
				}else{
					$response = array(
						"result" => true, 
						"code" => SUCCESS_GEL_NO, 
						"msg" => SUCCESS_GEL_MSG, 
						"content" => array()
					);
				}
			}
		}
		return $response;
	}
	public function getparams() {
		$data = getallheaders();
		$header = array_change_key_case($data, CASE_LOWER);
		$body = json_decode(file_get_contents('php://input'), true);
		$params = array(
			"header" => $header,
			"body" => $body
		);
		return $params;
	}
	public function ActionGetImage(){
		$params = $this->getparams();
		$check_commmon = $this->check_common($params['body']);
		if(!$check_commmon['result']){
			$response = $this->setresponse(
				$params, 
				$check_commmon
			);
		}else{
			$filter = $option = $result = $output = $content = array();
			if(isset($params['body']['keyword'])){
	        	$mongo = new Mongo();
	        	//$filter =  array('keyword'=>array( '$in' => $explode_keyword ));
	        	$explode_keyword = explode(",",$params['body']['keyword']);
	        	$filter['$and'] = array(array('copyright' => '1'),array('publish' => '1'),array('keyword'=>array( '$in' => $explode_keyword )));
	        	$select_limit = (isset($params['body']['limit']) && $params['body']['limit']>0) ? $params['body']['limit'] :$this->limit;
	        	$select_page = (isset($params['body']['page']) && $params['body']['page']>0) ? $params['body']['page'] :$this->page;

	        	$option['skip'] = ($select_page-1)*$select_limit;
	        	$option['limit'] = $select_limit;
	        	$result = $mongo->search_record('wenhsun', 'single', $filter, $option);
	        	$result = iterator_to_array($result);
	        	$cmd = [
		            'count' => "single",
		            'query' => $filter
		        ];

		        //$option['projection'] = array('single_id'=>1,'people_info'=>1,'object_name'=>1,'filming_date'=>1,'filming_location'=>1,'keyword'=>1);
		        $count_result = $mongo->command('wenhsun', $cmd)->toArray();
		        if (!empty($count_result)) {
		            $total_result = $count_result[0]->n;
		        }
		        
				foreach ($result as $key => $value) {
					$content[] = array(
						"img_url" => DOMAIN . "image_storage/O/" . $value->single_id . ".jpg",
						"filming_name" => $value->filming_name,
						"description" => $value->description,
						"website_url" => DOMAIN . "site/ImageInfo/" . $value->single_id,
						"image_id" => $value->single_id,
						// "category" => $value['filming_name'],
						"object_type" => "照片",
						"update_time" => $value->create_time,
						"keyword" => $value->keyword,
						"filming_date" => $value->filming_date,
						"filming_location" => $value->filming_location,
						"interface_system" => "台灣文學照片資料庫",
						"verify" => "文訊雜誌社",
						"people_info" => $value->people_info,
						"event_name" =>  $value->event_name,
						"photo_source" =>  $value->photo_source,
						"memo2" =>  $value->memo2
					);
				}
				$output["total_result"] = $total_result;
				$output["content"] = $content;
				if(empty($total_result)){
					$response = $this->setresponse(
						$params, 
						array(
							"result" => true, 
							"code" => SUCCESS_EMPTYERR_NO, 
							"msg" => SUCCESS_EMPTYERR_MSG, 
							"content" => $output
						)
					);
				}else{
					$response = $this->setresponse(
						$params, 
						array(
							"result" => true, 
							"code" => SUCCESS_GEL_NO, 
							"msg" => SUCCESS_GEL_MSG, 
							"content" => $output
						)
					);
				}
			}else{
				$response = $this->setresponse(
					$params, 
					array(
						"result" => false, 
						"code" => ERROR_WEB_PARAMETERTYPE_NO, 
						"msg" => ERROR_WEB_PARAMETERTYPE_MSG, 
						"content" => $output
					)
				);
			}
		}	
		echo $response;
		exit();
	}

	public function ActionGetImageDetail(){
		$params = $this->getparams();
		$check_commmon = $this->check_common($params['body']);
		if(!$check_commmon['result']){
			$response = $this->setresponse(
				$params, 
				$check_commmon
			);
		}else{
			$data = $output = array();
			if(isset($params['body']['image_id'])){
				$id = $params['body']['image_id'];
				$sql = "SELECT * FROM `single` s LEFT JOIN single_size ss on s.single_id = ss.single_id where s.single_id =" . $id . " and ss.size_type <> 'source' order by ss.single_size_id asc";
		        $result = Yii::app()->db->createCommand($sql)->queryAll();
		        $data['image_info'] = $data['size'] = array();
		        if(!empty($result)){
		        	foreach ($result as $key => $value) {
			            if($key == 0){
			                $data['size'][] = array(
			                    'size_type' => $value['size_type'],
			                    'size_description' => $value['size_description'],
			                    'dpi' => $value['dpi'],
			                    'mp' => $value['mp'],
			                    'w_h' => $value['w_h'],
			                    'ext' => $value['ext'],
			                    'print_w_h' => $value['print_w_h'],
			                    'file_size' => round($value['file_size']/1024/1024,2) . " MB",
			                    // 'sale_twd' => $value['sale_twd'],
			                    // 'sale_point' => $value['sale_point'],
			                );
			                $category_sql = 'SELECT a.name as child_name,b.name as parent_name FROM category a join category b on a.parents=b.category_id where a.category_id in('.'"'.$value["category_id"].'"'.')';
			                $category_result = Yii::app()->db->createCommand($category_sql)->queryAll();
			                $category = array();
			                foreach ($category_result as $category_key => $category_value) {
			                    $txt = $category_value['parent_name'] . ' => ' . $category_value['child_name'];
			                    array_push($category, $txt);
			                }
			                $data['image_info'] = array(
			                	"img_url" => DOMAIN . "image_storage/O/" . $value['single_id'] . ".jpg",
								"filming_name" => $value['filming_name'],
								"description" => $value['description'],
								"website_url" => DOMAIN . "site/ImageInfo/" . $value['single_id'],
								"image_id" => $value['single_id'],
								"category" => implode('<br/>', $category),
								"object_type" => "照片",
								"update_time" => $value['create_time'],
								"keyword" => explode(",",$value['keyword']),
								"filming_date" => $value['filming_date'],
								"filming_location" => $value['filming_location'],
								"interface_system" => "台灣文學照片資料庫",
								"verify" => "文訊雜誌社",
								"people_info" => $value['people_info'],
								"event_name" =>  $value['event_name'],
								"photo_source" =>  $value['photo_source'],
								"memo2" =>  $value['memo2']
			                );
			            }else{
			                $data['size'][] = array(
			                    'size_type' => $value['size_type'],
			                    'size_description' => $value['size_description'],
			                    'dpi' => $value['dpi'],
			                    'mp' => $value['mp'],
			                    'w_h' => $value['w_h'],
			                    'ext' => $value['ext'],
			                    'print_w_h' => $value['print_w_h'],
			                    'file_size' => round($value['file_size']/1024/1024,2) . " MB",
			                    // 'sale_twd' => $value['sale_twd'],
			                    // 'sale_point' => $value['sale_point'],
			                );
			            }        
			        }
			        $data['image_info']['size'] = $data['size'];
			        $output['content'] = $data['image_info'];
			        $response = $this->setresponse(
						$params, 
						array(
							"result" => true, 
							"code" => SUCCESS_GEL_NO, 
							"msg" => SUCCESS_GEL_MSG, 
							"content" => $output
						)
					);
		        }else{
		        	$response = $this->setresponse(
						$params, 
						array(
							"result" => true, 
							"code" => SUCCESS_EMPTYERR_NO, 
							"msg" => SUCCESS_EMPTYERR_MSG, 
							"content" => $output
						)
					);
		        }
			}else{
				$response = $this->setresponse(
					$params, 
					array(
						"result" => false, 
						"code" => ERROR_WEB_PARAMETERTYPE_NO, 
						"msg" => ERROR_WEB_PARAMETERTYPE_MSG, 
						"content" => $output
					)
				);
			}
		}
		echo $response;
		exit();
	}
}
?>