<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/15
 * Time: 下午 11:42
 */
class ReservationController extends Controller
{
    //public $layout = "//layouts/back_end";
    private $categorys = ["0" => "尚未使用", "1" => "正常使用", "2" => "異常", "3" => "取消預約"];

    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex()
    {


        /*if (!isset($_GET['device_id'])) {
            $device_id = '7';
        }

        if (isset($_GET['device_id'])) {
            $device_id = $_GET['device_id'];
        }*/


        //介面用戶化 找出目前使用者的機台權限
        $memberServer = new MemberService();
        $result = $memberServer->findByMemId(Yii::app()->session['uid']);
        if($result==NULL){
            $accountServer = new AccountService();
            $account = $accountServer->findAccountData(Yii::app()->session['uid']);
            if($account == NULL){
                echo '沒有找到使用者，請重新登入系統';
                sleep(1);
                $this->redirect('admin/login');
            }
        }
        $service = new DeviceService();
        if( Yii::app()->session['personal'] ){
            $user_permission_devices = $result->device_permission;
            $devices = $service->findDevicesPermission($user_permission_devices);
        }else{
            $devices = $service->findDevices();
        }

        $service = new ReservationService();
        $device_id = isset($_GET['device_id'])?$_GET['device_id']:$devices[0]['id'];
        $model = $service->findReservationAll($device_id);

        $this->render('index', ['model' => $model, 'device_id' => $device_id, 'devices' => $devices]);
    }

    public function actionPart_time()
    {
        $memberServer = new MemberService();
        $result = $memberServer->findByMemId(Yii::app()->session['uid']);
        if($result==NULL){
            $accountServer = new AccountService();
            $account = $accountServer->findAccountData(Yii::app()->session['uid']);
            if($account == NULL){
                echo '沒有找到使用者，請重新登入系統';
                sleep(1);
                $this->redirect('admin/login');
            }
        }

        $part_time_employees = EmployeeService::getPTEmployee(7);

        $service = new ReservationService();
        $part_time_empolyee_id = isset($_GET['part_time_empolyee_id'])?$_GET['part_time_empolyee_id']:$part_time_employees['part_time_empolyee_id'];
        $model = $service->findReservationAll($part_time_empolyee_id);

        $this->render('part_time', ['model' => $model, 'part_time_empolyee_id' => $part_time_empolyee_id, 'part_time_employees' => $part_time_employees]);
    }


    public function actionSpecial_list()
    {

        $start_time = date('Y-m-d') . ' 00:00:00';
        $end_time = date('Y-m-d') . ' 23:59:59';

        /* $start_time = '2018-05-12 00:00:00';
         $end_time = '2018-05-12 00:00:00';*/
        //  $end_time = date('Y-m-d').' 23:59:59';

        $service = new ReservationService();
        $model = $service->findReservationDayAll($start_time, $end_time);

        $service = new DeviceService();
        $devices = $service->findDevices();

        $service = new MemberService();
        $members = $service->findMemberlist();

        $this->render('special_list', ['model' => $model, 'devices' => $devices, 'categorys' => $this->categorys, 'members' => $members]);
    }

    public function actionCancel_list()
    {

        $start_time = date('Y-m-d') . ' 00:00:00';
        $end_time = date('Y-m-d') . ' 23:59:59';

        $service = new ReservationService();
        $model = $service->findReservationCancelDayAll($start_time, $end_time);

        $service = new DeviceService();
        $devices = $service->findDevices();

        $service = new MemberService();
        $members = $service->findMemberlist();

        $service = new AccountService();
        $accounts = $service->findAccounts();

        $this->render('cancel_list', ['model' => $model, 'devices' => $devices, 'categorys' => $this->categorys, 'members' => $members, 'accounts' => $accounts]);
    }

    public function actionGet_cancel_list()
    {

        $inputs["device_id"] = filter_input(INPUT_POST, "device_id");
        $inputs["start_time"] = filter_input(INPUT_POST, "start_date");
        $inputs["end_time"] = filter_input(INPUT_POST, "end_date");

        $service = new ReservationService();
        $model = $service->findReservationDayAllAndDevice($inputs);//查詢日期與取消條件

        $service = new DeviceService();
        $devices = $service->findDevices();

        $service = new MemberService();
        $members = $service->findMemberlist();

        $service = new AccountService();
        $accounts = $service->findAccounts();

        Yii::app()->session['device_id']  = $inputs["device_id"];
        Yii::app()->session['start_date'] = $inputs["start_time"];
        Yii::app()->session['end_date']   = $inputs["end_time"];

        $this->render('cancel_list', ['model' => $model, 'devices' => $devices, 'categorys' => $this->categorys, 'members' => $members, 'accounts' => $accounts]);
    }

    // 匯出excel
    function actionGet_cancel_excel()
    {

        // 查詢符合資料
        $inputs['device_id']  = Yii::app()->session['device_id'];
        $inputs['start_date'] = Yii::app()->session['start_date'];
        $inputs['end_date']   = Yii::app()->session['end_date'];

        $service = new DeviceService();
        $devices = $service->findDevices();

        $service = new MemberService();
        $members = $service->findMemberlist();

        $service = new AccountService();
        $accounts = $service->findAccounts();

        $service = new ReservationService();
        $model = $service->findReservationCancelAndConditionDayAll($inputs);//查詢日期與取消條件

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
            ->setCellValue('A1', '儀器名稱')
            ->setCellValue('B1', '預約人')
            ->setCellValue('C1', '預約開始時間')
            ->setCellValue('D1', '預約結束時間')
            ->setCellValue('E1', '是否正常使用')
            ->setCellValue('F1', '取消人員')
            ->setCellValue('G1', '取消原因')
            ->setCellValue('H1', '申請日')
            ->setCellValue('I1', '異動日');

        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i = 2;
        
        foreach ($model as $value) {

            foreach ($devices as $k => $v):
                if ($v->id == $value->device_id):
                    $devices_name = $v->name;
                endif;
            endforeach;

            foreach ($members as $k => $v):
                if ($v->id == $value->builder){
                    $member_name = $v->name;
                }else{
                    $member_name = '無資料';
                }
   
            endforeach;


            foreach ($this->categorys as $k => $v):
                if ($k == $value->status):
                    $categorys_name = $v;
                endif;
            endforeach;

            foreach ($accounts as $k => $v):
                if ($v->id == $value->canceler):
                    $account_name = $v->account_name;
                else:;
                    $account_name = '無資料';
                endif;
            endforeach;

           $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $devices_name)
                ->setCellValue('B' . $i, $member_name)
                ->setCellValue('C' . $i, $value->start_time)
                ->setCellValue('D' . $i, $value->end_time)
                ->setCellValue('E' . $i, $categorys_name)
                ->setCellValue('F' . $i, $account_name)
                ->setCellValue('G' . $i, $value->remark)
                ->setCellValue('H' . $i, $value->create_time)
                ->setCellValue('I' . $i, $value->modify_time);

            $i++;

        }
        
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-預約取消明細表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-預約取消明細表" . ".xls");
        ob_end_clean();
        header("Content-type: text/html; charset=utf-8");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=" . $filename);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    // 列印
    function actionGet_cancel_printer()
    {

        $this->layout = "back_end_cls";

        $inputs['device_id'] = Yii::app()->session['device_id'];
        $inputs['start_date'] = Yii::app()->session['start_date'];
        $inputs['end_date'] = Yii::app()->session['end_date'];



        $service = new DeviceService();
        $devices = $service->findDevices();

        $service = new MemberService();
        $members = $service->findMemberlist();

        $service = new AccountService();
        $accounts = $service->findAccounts();

        $service = new ReservationService();
        $model = $service->findReservationCancelAndConditionDayAll($inputs);//查詢日期與取消條件


        $this->render('cancel_print', ['model' => $model, 'devices' => $devices, 'categorys' => $this->categorys, 'members' => $members, 'accounts' => $accounts]);

    }


    public function actionGet_special_list()
    {
        $inputs["device_id"] = filter_input(INPUT_POST, "device_id");
        $inputs["start_time"] = filter_input(INPUT_POST, "start_time");
        $inputs["end_time"] = filter_input(INPUT_POST, "end_time");

        /* $start_time = '2018-05-12 00:00:00';
         $end_time = '2018-05-12 00:00:00';*/
        //  $end_time = date('Y-m-d').' 23:59:59';

        $service = new ReservationService();
        $model = $service->findReservationDayAllAndDevice($inputs);

        $service = new DeviceService();
        $devices = $service->findDevices();

        $service = new MemberService();
        $members = $service->findMemberlist();

        $this->render('special_list', ['model' => $model, 'devices' => $devices, 'members' => $members, 'categorys' => $this->categorys]);
    }

    public function actionList()
    {
        $start_time = date('Y-m-01') . ' 00:00:00';
        $end_time = date('Y-m-t', strtotime('now'));

        /* $start_time = '2018-05-12 00:00:00';
         $end_time = '2018-05-12 00:00:00';*/
        //  $end_time = date('Y-m-d').' 23:59:59';

        $service = new ReservationService();
        $model = $service->findReservationDayAll($start_time, $end_time);

        $service = new DeviceService();
        $devices = $service->findDevices();

        $memberSer = new MemberService();
        $memberAll = $memberSer->findMemberlist();

        $accountServer = new AccountService();
        $accountAll = $accountServer->findAccounts();

        $this->render('list', ['model' => $model, 'devices' => $devices ,'members'=>$memberAll, 'accounts' => $accountAll ]);
    }

    public function actionGet_day_list()
    {
        $date = filter_input(INPUT_POST, "date");
        $start_time = $date . ' 00:00:00';
        $end_time = $date . ' 23:59:59';

        /*var_dump($start_time);
        var_dump($end_time);
        exit();*/

        /* $start_time = '2018-05-12 00:00:00';
         $end_time = '2018-05-12 00:00:00';*/
        //  $end_time = date('Y-m-d').' 23:59:59';

        $service = new ReservationService();
        $model = $service->findReservationDayAll($start_time, $end_time);

        $service = new DeviceService();
        $devices = $service->findDevices();

        $service = new MemberService();
        $members = $service->findMemberlist();
 
        $this->render('list', ['model' => $model, 'devices' => $devices, 'members' => $members]);
    }


    public function actionGetevents()
    {

        //--------------------------------------------------------------------------------------------------
        // This script reads event data from a JSON file and outputs those events which are within the range
        // supplied by the "start" and "end" GET parameters.
        //
        // An optional "timezone" GET parameter will force all ISO8601 date stings to a given timezone.
        //
        // Requires PHP 5.2.0 or higher.
        //--------------------------------------------------------------------------------------------------

        // Require our Event class and datetime utilities
        //require dirname(__FILE__) . '/utils.php';
        require_once dirname(__FILE__) . '/../components/utils.php';

        // Short-circuit if the client did not give us a date range.
        /*  if (!isset($_GET['start']) || !isset($_GET['end'])) {//設定預設開始時間
              die("Please provide a date range.");
          }*/

        $start_date = date('Y-01-01', strtotime(date("Y-m-d"))); //取得當年份的第一天

        $end_date = date("Y-m-d", strtotime('+365 days', strtotime(date('Y-m-d')))); //取得一年後的日期

        $today = date("Y-m-d");

        //$_GET['start'] = '2018-05-01';
        //$_GET['end']  = '2018-05-31';
        // $device_id = 6;
        $part_time_empolyee_id = isset($_GET['part_time_empolyee_id'])?$_GET['part_time_empolyee_id']:'';

        // Parse the start/end parameters.
        // These are assumed to be ISO8601 strings with no time nor timezone, like "2013-12-29".
        // Since no timezone will be present, they will parsed as UTC.
        $range_start = parseDateTime($start_date);
        $range_end = parseDateTime($end_date);


        // Parse the timezone parameter if it is present.
        $timezone = null;
        if (isset($_GET['timezone'])) {
            $timezone = new DateTimeZone($_GET['timezone']);
        }

        // Read and parse our events JSON file into an array of event data arrays.

        /* $json = file_get_contents(dirname(__FILE__) . '/../components/events.json');
         $input_arrays = json_decode($json, true);*/

        $service = new ReservationService();
        //if($part_time_empolyee_id != ''){
        //    $model = $service->findReservationAll($part_time_empolyee_id);
        //}else{
        $model = $service->findReservationStatus();
        //}

        //儀器預約資料表
        $input_arrays = array();

        foreach ($model as $key => $value) {
            $part_time = EmployeeService::findEmployeeById($value->part_time_empolyee_id);
            if($value->builder_type){
                $service = new MemberService();
                $members = $service->findByMemId($value->builder);
                $name = $members['name'];


            }else{
                $service = new AccountService();
                $members = $service->findAccountData($value->builder);
                $name =$members['account_name'];
            }
            $input_arrays[] = array('start' => $value->start_time, 'end' => $value->end_time, 'title' => $part_time['name'].'已排班 排班者：'.$name, 'url' => Yii::app()->createUrl('reservation/cancelReservationByCalendar', ['id' => $value->id]));

        }


        //儀器關閉資料表
        $service = new DevcloseService();
        $model = $service->findDevicCloseAll($device_id);

        foreach ($model as $value) {//查系統管理員
            $clsreason_msg = '';
            $service = new AccountService();
            $model = $service->findAccountData($value['builder']);

            if($model!=NULL){
                $input_arrays[] = array('start' => $value['startc'], 'end' => $value['endc'], 'title' => '儀器關閉 管理者：' . $model->account_name.' 關閉原因：'.$value['rname'], 'color' => '#FF0000');
            }else{
                $input_arrays[] = array('start' => $value['startc'], 'end' => $value['endc'], 'title' => '儀器關閉 管理者帳號已移除', 'color' => '#FF0000');
            }
        }


        //開放預約的時段
        $today = strtotime($today);
        $endday = strtotime($end_date);
        $diff = ($endday - $today) / 86400;


        for ($i = 0; $i <= $diff; $i++) {
            $start = date("Y-m-d", strtotime('+' . $i . ' days', strtotime(date('Y-m-d')))); //取得今天日期
            $end = date("Y-m-d", strtotime('+' . $i . ' days', strtotime(date('Y-m-d')))); //取得今天日期

            array_push($input_arrays, array('start' => $start, 'end' => $end, 'title' => '開放排班', 'url' => Yii::app()->createUrl('reservation/create', ['part_time_empolyee_id' => $part_time_empolyee_id, 'start' => $start, 'end' => $end]), 'color' => '#66DD00'));

        }


        // Accumulate an output array of event data arrays.
        $output_arrays = array();
        foreach ($input_arrays as $array) {

            // Convert the input array into a useful Event object
            $event = new Event($array, $timezone);

            // If the event is in-bounds, add it to the output
            if ($event->isWithinDayRange($range_start, $range_end)) {
                $output_arrays[] = $event->toArray();
            }
        }

        echo json_encode($output_arrays);
    }


    public function actionCreate()
    {

        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostCreate() : $this->doGetCreate();
    }

    private function doPostCreate()
    {
        if (!CsrfProtector::comparePost()) {
            $this->redirect('index');
        }


        if(!isset($_POST['part_time_empolyee_id']) || !isset($_POST['start_date']) || !isset($_POST['start_hour']) || !isset($_POST['start_minute']) || !isset($_POST['end_date']) || !isset($_POST['end_hour']) || !isset($_POST['end_minute']) ){
            Yii::app()->session['error_msg'] = '工讀時間請填寫完整';//
            $this->redirect('part_time');
        }

        if(empty($_POST['part_time_empolyee_id']) || empty($_POST['start_date']) || empty($_POST['start_hour']) | empty($_POST['start_minute']) || empty($_POST['end_date']) || empty($_POST['end_hour']) || empty($_POST['end_minute'])){
                Yii::app()->session['error_msg'] = '工讀時間請填寫完整';//二個使用者可以預約同一時段 FIX
                $this->redirect('index');
        }

        $inputs = [];
        $inputs["part_time_empolyee_id"] = filter_input(INPUT_POST, "part_time_empolyee_id");
        $inputs["start_date"] = filter_input(INPUT_POST, "start_date");
        $inputs["start_hour"] = filter_input(INPUT_POST, "start_hour");
        $inputs["start_minute"] = filter_input(INPUT_POST, "start_minute");



        $inputs["end_date"] = filter_input(INPUT_POST, "end_date");
        $inputs["end_hour"] = filter_input(INPUT_POST, "end_hour");
        $inputs["end_minute"] = filter_input(INPUT_POST, "end_minute");

        $start_time = $inputs['start_date'] . " " . $inputs['start_hour'].":".$inputs['start_minute'].":00";
        $end_time = $inputs['end_date'] . " " . $inputs['end_hour'].":".$inputs['end_minute'].":00";
        $inputs['start_date_time'] = $start_time;
        $inputs['end_date_time'] = $end_time;




        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }



        $service = new ReservationService();
        $model = $service->create($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
            $this->redirect('create');
        } else {
            //if success should clear form fields session
            foreach ($inputs as $key => $val) {
                Yii::app()->session[$key] = "";
            }
            $this->redirect(Yii::app()->createUrl('reservation/part_time', ['part_time_empolyee_id' => $inputs["part_time_empolyee_id"]]));
        }
    }

    private function doGetCreate()
    {

        $device_id = $_GET['device_id'];
        $start = $_GET['start'];
        $end = $_GET['end'];
        $part_time_employees = EmployeeService::getPTEmployee(7);
        $this->render('create', ['part_time_employees' => $part_time_employees, 'device_id' => $device_id, 'start' => $start, 'end' => $end]);
        $this->clearMsg();

    }

    /**
     * @param $id
     */
    public function actionUpdate($id = null)
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostUpdate() : $this->doGetUpdate($id);

    }

    private function doPostUpdate()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $inputs = [];
        $inputs["id"] = filter_input(INPUT_POST, "id");
        $inputs["device_id"] = filter_input(INPUT_POST, "device_id");
        $inputs["start_time"] = filter_input(INPUT_POST, "start_time");
        $inputs["end_time"] = filter_input(INPUT_POST, "end_time");
        $inputs["status"] = filter_input(INPUT_POST, "status");
        $inputs["remark"] = filter_input(INPUT_POST, "remark");
        $inputs["builder"] = filter_input(INPUT_POST, "builder");
        $inputs["canceler"] = filter_input(INPUT_POST, "canceler");

        $service = new ReservationService();
        $model = $service->updateReservation($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '修改成功';
        }

        $this->redirect('update/' . $inputs['id']);
    }

    private function doGetUpdate($id)
    {
        $model = Reservation::model()->findByPk($id);

        $service = new DeviceService();
        $devices = $service->findDevices();

        $service = new MemberService();
        $members = $service->findMemberlist();

        $service = new AccountService();
        $accounts = $service->findAccounts();

        if ($model !== null) {

            $this->render('update', ['model' => $model, 'categorys' => $this->categorys, 'devices' => $devices, 'members' => $members, 'accounts' => $accounts]);
            $this->clearMsg();
        } else {
            $this->redirect(Yii::app()->createUrl('index'));
        }
    }

    /**
     * 聯絡資訊刪除
     */
    public function actionDelete()
    {
        ($_SERVER['REQUEST_METHOD'] === 'POST') ? $this->doPostDelete() : $this->redirect(['index']);
    }

    private function doPostDelete()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $id = filter_input(INPUT_POST, 'id');

        $model = Reservation::model()->findByPk($id);

        if ($model !== null) {
            $model->delete();
            $this->redirect(['index']);
        }
    }

    public function AjaxCancelReservation($csrf,$id){
        try{
            if (!$csrf){
                return json_encode('你非法操作系統，已記錄您的IP身分驗證有誤,請確認執行者身分');
                //exit;
            }
            //$_POST['id']=118;
            if( empty( $id ) ){

                return json_encode("沒有指定之預約");
                //exit;

            }else{
                $service = new ReservationService();
                if(Yii::app()->session['personal']){//一般使用者
                    if (isset(Yii::app()->session['uid'])) {//確定該預約是不是使用者自己的
                        $memberServer = new MemberService();
                        $result = $memberServer->findByMemId(Yii::app()->session['uid']);
                        $use_id = $result->id;

                        $now_time = date("Y-m-d H:i:s");

                        $model = $service->findReservationIDByUserID($use_id, $id);

                        if (!empty($model)) {//如果不是空的找出目前預約這筆資料
                            $before_start_time = date($model['start_time'], strtotime("-1 day"));//預約開始時間前24H
                            if ($now_time < $before_start_time) {//現在時間是否小於等於 預約開始的時間-24H  判斷目前時間是否在預約開始時間24小時以內
                                $reservationSv = new ReservationService();
                                $res = $reservationSv->editReservationStatus($id, 3);
                                if ($res == true) {
                                    return json_encode("已成功取消預約");
                                } else {
                                    return json_encode("取消預約失敗");
                                }
                            } else {
                                return json_encode("很抱歉！預約不可以在預約開始前24小時取消，所以您無法取消該筆預約請洽系統管理員");
                            }
                        } else {
                            return json_encode("很抱歉！這筆預約紀錄不是您，所以您無法取消該筆預約請洽系統管理員");
                        }
                    }
                }else{//系統管理員
                    $res = $service->editReservationStatus($id,3);
                    if ($res == true) {
                        return json_encode("已成功取消預約");
                    } else {
                        return json_encode("取消預約失敗");
                    }
                }
            }
        }catch(CDbException $e){
            return json_encode($e->getMessage());
            //exit();
        }
    }
    // 取消預約
    public function actioncancelReservationByCalendar(){
        $csrf = CsrfProtector::comparePost();
        $id = $_GET['id'];
        $result = $this->AjaxCancelReservation(true,$id);
        $message = json_decode($result);
        if ($message === '已成功取消預約') {
            $_SESSION['success_msg'] = $message;
        } else {
            $_SESSION['error_msg'] = $message;
        }
        $service = new ReservationService();
        $reservation = $service->findReservationById($id);
        $this->redirect(Yii::app()->createUrl('reservation/index?device_id=' . $reservation[0]['device_id']));
    }
    // 取消預約
    public function actioncancelReservation(){
        $csrf = CsrfProtector::comparePost();
        $id = $_POST['id'];
        $result = $this->AjaxCancelReservation($csrf,$id);
        echo $result;
        exit();
    }

}//class end