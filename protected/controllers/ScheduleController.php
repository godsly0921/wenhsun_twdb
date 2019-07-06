<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/15
 * Time: 下午 11:42
 */
class ScheduleController extends Controller
{
    //public $layout = "//layouts/back_end";
    private $categorys = ["0" => "尚未使用", "1" => "正常使用", "2" => "異常", "3" => "取消計畫"];

    protected function needLogin(): bool
    {
        return true;
    }

    public function actionShift_list(){
        $scheduleService = new scheduleService();
        $shift = $scheduleService->findAllScheduleShift();
        $active = $scheduleService->findAllScheduleActive();
        $this->render('shift_list', ['shift' => $shift, 'active' => $active]);
    }

    public function actionShift_create(){
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostShiftCreate() : $this->doGetShiftCreate();
    }

    public function actionShift_update($id){
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostShiftUpdate($id) : $this->doGetShiftUpdate($id);
    }
    public function ActionShift_delete(){
        $shift_id = $_POST['id'];
        $scheduleService = new scheduleService();
        $shift_delete = $scheduleService->shift_delete($shift_id);
        if( $shift_delete[0] === true ){
            Yii::app()->session['success_msg'] = $shift_delete[1];                
        }else{
            Yii::app()->session['error_msg'] = $shift_delete[1];
        }
        $this->redirect(Yii::app()->createUrl('schedule/shift_list'));
    }
    public function ActionActive_create(){
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostActiveCreate() : $this->doGetActiveCreate();
    }
    public function ActionActive_update($id){
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostActiveUpdate($id) : $this->doGetActiveUpdate($id);
    }
    public function ActionActive_delete(){
        $active_id = $_POST['id'];
        $scheduleService = new scheduleService();
        $active_delete = $scheduleService->active_delete($active_id);
        if( $active_delete[0] === true ){
            Yii::app()->session['success_msg'] = $active_delete[1];                
        }else{
            Yii::app()->session['error_msg'] = $active_delete[1];
        }
        $this->redirect(Yii::app()->createUrl('schedule/shift_list'));
    }
    public function doGetActiveUpdate($id){
        $active = Scheduleactive::model()->findByPk($id);
        $this->render('active_update', ['active' => $active]);
    }
    public function doPostActiveUpdate($id){
        if (!CsrfProtector::comparePost()) {
            $this->redirect('index');
        }
        $scheduleService = new scheduleService();
        $inputs = [];
        $inputs["active_id"] = $id;
        $inputs["active_name"] = filter_input(INPUT_POST, "active_name");
        $inputs["active_date"] = filter_input(INPUT_POST, "active_date");
        $active = $scheduleService -> active_update( $inputs );
        if( $active[0] === true ){
            Yii::app()->session['success_msg'] = $active[1];
        }else{
            Yii::app()->session['error_msg'] = $active[1];
        }
        $this->redirect(Yii::app()->createUrl('schedule/shift_list'));
    }
    public function doGetActiveCreate(){
        $this->render('active_create');
    }
    public function doPostActiveCreate(){
        if (!CsrfProtector::comparePost()) {
            $this->redirect('index');
        }
        $scheduleService = new scheduleService();
        $inputs = [];
        $inputs["active_name"] = filter_input(INPUT_POST, "active_name");
        $inputs["active_date"] = filter_input(INPUT_POST, "active_date");
        $active = $scheduleService -> active_create( $inputs );
        if( $active[0] === true ){
            Yii::app()->session['success_msg'] = $active[1];
        }else{
            Yii::app()->session['error_msg'] = $active[1];
        }
        $this->redirect(Yii::app()->createUrl('schedule/shift_list'));
    }
    public function doPostShiftCreate(){
        if (!CsrfProtector::comparePost()) {
            $this->redirect('index');
        }
        $scheduleService = new scheduleService();
        $inputs = [];
        $inputs["store_id"] = filter_input(INPUT_POST, "store_id");
        $inputs["in_out"] = filter_input(INPUT_POST, "in_out");
        $inputs["class"] = filter_input(INPUT_POST, "class");
        $inputs["is_special"] = filter_input(INPUT_POST, "is_special");
        $inputs["start_time"] = filter_input(INPUT_POST, "start_time");
        $inputs["end_time"] = filter_input(INPUT_POST, "end_time");
        $shift = $scheduleService -> shift_create( $inputs );
        if( $shift[0] === true ){
            Yii::app()->session['success_msg'] = $shift[1];
        }else{
            Yii::app()->session['error_msg'] = $shift[1];
        }
        $this->redirect(Yii::app()->createUrl('schedule/shift_list'));
    }

    public function doGetShiftCreate(){
        $this->render('shift_create');
    }

    public function doPostShiftUpdate($id){
        if (!CsrfProtector::comparePost()) {
            $this->redirect('index');
        }
        $scheduleService = new scheduleService();
        $inputs = [];
        $inputs["shift_id"] = $id;
        $inputs["store_id"] = filter_input(INPUT_POST, "store_id");
        $inputs["in_out"] = filter_input(INPUT_POST, "in_out");
        $inputs["class"] = filter_input(INPUT_POST, "class");
        $inputs["is_special"] = filter_input(INPUT_POST, "is_special");
        $inputs["start_time"] = filter_input(INPUT_POST, "start_time");
        $inputs["end_time"] = filter_input(INPUT_POST, "end_time");
        $shift = $scheduleService -> shift_update( $inputs );
        if( $shift[0] === true ){
            Yii::app()->session['success_msg'] = $shift[1];
        }else{
            Yii::app()->session['error_msg'] = $shift[1];
        }
        $this->redirect(Yii::app()->createUrl('schedule/shift_list'));
    }

    public function doGetShiftUpdate($id){
        $shift = Scheduleshift::model()->findByPk($id);
        $this->render('shift_update', ['shift' => $shift]);
    }

    public function actionIndex()
    {
        $employees = EmployeeService::findEmployeelist();

        $service = new ScheduleService();
        $empolyee_id = isset($_GET['empolyee_id'])?$_GET['empolyee_id']:$employees[0]->id;
        $model = $service->findScheduleAll($empolyee_id);

        $this->render('index', ['model' => $model, 'empolyee_id' => $empolyee_id, 'employees' => $employees]);
    }

    public function actionGetevents()
    {
        require_once dirname(__FILE__) . '/../components/utils.php';
        $start_date = date('Y-01-01', strtotime(date("Y-m-d"))); //取得當年份的第一天

        $end_date = date("Y-m-d", strtotime('+365 days', strtotime(date('Y-m-d')))); //取得一年後的日期

        $today = date("Y-m-d");
        $empolyee_id = isset($_GET['empolyee_id'])?$_GET['empolyee_id']:'';
        $range_start = parseDateTime($start_date);
        $range_end = parseDateTime($end_date);


        // Parse the timezone parameter if it is present.
        $timezone = null;
        if (isset($_GET['timezone'])) {
            $timezone = new DateTimeZone($_GET['timezone']);
        }
        $service = new ScheduleService();

        $model = $service->findByStatus(0);

        $input_arrays = array();
        $active = $service->findAllScheduleActive();
        foreach ($active as $key => $value) {
            $input_arrays[] = array('start' => $value['active_date'], 'end' => $value['active_date'], 'title' => "活動：".$value['active_name'],'color'=>'#b7d6aa');
        }
        foreach ($model as $key => $value) {
            $emplyee = EmployeeService::findEmployeeById($value->empolyee_id);
            if($value->builder_type){//1表示 員工 0表示系統管理員
                $service = new EmployeeService();
                $employee = $service->findEmployeeById($value->builder);
                $name = $employee->name;

            }else{
                $service = new AccountService();
                $members = $service->findAccountData($value->builder);
                $name =$members['account_name'];
            }
            $input_arrays[] = array('start' => $value->start_time, 'end' => $value->end_time, 'title' => $emplyee['name'].'已排班 排班者：'.$name, 'url' => Yii::app()->createUrl('schedule/cancelByCalendar', ['id' => $value->id]));

        }



        //開放排班的時段
        $today = strtotime($today);
        $endday = strtotime($end_date);
        $diff = ($endday - $today) / 86400;


        for ($i = 0; $i <= $diff; $i++) {

            $start = date("Y-m-d", strtotime('+' . $i . ' days', strtotime(date('Y-m-d')))); //取得今天日期
            $end = date("Y-m-d", strtotime('+' . $i . ' days', strtotime(date('Y-m-d')))); //取得今天日期
            if(date('w', strtotime($start)) != 1){//週一公休
                array_push($input_arrays, array('start' => $start, 'end' => $end, 'title' => '開放排班', 'url' => Yii::app()->createUrl('schedule/create', ['empolyee_id' => $empolyee_id, 'start' => $start, 'end' => $end]), 'color' => '#66DD00'));
            }
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

        $inputs = [];
        $inputs["empolyee_id"] = filter_input(INPUT_POST, "empolyee_id");
        $inputs["schedule_shift"] = filter_input(INPUT_POST, "schedule_shift");
        $shift = Scheduleshift::model()->findByPk($inputs["schedule_shift"]);
        $inputs["store_id"] = $shift->store_id;
        $inputs["in_out"] = $shift->in_out;
        $inputs["class"] = $shift->class;
        $inputs["start_time"] = $_POST['start_date']." " .$shift->start_time;
        $inputs["end_time"] = $_POST['end_date']." " .$shift->end_time;
       // $inputs["start_time"] = $shift->start_time;
        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }



        $service = new ScheduleService();
        $model = $service->schedule_create($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
            $this->redirect('shift_list');
        } else {
            //if success should clear form fields session
            foreach ($inputs as $key => $val) {
                Yii::app()->session[$key] = "";
            }
            $this->redirect(Yii::app()->createUrl('schedule/index', ['empolyee_id' => $inputs["empolyee_id"]]));
        }
    }

    private function doGetCreate()
    {
        $empolyee_id = $_GET['empolyee_id'];
        $start = $_GET['start'];
        $end = $_GET['end'];
        $employees = EmployeeService::findEmployeelist();
        $scheduleService = new scheduleService();
        $shift = $scheduleService->findAllScheduleShift();
        $shift_data = array();
        foreach ($shift as $key => $value) {
            if($value['in_out'] == '0') $in_out = "不分內外場";
            if($value['in_out'] == '1') $in_out = "內場";
            if($value['in_out'] == '2') $in_out = "外場";
            if(!$value['is_special']){               
                $shift_data[]= array(
                    'shift_id' => $value['shift_id'],
                    'store_id' => $value['store_id']==1?"一般館舍":"茶館",
                    'in_out' => $in_out,
                    'class' => $value['class'],
                    'time' => $value['start_time'] . " ~ " . $value['end_time'],
                );
            }else{
                if($value['start_date']<=$start && $value['end_date']>=$start){
                    $shift_data[]= array(
                        'shift_id' => $value['shift_id'],
                        'store_id' => $value['store_id']==1?"一般館舍":"茶館",
                        'in_out' => $in_out,
                        'class' => $value['class'],
                        'time' => $value['start_time'] . " ~ " . $value['end_time'],
                    );
                }
            }
        }
        //var_dump($shift_data);exit();
        $this->render('create', ['employees' => $employees, 'empolyee_id' => $empolyee_id, 'start' => $start, 'end' => $end, 'shift_data' => $shift_data]);
        $this->clearMsg();

    }

    public function AjaxCancelSchedule($csrf,$id){
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

                /*
                 *
                 *
                 *  //
                        $employeeService = new EmployeeService();
                        $login_result = $employeeService->findEmployeeById(Yii::app()->session['uid']);
                        $model = $service->findPartTimeIDByID($id);
                        $record_result = $employeeService->findEmployeeById($model->part_time_empolyee_id);
                        if($login_result->role < $record_result->role){
                            $res = $service->editPartTimeStatus($id,3);
                            if ($res == true) {
                                return json_encode("已成功取消排班");
                            } else {
                                return json_encode("取消排班失敗，你的權限不足");
                            }
                        }
                 *
                 */
                $service = new ScheduleService();
                if(Yii::app()->session['personal']){//一般使用者
                    if (isset(Yii::app()->session['uid'])) {//確定該排班是不是使用者自己的
                        $employeeService = new EmployeeService();
                        $result = $employeeService->findEmployeeById(Yii::app()->session['uid']);
                        $use_id = $result->id;
                        $now_time = date("Y-m-d H:i:s");
                        $model = $service->findByIDByUserID($use_id, $id);

                        if (!empty($model)) {//如果不是空的找出目前排班這筆資料
                            $before_start_time = date($model['start_time'], strtotime("-1 day"));//排班開始時間前24H
                            if ($now_time < $before_start_time) {//現在時間是否小於等於 排班開始的時間-24H  判斷目前時間是否在排班開始時間24小時以內
                                $parttimeSv = new ScheduleService();
                                $res = $parttimeSv->editScheduleStatus($id, 3);
                                if ($res == true) {
                                    return json_encode("已成功取消排班三");
                                } else {
                                    return json_encode("取消排班失敗三");
                                }
                            } else {
                                return json_encode("很抱歉！排班不可以在排班開始前24小時取消，所以您無法取消該筆排班請洽系統管理員");
                            }
                        } else {
                            $role = new GroupService();


                            $employeeService = new EmployeeService();
                            $login_result = $employeeService->findEmployeeById(Yii::app()->session['uid']);


                            $model = $service->findPartTimeIDByID($id);
                            $record_result = $employeeService->findEmployeeById($model['builder']);


                            $login_result_role = $role->groupById($login_result->role);
                            $record_result_role = $role->groupById($record_result->role);

                            if($login_result_role->group_number <= $record_result_role->group_number){
                                $res = $service->editPartTimeStatus($id,3);
                                if ($res == true) {
                                    return json_encode("已成功取消排班一");
                                } else {
                                    return json_encode("取消排班失敗一");
                                }
                            }else{
                                return json_encode("很抱歉！這筆排班紀錄不是您的且權限大於建立者，所以您無法取消該筆排班請洽系統管理員".$login_result_role->group_number .'||'.$record_result_role->group_number);
                            }

                        }
                    }
                }else{//系統管理員
                    $res = $service->editScheduleStatus($id,3);
                    if ($res == true) {
                        return json_encode("已成功取消排班二");
                    } else {
                        return json_encode("取消排班失敗二");
                    }
                }
            }
        }catch(CDbException $e){
            return json_encode($e->getMessage());
            //exit();
        }
    }
    // 取消排班
    public function actioncancelByCalendar($id){
        $csrf = CsrfProtector::comparePost();
        $id = $_GET['id'];
        $result = $this->AjaxCancelSchedule(true,$id);
        $message = json_decode($result);
        if ($message === '已成功取消排班') {
            $_SESSION['success_msg'] = $message;
        } else {
            $_SESSION['error_msg'] = $message;
        }
        $service = new EmployeeService();
        $employee = $service->findEmployeeById($id);
        //$service = new ScheduleService();
        //$parttime = $service->findPartTimeById($id);
        $this->redirect(Yii::app()->createUrl('schedule/index?part_time_empolyee_id=' . $employee[0]['id']));
    }
}//class end