<?php

class AttendancerecordController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function needLogin(): bool
    {
        return true;
    }

    public function actionReport()
    {
        $service = new EmployeeService();
        $employee = $service->findEmployeelist();//取出所有員工

        $employee_list = [];
        if(!empty($employee)){
            foreach ($employee as $key => $value) {
                $employee_list[$value->id] = $value->name.'/'.$value->user_name.'/'.$value->door_card_num;
            }
        }


        if (isset($_POST['employee'])) {
            if ($_POST[ 'employee' ] == 'all') {
                $choos_employee = "all";
            } else if($_POST[ 'employee' ] != 'all') {
                $choos_employee = $_POST['employee'];
            } else{
                $choos_employee = "all";
            }
        } else {
            $choos_employee = "all";
        }


        $service = new EmployeeService;
        //var_dump($choos_employee);
        $choos_employee = $service->getEmployee($choos_employee);

        $idarr = array();
        $cardarrs = array();
        foreach ($choos_employee as $k => $v) {
            array_push($idarr, $v->id );
            array_push($cardarrs, $v->door_card_num);

        }

        if (isset($_POST[ 'date_start' ]) && !empty($_POST[ 'date_start' ])) {

            $choose_start = $_POST[ 'date_start' ] . ' 00:00:00';

        } else {

            $choose_start = '0000-00-00 00:00:00';
        }

        if (isset($_POST[ 'date_end' ]) && !empty($_POST[ 'date_end' ])) {

            $choose_end = $_POST[ 'date_end' ] . ' 23:59:59';

        } else {

            $choose_end = date( "Y-m-d H:i:s" );
        }

        if (empty($_POST[ 'keycol' ])) {
            $_POST[ 'keycol' ] = 0;
        }
        // 抓出所有相同卡號的紀錄
        $attendance_service = new AttendancerecordService();
        $record_data = $attendance_service->get_by_condition($idarr, $choose_start, $choose_end );

        $finaldata = [];
        foreach ($record_data as $key => $value) {
                $temp['user_name'] = $value['user_name'];
                $temp['attendance_record_id'] = $value['attendance_record_id'];
                $temp['name'] = $value['name'];
                $temp['day'] = $value['day'];
                $temp['first_time'] = $value['first_time'];
                $temp['last_time'] = $value['last_time'];
                switch ($value['abnormal_type']) {
                    case "0":
                        $value['abnormal_type'] = "正常";
                        break;
                    case "1":
                        $value['abnormal_type'] = "異常";
                        break;
                    case "2":
                        $value['abnormal_type'] = "用戶已回覆，正常";
                        break;
                }
                $temp['abnormal_type'] = $value['abnormal_type'];
                $temp['abnormal'] = $value['abnormal'];
                $temp['att_create_at'] = $value['att_create_at'];
                $temp['update_at'] = $value['update_at'];

                array_push($finaldata, $temp);
        }
        // 每次找完資料都將資料存進session 方便匯出跟列印
        Yii::app()->session[ 'record_data' ] = $finaldata;

        $this->render( 'usereport', ['model' => $record_data,
            'employee_list' => $employee_list,
            'rcdata' => $finaldata
        ] );
    }

    // 匯出excel
    function actiongetexcel()
    {

        // 查詢符合資料
        $model = Yii::app()->session[ 'record_data' ];

        $service = new EmployeeService();
        $employee = $service->findEmployeelist();//取出所有員工

        $employee_list = [];
        if(!empty($employee)){
            foreach ($employee as $key => $value) {
                $employee_list[$value->id] = $value->name;
            }
        }


        error_reporting( E_ALL );
        ini_set( 'display_errors', TRUE );
        ini_set( 'display_startup_errors', TRUE );
        date_default_timezone_set( 'Europe/London' );
        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');
        /** Include PHPExcel */
        require_once dirname( __FILE__ ) . '/../components/PHPExcel.php';
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator( "文訊人資" )
            ->setLastModifiedBy( "文訊人資" )
            ->setTitle( "文訊人資" )
            ->setSubject( "文訊人資" )
            ->setDescription( "文訊人資" )
            ->setKeywords( "文訊人資" )
            ->setCategory( "文訊人資" );
        // Add some data 設定匯出欄位資料
        $objPHPExcel->setActiveSheetIndex( 0 )
            ->setCellValue( 'A1', '員工帳號' )
            ->setCellValue( 'B1', '員工姓名' )
            ->setCellValue( 'C1', '出勤日' )
            ->setCellValue( 'D1', '首筆出勤紀錄' )
            ->setCellValue( 'E1', '末筆出勤紀錄' )
            ->setCellValue( 'F1', '狀態' )
            ->setCellValue( 'G1', '說明' )
            ->setCellValue( 'H1', '建立時間' )
            ->setCellValue( 'I1', '修改時間' );


        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i = 2;
        foreach ($model as $value) {

            $objPHPExcel->setActiveSheetIndex( 0 )
                ->setCellValue( 'A' . $i, $value[ 'user_name' ] )
                ->setCellValue( 'B' . $i, $value[ 'name' ] )
                ->setCellValue( 'C' . $i, $value[ 'day' ] )
                ->setCellValue( 'D' . $i, $value[ 'first_time' ] )
                ->setCellValue( 'E' . $i, $value[ 'last_time' ] )
                ->setCellValue( 'F' . $i, $value[ 'abnormal_type' ] )
                ->setCellValue( 'G' . $i, $value[ 'abnormal' ] )
                ->setCellValue( 'H' . $i, $value[ 'att_create_at' ] )
                ->setCellValue( 'I' . $i, $value[ 'update_at' ] );
            $i++;

        }
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle( '文訊人資每日出缺勤一覽表' );
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex( 0 );

        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('文訊人資每日出缺勤一覽表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $filename = urlencode( "文訊人資每日出缺勤一覽表" . ".xlsx" );
        ob_end_clean();
        header( "Content-type: text/html; charset=utf-8" );
        header( "Content-Type: application/vnd.ms-excel" );
        header( "Content-Disposition: attachment;filename=" . $filename );
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        // $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
        $objWriter->save( 'php://output' );
        exit();

    }

    // 列印
    function actionprinter()
    {

        $this->layout = "back_end_cls";

        // 查詢符合資料
        $model = Yii::app()->session[ 'record_data' ];

        $service = new EmployeeService();
        $employee = $service->findEmployeelist();//取出所有員工

        $employee_list = [];
        if(!empty($employee)){
            foreach ($employee as $key => $value) {
                $employee_list[$value->id] = $value->name;
            }
        }
        $this->render( 'usereport_print', ['model' => $model] );


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
            $this->redirect('list');

        $inputs = [];


        $inputs["id"] = filter_input(INPUT_POST, "id");
        $inputs["reply_description"] = filter_input(INPUT_POST, "reply_description");
        $inputs["take"] = filter_input(INPUT_POST, "take");


        $service = new AttendancerecordService();
        $model = $service->update($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '修改成功';
        }

        $this->redirect('update/'.$inputs['id']);
    }

    private function doGetUpdate($id)
    {
        $model = Attendancerecord::model()->findByPk($id);


        $EmployeeService  =  new EmployeeService();
        $employee = $EmployeeService->findEmployeeById($model->employee_id);

        $data = [
            0=>'正常',
            1=>'普通傷病假',
            2=>'事假',
            3=>'公假',
            4=>'公傷病假',
            5=>'特別休假',
            6=>'產假含例假日',
            7=>'婚假',
            8=>'喪假',
            9=>'補休假',
            10=>'生理假',
            11=>'非請假(加班)',
            12=>'非請假(早退)',
            13=>'非請假(遲到加早退)',
            14=>'非請假(忘記刷卡)',
            ];


        if ($model !== null) {
            $this->render('update',['model' => $model,'data'=>$data,'employee'=> $employee]);
            $this->clearMsg();
        } else {
            $this->redirect(Yii::app()->createUrl('list'));
        }
    }


}