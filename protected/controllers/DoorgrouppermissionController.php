<?php
/**
 * @author Neil Kuo
 * @copyright Neil Kuo
 * @since 2015-07-01
 * @return 網站後台-群組(設定登入身份)資料控制與傳遞
 */
class DoorgrouppermissionController extends Controller
{

    public $layout = "//layouts/back_end";

    protected function beforeAction($action)
    {
        return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
    }

	public function actionIndex() {

        $groupService = new DoorgrouppermissionService();

        $datas = $groupService->findDoorgrouppermissions();

        $this->render('index', ['datas'=>$datas]);
	}

	public function actionCreate()
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostCreate() : $this->doGetCreate();
	}

    private function doPostCreate()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $input['group_number'] = filter_input(INPUT_POST, 'group_number');
        $input['group_name'] = filter_input(INPUT_POST, 'group_name');

        //if uncheck any check box, then it will be null
        $groupList = filter_input(INPUT_POST, 'group_list', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        $input['group_list'] = null;
        $tempLists = [];
        if ($groupList !== null) {
            foreach ($groupList as $lists) {
                foreach ($lists as $list) {
                    $tempLists[] = $list;
                }
            }
        }

        sort($tempLists);
        $input['group_list'] = implode(',', $tempLists);

        $groupService = new DoorgrouppermissionService();
        $groupModel = $groupService->create($input);

        if ($groupModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $groupModel->getErrors();
            $this->redirect('create');
            return;
        }

        $this -> redirect('index');
    }

    private function doGetCreate()
    {
        CsrfProtector::putToken();

        $powers = ExtPower::model()->power_list();
        $systems = ExtSystem::model()->system_list();

        $groupService = new DoorgrouppermissionService();
        $checkList = $groupService->getDoorgrouppermissionCheckList($systems, $powers);

        $data = ['powers' => $powers, 'systems'=> $systems, 'checkList' => $checkList];

        $this->render('create', $data);
        $this->clearMsg();


    }

    /**
     * 群組刪除
     *
     */
	public function actionDelete($id)
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostDelete($id) : $this->redirect(['index']);
	}

    private function doPostDelete($id)
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $groups = Doorgrouppermission::model()->findByPk($id);

        if ($groups !== null) {
            $groups -> delete();
            $this->redirect(['index']);
        }
    }


    /**
     * 群組功能更新
     *
     * @param $id
     */
	public function actionUpdate($id = null)
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostUpdate() : $this->doGetUpdate($id);
	}

    /**
     *
     */
    private function doPostUpdate()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $inputs['id'] = filter_input(INPUT_POST, 'id');
        $inputs['door_group_permission_name'] = filter_input(INPUT_POST, 'door_group_permission_name');
        $inputs['door_group_permission_number'] = filter_input(INPUT_POST, 'door_group_permission_number');

        //if uncheck any check box, then it will be null
        $groupList = filter_input(INPUT_POST, 'door_group_permission_list', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);



        $tempLists = [];
        if ($groupList !== null) {
            foreach ($groupList as $lists) {
                foreach ($lists as $list) {
                    $tempLists[] = $list;
                }
            }
        }

        sort($tempLists);
        $inputs['door_group_permission_list'] = implode(',', $tempLists);
        $st_door_group_list = explode(',',$inputs['door_group_permission_list']);

        $record_id = str_pad($inputs['id'],3,"0",STR_PAD_LEFT);//左邊補兩個字元
        $record_right_id = str_pad($inputs['id'],3," ",STR_PAD_RIGHT);//右邊邊補兩個字元

        $door = '';

        $service = new DoorService();
        $doors = $service->findDoor();

        $door_array = [];
        foreach($doors as $key=> $value){
            $door_array[] = $value->station;
        }
        sort($door_array);

        foreach($door_array as $key=> $value){
            if(in_array((int)$value,$st_door_group_list)){
                $door .= '1';
            }else{
                $door.='0';
            }
        }
        $record_door = str_pad($door,254," ",STR_PAD_RIGHT);//右邊補足255個字元

        //$door_record = '001群 組 1             0  011000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000 ';

        $data = $record_id.'DOORG '.$record_right_id.'           0  0'.$record_door.' ';

        if($this->st_change_door_group( $inputs['id'], $data)){

            $download_service = new StcardService();
            $card_download_res = $download_service->st_card_download();
            if($card_download_res==false){
                Yii::app()->session['success_msg'] = 'ST通知上傳失敗';
                exit();
            }

            $groupService = new DoorgrouppermissionService();
            $groupModel = $groupService->update($inputs);

            if ($groupModel->hasErrors()) {
                Yii::app()->session['error_msg'] = $groupModel->getErrors();
            } else {
                Yii::app()->session['success_msg'] = '門組修改成功';
            }

            $this->redirect('update/'.$inputs['id']);

        }else{
            Yii::app()->session['error_msg'] = 'ST門組寫入失敗';
        }
        $this->redirect('update/'.$inputs['id']);

    }

    private function doGetUpdate($id)
    {
        $service = new DoorService();
        $doors = $service->get_all();
        $model = Doorgrouppermission::model()->findByPk($id);

        $door_group_permission_list = explode(",", $model->door_group_permission_list);

        $groupService = new DoorgrouppermissionService();
        $checked_list = $groupService->getUpdateCheckList($doors, $door_group_permission_list);



        $data = [
            'model' => $model,
            'checked_list'=> $checked_list,
        ];

        $this -> render('update', $data);
        $this->clearMsg();
    }

    /*
     * 改變門時段設定
     * -----------------------------------------------------------------
     * 改變st中,帶入新的門時段
     *
     */
    public function st_change_door_group($id,$data)
    {
        header('Content-Type: text/html; charset=big5');
        //$official = "/Applications/XAMPP/xamppfiles/htdocs/chingda/DoorGroup.st";
        //$official2 = "/Applications/XAMPP/xamppfiles/htdocs/chingda/CardInfo2.st";
        $official ="C:/ST/DoorGroup/DoorGroup.st";
        if (file_exists($official)) {

            require ( "RandomAccessFile.php" ) ;

            if (php_sapi_name() != 'cli') {
                echo "<pre>";
            }
            $random_file = $official;
            $rf = new RandomAccessFile($random_file, 282);
            //$rf -> Open (true);//唯讀
            $rf->Open();//
            //  $rf -> Copy ( 0, 100, 20 ) ;
            //  $rf -> Write ( (int)$id-1, $data );

            $result = $rf->Write((int)$id - 1, $data);
            return $result;

        }
    }


}