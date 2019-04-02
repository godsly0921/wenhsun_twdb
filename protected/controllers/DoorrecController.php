<?php
ini_set('memory_limit', '256M');
class DoorrecController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function beforeAction($action)
    {
        return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
    }

    public function actionreport(){

            $service = new MemberService();
            $professor = $service->findByMemberOfLevel( 2 );//取出所有教授帳號

            $professor_array = [];
            foreach ($professor as $key => $value) {
                $professor_array[ $value->id ] = $value->name;
            }


            if (isset($_POST[ 'grp' ])) {
                if (!empty($_POST[ 'grp' ])) {
                    $allgrpid = $_POST[ 'grp' ];
                } else {
                    $allgrpid = NULL;
                }
            } else {
                $allgrpid = NULL;
            }

            if( isset($_POST['professor']) ){
                
                if( $_POST['professor'] != 0 ){
                    $choospro = $_POST['professor'];
                }else{
                    $choospro = "";
                }
            }else{
                $choospro = "";
            }

            //var_dump($allgrpid);
            //var_dump($choospro);
            //exit();

            $memser = new MemberService;
            $choosemem = $memser->get_mem_by_grp_lv2($allgrpid,$choospro);


            $idarr  = array();
            $cardarrs = array();

            foreach ($choosemem as $cmk => $cmv) {

                array_push($idarr, $cmv['id']);
                array_push($cardarrs, $cmv['card_number']);

            }



            

            if( !empty($_POST['keyword']) ){
                $keysw = 1;
            }else{
                $keysw = 0;
                $_POST['keyword'] = '';
            }


            // 日期
            if( isset($_POST['date_start']) && !empty($_POST['date_start']) ){

                $choosestart = $_POST['date_start'].' 00:00:00';

            }else{
                
                $choosestart = '0000-00-00 00:00:00';
            }

            if( isset($_POST['date_end']) && !empty($_POST['date_end']) ){

                $chooseend = $_POST['date_end'].' 23:59:59';

            }else{
                
                $chooseend = date("Y-m-d H:i:s");
            }            

            if( empty($_POST['keycol'] )){
                $_POST['keycol'] = 0;
            }

            // 抓出門禁紀錄
            $recordservice = new RecordService;

            $finaldata = array();


            foreach ($cardarrs as $cardarrk => $cardarr) {
                if(!empty($cardarr)){
                    $getdatas = $recordservice->get_by_card_and_key($keysw,$_POST['keycol'],$cardarr,$choosestart,$chooseend,$_POST['keyword']);

                    if( count($getdatas) > 0){

                        foreach ($getdatas as $getdatakey => $getdata) {

                            $temp['positionname'] = $getdata['positionname'];
                            $temp['username']     = $getdata['username'];
                            $temp['card_number']  = $getdata['card_number'];
                            $temp['usergrp']      = $getdata['usergrp'];
                            $temp['flashDate']    = $getdata['flashDate'];
                            $temp['professor']    = $professor_array[$getdata['professor']];

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
        $grp   = $g_ser->getLevelOneAll();
        

        // 每次找完資料都將資料存進session 方便匯出跟列印
        Yii::app()->session['doorrec']       = $finaldata;

        $this->render('usereport',['model'    => $data,
                                   'professor'=> $professor_array,
                                   'device'   => $device,
                                   'grp'      => $grp,
                                   'rcdata'   => $finaldata
                                  ]);
    }

    // 匯出excel
    function actiongetexcel(){
        
        // 查詢符合資料
        $biiservice  = new BillService;
        $model      = Yii::app()->session['doorrec'];
        
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
            ->setCellValue('A1', '地點名稱')
            ->setCellValue('B1', '使用者姓名')
            ->setCellValue('C1', '卡號')
            ->setCellValue('D1', '教授姓名')
            ->setCellValue('E1', '第二層單位')
            ->setCellValue('F1', '刷卡時間');

        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i=2;
        foreach($model as $value){

            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $value['positionname'])
            ->setCellValue('B'.$i, $value['username'])
            ->setCellValue('C'.$i, $value['card_number'])
            ->setCellValue('D'.$i, $value['professor'])
            ->setCellValue('E'.$i, $value['usergrp'])
            ->setCellValue('F'.$i, $value['flashDate']);


            $i++;
            
        }
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-門禁記錄明細表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-門禁記錄明細表".".xls");
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
        $biiservice  = new BillService;
        $model       = Yii::app()->session['doorrec'];
        
        $p_ser = new ProfessorService;
        $professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[$value->id] = $value->name;
        }

        $this->render('usereport_print',['model'    => $model,
                                    
                                  ]);


    }
}