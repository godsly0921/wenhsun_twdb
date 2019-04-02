<?php

class DoorcountController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function beforeAction($action)
    {
        return RequestLogin::checkLogin( $action ) ? true : $this->redirect( Yii::app()->createUrl( 'admin/index' ) );
    }

    public function actionreport()
    {

        // 如果有接收到表示
        //if( isset($_POST['filter']) && $_POST['filter'] == 1){

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

        if (isset($_POST[ 'professor' ])) {

            if ($_POST[ 'professor' ] != 0) {
                $choospro = $_POST[ 'professor' ];
            } else {
                $choospro = "";
            }
        } else {
            $choospro = "";
        }


        $memser = new MemberService;
        $choosemem = $memser->get_mem_by_grp_lv1( $allgrpid, $choospro );


        $idarr = array();
        $cardarrs = array();

        foreach ($choosemem as $cmk => $cmv) {

            array_push( $idarr, $cmv[ 'id' ] );
            array_push( $cardarrs, $cmv[ 'card_number' ] );

        }


        // 門禁
        $choose_door = array();

        if (isset($_POST[ 'device' ]) && $_POST[ 'device' ] != 0) {

            $choosedev = array($_POST[ 'device' ]);

        } else {

            // 抓取全部門禁
            $door_service = new DoorService();
            $all_door = $door_service->findDoor();

            foreach ($all_door as $key => $value) {
                array_push( $choose_door, $value->id );
            }

        }

        // 日期
        if (isset($_POST[ 'date_start' ]) && !empty($_POST[ 'date_start' ])) {

            $choosestart = $_POST[ 'date_start' ] . ' 00:00:00';

        } else {

            $choosestart = '0000-00-00 00:00:00';
        }

        if (isset($_POST[ 'date_end' ]) && !empty($_POST[ 'date_end' ])) {

            $chooseend = $_POST[ 'date_end' ] . ' 23:59:59';

        } else {

            $chooseend = date( "Y-m-d H:i:s" );
        }

        if (empty($_POST[ 'keycol' ])) {
            $_POST[ 'keycol' ] = 0;
        }
        // 抓出所有相同卡號的紀錄
        $biiservice = new BilldoorService();
        $door_data = $biiservice->get_by_condition( $idarr, $choose_door, $choosestart, $chooseend );


        // 抓出門禁紀錄

        $finaldata = array();
        $recordservice = new RecordService();
        $billdoorService = new BilldoorService();

        if(!empty($choosemem)){
            foreach ($choosemem as $key => $value) {
                $countdatas = $recordservice->get_by_card_bd( $value[ 'card_number' ], $choosestart, $chooseend );
                $countbilldoor = $billdoorService->get_by_condition_total($value[ 'id' ], $choosestart, $chooseend );

                        if (!empty($countdatas[  0 ])) {
                            $tmpdata[ 'username' ] = $value[ 'name' ];
                            $tmpdata[ 'cardnum' ] = $value[ 'card_number' ];
                            $tmpdata[ 'pname' ] = isset($professor_array[ $value[ 'professor' ] ])?$professor_array[ $value[ 'professor' ] ]:'用戶未設定教授';
                            $tmpdata[ 'usetime' ] = $countdatas[ 0 ][ 'total_count' ];
                            $tmpdata[ 'use_price' ] = $countbilldoor[ 0 ][ 'total_count' ];
                            array_push( $finaldata, $tmpdata );
                        }
            }
        }


        $data = array();

        $service = new MemberService();
        $professor = $service->findByMemberOfLevel( 2 );//取出教授管理帳號

        // 抓出全部儀器
        $d_ser = new DeviceService;
        $device = $d_ser->findDevices();

        // 抓出所有一級分類
        $g_ser = new UsergrpService();
        $grp = $g_ser->getLevelOneAll();


        // 每次找完資料都將資料存進session 方便匯出跟列印
        Yii::app()->session[ 'doorcount' ] = $finaldata;

        $this->render( 'usereport', ['model' => $data,
            'professor' => $professor,
            'device' => $device,
            'grp' => $grp,
            'rcdata' => $finaldata
        ] );
    }

    // 匯出excel
    function actiongetexcel()
    {

        // 查詢符合資料
        $biiservice = new BillService;
        $model = Yii::app()->session[ 'doorcount' ];

        // 抓出所有教授,並且存成陣列
        $p_ser = new ProfessorService;
        $professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[ $value->id ] = $value->name;
        }
        $professor = $proarr;

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
        $objPHPExcel->getProperties()->setCreator( "清大門禁系統" )
            ->setLastModifiedBy( "清大門禁系統" )
            ->setTitle( "清大門禁系統" )
            ->setSubject( "清大門禁系統" )
            ->setDescription( "清大門禁系統" )
            ->setKeywords( "清大門禁系統" )
            ->setCategory( "清大門禁系統" );
        // Add some data 設定匯出欄位資料
        $objPHPExcel->setActiveSheetIndex( 0 )
            ->setCellValue( 'A1', '使用者姓名' )
            ->setCellValue( 'B1', '卡號' )
            ->setCellValue( 'C1', '教授姓名' )
            ->setCellValue( 'D1', '刷卡次數' )
            ->setCellValue( 'E1', '總金額' );


        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i = 2;
        foreach ($model as $value) {

            $objPHPExcel->setActiveSheetIndex( 0 )
                ->setCellValue( 'A' . $i, $value[ 'username' ] )
                ->setCellValue( 'B' . $i, $value[ 'cardnum' ] )
                ->setCellValue( 'C' . $i, $value[ 'pname' ] )
                ->setCellValue( 'D' . $i, $value[ 'usetime' ] )
                ->setCellValue( 'E' . $i, $value[ 'use_price' ] );


            $i++;

        }
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle( '清大門禁系統-門禁記錄統計表' );
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex( 0 );

        //目前支援xls匯出
        $filename = urlencode( "清大門禁系統-門禁記錄統計表" . ".xls" );
        ob_end_clean();
        header( "Content-type: text/html; charset=utf-8" );
        header( "Content-Type: application/vnd.ms-excel" );
        header( "Content-Disposition: attachment;filename=" . $filename );
        $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
        $objWriter->save( 'php://output' );
        exit;
    }

    // 列印
    function actionprinter()
    {

        $this->layout = "back_end_cls";

        // 查詢符合資料
        $biiservice = new BillService;
        $model = Yii::app()->session[ 'doorcount' ];

        $p_ser = new ProfessorService;
        $professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[ $value->id ] = $value->name;
        }

        $this->render( 'usereport_print', ['model' => $model,

        ] );


    }
}