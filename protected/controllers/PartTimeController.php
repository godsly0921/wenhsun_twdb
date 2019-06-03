<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/15
 * Time: 下午 11:42
 */
class PartTimeController extends Controller
{
    //public $layout = "//layouts/back_end";
    private $categorys = ["0" => "尚未使用", "1" => "正常使用", "2" => "異常", "3" => "取消排班"];

    protected function needLogin(): bool
    {
        return true;
    }


    public function actionIndex()
    {
        $service = new EmployeeService();
        $result = $service->findEmployeeId(Yii::app()->session['uid']);
        if($result==NULL){
            $accountServer = new AccountService();
            $account = $accountServer->findAccountData(Yii::app()->session['uid']);
            if($account == NULL){
                echo '沒有找到使用者，請重新登入系統';
                sleep(1);
                Yii::app()->createUrl($this->redirect('admin/login'));

            }
        }

        $part_time_employees = EmployeeService::getPTEmployee(7);

        $service = new ParttimeService();
        $part_time_empolyee_id = isset($_GET['part_time_empolyee_id'])?$_GET['part_time_empolyee_id']:$part_time_employees[0]->id;
        $model = $service->findPartTimeAll($part_time_empolyee_id);

        $this->render('index', ['model' => $model, 'part_time_empolyee_id' => $part_time_empolyee_id, 'part_time_employees' => $part_time_employees]);
    }

    public function actionGetevents()
    {
        require_once dirname(__FILE__) . '/../components/utils.php';
        $start_date = date('Y-01-01', strtotime(date("Y-m-d"))); //取得當年份的第一天

        $end_date = date("Y-m-d", strtotime('+365 days', strtotime(date('Y-m-d')))); //取得一年後的日期

        $today = date("Y-m-d");
        $part_time_empolyee_id = isset($_GET['part_time_empolyee_id'])?$_GET['part_time_empolyee_id']:'';
        $range_start = parseDateTime($start_date);
        $range_end = parseDateTime($end_date);


        // Parse the timezone parameter if it is present.
        $timezone = null;
        if (isset($_GET['timezone'])) {
            $timezone = new DateTimeZone($_GET['timezone']);
        }
        $service = new ParttimeService();

        $model = $service->findPartTimeStatus();

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
            $input_arrays[] = array('start' => $value->start_time, 'end' => $value->end_time, 'title' => $part_time['name'].'已排班 排班者：'.$name, 'url' => Yii::app()->createUrl('parttime/cancelPartTimeByCalendar', ['id' => $value->id]));

        }


        //開放排班的時段
        $today = strtotime($today);
        $endday = strtotime($end_date);
        $diff = ($endday - $today) / 86400;


        for ($i = 0; $i <= $diff; $i++) {
            $start = date("Y-m-d", strtotime('+' . $i . ' days', strtotime(date('Y-m-d')))); //取得今天日期
            $end = date("Y-m-d", strtotime('+' . $i . ' days', strtotime(date('Y-m-d')))); //取得今天日期

            array_push($input_arrays, array('start' => $start, 'end' => $end, 'title' => '開放排班', 'url' => Yii::app()->createUrl('parttime/create', ['part_time_empolyee_id' => $part_time_empolyee_id, 'start' => $start, 'end' => $end]), 'color' => '#66DD00'));

        }


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
                Yii::app()->session['error_msg'] = '工讀時間請填寫完整';//二個使用者可以排班同一時段 FIX
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



        $service = new ParttimeService();
        $model = $service->create($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
            $this->redirect('create');
        } else {
            //if success should clear form fields session
            foreach ($inputs as $key => $val) {
                Yii::app()->session[$key] = "";
            }
            $this->redirect(Yii::app()->createUrl('parttime/index', ['part_time_empolyee_id' => $inputs["part_time_empolyee_id"]]));
        }
    }

    private function doGetCreate()
    {

        $part_time_empolyee_id = $_GET['part_time_empolyee_id'];
        $start = $_GET['start'];
        $end = $_GET['end'];
        $part_time_employees = EmployeeService::getPTEmployee(7);
        $this->render('create', ['part_time_employees' => $part_time_employees, 'part_time_empolyee_id' => $part_time_empolyee_id, 'start' => $start, 'end' => $end]);
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
        $inputs["part_time_empolyee_id"] = filter_input(INPUT_POST, "part_time_empolyee_id");
        $inputs["start_time"] = filter_input(INPUT_POST, "start_time");
        $inputs["end_time"] = filter_input(INPUT_POST, "end_time");
        $inputs["status"] = filter_input(INPUT_POST, "status");
        $inputs["remark"] = filter_input(INPUT_POST, "remark");
        $inputs["builder"] = filter_input(INPUT_POST, "builder");
        $inputs["canceler"] = filter_input(INPUT_POST, "canceler");

        $service = new ParttimeService();
        $model = $service->updatePartTime($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '修改成功';
        }

        $this->redirect('update/' . $inputs['id']);
    }

    private function doGetUpdate($id)
    {
        $model = PartTime::model()->findByPk($id);

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

        $model = PartTime::model()->findByPk($id);

        if ($model !== null) {
            $model->delete();
            $this->redirect(['index']);
        }
    }

    public function AjaxCancelPartTime($csrf,$id){
        try{
            if (!$csrf){
                return json_encode('你非法操作系統，已記錄您的IP身分驗證有誤,請確認執行者身分');
                //exit;
            }
            //$_POST['id']=118;
            if( empty( $id ) ){

                return json_encode("沒有指定之排班");
                //exit;

            }else{
                $service = new ParttimeService();
                if(Yii::app()->session['personal']){//一般使用者
                    if (isset(Yii::app()->session['uid'])) {//確定該排班是不是使用者自己的
                        $memberServer = new MemberService();
                        $result = $memberServer->findByMemId(Yii::app()->session['uid']);
                        $use_id = $result->id;

                        $now_time = date("Y-m-d H:i:s");

                        $model = $service->findPartTimeIDByUserID($use_id, $id);

                        if (!empty($model)) {//如果不是空的找出目前排班這筆資料
                            $before_start_time = date($model['start_time'], strtotime("-1 day"));//排班開始時間前24H
                            if ($now_time < $before_start_time) {//現在時間是否小於等於 排班開始的時間-24H  判斷目前時間是否在排班開始時間24小時以內
                                $parttimeSv = new ParttimeService();
                                $res = $parttimeSv->editPartTimeStatus($id, 3);
                                if ($res == true) {
                                    return json_encode("已成功取消排班");
                                } else {
                                    return json_encode("取消排班失敗");
                                }
                            } else {
                                return json_encode("很抱歉！排班不可以在排班開始前24小時取消，所以您無法取消該筆排班請洽系統管理員");
                            }
                        } else {
                            return json_encode("很抱歉！這筆排班紀錄不是您，所以您無法取消該筆排班請洽系統管理員");
                        }
                    }
                }else{//系統管理員
                    $res = $service->editPartTimeStatus($id,3);
                    if ($res == true) {
                        return json_encode("已成功取消排班");
                    } else {
                        return json_encode("取消排班失敗");
                    }
                }
            }
        }catch(CDbException $e){
            return json_encode($e->getMessage());
            //exit();
        }
    }
    // 取消排班
    public function actioncancelPartTimeByCalendar(){
        $csrf = CsrfProtector::comparePost();
        $id = $_GET['id'];
        $result = $this->AjaxCancelPartTime(true,$id);
        $message = json_decode($result);
        if ($message === '已成功取消排班') {
            $_SESSION['success_msg'] = $message;
        } else {
            $_SESSION['error_msg'] = $message;
        }
        $service = new ParttimeService();
        $parttime = $service->findPartTimeById($id);
        $this->redirect(Yii::app()->createUrl('parttime/index?part_time_empolyee_id=' . $parttime[0]['part_time_empolyee_id']));
    }
    // 取消排班
    public function actioncancelPartTime(){
        $csrf = CsrfProtector::comparePost();
        $id = $_POST['id'];
        $result = $this->AjaxCancelPartTime($csrf,$id);
        echo $result;
        exit();
    }

}//class end