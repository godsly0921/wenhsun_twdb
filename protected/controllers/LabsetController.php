<?php
class LabsetController extends Controller{

  // layout
  public $layout = "//layouts/back_end";
  
  // 登入驗證
  protected function beforeAction($action){
  
    return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
  }

  // 預設頁面
  public function Actionlist(){

    $temser = new UsergrpService();
    $datas  = $temser -> getall(); 

    $this->render('list',['datas'=>$datas]);
  
  }

  // 新增頁面
  public function Actionnew(){
    
    $temser = new UsergrpService();
    $grp    = $temser -> getall();

    $this->render('new',['groups'=>$grp]);

  }

  // 新增動作
  public function Actionnewdo(){
    
    // 如果是post才執行
  	if( Yii::app()->request->isPostRequest ){
      

      if (!CsrfProtector::comparePost()) {
        $this->redirect('index');
      }
      
      // 名稱沒輸入直接退回
      if( empty( trim($_POST['cname']) ) || !isset($_POST['cname'])){

        Yii::app()->session['error_msg'] = array(array('名稱不可為空值'));
        $this->redirect(Yii::app()->createUrl('labset/new'));
        exit;
      
      }

      $tmpser = new UsergrpService();
      $newres = $tmpser -> create( $_POST['cname'] , $_POST['parents'] );

      if( $newres[0] === true ){
        Yii::app()->session['success_msg'] = $newres[1];
      }else{
        Yii::app()->session['error_msg'] = array( array($newres[1]) );
      }

      $this->redirect(Yii::app()->createUrl('labset/new'));
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
      $this->redirect(Yii::app()->createUrl('labset/list'));
    }
    
    $tmpser = new UsergrpService();
    $datas  = $tmpser -> get_one_class( $tmpid );
    $grp    = $tmpser -> getall();
    $this->render('update',['datas'=>$datas,'groups'=>$grp]);

  }

  // 更新分類動作
  public function Actionupdatedo(){
    // 如果是post才執行
  	if( Yii::app()->request->isPostRequest ){

      if (!CsrfProtector::comparePost()) {
        $this->redirect('index');
      }

      // 名稱沒輸入直接退回
      if( empty( trim($_POST['cname']) ) || !isset($_POST['cname'])){

        Yii::app()->session['error_msg'] = array(array('名稱不可為空值'));
        $this->redirect(Yii::app()->createUrl("labset/update/id/".$_POST['cid']));
        exit;
      
      }
      
      $tmpser = new UsergrpService();
      $updres = $tmpser -> update( $_POST['cname'] , $_POST['parents'] , $_POST['cid']);
      
      if( $updres[0] === true ){

        Yii::app()->session['success_msg'] = $updres[1];

      }else{

      	Yii::app()->session['error_msg'] = array(array($updres[1]));

      }
      
      $this->redirect(Yii::app()->createUrl("labset/update/id/".$_POST['cid']));
     
  	}else{

  	  $this->redirect(Yii::app()->createUrl('labset/list'));

  	} 
  }
  
  // 刪除
  public function Actiondelete(){

  	if( Yii::app()->request->isPostRequest ){

      if (!CsrfProtector::comparePost()) {
        $this->redirect('index');
      }
      
      if(  empty( trim($_POST['id']) ) || !isset($_POST['id']) ){

      	$this->redirect(Yii::app()->createUrl('labset/list'));
      
      }

      $tmpser = new UsergrpService();
      $delres = $tmpser -> del( $_POST['id'] );

      $this->redirect(Yii::app()->createUrl('labset/list'));

    }else{

      $this->redirect(Yii::app()->createUrl('labset/list'));

    }

  }
}
?>