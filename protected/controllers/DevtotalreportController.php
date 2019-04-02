<?php
class DevtotalreportController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function beforeAction($action)
    {
        return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
    }

    public function actionreport(){
        

            $allgrpid = array();
            //優先算出條件群組
            if( isset($_POST['grp']) ){

                $tmpgrp = new UsergrpService();
                
                foreach ($_POST['grp'] as $key => $grp) {
                    
                    $tmpres = $tmpgrp->getchild($grp);

                    foreach ($tmpres as $ck => $cv) {
                        array_push($allgrpid, $cv->id);
                    }
                }
                  
                //$grpstr = implode(",",$allgrpid);

            }else{

                $tmpgrp = new UsergrpService();
                $tmpres = $tmpgrp->getLevelTwoAll();
                
                foreach ($tmpres as $ck => $cv) {
                    array_push($allgrpid, $cv->id);
                }
                //$grpstr = implode(",",$allgrpid);
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
            
            $memser = new MemberService;
            $choosemem = $memser->get_mem_by_gp($allgrpid,$choospro);
            
           
            $idarr = array();

            foreach ($choosemem as $cmk => $cmv) {
                array_push($idarr, $cmv['id']);
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
            // 抓出全部儀器
            $d_ser = new DeviceService;
            $device = $d_ser->findDevices();
            
            $biiservice = new BillService;
            
            $data = array();
            // 依序抓出各儀器相關數值
            foreach ($device as $devk => $devv) {

                $temparr = array();
                $temparr['id']  = $devv->id;
                $temparr['name']  = $devv->name;
                $temparr['code']  = $devv->codenum;
                $temparr['count'] = $usecount = $biiservice->get_use_count($idarr,$choosestart,$chooseend,$devv->id);
                $temparr['time']  = $biiservice->get_use_time($idarr,$choosestart,$chooseend,$devv->id);
                $temparr['price'] = $biiservice->get_use_price($idarr,$choosestart,$chooseend,$devv->id);
                array_push($data, $temparr);
            }
            

            // 抓出所有相同卡號的紀錄
            
           // $rcdata     = $biiservice->get_total_report_by_condition($idarr,$choosestart,$chooseend);

        
        
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
        Yii::app()->session['totalreport']     = $data;



        $this->render('usereport',['model'    => $data,
                                   'device'   => $device,
                                   'grp'      => $grp,
                                   'data'     => $data 
                                  ]);
    }

    // 匯出excel
    function actiongetexcel(){
        
        $model      = Yii::app()->session['totalreport'];
        
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
            ->setCellValue('A1', 'id')
            ->setCellValue('B1', '儀器名稱')
            ->setCellValue('C1', '儀器編號')
            ->setCellValue('D1', '使用次數')
            ->setCellValue('E1', '總時數')
            ->setCellValue('F1', '金額');


        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i=2;
        foreach($model as $value){
            


            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $value['id'])
            ->setCellValue('B'.$i, $value['name'])
            ->setCellValue('C'.$i, $value['code'])
            ->setCellValue('D'.$i, $value['count'])
            ->setCellValue('E'.$i, $value['time'])
            ->setCellValue('F'.$i,  $value['price']);

            $i++;
            
        }
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-儀器使用統計總表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-儀器使用統計總表".".xls");
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
        $model      = Yii::app()->session['totalreport'];
        
        $this->render('usereport_print',['model'    => $model,
                
                                  ]);


    }
}