<?php
class RecordController extends Controller
{
    public $layout = "//layouts/back_end";

    private $categorys = ["0" => "尚未使用", "1" => "正常使用", "2" => "異常", "3" => "取消預約"];

    public function actionAbnormal_list()
    {

        $inputs['start'] = date('Y-m-d') . ' 00:00:00';
        $inputs['end'] = date('Y-m-d') . ' 23:59:59';
        $inputs['keyword'] = "";
        $inputs['status'] = "1";

        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $service = new RecordService();
        $model = $service->findAbnormalRecordAndConditionDayAll($inputs);//找出今天的門禁異常資訊

        $this->render('abnormal_list', ['model' => $model]);
    }


    // 匯出excel
    function actionGet_abnormal_list_excel()
    {

        // 查詢符合資料
        $inputs['start'] = Yii::app()->session['start'];
        $inputs['end'] = Yii::app()->session['end'];
        $inputs['keyword'] = Yii::app()->session['keyword'];
        $inputs['status'] = Yii::app()->session['status'];

        $service = new RecordService();
        $model = $service->findAbnormalRecordAndConditionDayAll($inputs);//查詢日期與取消條件

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
            ->setCellValue('A1', '日期')
            ->setCellValue('B1', '使用者')
            ->setCellValue('C1', '門禁分類')
            ->setCellValue('D1', '卡號')
            ->setCellValue('E1', '門禁刷卡時間')
            ->setCellValue('F1', '異常描述');

        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i = 2;
        foreach ($model as $value) {
            $objPHPExcel->setActiveSheetIndex(0)

                ->setCellValue('A' . $i, $value->date)
                ->setCellValue('B' . $i, $value->user_name)
                ->setCellValue('C' . $i, $value->station_name)
                ->setCellValue('D' . $i, $value->card_number)
                ->setCellValue('E' . $i, $value->card_time)
                ->setCellValue('F' . $i, $value->exception_description);

            $i++;

        }
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-門禁異常記錄表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-門禁異常記錄表" . ".xls");
        ob_end_clean();
        header("Content-type: text/html; charset=utf-8");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=" . $filename);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    // 列印
    function actionGet_abnormal_list_printer()
    {

        $this->layout = "back_end_cls";

        $inputs['start'] = Yii::app()->session['start'];
        $inputs['end'] = Yii::app()->session['end'];
        $inputs['keyword'] = Yii::app()->session['keyword'];
        $inputs['status'] = Yii::app()->session['status'];

        $service = new RecordService();
        $model = $service->findAbnormalRecordAndConditionDayAll($inputs);//查詢日期與取消條件

        $this->render('abnormal_print', ['model' => $model]);

    }


    public function actionGet_abnormal_list()
    {

        $inputs['start'] = filter_input(INPUT_POST, "start"). ' 00:00:00';
        $inputs['end'] =  filter_input(INPUT_POST, "end"). ' 23:59:59';
        $inputs['keyword'] = filter_input(INPUT_POST, "keyword");
        $inputs['status'] =  filter_input(INPUT_POST, "status");

        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $service = new RecordService();
        $model = $service->findAbnormalRecordAndConditionDayAll($inputs);

        $this->render('abnormal_list', ['model' => $model]);
    }

}