<?php
class ApiController extends CController{
	public $page = 1;
	public $limit = 30;
	private $output = array();
	public function setresponse($params = array() , $result = array(), $log=1) {
		$apiservice = new ApiService();
	    //設定header
	    //header 訊息修改
	    switch ($result['code']) {
	    	case '200': $headermsg = SUCCESS_GEL_EMSG; break;
	    	case '299': $headermsg = SUCCESS_LOGERR_EMSG; break;
	    	case '399': $headermsg = SUCCESS_EMPTYERR_EMSG; break;
	    	case '400': $headermsg = ERROR_WEB_POSTONLY_EMSG; break;
	    	case '401': $headermsg = ERROR_WEB_TOKEN_EMSG; break;
	    	case '308': $headermsg = ERROR_WEB_TOKENERR_EMSG; break;
	    	case '404': $headermsg = ERROR_WEB_NOAPI_EMSG; break;
	    	case '410': $headermsg = ERROR_WEB_PARAMETERTYPE_EMSG; break;
	    	case '500': $headermsg = ERROR_SERVER_EMSG; break;
	    	
	    }
	    header("HTTP/1.1 ".$result['code']." ".$headermsg." ");
	    header('Content-Type: application/json; charset=utf-8');
	    // output format
	    $response = array(
	        "result" => $result['result'],
	        "code"   => $result['code'],
	        "msg"    => $result['msg'],
	        // "debugMsg"  => $debug,
	        "content"   => $result['content']
	    );
	    //$response = json_encode($response);
	    $action = Yii::app()->controller->action->id;
	    $apiservice->LogRecord($action,$params,$response);
	    return json_encode($response);
	}
	public function checkToken($token){
		$sql = "SELECT * FROM api_manage WHERE api_token = '" . $token . "'";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		if(!empty($data)){
			if($data[0]['isNeedToken'] == 1){
				$token_createtime = $data[0]['token_createtime'];
				$token_expire = date('Y-m-d H:i:s', strtotime($token_createtime.' +30 minutes'));
				if(strtotime("now")<=strtotime($token_expire)){
					return true;
				}else{
					return false;
				}
			}else{
				return true;
			}
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
		$start_microtime = microtime(true);
		$params = array(
			"start_microtime" => $start_microtime,
			"start" => DateTime::createFromFormat('U.u', $start_microtime)->format("Y-m-d H:i:s.u"),
			"header" => $header,
			"body" => $body,
			"token" => isset($body['token'])?$body['token']:"",
			"api_key" => isset($body['api_key'])?$body['api_key']:""
		);
		return $params;
	}
	public function ActionreVerifyToken(){
		$params = $this->getparams();
		try{
			if( !Yii::app()->request->isPostRequest ){
				$response = $this->setresponse(
					$params, 
					array(
						"result" => false, 
						"code" => ERROR_WEB_POSTONLY_NO, 
						"msg" => ERROR_WEB_POSTONLY_MSG, 
						"content" => array()
					)
				);
			}else{
				if(!empty($params['body']['api_key']) && !empty($params['body']['password'])){
					$sql = "SELECT * FROM api_manage WHERE api_key = '" . $params['body']['api_key'] . "' AND api_password = '" . $params['body']['password'] . "'";
					$data = Yii::app()->db->createCommand($sql)->queryAll();
					if(!empty($data)){
						if($data[0]["isNeedToken"] == 1){
							$token_expire = date('Y-m-d H:i:s', strtotime($data[0]['token_createtime'].' +30 minutes'));
							if(strtotime("now")<=strtotime($token_expire) && !empty($data[0]["api_token"])){
								$jwt = $data[0]["api_token"];
							}else{
								$request_time = date("Y-m-d H:i:s");
								$secret = $data[0]['createtime'];
								// Create token header as a JSON string
								$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
								// Create token payload as a JSON string
								$payload = json_encode(['user_id' => $data[0]['id'], 'request_time' => $request_time]);
								// Encode Header to Base64Url String
								$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
								// Encode Payload to Base64Url String
								$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
								// Create Signature Hash
								$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
								// Encode Signature to Base64Url String
								$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
								// Create JWT
								$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
								$model = Apimanage::model()->findByPk($data[0]['id']);
								$model->api_token = $jwt;
								$model->token_createtime = date("Y-m-d H:i:s");
								$model->save();
							}
						}else{
							$jwt = $data[0]["api_token"];
						}
						
						$this->output = array("token"=>$jwt);
						$response = $this->setresponse(
							$params, 
							array(
								"result" => true, 
								"code" => SUCCESS_GEL_NO, 
								"msg" => SUCCESS_GEL_MSG, 
								"content" => $this->output
							)
						);
					}else{
						$response = $this->setresponse(
							$params, 
							array(
								"result" => false, 
								"code" => ERROR_WEB_TOKENERR_NO, 
								"msg" => ERROR_WEB_TOKENERR_MSG, 
								"content" => $this->output
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
							"content" => $this->output
						)
					);
				}
			}
		} catch (Exception $e) {
		    $response = $this->setresponse(
				$params, 
				array(
					"result" => false, 
					"code" => ERROR_SERVER_NO, 
					"msg" => ERROR_SERVER_MSG, 
					"content" => $this->output
				)
			);
			Yii::log(date("Y-m-d H:i:s").' VerifyToken false。error message => ' . $e->getMessage(), CLogger::LEVEL_INFO);
		}
		echo $response;
		exit();
	}

	public function ActionGetImage(){
		$params = $this->getparams();
		try{
			$check_commmon = $this->check_common($params['body']);
			if(!$check_commmon['result']){
				$response = $this->setresponse(
					$params, 
					$check_commmon
				);
			}else{
				$filter = $option = $result = $content = array();
				if(isset($params['body']['keyword'])){
		        	$mongo = new Mongo();
		        	//$filter =  array('keyword'=>array( '$in' => $explode_keyword ));
		        	$explode_keyword = explode(",",$params['body']['keyword']);
		        	if(empty($params['body']['keyword'])){
						$filter['$and'] = array(array('photo_limit'=>array( '$in' => array('3'))),array('copyright' => '1'),array('publish' => '1'));
						//array('photo_limit'=>array( '$in' => array('1','3'));//通通開放 僅限API使用
		        	}else{
						$filter['$and'] = array(array('photo_limit'=>array( '$in' => array('3'))),array('copyright' => '1'),array('publish' => '1'),array('keyword'=>array( '$in' => $explode_keyword )));
						//$filter['$and'] = array(array('photo_limit' => '3'));//通通開放 僅限API使用
		        	}
		        	
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
							"img_url" => DOMAIN . "image_storage/M/" . $value->single_id . ".jpg",
							"filming_name" => $value->filming_name,
							"description" => $value->description,
							"website_url" => DOMAIN . "site/ImageInfo/" . $value->single_id ."/1",
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
							"event_name" => !empty($value->event_name)?$value->event_name:"",
							"photo_source" =>  !empty($value->photo_source)?$value->photo_source:"",
							"memo2" =>  $value->memo2
						);
					}
					$this->output["total_result"] = $total_result;
					$this->output["content"] = $content;
					if(empty($total_result)){
						$response = $this->setresponse(
							$params, 
							array(
								"result" => true, 
								"code" => SUCCESS_EMPTYERR_NO, 
								"msg" => SUCCESS_EMPTYERR_MSG, 
								"content" => $this->output
							)
						);
					}else{
						$response = $this->setresponse(
							$params, 
							array(
								"result" => true, 
								"code" => SUCCESS_GEL_NO, 
								"msg" => SUCCESS_GEL_MSG, 
								"content" => $this->output
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
							"content" => $this->output
						)
					);
				}
			}
		} catch (Exception $e) {
		    $response = $this->setresponse(
				$params, 
				array(
					"result" => false, 
					"code" => ERROR_SERVER_NO, 
					"msg" => ERROR_SERVER_MSG, 
					"content" => $this->output
				)
			);
			Yii::log(date("Y-m-d H:i:s").' VerifyToken false。error message => ' . $e->getMessage(), CLogger::LEVEL_INFO);
		}	
		echo $response;
		exit();
	}

	public function ActionGetImageDetail(){
		$params = $this->getparams();
		try{
			$check_commmon = $this->check_common($params['body']);
			if(!$check_commmon['result']){
				$response = $this->setresponse(
					$params, 
					$check_commmon
				);
			}else{
				$data = array();
				if(isset($params['body']['image_id'])){
					$id = $params['body']['image_id'];
					$sql = "SELECT * FROM `single` s LEFT JOIN single_size ss ON s.single_id = ss.single_id WHERE s.single_id =" . $id . " AND ss.size_type <> 'source' AND publish='1' AND copyright='1' AND photo_limit IN('3') order by ss.single_size_id asc";
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
				                	"img_url" => DOMAIN . "image_storage/M/" . $value['single_id'] . ".jpg",
									"filming_name" => $value['filming_name'],
									"description" => $value['description'],
									"website_url" => DOMAIN . "site/ImageInfo/" . $value['single_id'] ."/1",
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
				       	$this->output = $data['image_info'];
				        $response = $this->setresponse(
							$params, 
							array(
								"result" => true, 
								"code" => SUCCESS_GEL_NO, 
								"msg" => SUCCESS_GEL_MSG, 
								"content" => $this->output
							)
						);
			        }else{
			        	$response = $this->setresponse(
							$params, 
							array(
								"result" => true, 
								"code" => SUCCESS_EMPTYERR_NO, 
								"msg" => SUCCESS_EMPTYERR_MSG, 
								"content" => $this->output
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
							"content" => $this->output
						)
					);
				}
			}
		} catch (Exception $e) {
		    $response = $this->setresponse(
				$params, 
				array(
					"result" => false, 
					"code" => ERROR_SERVER_NO, 
					"msg" => ERROR_SERVER_MSG, 
					"content" => $this->output
				)
			);
			Yii::log(date("Y-m-d H:i:s").' VerifyToken false。error message => ' . $e->getMessage(), CLogger::LEVEL_INFO);
		}
		echo $response;
		exit();
	}

	public function ActiongetImageAuthorization(){
		$params = $this->getparams();
		try{
			$check_commmon = $this->check_common($params['body']);
			if(!$check_commmon['result']){
				$response = $this->setresponse(
					$params, 
					$check_commmon
				);
			}else{
				$result = array();
				if(isset($params['body']['image_id'])){
					$id = $params['body']['image_id'];
					$sql = "SELECT * FROM `single` WHERE single_id='".$id."' AND publish='1' AND copyright='1'";
			        $result = Yii::app()->db->createCommand($sql)->queryAll();
			        if(!empty($result)){
			        	$response = $this->setresponse(
							$params, 
							array(
								"result" => true, 
								"code" => SUCCESS_GEL_NO, 
								"msg" => SUCCESS_GEL_MSG, 
								"content" => array(
					        		"authorization_status" => $result[0]["authorization_status"],
					        		"contributor" => "文訊雜誌社",
					        		"right_of_portrait" => $result[0]["people_info"]
					        	)
							)
						);
			        }else{
			        	$response = $this->setresponse(
							$params, 
							array(
								"result" => true, 
								"code" => SUCCESS_EMPTYERR_NO, 
								"msg" => SUCCESS_EMPTYERR_MSG, 
								"content" => $this->output
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
							"content" => $this->output
						)
					);
				}
			}
		} catch (Exception $e) {
		    $response = $this->setresponse(
				$params, 
				array(
					"result" => false, 
					"code" => ERROR_SERVER_NO, 
					"msg" => ERROR_SERVER_MSG, 
					"content" => $this->output
				)
			);
			Yii::log(date("Y-m-d H:i:s").' VerifyToken false。error message => ' . $e->getMessage(), CLogger::LEVEL_INFO);
		}
		echo $response;
		exit();
	}

	public function ActiongetDownload(){
		$params = $this->getparams();
		try{
			$check_commmon = $this->check_common($params['body']);
			if(!$check_commmon['result']){
				$response = $this->setresponse(
					$params, 
					$check_commmon
				);
			}else{
				$result = array();
				$apiservice = new ApiService();
				if(isset($params['body']['image_id']) && isset($params['body']['size'])){
					$sql = "SELECT * FROM `single` s LEFT JOIN single_size ss ON s.single_id = ss.single_id WHERE s.single_id =" . $params['body']['image_id'] . " AND ss.size_type = '".$params['body']['size']."' AND publish='1' AND copyright='1'";
			        $result = Yii::app()->db->createCommand($sql)->queryAll();
			        if(!empty($result)){
			        	if(!file_exists(PHOTOGRAPH_STORAGE_DIR . $params['body']['size'] . "/" . $params['body']['image_id'] . "." . $result[0]["ext"])){
			        		$photographService = new PhotographService();
			        		$photographService->ConvertImage($result);
			        	}
						$zip = new ZipArchive;
						$zip_filename = rtrim(strtr(base64_encode(date("Y-m-d H:i:s")."_". $params['body']['image_id'] . "_" . $params['body']['size']), '+/', '-_'), '=') . '_' . $params['body']['image_id'] .'.zip';
						if ($zip->open(ROOT_DIR.API_DOWNLOAD_PATH.$zip_filename, ZipArchive::CREATE) === TRUE){
						    // Add files to the zip file
						    $zip->addFile(PHOTOGRAPH_STORAGE_DIR . $params['body']['size'] . "/" . $params['body']['image_id'] . "." . $result[0]["ext"],$params['body']['image_id'] . "_".$params['body']['size']."." . $result[0]["ext"]);
						    // All files are added, so close the zip file.
						    $zip->close();
						}
						$sql = "SELECT * FROM api_manage WHERE api_token = '" . $params['body']['token'] . "'";
						$api_data = Yii::app()->db->createCommand($sql)->queryAll();
						if(!empty($api_data)){
							$apiservice->ApiDownloadLog($api_data[0]['id'], $params['body']['image_id'], $params['body']['size'], $zip_filename);
						}
						$response = $this->setresponse(
							$params, 
							array(
								"result" => true, 
								"code" => SUCCESS_GEL_NO, 
								"msg" => "正確提供圖檔連結", 
								"content" => array(
									"download_url" => API_DOMAIN . API_DOWNLOAD_PATH . $zip_filename
								)
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
							"content" => $this->output
						)
					);
				}
			}
		} catch (Exception $e) {
		    $response = $this->setresponse(
				$params, 
				array(
					"result" => false, 
					"code" => ERROR_SERVER_NO, 
					"msg" => ERROR_SERVER_MSG, 
					"content" => $this->output
				)
			);
			Yii::log(date("Y-m-d H:i:s").' VerifyToken false。error message => ' . $e->getMessage(), CLogger::LEVEL_INFO);
		}
		echo $response;
		exit();
	}

	public function ActiongetRequestRecordByImage(){
		$params = $this->getparams();
		try{
			$check_commmon = $this->check_common($params['body']);
			if(!$check_commmon['result']){
				$response = $this->setresponse(
					$params, 
					$check_commmon
				);
			}else{
				$token = $params['body']['token'];
				$apiservice = new ApiService();
				$this->output=$apiservice->FindLogByTokenAndLogFormat($token,'getimage');
		        if(!empty($this->output)){
		        	$response = $this->setresponse(
						$params, 
						array(
							"result" => true, 
							"code" => SUCCESS_GEL_NO, 
							"msg" => SUCCESS_GEL_MSG, 
							"content" => $this->output
						)
					);
		        }else{
		        	$response = $this->setresponse(
						$params, 
						array(
							"result" => true, 
							"code" => SUCCESS_EMPTYERR_NO, 
							"msg" => SUCCESS_EMPTYERR_MSG, 
							"content" => $this->output
						)
					);
		        }
			}
		} catch (Exception $e) {
		    $response = $this->setresponse(
				$params, 
				array(
					"result" => false, 
					"code" => ERROR_SERVER_NO, 
					"msg" => ERROR_SERVER_MSG, 
					"content" => $this->output
				)
			);
			Yii::log(date("Y-m-d H:i:s").' VerifyToken false。error message => ' . $e->getMessage(), CLogger::LEVEL_INFO);
		}
		echo $response;
		exit();
	}
	public function ActiongetRequestRecordByDownload(){
		$params = $this->getparams();
		try{
			$check_commmon = $this->check_common($params['body']);
			if(!$check_commmon['result']){
				$response = $this->setresponse(
					$params, 
					$check_commmon
				);
			}else{
				$token = $params['body']['token'];
				$apiservice = new ApiService();
				$this->output=$apiservice->FindLogByTokenAndLogFormat($token,'getdownload');
		        if(!empty($this->output)){
		        	$response = $this->setresponse(
						$params, 
						array(
							"result" => true, 
							"code" => SUCCESS_GEL_NO, 
							"msg" => SUCCESS_GEL_MSG, 
							"content" => $this->output
						)
					);
		        }else{
		        	$response = $this->setresponse(
						$params, 
						array(
							"result" => true, 
							"code" => SUCCESS_EMPTYERR_NO, 
							"msg" => SUCCESS_EMPTYERR_MSG, 
							"content" => $this->output
						)
					);
		        }
			}
		} catch (Exception $e) {
		    $response = $this->setresponse(
				$params, 
				array(
					"result" => false, 
					"code" => ERROR_SERVER_NO, 
					"msg" => ERROR_SERVER_MSG, 
					"content" => $this->output
				)
			);
			Yii::log(date("Y-m-d H:i:s").' VerifyToken false。error message => ' . $e->getMessage(), CLogger::LEVEL_INFO);
		}
		echo $response;
		exit();
	}

	public function ActiongetRequestRecordByImageDetail(){
		$params = $this->getparams();
		try{
			$check_commmon = $this->check_common($params['body']);
			if(!$check_commmon['result']){
				$response = $this->setresponse(
					$params, 
					$check_commmon
				);
			}else{
				$token = $params['body']['token'];
				$apiservice = new ApiService();
				$this->output=$apiservice->FindLogByTokenAndLogFormat($token,'getimagedetail');
		        if(!empty($this->output)){
		        	$response = $this->setresponse(
						$params, 
						array(
							"result" => true, 
							"code" => SUCCESS_GEL_NO, 
							"msg" => SUCCESS_GEL_MSG, 
							"content" => $this->output
						)
					);
		        }else{
		        	$response = $this->setresponse(
						$params, 
						array(
							"result" => true, 
							"code" => SUCCESS_EMPTYERR_NO, 
							"msg" => SUCCESS_EMPTYERR_MSG, 
							"content" => $this->output
						)
					);
		        }
			}
		} catch (Exception $e) {
		    $response = $this->setresponse(
				$params, 
				array(
					"result" => false, 
					"code" => ERROR_SERVER_NO, 
					"msg" => ERROR_SERVER_MSG, 
					"content" => $this->output
				)
			);
			Yii::log(date("Y-m-d H:i:s").' VerifyToken false。error message => ' . $e->getMessage(), CLogger::LEVEL_INFO);
		}
		echo $response;
		exit();
	}

	public function ActionGetAuthor()
	{
		$params = $this->getparams();
		try {
			$check_commmon = $this->check_common($params['body']);
			if (!$check_commmon['result']) {
				$response = $this->setresponse(
					$params,
					$check_commmon
				);
			} else {
				$size = (int) $params['body']['size'];
				$page = (int) $params['body']['page'];
				$keyword = (string) $params['body']['keyword'];
				$siteService = new ApiService();
				$authors = $siteService->findAuthorByKeyword($keyword, $size, $page);
				$this->output = $authors;
				if (!empty($this->output)) {
					$response = $this->setresponse(
						$params,
						[
							'result' => true,
							'code' => SUCCESS_GEL_NO,
							'msg' => SUCCESS_GEL_MSG,
							'content' => $this->output
						]
					);
				} else {
					$response = $this->setresponse(
						$params,
						[
							'result' => true,
							'code' => SUCCESS_EMPTYERR_NO,
							'msg' => SUCCESS_EMPTYERR_MSG,
							'content' => ''
						]
					);
				}
			}
		} catch (Exception $e) {
			$response = $this->setresponse(
				$params,
				array(
					'result' => false,
					'code' => ERROR_SERVER_NO,
					'msg' => ERROR_SERVER_MSG,
					'content' => $this->output
				)
			);
			Yii::log(date("Y-m-d H:i:s") . ' VerifyToken false。error message => ' . $e->getMessage(), CLogger::LEVEL_INFO);
		}
		echo $response;
		exit;
	}

	public function ActionGetAuthorDetail()
	{
		$params = $this->getparams();
		try {
			$check_commmon = $this->check_common($params['body']);
			if (!$check_commmon['result']) {
				$response = $this->setresponse(
					$params,
					$check_commmon
				);
			} else {
				$apiservice = new ApiService();
				$author = $apiservice->findAuthorById($params['body']['author_id']);
				$output = [];
				if (!empty($author)) {
					$siteService = new SiteService();
					$subfix = '作者教學資源';
					$image = $siteService->findPhoto('', $author["name"] . $subfix, '', '', '', '0', '0');
					// $image = [];
					// 作者年表
					$event = $apiservice->findAuthorEvent($params['body']['author_id']);
					// 作者書籍
					$book = $apiservice->findAuthorBook($params['body']['author_id']);

					$output['image'] = $image;
					$output['author'] = $author;
					$output['event'] = $event;
					$output['book'] = $book;
					$this->output = $output;
					$response = $this->setresponse(
						$params,
						[
							'result' => true,
							'code' => SUCCESS_GEL_NO,
							'msg' => SUCCESS_GEL_MSG,
							'content' => $this->output
						]
					);
				} else {
					$response = $this->setresponse(
						$params,
						[
							'result' => true,
							'code' => SUCCESS_EMPTYERR_NO,
							'msg' => SUCCESS_EMPTYERR_MSG,
							'content' => ''
						]
					);
				}
			}
		} catch (Exception $e) {
			$response = $this->setresponse(
				$params,
				array(
					'result' => false,
					'code' => ERROR_SERVER_NO,
					'msg' => ERROR_SERVER_MSG,
					'content' => $this->output
				)
			);
			Yii::log(date("Y-m-d H:i:s") . ' VerifyToken false。error message => ' . $e->getMessage(), CLogger::LEVEL_INFO);
		}
		echo $response;
		exit;
	}

	private function objectsToArray($obj)
	{
		$arr = [];
		$i = 0;
		foreach ($obj as $value) {
			foreach($value as $k => $v) {
				$arr[$i][$k] = $v;
			}
			$i++;
		}

		return $arr;
	}

	private function objectToArray($obj)
	{
		$arr = [];
		$i = 0;
		foreach ($obj as $k => $v) {
			$arr[$i][$k] = $v;
		}
		$i++;

		return $arr;
	}
}
?>
