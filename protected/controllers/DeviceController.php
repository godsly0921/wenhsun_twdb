<?php
class DeviceController extends Controller{

  // layout
  public $layout = "//layouts/back_end";
  
  // 登入驗證
  protected function beforeAction($action){
  
    return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
  }

  // 預設頁面
  public function Actionlist(){

    $temser = new DeviceService();
    $datas  = $temser -> getall();
      $device_status = DevicestatusService::findDevicestatus();//儀器狀態

    $this->render('list',['datas'=>$datas,'device_status'=>$device_status]);
  
  }

  // 新增頁面
  public function Actionnew(){
    
    $temser = new DeviceService();
    $grp    = $temser -> getallposition();
    $numbers = Common::numbers(100);//可用年限選單
    $device_status = DevicestatusService::findDevicestatus();//儀器狀態

    $this->render('new',['groups'=>$grp,'numbers'=>$numbers,'device_status'=>$device_status]);

  }

  // 新增動作
  public function Actionnewdo(){
    
    // 如果是post才執行
  	if( Yii::app()->request->isPostRequest ){
      

      if (!CsrfProtector::comparePost()) {
        $this->redirect('index');
      }

        $inputs['purchase_date'] = filter_input(INPUT_POST, 'purchase_date');
        $inputs['available_year'] = filter_input(INPUT_POST, 'available_year');
        $inputs['codenum'] = filter_input(INPUT_POST, 'codenum');
        $inputs['name'] = filter_input(INPUT_POST, 'name');
        $inputs['en_name'] = filter_input(INPUT_POST, 'en_name');
        $inputs['name'] = filter_input(INPUT_POST, 'name');
        $inputs['position'] = filter_input(INPUT_POST, 'position');
        $inputs['status'] = filter_input(INPUT_POST, 'status');
        $inputs['attention_item'] = filter_input(INPUT_POST, 'attention_item');
        $inputs['ip'] = filter_input(INPUT_POST, 'ip');
        $inputs['station'] = filter_input(INPUT_POST, 'station');
      
      $tmpser = new DeviceService();
      $newres = $tmpser -> createdev($inputs);

      if( $newres[0] === true ){
        Yii::app()->session['success_msg'] = $newres[1];
      }else{
        Yii::app()->session['error_msg']   = $newres[1];
      }

      $this->redirect(Yii::app()->createUrl('device/new'));
      exit;  
      
  	}else{

      // 導回新增頁面
  	  $this->redirect(Yii::app()->createUrl('device/new'));

  	}
  }

  // 新增地點
  public function Actionpositionnew(){
    
    $this->render('positionnew');
  }

  // 新增地點動作
  public function Actionpositionnewdo(){
    if( Yii::app()->request->isPostRequest ){
      

      if (!CsrfProtector::comparePost()) {
        $this->redirect('index');
      }
    
      // 名稱沒輸入直接退回
      if( empty( trim($_POST['cname']) ) || !isset($_POST['cname'])){

        Yii::app()->session['error_msg'] = array(array('名稱不可為空值'));
        $this->redirect(Yii::app()->createUrl('device/positionnew'));
        exit;
      
      }

      $tmpser = new DeviceService();
      $newres = $tmpser -> create( $_POST['cname'] );

      if( $newres[0] === true ){
        Yii::app()->session['success_msg'] = $newres[1];
      }else{
        Yii::app()->session['error_msg'] = $newres[1];
      }

      $this->redirect(Yii::app()->createUrl('device/positionnew'));
      exit;  

    }else{

      // 導回新增頁面
      $this->redirect(Yii::app()->createUrl('labset/new'));

    }    
  }

  // 更新分類
  public function Actionupdate(){

    $tmpid = Yii::app()->getRequest()->getParam('id');

    if( empty( trim($tmpid) ) ){
      $this->redirect(Yii::app()->createUrl('device/list'));
    }
    
    $tmpser  = new DeviceService();
   
    $datas  = $tmpser -> get_one_device( $tmpid );
    $grp    = $tmpser -> getallposition();

    $numbers = Common::numbers(100);//可用年限選單
    $device_status = DevicestatusService::findDevicestatus();//儀器狀態

    $this->render('update',['datas'=>$datas,'groups'=>$grp,'numbers'=>$numbers,'device_status'=>$device_status]);

  }

  // 更新分類動作
  public function Actionupdatedo(){
    // 如果是post才執行
  	if( Yii::app()->request->isPostRequest ){

      if (!CsrfProtector::comparePost()) {
        $this->redirect('index');
      }

      // 名稱沒輸入直接退回
      /*
      if( empty( trim($_POST['cname']) ) || !isset($_POST['cname'])){

        Yii::app()->session['error_msg'] = array(array('儀器名稱不可為空值'));
        $this->redirect(Yii::app()->createUrl("device/update/id/".$_POST['cid']));
        exit;
      
      }
      if( empty( trim($_POST['code']) ) || !isset($_POST['code'])){

        Yii::app()->session['error_msg'] = array(array('儀器代碼不可為空值'));
        $this->redirect(Yii::app()->createUrl("device/update/id/".$_POST['cid']));
        exit;
      
      } */     

      $tmpser = new DeviceService();

        $inputs['id'] = filter_input(INPUT_POST, 'id');
        $inputs['purchase_date'] = filter_input(INPUT_POST, 'purchase_date');
        $inputs['available_year'] = filter_input(INPUT_POST, 'available_year');
        $inputs['codenum'] = filter_input(INPUT_POST, 'codenum');
        $inputs['name'] = filter_input(INPUT_POST, 'name');
        $inputs['en_name'] = filter_input(INPUT_POST, 'en_name');
        $inputs['name'] = filter_input(INPUT_POST, 'name');
        $inputs['position'] = filter_input(INPUT_POST, 'position');
        $inputs['status'] = filter_input(INPUT_POST, 'status');
        $inputs['attention_item'] = filter_input(INPUT_POST, 'attention_item');
        $inputs['ip'] = filter_input(INPUT_POST, 'ip');
        $inputs['station'] = filter_input(INPUT_POST, 'station');

      $updres = $tmpser -> update($inputs);
      
      if( $updres[0] === true ){

        Yii::app()->session['success_msg'] = $updres[1];

      }else{

      	Yii::app()->session['error_msg'] = $updres[1];

      }
      
      $this->redirect(Yii::app()->createUrl("device/update/id/".$inputs['id']));
     
  	}else{

  	  $this->redirect(Yii::app()->createUrl('device/list'));

  	} 
  }
  
  // 刪除
  public function Actiondelete(){


  	if( Yii::app()->request->isPostRequest ){

      if (!CsrfProtector::comparePost()) {
        $this->redirect('index');
      }

      if(  empty( trim($_POST['id']) ) || !isset($_POST['id']) ){

      	$this->redirect(Yii::app()->createUrl('device/list'));
      
      }

      $tmpser = new DeviceService();
      $delres = $tmpser -> del( $_POST['id'] );

      $this->redirect(Yii::app()->createUrl('device/list'));

    }else{

      $this->redirect(Yii::app()->createUrl('device/list'));

    }

  }
}
?>