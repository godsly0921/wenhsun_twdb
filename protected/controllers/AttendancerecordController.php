<?php

class AttendancerecordController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function needLogin(): bool
    {
        return true;
    }

    public $fake = [
        0=>'正常',
        1=>'普通傷病假',
        2=>'事假',
        3=>'公假',
        4=>'公傷病假',
        5=>'特別休假',
        6=>'分娩假含例假日',
        7=>'婚假',
        8=>'喪假',
        9=>'補休假',
        10=>'生理假',
        11=>'非請假(加班)',
        12=>'非請假(早退)',
        13=>'非請假(遲到加早退)',
        14=>'非請假(遲到)',
        15=>'非請假(忘記刷卡)',
        16=>'陪產假',
        17=>'流產假',
        18=>'產檢假',
        ];

    public $fake_type = [
        0=>['name'=>'正常','rule'=>'說明:出缺勤正常。'],
        1=>['name'=>'普通傷病假','rule'=>'說明:未住院者，1年內合計不得超過30日。住院者，2年內合計不得超過1年。未住院傷病假與住院傷病假2年內合計不得超過1年。經醫師診斷，罹患癌症（含原位癌）採門診方式治療或懷孕期間需安胎休養者，其治療或休養期間，併入住院傷病假計算。'],
        2=>['name'=>'事假','rule'=>'說明:1年內合計不得超過14日。'],
        3=>['name'=>'公假','rule'=>'說明:依法令規定應給予公假者。'],
        4=>['name'=>'公傷病假','rule'=>'說明:因職業災害而致失能、傷害或疾病者，其治療、休養期間，給予公傷病假。未住院者，1年內合計不得超過30日。住院者，2年內合計不得超過1年。未住院傷病假與住院傷病假2年內合計不得超過1年。經醫師診斷，罹患癌症（含原位癌）採門診方式治療或懷孕期間需安胎休養者，其治療或休養期間，併入住院傷病假計算。'],
        5=>['name'=>'特別休假','rule'=>'說明:6個月至未滿1年：3日。工作滿第1年：7日。工作滿第2年：10日。工作滿第3年：14日。工作滿第4年：14日。工作滿第5年：15日。工作滿第6年：15日。工作滿第7年：15日。工作滿第8年：15日。工作滿第9年：15日。工作滿第10年：16日。工作滿第11年：17日。工作滿第12年：18日。工作滿第13年：19日。工作滿第14年：20日。工作滿第15年：21日。工作滿第16年：22日。工作滿第17年：23日。工作滿第18年：24日。工作滿第19年：25日。工作滿第20年：26日。工作滿第21年：27日。工作滿第22年：28日。工作滿第23年：29日。工作滿第24年：30日。工作滿第25年以上：30日。'],
        6=>['name'=>'分娩假含例假日','rule'=>'說明:一般勞工共 8 週（含假日）／ 公務員共 42 天（不含假日'],
        7=>['name'=>'婚假','rule'=>'說明:勞工如於104年10月18日登記結婚，自 10月8日起3個月內(即105年1月7日前)均可請休婚假，但經雇主同意者得於105年10月7日前請畢'],
        8=>['name'=>'喪假','rule'=>'說明:一、父母、養父母、繼父母、配偶喪亡者，給予喪假八日，工資照給。二、祖父母、子女、配偶之父母、配偶之養父母或繼父母喪亡者，給予喪假六日，工資照給。三、曾祖父母、兄弟姊妹、配偶之祖父母喪亡者，給予喪假三日，工資照給'],
        9=>['name'=>'補休假','rule'=>'說明:依勞動基準法施行細則第14條之1規定，「工資各項目計算方式明細」應記載加班費，至勞工如有將加班費選擇為加班補休時數，建議雇主明確記載勞工換取加班補休之時數，勞雇雙方始能比對加班費金額。'],
        10=>['name'=>'生理假','rule'=>'說明:生理假無須提出證明文件，並且前3次將不計入病假，意即不影響全勤！請假天數每月可請 1 天假期薪水半薪'],
        11=>['name'=>'非請假(加班)','rule'=>'非請假(加班)'],
        12=>['name'=>'非請假(早退)','rule'=>'非請假(早退)'],
        13=>['name'=>'非請假(遲到加早退)','rule'=>'非請假(遲到加早退)'],
        14=>['name'=>'非請假(遲到)','rule'=>'非請假(遲到)'],
        15=>['name'=>'非請假(忘記刷卡)','rule'=>'非請假(忘記刷卡)'],
        16=>['name'=>'陪產假','rule'=>'說明:一般勞工、公務員共 5 天 ／ 約聘公務員共 3 天。'],
        17=>['name'=>'流產假','rule'=>'說明:1. 女性員工妊娠六個月以上流產者，給予流產假 8 星期。2. 妊娠 3 個月以上流產者，給予流產假 4 星期。3. 妊娠 2 個月以上未滿 3 個月流產者，給予流產假 1 星期。4. 妊娠未滿 2 個月流產者，應停止工作，給予流產假 5 日。'],
        18=>['name'=>'產檢假','rule'=>'說明:5天 請假時應檢附媽媽手冊，並於產前請畢，不可延至產後，請假得以時計，產檢後應檢附產檢證明(如繳費收據、門診證明等)。'],
    ];


    public function actionReport()
    {

        if (!empty($_POST['keyword'])) {
            $keyword_selected = 1;
        } else {
            $keyword_selected = 0;
            $_POST['keyword'] = '';
        }

        // 日期
        if (isset($_POST['date_start']) && !empty($_POST['date_start'])) {

            $choose_start = $_POST['date_start'] . ' 00:00:00';

        } else {

            $choose_start = date("Y-m-d").' 00:00:00';
        }

        if (isset($_POST['date_end']) && !empty($_POST['date_end'])) {

            $choose_end = $_POST['date_end'] . ' 23:59:59';

        } else {

            $choose_end = date("Y-m-d").' 23:59:59';
        }

        if (empty($_POST['key_column'])) {
            $_POST['key_column'] = 0;
        }
        // 抓出所有相同卡號的紀錄
        $attendance_service = new AttendancerecordService();
        $record_data = $attendance_service->get_by_condition($keyword_selected,$_POST['keyword'],$_POST['key_column'], $choose_start, $choose_end );

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
                $temp['reply_description'] = $value['reply_description'];
                $temp['take'] = $this->fake[$value['take']];
                $temp['att_create_at'] = $value['att_create_at'];
                $temp['update_at'] = $value['update_at'];

                array_push($finaldata, $temp);
        }
        // 每次找完資料都將資料存進session 方便匯出跟列印
        Yii::app()->session[ 'record_data' ] = $finaldata;

        $this->render( 'usereport', ['model' => $record_data,
            //'employee_list' => $employee_list,
            'rcdata' => $finaldata
        ] );
    }

    public function actionPersonal()
    {
        if( Yii::app()->session['personal'] == false){
            $this->redirect(Yii::app()->createUrl('admin/login'));
        }
        $employee = new EmployeeService();
        $keyword = Yii::app()->session['uid'];

        $keyword_selected = 1;
        $key_column = 3;





        // 日期
        if (isset($_POST['date_start']) && !empty($_POST['date_start'])) {

            $choose_start = $_POST['date_start'] . ' 00:00:00';

        } else {

            $choose_start = date("Y-m-d").' 00:00:00';
        }

        if (isset($_POST['date_end']) && !empty($_POST['date_end'])) {

            $choose_end = $_POST['date_end'] . ' 23:59:59';

        } else {

            $choose_end = date("Y-m-d").' 23:59:59';
        }

        if (empty($_POST['key_column'])) {
            $_POST['key_column'] = 0;
        }
        // 抓出所有相同卡號的紀錄
        $attendance_service = new AttendancerecordService();
        $record_data = $attendance_service->get_by_condition($keyword_selected,$keyword,$key_column, $choose_start, $choose_end );

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
            $temp['reply_description'] = $value['reply_description'];
            $temp['take'] = $this->fake[$value['take']];
            $temp['att_create_at'] = $value['att_create_at'];
            $temp['update_at'] = $value['update_at'];

            array_push($finaldata, $temp);
        }
        // 每次找完資料都將資料存進session 方便匯出跟列印
        Yii::app()->session[ 'record_data' ] = $finaldata;

        $this->render( 'personal', ['model' => $record_data,
            //'employee_list' => $employee_list,
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
            ->setCellValue( 'H1', '假別' )
            ->setCellValue( 'I1', '員工回覆' )
            ->setCellValue( 'J1', '建立時間' )
            ->setCellValue( 'K1', '修改時間' );


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
                ->setCellValue( 'H' . $i, $value[ 'take' ] )
                ->setCellValue( 'I' . $i, $value[ 'reply_description' ] )
                ->setCellValue( 'J' . $i, $value[ 'att_create_at' ] )
                ->setCellValue( 'K' . $i, $value[ 'update_at' ] );
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

        $data = $this->fake;

        if ($model !== null) {
            $this->render('update',['model' => $model,'data'=>$data,'employee'=> $employee]);
            $this->clearMsg();
        } else {
            $this->redirect(Yii::app()->createUrl('list'));
        }
    }


}