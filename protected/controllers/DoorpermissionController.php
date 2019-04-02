<?php

class DoorpermissionController extends Controller
{
    public $layout = "//layouts/back_end";

    public function actionTime_group_list()
    {
        $service = new DoorpermissionService();
        $model = $service->findTimePermissionAll();
        $this->render('time_group_list', ['model' => $model]);
    }

    public function actionTime_group_update($id)
    {

        $service = new DoorpermissionService();
        $model = $service->findTimePermissionById($id);
        $this->render('time_group_update', ['model' => $model]);
    }

    public function actionTime_group_create()
    {
        $this->render('time_group_create');
    }

    public function actionTime_group_create_form()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === "POST") {
            $this->doPostCreate();
        } else {
            echo '異常操作！！！！';
        }
    }

    private function doPostCreate()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $name = !empty($_POST["name"]) ? $_POST["name"] : null;
        if($name===null){
            Yii::app()->session['error_msg'] =  array(array('時段名稱未設定或空白'));
            $this->redirect('time_group_create');
        }

        $weeks = !empty($_POST["weeks"]) ? $_POST["weeks"] : null;
        if($weeks===null){
            Yii::app()->session['error_msg'] =  array(array('星期未勾選'));
            $this->redirect('time_group_create');
        }

        $inputs['name'] = $name;
        $inputs['weeks'] = $weeks;
        $inputs['start_hors'] = filter_input(INPUT_POST, 'start_hors');
        $inputs['start_minute'] = filter_input(INPUT_POST, 'start_minute');
        $inputs['end_hors'] = filter_input(INPUT_POST, 'end_hors');
        $inputs['end_minute'] = filter_input(INPUT_POST, 'end_minute');

        //進階判斷
        $start =  strtotime(date("Y-m-d").' '.$inputs['start_hors'].':'.$inputs['start_minute']);
        $end =  strtotime(date("Y-m-d").' '.$inputs['end_hors'].':'.$inputs['end_minute']);

        if($start>=$end){
            Yii::app()->session['error_msg'] =  array(array('新增失敗：開始時間不可「大於等於」結束時間'));
            $this->redirect('time_group_create');
        }

        if($weeks===null){
            Yii::app()->session['error_msg'] =  array(array('星期未勾選'));
            $this->redirect('time_group_create');
        }

        $service = new DoorpermissionService();
        $model   = $service->create($inputs);


        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
            $this->redirect('time_group_list');
            return;
        } else {
            Yii::app()->session['success_msg'] = '新增門禁時段設定成功';
            $this->redirect('time_group_list');
            return;
        }

    }


    public function actiontime_group_delete()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            $id = $_POST['id'];

            $model = Doorpermission::model()->findByPk($id);

            if ($model !== null) {
                $model->delete();
                $this->redirect('time_group_list');
            }
        } else {
            $this->redirect('time_group_list');
        }
    }


    public function actionTime_group_update_form()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === "POST") {
            $this->doPostUpdate();
        } else {
           echo '異常操作！！！！';
        }
    }



    private function doPostUpdate()
    {
        if (!CsrfProtector::comparePost()){
            $this->redirect('index');
        }
        $inputs['id'] = filter_input(INPUT_POST, 'id');

        $name = !empty($_POST["name"]) ? $_POST["name"] : null;
        if($name===null){
            Yii::app()->session['error_msg'] =  array(array('時段名稱未設定或空白'));
            $this->redirect('time_group_update/'.$inputs['id']);
        }

        $weeks = !empty($_POST["weeks"]) ? $_POST["weeks"] : null;
        if($weeks===null){
            Yii::app()->session['error_msg'] =  array(array('星期未勾選'));
            $this->redirect('time_group_update/'.$inputs['id']);
        }

        $inputs['name'] = $name;
        $inputs['weeks'] = $weeks;
        $inputs['start_hors'] = filter_input(INPUT_POST, 'start_hors');
        $inputs['start_minute'] = filter_input(INPUT_POST, 'start_minute');
        $inputs['end_hors'] = filter_input(INPUT_POST, 'end_hors');
        $inputs['end_minute'] = filter_input(INPUT_POST, 'end_minute');

        //進階判斷
        $start =  strtotime(date("Y-m-d").' '.$inputs['start_hors'].':'.$inputs['start_minute']);
        $end =  strtotime(date("Y-m-d").' '.$inputs['end_hors'].':'.$inputs['end_minute']);

        if($start>=$end){
            Yii::app()->session['error_msg'] =  array(array('修改失敗：開始時間不可「大於等於」結束時間'));
            $this->redirect('time_group_update/'.$inputs['id']);
        }


        $id = str_pad($inputs['id'],2," ",STR_PAD_RIGHT);//兩個字元
        $time = '';
     //   var_dump($weeks);
      //  exit();
        foreach(Common::st_weeks() as $key=> $value){
            if(in_array($key,$weeks)){
                $time .= $inputs['start_hors'].':'.$inputs['start_minute'].$inputs['end_hors'].':'. $inputs['end_minute'];
            }else{
                $time.='00:0000:0000:0000:00';
            }
        }
        $data = $id.'Group'.$id.'             00 '.$time.'00:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:0000:00';//更新的資料




        if($this->st_change_door_time($inputs['id'],$data)){

            $download_service = new StcardService();
            $card_download_res = $download_service->st_card_download();
            if($card_download_res==false){
                Yii::app()->session['success_msg'] = 'ST通知上傳失敗';
                exit();
            }

            $service = new DoorpermissionService();
            $model   = $service->update($inputs);



            if ($model->hasErrors()) {
                Yii::app()->session['error_msg'] = $model->getErrors();
                $this->redirect('time_group_update/'.$inputs['id']);
                return;
            } else {
                Yii::app()->session['success_msg'] = '更新門禁時段設定成功';
                $this->redirect('time_group_update/'.$inputs['id']);
                return;
            }
        }else{
            Yii::app()->session['success_msg'] = '寫入ST時段失敗';
            $this->redirect('time_group_update/'.$inputs['id']);
            return;
        }


    }

    /*
     * 改變門時段設定
     * -----------------------------------------------------------------
     * 改變st中,帶入新的門時段
     *
     */

    public function st_change_door_time($id,$data){
        header ('Content-Type: text/html; charset=big5');
        //$official = "/Applications/XAMPP/xamppfiles/htdocs/chingda/TimeZone_V2.st";
        //$official2 = "/Applications/XAMPP/xamppfiles/htdocs/chingda/CardInfo2.st";

        $official ="C:/ST/TimeZone_V2.st";
        if( file_exists($official) ){

            require ( "RandomAccessFile.php" ) ;

            if  ( php_sapi_name ( )  !=  'cli' ){
                echo "<pre>" ;
            }
            $random_file	=  $official ;
            $rf = new RandomAccessFile($random_file, 335) ;
            $rf -> Open () ;
          //  $rf -> Copy ( 0, 100, 20 ) ;
          //  $rf -> Write ( (int)$id-1, $data );

          $result  = $rf -> Write ( (int)$id-1, $data );
          return $result;


         //   exit();

            // Display data
            // echo "HEADER DATA  : [{$rf -> Header}]\n" ;
            // echo "HEADER SIZE  : {$rf -> HeaderSize}\n" ;
            // echo "RECORD COUNT : " . count ( $rf ) . "\n" ;

            /*
            for  ( $i = 0 ; $i  <  count ( $rf ) ; $i ++ )
                //echo strlen($rf [$i])."\n";
                echo "RECORD #" . ( $i + 1 ) . " : [{$rf [$i]}]\n" ;
            */



        }
       }

}