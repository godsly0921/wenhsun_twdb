<?php
class DevreportController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function beforeAction($action)
    {
        return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
    }

    public function actionusereport(){
        
        // 如果有接收到表示
        //if( isset($_POST['filter']) && $_POST['filter'] == 1){

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
            
            // 儀器 
            $choosedev = array();

            if( isset($_POST['device']) &&  $_POST['device']!=0 ){

                $choosedev = array($_POST['device']);

            }else{
                
                // 抓取全部儀器id
                $dev_ser = new DeviceService;
                $alldev  = $dev_ser->findDevices();

                foreach ($alldev as $key => $value) {
                    array_push($choosedev, $value->id );
                }
                
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


            // 抓出所有相同卡號的紀錄
            $biiservice = new BillService;
            $rcdata     = $biiservice->get_by_condition($idarr,$choosedev,$choosestart,$chooseend);
        //}

        $data = array();
        
        // 抓出全部教授
        /*$p_ser = new ProfessorService;
        $professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[$value->id] = $value->name;
        }*/
        $pro_objs=$memser->get_all_professor(2);
        //var_dump($pro_obj);

        foreach ($pro_objs as $pro_objk => $pro_obj) {

            $proarr[$pro_obj->id] = $pro_obj->name;
        }

        // 抓出全部儀器
        $d_ser = new DeviceService;
        $device = $d_ser->findDevices();
        
        // 抓出所有一級分類
        $g_ser = new UsergrpService();
        $grp   = $g_ser->getLevelOneAll();
        

        // 每次找完資料都將資料存進session 方便匯出跟列印
        Yii::app()->session['idarr']       = $idarr;
        Yii::app()->session['choosedev']   = $choosedev;
        Yii::app()->session['choosestart'] = $choosestart;
        Yii::app()->session['chooseend']   = $chooseend;


        $this->render('usereport',['model'    => $data,
                                   'professor'=> $proarr,
                                   'device'   => $device,
                                   'grp'      => $grp,
                                   'rcdata'   => $rcdata 
                                  ]);
    }

    // 匯出excel
    function actiongetexcel(){
        
        // 查詢符合資料
        $idarr       = Yii::app()->session['idarr'];
        $choosedev   = Yii::app()->session['choosedev'];
        $choosestart = Yii::app()->session['choosestart'];
        $chooseend   = Yii::app()->session['chooseend'];
        $biiservice  = new BillService;
        $model      = $biiservice->get_by_condition($idarr,$choosedev,$choosestart,$chooseend);
        
        // 抓出所有教授,並且存成陣列
        $p_ser = new ProfessorService;
        /*$professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[$value->id] = $value->name;
        }*/
        $memser = new MemberService;
        $pro_objs=$memser->get_all_professor(2);
        //var_dump($pro_obj);

        foreach ($pro_objs as $pro_objk => $pro_obj) {

            $proarr[$pro_obj->id] = $pro_obj->name;
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
            ->setCellValue('A1', '使用者')
            ->setCellValue('B1', '教授')
            ->setCellValue('C1', '儀器名稱')
            ->setCellValue('D1', '開始使用')
            ->setCellValue('E1', '結束使用')
            ->setCellValue('F1', '使用時間')
            ->setCellValue('G1', '折扣後金額');

        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i=2;
        foreach($model as $value){
            
            // 教授名稱
            if (array_key_exists($value['mp'],$professor)) {
                $tmppro =  $professor[$value['mp']];
            }else{
                $tmppro = "無";
            }
            
            // 計算使用時間
            $seconds    = abs(strtotime($value['usestart']) - strtotime($value['useend']) );
            $hours      = floor($seconds / 3600);
            $mins       = floor($seconds / 60 % 60);
            $secs       = floor($seconds % 60);
            $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $value['username'])
            ->setCellValue('B'.$i, $tmppro)
            ->setCellValue('C'.$i, $value['dename'])
            ->setCellValue('D'.$i, $value['usestart'])
            ->setCellValue('E'.$i, $value['useend'])
            ->setCellValue('F'.$i, $timeFormat)
            ->setCellValue('G'.$i, $value['d_price']);

            $i++;
            
        }
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-儀器使用明細表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-儀器使用明細表".".xls");
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
        $model       = $biiservice->get_by_condition($idarr,$choosedev,$choosestart,$chooseend);
        
        $p_ser = new ProfessorService;
        /*$professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[$value->id] = $value->name;
        }*/
        $memser = new MemberService;
        $pro_objs=$memser->get_all_professor(2);
        //var_dump($pro_obj);

        foreach ($pro_objs as $pro_objk => $pro_obj) {

            $proarr[$pro_obj->id] = $pro_obj->name;
        }
        

        $this->render('usereport_print',['model'    => $model,
                                         'professor'=> $proarr,
                                  ]);


    }
}