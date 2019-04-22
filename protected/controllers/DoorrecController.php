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

        foreach ($choose_employee as $key => $value) {

            array_push($idarr, $value['id']);
            array_push($cardarrs, $value['door_card_num']);

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

            $choosestart = '0000-00-00 00:00:00';
        }

        if (isset($_POST['date_end']) && !empty($_POST['date_end'])) {

            $chooseend = $_POST['date_end'] . ' 23:59:59';

        } else {

            $chooseend = date("Y-m-d H:i:s");
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
                if (!empty($data)) {
                    foreach ($data as $key => $value) {

                        $temp['position_name'] = $value['position_name'];
                        $temp['username'] = $value['username'];
                        $temp['card_number'] = $value['card_number'];
                        $temp['usergrp'] = $value['usergrp'];
                        $temp['flashDate'] = $value['flashDate'];

                        array_push($finaldata, $temp);
                    }

                }
            }
        }


        //}

        $data = array();


        // 抓出全部儀器
        $d_ser = new DeviceService;
        $device = $d_ser->findDevices();

        // 抓出所有一級分類
        $g_ser = new UsergrpService();
        $grp = $g_ser->getLevelOneAll();


        // 每次找完資料都將資料存進session 方便匯出跟列印
        Yii::app()->session['doorrec'] = $finaldata;

        $this->render('usereport', ['model' => $data,
            'professor' => $professor_array,
            'device' => $device,
            'grp' => $grp,
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
            ->setCellValue('A1', '地點名稱')
            ->setCellValue('B1', '員工姓名')
            ->setCellValue('C1', '卡號')
            ->setCellValue('D1', '刷卡時間');

        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i = 2;
        foreach ($model as $value) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $value['position_name'])
                ->setCellValue('B' . $i, $value['username'])
                ->setCellValue('C' . $i, $value['card_number'])
                ->setCellValue('D' . $i, $value['flashDate']);
            $i++;
        }
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('文訊人資出勤紀錄明細表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        //目前支援xls匯出
        $filename = urlencode("文訊人資出勤紀錄明細表" . ".xlsx");




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

        $p_ser = new ProfessorService;
        $professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[$value->id] = $value->name;
        }

        $this->render('usereport_print', ['model' => $model,

        ]);


    }


}