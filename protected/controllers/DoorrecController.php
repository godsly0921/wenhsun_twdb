<?php
ini_set('memory_limit', '256M');

class DoorrecController extends Controller
{
    protected function needLogin(): bool
    {
        return true;
    }

    protected function beforeAction($action)
    {
        return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
    }

    public function actionreport()
    {
        $employee = new EmployeeService();
        $choose_employee = $employee->findEmployeelist();


        $idarr = array();
        $cardarrs = array();


        if(!empty($choose_employee)){
            foreach ($choose_employee as $key => $value) {

                array_push($idarr, $value['id']);
                array_push($cardarrs, $value['door_card_num']);

            }
        }


        if (!empty($_POST['keyword'])) {
            $key_sw = 1;
        } else {
            $key_sw = 0;
            $_POST['keyword'] = '';
        }

        // 日期
        if (isset($_POST['date_start']) && !empty($_POST['date_start'])) {

            $choosestart = $_POST['date_start'] . ' 00:00:00';

        } else {

            $choosestart = date("Y-m-d").' 00:00:00';
        }

        if (isset($_POST['date_end']) && !empty($_POST['date_end'])) {

            $chooseend = $_POST['date_end'] . ' 23:59:59';

        } else {

            $chooseend = date("Y-m-d").' 23:59:59';
        }

        if (empty($_POST['keycol'])) {
            $_POST['keycol'] = 0;
        }

        // 抓出門禁紀錄
        $recordservice = new RecordService;

        $finaldata = array();


        foreach ($cardarrs as $cardarrk => $cardarr) {
            if (!empty($cardarr)) {
                $data = $recordservice->get_by_card_and_key($key_sw, $_POST['keycol'], $cardarr, $choosestart, $chooseend, $_POST['keyword']);
                $total = count($data);


                if (!empty($data) && $total != 0 ) {
                    foreach ($data as $key => $value) {
                        $temp['e_user_name'] = $value['e_user_name'];
                        $temp['position_name'] = $value['position_name'];
                        $temp['username'] = $value['username'];
                        $temp['card_number'] = $value['card_number'];
                        //$temp['usergrp'] = $value['usergrp'];
                        $temp['flashDate'] = $value['flashDate'];
                        $temp['memol'] = $value['memol'];
                        $temp['id'] = $value['id'];
                        array_push($finaldata, $temp);
                    }

                }
            }
        }



        // 每次找完資料都將資料存進session 方便匯出跟列印
        Yii::app()->session['doorrec'] = $finaldata;

        $this->render('usereport', ['model' => $finaldata,
            'rcdata' => $finaldata
        ]);
    }


    public function actionPersonal_report()
    {
        if( Yii::app()->session['personal'] == false){
            $this->redirect(Yii::app()->createUrl('admin/login'));
        }
        $employee = new EmployeeService();
        $choose_employee = $employee->findEmployeeById( Yii::app()->session['uid']);

        $idarr = array();
        $cardarrs = array();


        array_push($idarr, $choose_employee['id']);
        array_push($cardarrs, $choose_employee['door_card_num']);


        // 日期
        if (isset($_POST['date_start']) && !empty($_POST['date_start'])) {

            $choosestart = $_POST['date_start'] . ' 00:00:00';

        } else {

            $choosestart = date("Y-m-d").' 00:00:00';
        }

        if (isset($_POST['date_end']) && !empty($_POST['date_end'])) {

            $chooseend = $_POST['date_end'] . ' 23:59:59';

        } else {

            $chooseend = date("Y-m-d").' 23:59:59';
        }

        if (isset($_POST['date_start']) && !empty($_POST['date_start'])) {

            $choosestart = $_POST['date_start'] . ' 00:00:00';

        } else {

            $choosestart = date("Y-m-d").' 00:00:00';
        }

        if (empty($_POST['keycol'])) {
            $_POST['keycol'] = 0;
        }

        if (!empty($_POST['keyword'])) {
            $key_sw = 1;
        } else {
            $key_sw = 0;
            $_POST['keyword'] = '';
        }

        // 抓出門禁紀錄
        $recordservice = new RecordService;

        $finaldata = array();


        foreach ($cardarrs as $cardarrk => $cardarr) {
            if (!empty($cardarr)) {
                $data = $recordservice->get_by_card_and_key($key_sw, $_POST['keycol'], $cardarr, $choosestart, $chooseend, $_POST['keyword']);
                $total = count($data);


                if (!empty($data) && $total != 0 ) {
                    foreach ($data as $key => $value) {
                        $temp['e_user_name'] = $value['e_user_name'];
                        $temp['position_name'] = $value['position_name'];
                        $temp['username'] = $value['username'];
                        $temp['card_number'] = $value['card_number'];
                        //$temp['usergrp'] = $value['usergrp'];
                        $temp['flashDate'] = $value['flashDate'];
                        $temp['memol'] = $value['memol'];
                        $temp['id'] = $value['id'];
                        array_push($finaldata, $temp);
                    }

                }
            }
        }



        // 每次找完資料都將資料存進session 方便匯出跟列印
        Yii::app()->session['doorrec'] = $finaldata;

        $this->render('personal_report', ['model' => $finaldata,
            'rcdata' => $finaldata
        ]);
    }

    // 匯出excel
    function actiongetexcel()
    {

        $model = Yii::app()->session['doorrec'];


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
        $objPHPExcel->getProperties()->setCreator("文訊")
            ->setLastModifiedBy("文訊")
            ->setTitle("文訊")
            ->setSubject("文訊")
            ->setDescription("文訊")
            ->setKeywords("文訊")
            ->setCategory("文訊");
        // Add some data 設定匯出欄位資料
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '員工帳號')
            ->setCellValue('B1', '員工姓名')
            ->setCellValue('C1', '卡號')
            ->setCellValue('D1', '刷卡時間')
            ->setCellValue('E1', '刷卡狀態')
            ->setCellValue('F1', '原廠編號');

        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i = 2;
        foreach ($model as $value) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $value['e_user_name'])
                ->setCellValue('B' . $i, $value['username'])
                ->setCellValue('C' . $i, $value['card_number'])
                ->setCellValue('D' . $i, $value['flashDate'])
                ->setCellValue('E' . $i, $value['memol'])
                ->setCellValue('F' . $i, $value['id']);
            $i++;
        }
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('文訊人資出勤紀錄明細表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $filename = urlencode( "文訊人資出勤紀錄明細表" . ".xlsx" );
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
        $biiservice = new BillService;
        $model = Yii::app()->session['doorrec'];

        $this->render('usereport_print', ['model' => $model,

        ]);


    }


}