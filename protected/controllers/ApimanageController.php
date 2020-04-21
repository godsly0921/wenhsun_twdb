<?php
class ApimanageController extends Controller{
	// layout
    public $layout = "//layouts/back_end";
    
    // 登入驗證
    protected function needLogin(): bool
    {
        return true;
    }
    public function ActionApi_download_delete($id){
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST') {
            $apiservice = new ApiService();
            $data = $apiservice->api_download_delete($id);
            if($data['status']){
                Yii::app()->session['success_msg'] = $data['msg'];
            }else{
                Yii::app()->session['error_msg'] = $data['msg'];
            }
            $this->redirect(Yii::app()->createUrl('apimanage/api_download_list'));
        } else {
            Yii::app()->session['error_msg'] = "request only post";
            $this->redirect(Yii::app()->createUrl('apimanage/api_download_list'));
        }
    }
    public function ActionApi_download_list(){
        $apiservice = new ApiService();
        $data = $apiservice->Api_download_list();
        $this->render('api_download_list',array( 'data' => $data ));
    }
    public function ActionLog_list(){
        $apiservice = new ApiService();
        $data = $apiservice->Log_list();
        $this->render('log_list',array( 'data' => $data ));
    }
    public function ActionApimanage_index(){
        $apiservice = new ApiService();
        $data = $apiservice->ApimanageList();
        $this->render('apimanage_index',array( 'data' => $data ));
    }

    public function ActionApimanage_update($id){
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST') {
            if (!CsrfProtector::comparePost()) {
               $this->redirect(Yii::app()->createUrl('apimanage/apimanage_index'));
            }
            $apiservice = new ApiService();
            $data = $apiservice->apimanage_create($_POST);
            if($data['status']){
            	Yii::app()->session['success_msg'] = $data['msg'];
                $this->redirect(Yii::app()->createUrl('apimanage/apimanage_index'));
            }else{
            	Yii::app()->session['error_msg'] = $data['msg'];
                $this->render('apimanage_update/'.$id,array("data" => $data['data']));
            }
        } else {
            $data = Apimanage::model()->findByPk($id);
            if(!empty($data)){
                $this->render('apimanage_create',array("data" => $data));
            }else{
                Yii::app()->session['error_msg'] = "編號：" . $id . "資料不存在";
                $this->redirect(Yii::app()->createUrl('apimanage/apimanage_index'));
            }
        }
    }

    public function ActionApimanage_create(){
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method === 'POST') {
			if (!CsrfProtector::comparePost()) {
				$this->redirect(Yii::app()->createUrl('apimanage/apimanage_index'));
			}
			$apiservice = new ApiService();
			$data = $apiservice->apimanage_create($_POST);
			if($data['status']){
				Yii::app()->session['success_msg'] = $data['msg'];
				$this->redirect(Yii::app()->createUrl('apimanage/apimanage_index'));
			}else{
				Yii::app()->session['error_msg'] = $data['msg'];
				$this->render('apimanage_create',array("data" => $data['data']));
			}
		} else {
			$data = array(
				"id" => "",
				"api_key" => bin2hex(random_bytes(32)),
				"api_password" => bin2hex(random_bytes(8)),
				"status" => 1,
				"remark" => "",
			);
			$this->render('apimanage_create',array( 'data' => $data ));
		}
    }
    public function ActionApimanage_delete($id){
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method === 'POST') {
			$apiservice = new ApiService();
			$data = $apiservice->apimanage_delete($id);
            if($data['status']){
            	Yii::app()->session['success_msg'] = $data['msg'];
			}else{
				Yii::app()->session['error_msg'] = $data['msg'];
			}
			$this->redirect(Yii::app()->createUrl('apimanage/apimanage_index'));
		} else {
			Yii::app()->session['error_msg'] = "request only post";
			$this->redirect(Yii::app()->createUrl('apimanage/apimanage_index'));
		}
	}
}
?>