<?php
class DevrecController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function beforeAction($action)
    {
        return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
    }

    public function actionreport(){


        //var_dump($_POST);
        // 如果有接收到表示
        //if( isset($_POST['filter']) && $_POST['filter'] == 1){

            // 日期
            if( isset($_POST['date_start']) && !empty($_POST['date_start']) ){

                $choosestart = $_POST['date_start']." {$_POST['starth']}:{$_POST['startm']}:00";

            }else{
                
                $choosestart = '0000-00-00 00:00:00';
            }

            if( isset($_POST['date_end']) && !empty($_POST['date_end']) ){

                $chooseend = $_POST['date_end']." {$_POST['endh']}:{$_POST['endm']}:59";

            }else{
                
                $chooseend = date("Y-m-d H:i:s");
            }            
            
            if( !isset($_POST['position'])){
                $_POST['position'] ='';
            }
            if( !isset($_POST['cardnum'])){
                $_POST['cardnum'] ='';
            }
            if( !isset($_POST['username'])){
                $_POST['username'] ='';
            }                        

            // 抓出所有相同卡號的紀錄
            $recordservice = new Device_recordService;
            
            // 抓出全部儀器
            $d_ser = new DeviceService;
            $device = $d_ser->findDevices();
            
            $devarr = array();
            foreach ($device as $devicek => $devicev) {

                $devarr[$devicev['station']] = $devicev['name'];
            }



            $rcdatas     = $recordservice->get_record_for_env($choosestart,$chooseend,$_POST['position']);
            
            if( empty($_POST['position']) ){

                $_POST['position']='';

            }
            // 添加條件搜尋條件
            if( /*!empty($_POST['position'])*/ true){

                
                foreach ($rcdatas as $rcdatak => $rcdata ) {
                   
                    $cond_res = $d_ser->get_by_station_for_devrec((int)$rcdata['station'],$_POST['position']);
         
                    if( $cond_res == false ){

                        unset($rcdatas["$rcdatak"]);
                    
                    }else{
  
                        $rcdatas["$rcdatak"]['devname'] = $cond_res['name'];
                        $rcdatas["$rcdatak"]['ip'] = $cond_res['ip'];
                        $rcdatas["$rcdatak"]['lname'] = $cond_res['lname'];
                        $rcdatas["$rcdatak"]['devstatus'] = $cond_res['status'];
                    }

                    //var_dump($rcdata);
                   

                }

            }
            

            $rcdata = $rcdatas;
        //}

        $data = array();
        
        // 抓出全部教授
        $p_ser = new ProfessorService;
        $professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[$value->id] = $value->name;
        }


        
        // 抓出所有一級分類
        $g_ser = new UsergrpService();
        $grp   = $g_ser->getLevelOneAll();
        

        // 每次找完資料都將資料存進session 方便匯出跟列印
        Yii::app()->session['envcdata']    = $rcdata;
        Yii::app()->session['choosedev']   = '';
        Yii::app()->session['choosestart'] = '';
        Yii::app()->session['chooseend']   = '';
        
        //var_dump($rcdata);


        $this->render('usereport',['model'    => $data,
                                   'professor'=> $proarr,
                                   'device'   => $device,
                                   'grp'      => $grp,
                                   'rcdata'   => $rcdata,
                                   'devarr'   => $devarr
                                  ]);
    }

    // 匯出excel
    function actiongetexcel(){
        
        // 查詢符合資料
        $biiservice  = new BillService;
        $model      = Yii::app()->session['envcdata'];
        
        // 抓出所有教授,並且存成陣列
        $p_ser = new ProfessorService;
        $professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[$value->id] = $value->name;
        }
        $professor = $proarr;

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
            ->setCellValue('A1', '顯示名稱')
            ->setCellValue('B1', '動作地點')
            ->setCellValue('C1', '服務來源')
            ->setCellValue('D1', '服務名稱')
            ->setCellValue('E1', '動作時間')
            ->setCellValue('F1', '動作開關')
            ->setCellValue('G1', '卡片號碼')
            ->setCellValue('H1', '持卡人')
            ->setCellValue('I1', 'IP(區域群組)')
            ->setCellValue('J1', '備註');

        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i=2;
        foreach($model as $value){


            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $value['devname'])
            ->setCellValue('B'.$i, $value['lname'])
            ->setCellValue('C'.$i, '儀器')
            ->setCellValue('D'.$i, '送電')
            ->setCellValue('E'.$i, $value['use_date'])
            ->setCellValue('F'.$i, 'on')
            ->setCellValue('G'.$i, $value['card'])
            ->setCellValue('H'.$i, $value['name'])
            ->setCellValue('I'.$i, $value['ip'])
            ->setCellValue('J'.$i, $value['des']);
            $i++;
            
        }
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-機台刷卡記錄表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-機台刷卡記錄表".".xls");
        ob_end_clean();
        header("Content-type: text/html; charset=utf-8");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=".$filename);
        $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    // 列印
    function actionprinter(){

        $this->layout = "back_end_cls";
        
        // 查詢符合資料
        $idarr       = Yii::app()->session['idarr'];
        $choosedev   = Yii::app()->session['choosedev'];
        $choosestart = Yii::app()->session['choosestart'];
        $chooseend   = Yii::app()->session['chooseend'];
        $biiservice  = new BillService;
        $model       =  Yii::app()->session['envcdata'];
        
        $p_ser = new ProfessorService;
        $professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[$value->id] = $value->name;
        }

        $this->render('usereport_print',['model'    => $model,
                                         'professor'=> $proarr,
                                  ]);


    }
}