<?php

class DevicepermissionController extends Controller
{
    public $layout = "//layouts/back_end";

    public function actionlist()
    {
        // 查詢符合資料
        $inputs['device'] = "0";
        $inputs['weeks'] = Common::week_list();
        $inputs['start_hors'] = '00';
        $inputs['start_minute'] = '00';
        $inputs['end_hors'] = '23';
        $inputs['end_minute'] = '59';


        $datas = $this->devicePermission($inputs);
        $model = $datas['model'];
        $device = $datas['device'];
        $devices = $datas['devices'];
        $members = $datas['members'];
        $data = $datas['data'];

        $this->render('list', ['model' => $model,'device'=>$device,'devices'=>$devices,'members'=>$members,'data'=>$data]);
    }

    public function actionTime_group_list()
    {
        $service = new DevicepermissionService();
        $model = $service->findTimePermissionAll();
        $this->render('time_group_list', ['model' => $model]);
    }

    public function actionTime_group_update($id)
    {

        $service = new DevicepermissionService();
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




        $service = new DevicepermissionService();
        $model   = $service->create($inputs);


        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
            $this->redirect('time_group_list');
            return;
        } else {
            Yii::app()->session['success_msg'] = '新增儀器時段設定成功';
            $this->redirect('time_group_list');
            return;
        }

    }


    public function actiontime_group_delete()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            $id = $_POST['id'];

            $model = Devicepermission::model()->findByPk($id);

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


        $service = new DevicepermissionService();
        $model   = $service->update($inputs);


        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
            $this->redirect('time_group_update/'.$inputs['id']);
            return;
        } else {
            Yii::app()->session['success_msg'] = '更新儀器時段設定成功';
            $this->redirect('time_group_update/'.$inputs['id']);
            return;
        }

    }


    // 匯出excel
    function actionGet_list_excel()
    {

        // 查詢符合資料
        $inputs['device'] = Yii::app()->session['device'];
        $inputs['weeks'] =Yii::app()->session['weeks'];
        $inputs['start_hors'] = Yii::app()->session['start_hors'];
        $inputs['start_minute'] = Yii::app()->session['start_minute'];
        $inputs['end_hors'] = Yii::app()->session['end_hors'];
        $inputs['end_minute'] = Yii::app()->session['end_minute'];
        $datas = $this->devicePermission($inputs);

        $model = $datas['model'];
        $device = $datas['device'];
        $devices = $datas['devices'];
        $members = $datas['members'];
        $data = $datas['data'];

        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');
        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');
        /** Include PHPExcel */
        require_once dirname(__FILE__) . '/../components/PHPExcel.php';
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("清大門禁系統")
            ->setLastModifiedBy("清大門禁系統")
            ->setTitle("清大門禁系統")
            ->setSubject("清大門禁系統")
            ->setDescription("清大門禁系統")
            ->setKeywords("清大門禁系統")
            ->setCategory("清大門禁系統");
        // Add some data 設定匯出欄位資料

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '機台名稱')
            ->setCellValue('B1', '使用者')
            ->setCellValue('C1', '使用星期')
            ->setCellValue('D1', '使用時段');

        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i = 2;
        foreach ($data as $value) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $value['device_name'])
                ->setCellValue('B' . $i,$value['member_name'])
                ->setCellValue('C' . $i,$value['week'])
                ->setCellValue('D' . $i,$value['permission_time']);
            $i++;
        }
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-機台使用權限明細表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-機台使用權限明細表" . ".xls");
        ob_end_clean();
        header("Content-type: text/html; charset=utf-8");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=" . $filename);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    // 列印
    function actionGet_list_printer()
    {

        $this->layout = "back_end_cls";

        $inputs['device'] = Yii::app()->session['device'];
        $inputs['weeks'] =Yii::app()->session['weeks'];
        $inputs['start_hors'] = Yii::app()->session['start_hors'];
        $inputs['start_minute'] = Yii::app()->session['start_minute'];
        $inputs['end_hors'] = Yii::app()->session['end_hors'];
        $inputs['end_minute'] = Yii::app()->session['end_minute'];

        $datas = $this->devicePermission($inputs);
        $model = $datas['model'];
        $device = $datas['device'];
        $devices = $datas['devices'];
        $members = $datas['members'];
        $data = $datas['data'];

        $this->render('print', ['model' => $model,'device'=>$device,'devices'=>$devices,'members'=>$members,'data'=>$data]);

    }


    public function actionGet_list()
    {

        $inputs['device'] = filter_input(INPUT_POST, "device");
        $weeks = !empty($_POST["weeks"]) ? $_POST["weeks"] : null;
        $inputs['weeks'] = $weeks;
        $inputs['start_hors'] = filter_input(INPUT_POST, "start_hors");
        $inputs['start_minute'] = filter_input(INPUT_POST, "start_minute");
        $inputs['end_hors'] = filter_input(INPUT_POST, "end_hors");
        $inputs['end_minute'] = filter_input(INPUT_POST, "end_minute");

        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $datas = $this->devicePermission($inputs);
        $model = $datas['model'];
        $device = $datas['device'];
        $devices = $datas['devices'];
        $members = $datas['members'];
        $data = $datas['data'];
        $this->render('list', ['model' => $model,'device'=>$device,'devices'=>$devices,'members'=>$members,'data'=>$data]);
    }

    public  function devicePermission($inputs){
        $service = new DeviceService();
        $device = $service->findDeviceList();//取出所有儀器

        $service = new DeviceService();
        $devices = $service->findDeviceListWithStation();//取出所有儀器ID key


        $memberService = new MemberService();
        $members = $memberService->findMembers();

        $service = new DevicepermissionService();
        $model = $service->findDevicepermissionByWeekTime($inputs);
        $device_permission = array();
        foreach ($model as $key => $value){
            $device_permission[$value->id] = array(
                'weeks' => $value -> weeks,
                'name' => $value -> name,
                'type' => $value -> type,
                'start_hors' => $value -> start_hors,
                'start_minute' => $value -> start_minute,
                'end_hors' => $value -> end_hors,
                'end_minute' => $value -> end_minute,
            );
        }
        $weeks = Common::weeks();
        $data = array();
        foreach ($members as $key => $value){
            if($value -> device_permission_type != ''){
                $d_p_t = json_decode($value -> device_permission_type,true);
                if($inputs['device'] != 0 && isset($d_p_t[$device[$inputs['device']]->station])){
                    $station = $device[$inputs['device']]->station;
                    if(isset($device_permission[$d_p_t[$station]])){
                        $week = json_decode($device_permission[$d_p_t[$station]]['weeks']);
                        $permission_week = array();
                        foreach ($week as $w_k => $w_v){
                            array_push($permission_week, $weeks[$w_v]);
                        }
                        $permission_week = implode(",",$permission_week);
                        $data[] = array(
                            'device_name' => $devices[$station]->name,
                            'member_name' => $value -> name,
                            'week' => $permission_week,
                            'permission_time' => $device_permission[$d_p_t[$station]]['start_hors'].':'.$device_permission[$d_p_t[$station]]['start_minute'].'~'.$device_permission[$d_p_t[$station]]['end_hors'].':'.$device_permission[$d_p_t[$station]]['end_minute']
                        );
                    }
                }else{
                    foreach ($d_p_t as $device_id => $permission){
                        if(isset($device_permission[$permission])){
                            $week = json_decode($device_permission[$permission]['weeks']);
                            $permission_week = array();
                            foreach ($week as $w_k => $w_v){
                                array_push($permission_week, $weeks[$w_v]);
                            }
                            $permission_week = implode(",",$permission_week);
                            $data[] = array(
                                'device_name' => $devices[$device_id]->name,
                                'member_name' => $value -> name,
                                'week' => $permission_week,
                                'permission_time' => $device_permission[$permission]['start_hors'].':'.$device_permission[$permission]['start_minute'].'~'.$device_permission[$permission]['end_hors'].':'.$device_permission[$permission]['end_minute']
                            );
                        }
                    }
                }
            }
        }
        return array('data' => $data, 'model' => $model, 'device' => $device, 'devices'=>$devices, 'members'=>$members);
    }
}