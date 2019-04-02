<?php
class DevcloseController extends Controller{

  // layout
  public $layout = "//layouts/back_end";
  
  // 登入驗證
  protected function beforeAction($action){
  
    return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
  }

  // 預設頁面
  public function Actionlist(){

    $temser = new DevcloseService();
    $datas  = $temser -> getall(); 

    $this->render('list',['datas'=>$datas]);
  
  }

  // 新增頁面
  public function Actionnew(){
    
    $temser  = new DevcloseService();
    $grp     = $temser -> getallnew();
    $reasons = $temser -> getallr();
    
    $this->render('new',['groups'=>$grp,'reasons'=>$reasons]);

  }

  // 新增動作
  public function Actionnewdo(){
    

    // 如果是post才執行
  	if( Yii::app()->request->isPostRequest ){
      

      if (!CsrfProtector::comparePost()) {
        $this->redirect('index');
      }
      
      /*避免時間段空值*/
      if( empty(trim($_POST['startd'])) || empty(trim($_POST['startt'])) || empty(trim($_POST['endd'])) || empty(trim($_POST['endt'])) ){

          Yii::app()->session['error_msg'] = array(array('請確實填寫日期時間'));
          $this->redirect(Yii::app()->createUrl('devclose/new'));
          exit;
      }
      
      $startc = $_POST['startd']." ".$_POST['startt'];
      $endc = $_POST['endd']." ".$_POST['endt'];
      
      if( strtotime($endc) < strtotime($startc) ){
          Yii::app()->session['error_msg'] = array(array('結束時間不可再開始時間之前'));
          $this->redirect(Yii::app()->createUrl('devclose/new'));
          exit;
      }
     

      /* 找時間區間 */
      $tmpser  = new DevcloseService();
      $isexist = $tmpser ->checkexist( $_POST['dev'], $startc );
      
      if($isexist){
        Yii::app()->session['error_msg'] = array(array('此時段已被設定'));
        $this->redirect(Yii::app()->createUrl('devclose/new'));
        exit;
      }

      $newres = $tmpser -> createdev($_POST['dev'] , $_POST['reason'], $startc , $endc );

      if( $newres[0] === true ){
        Yii::app()->session['success_msg'] = $newres[1];
      }else{
        Yii::app()->session['error_msg']   = $newres[1];
      }

      $this->redirect(Yii::app()->createUrl('devclose/new'));
      exit;  
      
  	}else{

      // 導回新增頁面
  	  $this->redirect(Yii::app()->createUrl('devclose/new'));

  	}
  }

  // 新增地點
  public function Actionnewreason(){
    
    $this->render('newreason');
  }

  // 新增地點動作
  public function Actionnewreasondo(){
    if( Yii::app()->request->isPostRequest ){
      

      if (!CsrfProtector::comparePost()) {
        $this->redirect('index');
      }

      $tmpser = new DevcloseService();
      $newres = $tmpser -> create( $_POST['cname'] );

      if( $newres[0] === true ){
        Yii::app()->session['success_msg'] = $newres[1];
      }else{
        Yii::app()->session['error_msg'] = $newres[1];
      }

      $this->redirect(Yii::app()->createUrl('devclose/newreason'));
      exit;  

    }else{

      // 導回新增頁面
      $this->redirect(Yii::app()->createUrl('devclose/newreason'));

    }    
  }

  // 更新分類
  public function Actionupdate(){

    $tmpid = Yii::app()->getRequest()->getParam('id');

    if( empty( trim($tmpid) ) ){
      $this->redirect(Yii::app()->createUrl('devclose/list'));
    }
    
    $tmpser   = new DevcloseService();
    $tmpser2  = new DeviceService();
   
    $datas  = $tmpser -> get_one_device( $tmpid );
    $grp    = $tmpser -> getallr();
    $grp2   = $tmpser2 -> getall();
    $this->render('update',['datas'=>$datas,'reasons'=>$grp,'groups'=>$grp2]);

  }

  // 更新分類動作
  public function Actionupdatedo(){
    // 如果是post才執行

  	if( Yii::app()->request->isPostRequest ){

      if (!CsrfProtector::comparePost()) {
        $this->redirect('index');
      }
  
      /*避免時間段空值*/
      if( empty(trim($_POST['startd'])) || empty(trim($_POST['startt'])) || empty(trim($_POST['endd'])) || empty(trim($_POST['endt'])) ){

          Yii::app()->session['error_msg'] = array(array('請確實填寫日期時間'));
          $this->redirect(Yii::app()->createUrl('devclose/update/').'/'.$_POST['cid']);
          exit;
      }
      
      $startc = $_POST['startd']." ".$_POST['startt'];
      $endc = $_POST['endd']." ".$_POST['endt'];
      
      if( strtotime($endc) < strtotime($startc) ){
          Yii::app()->session['error_msg'] = array(array('結束時間不可再開始時間之前'));
          $this->redirect(Yii::app()->createUrl('devclose/update/').'/'.$_POST['cid']);
          exit;
      }
     

      /* 找時間區間 */
      /*$tmpser  = new DevcloseService();
      $isexist = $tmpser ->checkexist( $_POST['dev'], $startc );
      
      if($isexist){
        Yii::app()->session['error_msg'] = array(array('此時段已被設定'));
        $this->redirect(Yii::app()->createUrl('devclose/update').'/'.$_POST['cid']);
        exit;
      }*/

      $tmpser = new DevcloseService();
      $updres = $tmpser -> update($_POST['dev'] , $_POST['reason'], $startc , $endc, $_POST['cid']);
      
      if( $updres[0] === true ){

        Yii::app()->session['success_msg'] = $updres[1];

      }else{

      	Yii::app()->session['error_msg'] = $updres[1];

      }
      
      $this->redirect(Yii::app()->createUrl("devclose/update/id/".$_POST['cid']));
     
  	}else{

  	  $this->redirect(Yii::app()->createUrl('devclose/list'));

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

      $tmpser = new DevcloseService();
      $delres = $tmpser -> del( $_POST['id'] );

      $this->redirect(Yii::app()->createUrl('devclose/list'));

    }else{

      $this->redirect(Yii::app()->createUrl('devclose/list'));

    }

  }
}
?>