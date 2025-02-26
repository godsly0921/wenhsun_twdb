<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/15
 * Time: 下午 11:42
 */
class PersonalCalendarController extends Controller
{
    //public $layout = "//layouts/back_end";
    private $categorys = ["0" => "尚未使用", "1" => "正常使用", "2" => "異常", "3" => "取消計畫"];

    protected function needLogin(): bool
    {
        return true;
    }


    public function actionIndex()
    {

        $public = 'N';
        if (isset($_GET['public'])) {
            $public = $_GET['public'];
        }

        // 文訊活動權限
        $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']);
        $wenhsunActivity = false;
        foreach($session_jsons as $value) {
            if (in_array('wenhsun_activity', $value)) {
                $wenhsunActivity = true;
            }
        }

        if( Yii::app()->session['personal'] == false){//如果不是員工帳號不能用
            $this->redirect(Yii::app()->createUrl('admin/login'));
        }

        $employee = new EmployeeService();
        $choose_employee = $employee->findEmployeeById(Yii::app()->session['uid']);

        if(!empty($choose_employee)){

            $service = new PersonalCalendarService();
            $model = $service->findPersonalCalendarAll($choose_employee->id);

        }

        $this->render('index', ['model' => $model, 'employee_id' => $choose_employee->id, 'wenhsunActivity' => $wenhsunActivity, 'public' => $public]);
    }

    public function actionGetevents()
    {
        require_once dirname(__FILE__) . '/../components/utils.php';
        $start_date = date('Y-01-01', strtotime(date("Y-m-d"))); //取得當年份的第一天

        $end_date = date("Y-m-d", strtotime('+365 days', strtotime(date('Y-m-d')))); //取得一年後的日期

        $today = date("Y-m-d");
        $employee_id = isset($_GET['employee_id'])?$_GET['employee_id']:'';

        $range_start = parseDateTime($start_date);
        $range_end = parseDateTime($end_date);

        // Parse the timezone parameter if it is present.
        $timezone = null;
        if (isset($_GET['timezone'])) {
            $timezone = new DateTimeZone($_GET['timezone']);
        }
        $service = new PersonalCalendarService();

        if ($_GET['public'] == 'Y') {
            $model = $service->findCalendarOpen();
        } else {
            $model = $service->findPersonalCalendarPrivate($employee_id);
        }

        $input_arrays = array();

        foreach ($model as $key => $value) {
            if($value->builder_type == 1){//1表示 員工 0表示系統管理員
                $service = new EmployeeService();
                $employee = $service->findEmployeeById($value->employee_id);
                $name = $employee['name'];

            }elseif($value->builder_type == 0){
                $service = new AccountService();
                $members = $service->findAccountData($value->builder);
                $name =$members['account_name'];
            }

            $input_arrays[] = array('start' => $value->start_time, 'end' => $value->end_time, 'title' => $value['content'] . ' ' . ' 計畫：' .$name, 'color' =>($value->public === 'ADMIN' ? '#92005a' : '#337ab7'), 'delete' => Yii::app()->createUrl('personalcalendar/cancelPersonalCalendarByCalendar', ['id' => $value->id]));
        }




        //開放計畫的時段
        $today = strtotime($today);
        $endday = strtotime($end_date);
        $diff = ($endday - $today) / 86400;


        for ($i = 0; $i <= $diff; $i++) {
            $start = date("Y-m-d", strtotime('+' . $i . ' days', strtotime(date('Y-m-d')))); //取得今天日期
            $end = date("Y-m-d", strtotime('+' . $i . ' days', strtotime(date('Y-m-d')))); //取得今天日期

            array_push($input_arrays, array('start' => $start, 'end' => $end, 'title' => '可計畫日期', 'url' => "javascript:createCalendar('$start', '$end');", 'color' => '#66DD00'));

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

        if(!isset($_POST['employee_id']) || !isset($_POST['start_date']) || !isset($_POST['start_hour']) || !isset($_POST['start_minute']) || !isset($_POST['end_date']) || !isset($_POST['end_hour']) || !isset($_POST['end_minute'])){
            Yii::app()->session['error_msg'] = '計畫時間請填寫完整';//
            $this->redirect('index');
        }

        if(empty($_POST['employee_id']) || empty($_POST['start_date']) || empty($_POST['start_hour']) | empty($_POST['start_minute']) || empty($_POST['end_date']) || empty($_POST['end_hour']) || empty($_POST['end_minute'])){
                Yii::app()->session['error_msg'] = '計畫時間請填寫完整';//二個使用者可以計畫同一時段 FIX
                $this->redirect('index');
        }

        $inputs = [];
        $inputs["employee_id"] = filter_input(INPUT_POST, "employee_id");
        $inputs["start_date"] = filter_input(INPUT_POST, "start_date");
        $inputs["start_hour"] = filter_input(INPUT_POST, "start_hour");
        $inputs["start_minute"] = filter_input(INPUT_POST, "start_minute");



        $inputs["end_date"] = filter_input(INPUT_POST, "end_date");
        $inputs["end_hour"] = filter_input(INPUT_POST, "end_hour");
        $inputs["end_minute"] = filter_input(INPUT_POST, "end_minute");

        $start_time = $inputs['start_date'] . " " . $inputs['start_hour'].":".$inputs['start_minute'].":00";
        $end_time = $inputs['end_date'] . " " . $inputs['end_hour'].":".$inputs['end_minute'].":00";

        // 檢查時間
        if ($start_time > $end_time) {
            Yii::app()->session['error_msg'] = '開始時間不可大於結束時間';
            $this->redirect('index');
        }

        $inputs['start_date_time'] = $start_time;
        $inputs['end_date_time'] = $end_time;

        $inputs['public'] = filter_input(INPUT_POST, "public");
        $inputs['content'] = filter_input(INPUT_POST, "content");

        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }



        $service = new PersonalCalendarService();
        $model = $service->create($inputs);

        $public = 'Y';
        if ($inputs['public'] == 'PIRVATE') {
            $public = 'N';
        }

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
            $this->redirect('create');
        } else {
            //if success should clear form fields session
            foreach ($inputs as $key => $val) {
                Yii::app()->session[$key] = "";
            }
            $this->redirect(Yii::app()->createUrl('personalcalendar/index', ['employee_id' => $inputs["employee_id"], 'public' => $public]));
        }
    }

    private function doGetCreate()
    {

        $employee_id = $_GET['employee_id'];
        $start = $_GET['start'];
        $end = $_GET['end'];
        $this->render('create', ['employee_id' => $employee_id, 'start' => $start, 'end' => $end]);
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

        $service = new PersonalCalendarService();
        $model = $service->updatePersonalCalendar($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '修改成功';
        }

        $this->redirect('update/' . $inputs['id']);
    }

    private function doGetUpdate($id)
    {
        $model = PersonalCalendar::model()->findByPk($id);

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

        $model = PersonalCalendar::model()->findByPk($id);

        if ($model !== null) {
            $model->delete();
            $this->redirect(['index']);
        }
    }

    public function AjaxCancelPersonalCalendar($csrf,$id){
        try{
            if (!$csrf){
                return json_encode('你非法操作系統，已記錄您的IP身分驗證有誤,請確認執行者身分');
                //exit;
            }
            //$_POST['id']=118;
            if( empty( $id ) ){

                return json_encode("沒有指定之計畫");
                //exit;

            }else{
                $service = new PersonalCalendarService();
                if (Yii::app()->session['personal']) { //一般使用者
                    if (isset(Yii::app()->session['uid'])) {
                        $use_id = Yii::app()->session['uid'];
                        $model = $service->findPersonalCalendarIDByUserID($use_id, $id);
                        if (!empty($model)) { //如果不是空的找出目前計畫這筆資料
                            $personalcalendarSv = new PersonalCalendarService();
                            $res = $personalcalendarSv->editPersonalCalendarStatus($id, 3);
                            if ($res == true) {
                                return json_encode("已成功取消計畫");
                            } else {
                                return json_encode("取消計畫失敗");
                            }
                        } else {
                            return json_encode("很抱歉！這筆計畫紀錄不是您，所以您無法取消該筆計畫請洽系統管理員");
                        }
                    }
                }
            }
        }catch(CDbException $e){
            return json_encode($e->getMessage());
            //exit();
        }
    }
    // 取消計畫
    public function actioncancelPersonalCalendarByCalendar(){
        $csrf = CsrfProtector::comparePost();
        $id = $_GET['id'];
        $result = $this->AjaxCancelPersonalCalendar(true,$id);
        $message = json_decode($result);
        if ($message === '已成功取消計畫') {
            $_SESSION['success_msg'] = $message;
        } else {
            $_SESSION['error_msg'] = $message;
        }
        $service = new PersonalCalendarService();
        $personalcalendar = $service->findPersonalCalendarById($id);
        $public = 'Y';
        if ($personalcalendar[0]['public'] === 'PRIVATE') {
            $public = 'N';
        }
        $this->redirect(Yii::app()->createUrl('personalcalendar/index', [ 'employee_id' => Yii::app()->session['uid'], 'public' => $public]));
    }
    // 取消計畫
    public function actioncancelPersonalCalendar(){
        $csrf = CsrfProtector::comparePost();
        $id = $_POST['id'];
        $result = $this->AjaxCancelPersonalCalendar($csrf,$id);
        echo $result;
        exit();
    }

}//class end