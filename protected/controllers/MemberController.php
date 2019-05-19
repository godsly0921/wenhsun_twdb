<?php

class MemberController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function needLogin(): bool
    {
        return true;
    }

    //-------- 員工座位相關 --------

    public function actionSeats()
    {
        $this->clearMsg();
        $employeeSeatsRepo = new EmployeeSeatsRepo();
        $seats = $employeeSeatsRepo->getAll();

        $this->render('seats/list', ['seats' => $seats]);
    }

    public function actionSeatsCreate()
    {
        $this->render('seats/create');
    }

    //-------- END --------

    public function actionDoorAndTimeUpdate()
    {

        $inputs['id'] = filter_input(INPUT_POST, 'id');
        $inputs['card'] = filter_input(INPUT_POST, 'card');
        $inputs['door'] = filter_input(INPUT_POST, 'door');
        $inputs['time'] = filter_input(INPUT_POST, 'time');

        if (!isset($inputs['id']) || $inputs['id'] == "" || $inputs['id'] == NULL) {
            echo '沒有傳送用戶ID';
            exit();
        }

        if (!isset($inputs['card']) || $inputs['card'] == "" || $inputs['card'] == NULL) {
            echo '使用者沒有卡號';
            exit();
        }


        if (!isset($inputs['door']) || $inputs['door'] == "" || $inputs['door'] == NULL) {
            echo '使用者沒有設定門組';
            exit();
        }

        if (!isset($inputs['time']) || $inputs['time'] == "" || $inputs['time'] == NULL) {
            echo '使用者沒有設定門組';
            exit();
        }

        $id = $inputs['id'];
        $card = $inputs['card'];
        $door = $inputs['door'];
        $time = $inputs['time'];

        $stcard_door = new StcardService();
        $res_door = $stcard_door->st_change_door($card, $door);

        $stcard_time = new StcardService();
        $res_time = $stcard_time->st_change_time($card, $time);

        if ($res_door == true && $res_time == true) {
            $download_service = new StcardService();
            $card_download_res = $download_service->st_card_download();
            if ($card_download_res == false) {
                echo 'Door group/time slot upload failed';
                exit();
            }

            if ($card_download_res == true) {

                $model = Member::model()->findByPk($id);

                if (!$model->hasErrors()) {
                    $model->door = $door;
                    $model->time = $time;
                    $model->update();
                }

                if ($model->update()) {
                    header('Content-Type: text/html; charset=utf8');
                    Yii::app()->session['error_msg'] = array(array('門組/時段上傳卡機成功,資料庫更新成功'));
                    $this->redirect('update/' . $inputs['id']);

                    set_time_limit(10);
                    exec('START C:\xampp\htdocs\chingda\stserver_open.bat');
                    sleep(5);
                    exec('START C:\xampp\htdocs\chingda\stserver_stop.bat');
                    exit();


                } else {
                    header('Content-Type: text/html; charset=utf8');
                    Yii::app()->session['error_msg'] = array(array('門組/時段上傳卡機成功,資料庫更新失敗'));
                    $this->redirect('update/' . $inputs['id']);
                    exit();
                }

            }
        } else {
            header('Content-Type: text/html; charset=utf8');
            Yii::app()->session['error_msg'] = array(array('Door/time period write to cardinfo failed'));
            $this->redirect('update/' . $inputs['id']);
            exit();
        }


        unset($res_door);
        unset($res_time);
        unset($download_service);
        unset($stcard_time);
        unset($stcard_door);
    }

    public function actionList()
    {
        $this->clearMsg();

        $memberService = new MemberService();
        $datas = $memberService->findMemberlist();

        $this->render('list', array('datas' => $datas));
    }

    // 列印
    function actionPrinter()
    {

        $this->layout = "back_end_cls";
        // 查詢符合資料
        $inputs['keyword'] = Yii::app()->session['keyword'];
        $inputs['keyword_field'] = Yii::app()->session['keyword_field'];
        $inputs['user_group'] = Yii::app()->session['user_group'];


        $memberService = new MemberService();
        $groups = ExtGroup::model()->group_list();
        $grp_service = new UsergrpService();
        $grp_data = $grp_service->getLevelOneAll();
        $grp_data2 = $grp_service->getLevelTwoAll();

        $service = new MemberService();
        $professor = $service->get_all_professor(2);
        $datas = $memberService->findMemberDetailList($inputs, $groups);


        $this->render('member_print', array('datas' => $datas, 'groups' => $groups, 'grp_data' => $grp_data, 'professor' => $professor, 'grp_data2' => $grp_data2));


    }

    // 列印
    function actionProfessor_printer()
    {

        $this->layout = "back_end_cls";

        $inputs['grp1'] = Yii::app()->session['grp1'];
        $inputs['grp2'] = Yii::app()->session['grp2'];
        $inputs['grp3'] = Yii::app()->session['grp3'];

        //remember fields 記得查詢欄位
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $memberService = new MemberService();
        $datas = $memberService->findMemberlist();
        $groups = ExtGroup::model()->group_list();

        $grp_service = new UsergrpService();
        $grp_data = $grp_service->getLevelOneAll();
        $grp_data2 = $grp_service->getLevelTwoAll();


        $service = new MemberService();
        $professor = $service->get_all_professor(2);


        $datas = $memberService->findProfessorMemberDetailList($inputs, $groups);

        $this->render('professor_print', array('datas' => $datas, 'groups' => $groups, 'grp_data' => $grp_data, 'professor' => $professor, 'grp_data2' => $grp_data2));


    }

    // 匯出excel
    function actiongetexcel()
    {

        // 查詢符合資料
        $inputs['keyword'] = Yii::app()->session['keyword'];
        $inputs['keyword_field'] = Yii::app()->session['keyword_field'];
        $inputs['user_group'] = Yii::app()->session['user_group'];


        $memberService = new MemberService();
        $groups = ExtGroup::model()->group_list();
        $grp_service = new UsergrpService();
        $grp_data = $grp_service->getLevelOneAll();
        $grp_data2 = $grp_service->getLevelTwoAll();

        $service = new MemberService();
        $professor = $service->get_all_professor(2);

        $model = $memberService->findMemberDetailList($inputs, $groups);

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
            ->setCellValue('A1', '使用者姓名')
            ->setCellValue('B1', '帳號')
            ->setCellValue('C1', '使用者身分')
            ->setCellValue('D1', '性別')
            ->setCellValue('E1', '第一層/第二層單位')
            ->setCellValue('F1', '指導教授')
            ->setCellValue('G1', '聯絡電話')
            ->setCellValue('H1', '作廢日期');

        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i = 2;
        foreach ($model as $value) {

            foreach ($groups as $group):
                if ($value->user_group == $group->group_number):
                    $group_name = $group->group_name;
                endif;
            endforeach;

            if ($value->sex == 0):
                $sex = '女生';
            elseif ($value->sex == 1):
                $sex = '男生';
            elseif ($value->sex == 2):
                $sex = '未設定';
            endif;

            foreach ($grp_data as $grp_key => $grp_val):
                if ($grp_val->id == $value->grp_lv1):
                    $level = $grp_val->name;
                endif;
            endforeach;
            $level = $level . '/';
            foreach ($grp_data2 as $grp_key => $grp_val):
                if ($grp_val->id == $value->grp_lv2):
                    $level = $level . $grp_val->name;
                endif;
            endforeach;

            $pro = '';
            foreach ($professor as $k => $v):
                if ($v->id == $value->professor):
                    $pro = $v->name;
                endif;
            endforeach;

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $value->name)
                ->setCellValue('B' . $i, $value->account)
                ->setCellValue('C' . $i, $group_name)
                ->setCellValue('D' . $i, $sex)
                ->setCellValue('E' . $i, $level)
                ->setCellValue('F' . $i, $pro)
                ->setCellValue('G' . $i, $value->phone1)
                ->setCellValue('H' . $i, $value->invalidation_date);

            $i++;

        }
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-使用者帳號明細表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-使用者帳號明細表" . ".xls");
        ob_end_clean();
        header("Content-type: text/html; charset=utf-8");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=" . $filename);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    // 匯出excel
    function actionProfessor_getexcel()
    {

        // 查詢符合資料
        $inputs['grp1'] = Yii::app()->session['grp1'];
        $inputs['grp2'] = Yii::app()->session['grp2'];
        $inputs['grp3'] = Yii::app()->session['grp3'];

        $memberService = new MemberService();
        $groups = ExtGroup::model()->group_list();
        $grp_service = new UsergrpService();
        $grp_data = $grp_service->getLevelOneAll();
        $grp_data2 = $grp_service->getLevelTwoAll();

        $service = new MemberService();
        $professor = $service->get_all_professor(2);

        $model = $memberService->findProfessorMemberDetailList($inputs, $groups);

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
            ->setCellValue('A1', '教授姓名')
            ->setCellValue('B1', '學員姓名')
            ->setCellValue('C1', '登入帳號')
            ->setCellValue('D1', '聯絡電話')
            ->setCellValue('E1', 'E-mail');

        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i = 2;
        foreach ($model as $value) {

            foreach ($professor as $k => $v):
                if ($v->id == $value->professor):
                    $professor_value = $v->name;
                endif;
            endforeach;

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $professor_value)
                ->setCellValue('B' . $i, $value->name)
                ->setCellValue('C' . $i, $value->account)
                ->setCellValue('D' . $i, $value->phone1)
                ->setCellValue('E' . $i, $value->email1);

            $i++;

        }
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-各教授學員明細表');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-各教授學員明細表" . ".xls");
        ob_end_clean();
        header("Content-type: text/html; charset=utf-8");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=" . $filename);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function actionDetail_list()
    {
        $this->clearMsg();

        $memberService = new MemberService();
        $datas = $memberService->findMemberlist();
        $groups = ExtGroup::model()->group_list();

        $grp_service = new UsergrpService();
        $grp_data = $grp_service->getLevelOneAll();
        $grp_data2 = $grp_service->getLevelTwoAll();


        $service = new MemberService();
        $professor = $service->get_all_professor(2);

        $this->render('detail_list', array('datas' => $datas, 'groups' => $groups, 'grp_data' => $grp_data, 'professor' => $professor, 'grp_data2' => $grp_data2));
    }

    public function actionProfessor_detail_list()
    {
        $this->clearMsg();

        $memberService = new MemberService();
        $datas = $memberService->findMemberlist();
        $groups = ExtGroup::model()->group_list();

        $grp_service = new UsergrpService();
        $grp_data = $grp_service->getLevelOneAll();
        $grp_data2 = $grp_service->getLevelTwoAll();


        $service = new MemberService();
        $professor = $service->get_all_professor(2);

        $this->render('professor_detail_list', array('datas' => $datas, 'groups' => $groups, 'grp_data' => $grp_data, 'professor' => $professor, 'grp_data2' => $grp_data2));
    }

    public function actionBlack_recovery_list()
    {
        $this->clearMsg();

        $inputs['start_time'] = date('Y-m-d') . ' 00:00:00';
        $inputs['end_time'] = date('Y-m-d') . ' 23:59:59';

        $service = new MemberService();
        $datas = $service->findMemberBlacklist($inputs);
        $groups = ExtGroup::model()->group_list();

        $grp_service = new UsergrpService();
        $grp_data = $grp_service->getLevelOneAll();
        $grp_data2 = $grp_service->getLevelTwoAll();

        $service = new AccountService();
        $accounts = $service->findAccounts();


        $service = new MemberService();
        $professor = $service->get_all_professor(2);

        $this->render('black_recovery_list', array('datas' => $datas, 'groups' => $groups, 'grp_data' => $grp_data, 'professor' => $professor, 'grp_data2' => $grp_data2, 'accounts' => $accounts));
    }

    public function actionGet_black_recovery_list()
    {
        $this->clearMsg();

        $inputs['grp1'] = filter_input(INPUT_POST, 'grp1');
        $inputs['grp2'] = filter_input(INPUT_POST, 'grp2');
        $inputs['grp3'] = filter_input(INPUT_POST, 'grp3');
        $inputs['start_date'] = filter_input(INPUT_POST, 'start_date');
        $inputs['end_date'] = filter_input(INPUT_POST, 'end_date');
        $inputs['status'] = filter_input(INPUT_POST, 'status');

        //remember fields 記得查詢欄位
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $memberService = new MemberService();
        $groups = ExtGroup::model()->group_list();

        $grp_service = new UsergrpService();
        $grp_data = $grp_service->getLevelOneAll();
        $grp_data2 = $grp_service->getLevelTwoAll();


        $service = new MemberService();
        $professor = $service->get_all_professor(2);

        $service = new AccountService();
        $accounts = $service->findAccounts();


        $datas = $memberService->findProfessorMemberBlacklist($inputs);

        $this->render('black_recovery_list', array('datas' => $datas, 'groups' => $groups, 'grp_data' => $grp_data, 'professor' => $professor, 'grp_data2' => $grp_data2, 'accounts' => $accounts));
    }

    public function actionGet_professor_detail_list()
    {
        $this->clearMsg();

        $inputs['grp1'] = filter_input(INPUT_POST, 'grp1');
        $inputs['grp2'] = filter_input(INPUT_POST, 'grp2');
        $inputs['grp3'] = filter_input(INPUT_POST, 'grp3');

        //remember fields 記得查詢欄位
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $memberService = new MemberService();
        $datas = $memberService->findMemberlist();
        $groups = ExtGroup::model()->group_list();

        $grp_service = new UsergrpService();
        $grp_data = $grp_service->getLevelOneAll();
        $grp_data2 = $grp_service->getLevelTwoAll();


        $service = new MemberService();
        $professor = $service->get_all_professor(2);


        $datas = $memberService->findProfessorMemberDetailList($inputs, $groups);

        $this->render('professor_detail_list', array('datas' => $datas, 'groups' => $groups, 'grp_data' => $grp_data, 'professor' => $professor, 'grp_data2' => $grp_data2));
    }

    public function actionGet_detail_list()
    {
        $this->clearMsg();

        $inputs['keyword'] = filter_input(INPUT_POST, 'keyword');
        $inputs['keyword_field'] = filter_input(INPUT_POST, 'keyword_field');
        $inputs['user_group'] = filter_input(INPUT_POST, 'user_group');

        //remember fields 記得查詢欄位
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $memberService = new MemberService();
        $groups = ExtGroup::model()->group_list();
        $grp_service = new UsergrpService();
        $grp_data = $grp_service->getLevelOneAll();
        $grp_data2 = $grp_service->getLevelTwoAll();

        $service = new MemberService();
        $professor = $service->get_all_professor(2);

        $datas = $memberService->findMemberDetailList($inputs, $groups);

        $this->render('detail_list', array('datas' => $datas, 'groups' => $groups, 'grp_data' => $grp_data, 'professor' => $professor, 'grp_data2' => $grp_data2));
    }

    public function actionCreate()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === "POST") {
            $this->doPostCreate();
        } else {
            $this->doGetCreate();
        }
    }

    private function doPostCreate()
    {

        if (!CsrfProtector::comparePost()) {
            $this->redirect('list');
        }

        $inputs['account'] = filter_input(INPUT_POST, 'account');
        $inputs['password'] = filter_input(INPUT_POST, 'password');
        $inputs['password_confirm'] = filter_input(INPUT_POST, 'password_confirm');
        $inputs['name'] = filter_input(INPUT_POST, 'name');
        $inputs['email'] = filter_input(INPUT_POST, 'email');
        $inputs['gender'] = filter_input(INPUT_POST, 'gender');
        $inputs['birthday'] = filter_input(INPUT_POST, 'year') . '-' .
            filter_input(INPUT_POST, 'month') . '-' . filter_input(INPUT_POST, 'day');
        $inputs['phone'] = filter_input(INPUT_POST, 'phone');
        $inputs['mobile'] = filter_input(INPUT_POST, 'mobile');
        $inputs['member_type'] = filter_input(INPUT_POST, 'member_type');
        $inputs['account_type'] = filter_input(INPUT_POST, 'account_type');
        $inputs['nationality'] = filter_input(INPUT_POST, 'nationality');
        $inputs['county'] = filter_input(INPUT_POST, 'county');
        $inputs['town'] = filter_input(INPUT_POST, 'town');
        $inputs['address'] = filter_input(INPUT_POST, 'address');

        $big5Name = iconv("UTF-8", "big5", $inputs['name']);
        $big5Lenght = strlen($big5Name);

        if ($big5Lenght > 20) {
            Yii::app()->session['error_msg'] = array(array('中文姓名限制為10個字,英文為20個字'));
            $this->redirect('create');
            exit;

        }

        $service = new MemberService();
        $model = $service->create($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
            $this->redirect('create');
            return;
        } else {
            Yii::app()->session['success'] = '新增會員帳號成功';
            $this->redirect('list');
            return;
        }

    }

    private function doGetCreate()
    {

        $years = Common::years();
        $months = Common::months();
        $days = Common::days();

        $this->render('create',
            [
                'years' => $years,
                'months' => $months,
                'days' => $days
            ]
        );
        $this->clearMsg();
    }

    public function actionCreate_Register()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === "POST") {
            $this->doPostCreate_Register();
        } else {
            $this->doGetCreate_Register();
        }
    }

    private function doPostCreate_Register()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $inputs['name'] = filter_input(INPUT_POST, 'name');
        $inputs['account'] = filter_input(INPUT_POST, 'account');
        $inputs['password'] = filter_input(INPUT_POST, 'password');
        $inputs['password_confirm'] = filter_input(INPUT_POST, 'password_confirm');
        $inputs['sex'] = filter_input(INPUT_POST, 'sex');
        $inputs['phone1'] = filter_input(INPUT_POST, 'phone1');
        $inputs['phone2'] = filter_input(INPUT_POST, 'phone2');
        $inputs['tel_no1'] = filter_input(INPUT_POST, 'tel_no1');
        $inputs['tel_no2'] = filter_input(INPUT_POST, 'tel_no2');
        $inputs['email1'] = filter_input(INPUT_POST, 'email1');
        $inputs['email2'] = filter_input(INPUT_POST, 'email2');
        $inputs['year'] = filter_input(INPUT_POST, 'year');
        $inputs['month'] = filter_input(INPUT_POST, 'month');
        $inputs['user_group'] = filter_input(INPUT_POST, 'user_group');
        $inputs['day'] = filter_input(INPUT_POST, 'day');
        $inputs['address'] = filter_input(INPUT_POST, 'address');


        $service = new MemberService();
        $model = $service->create($inputs);


        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
            $this->redirect('create');
            return;
        } else {
            Yii::app()->session['success'] = '新增使用者帳號成功';
            $this->redirect('list');
            return;
        }

    }


    private function doGetCreate_Register()
    {

        $years = Common::years();
        $months = Common::months();
        $days = Common::days();

        $grp_service = new UsergrpService();
        $grp_data = $grp_service->getLevelOneAll();
        $grp_data2 = $grp_service->getLevelTwoAll();

        $service = new MemberService();
        $professor = $service->get_all_professor(2);

        $service = new AccountService();
        $accounts = $service->findAccounts();

        $this->render('create', ['years' => $years, 'months' => $months, 'days' => $days, 'grp_data' => $grp_data, 'professor' => $professor, 'grp_data2' => $grp_data2, 'accounts' => $accounts]);
        $this->clearMsg();
    }

    /**
     * @param null $mem_id
     */
    public function actionUpdate($mem_id = null)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            $updateType = filter_input(INPUT_POST, 'update_type');
            if ($updateType === 'PW') {
                $this->doPostUpdatePasswd();
            } else {
                $this->doPostUpdate();
            }
        } else {
            $this->doGetUpdate($mem_id);
        }
    }

    /**
     * @param $mem_id
     */
    private function doGetUpdate()
    {
        $service = new MemberService();
        $years = Common::years();
        $months = Common::months();
        $days = Common::days();
        $data = $service->findByMemId($_GET['id']);

        if ($data !== null) {
            if ($data->birthday != '0000-00-00') {
                $year = substr($data->birthday, 0, 4);
                $month = substr($data->birthday, 5, 2);
                $day = substr($data->birthday, 8, 2);
            } else {
                $year = '';
                $month = '';
                $day = '';
            }
            $this->render('update', ['data' => $data, 'years' => $years, 'months' => $months, 'days' => $days, 'year' => $year, 'month' => $month, 'day' => $day]);
        } else {
            $this->redirect(['list']);
        }
    }

    private function doPostUpdate()
    {
        header('Content-Type: text/html; charset=big5');
        if (!CsrfProtector::comparePost()) {
            $this->redirect('list');
        }

        $inputs['id'] = filter_input(INPUT_POST, 'id');
        $inputs['account'] = filter_input(INPUT_POST, 'account');
        $inputs['name'] = filter_input(INPUT_POST, 'name');
        $inputs['email'] = filter_input(INPUT_POST, 'email');
        $inputs['gender'] = filter_input(INPUT_POST, 'gender');
        $inputs['birthday'] = filter_input(INPUT_POST, 'year') . '-' .
            filter_input(INPUT_POST, 'month') . '-' . filter_input(INPUT_POST, 'day');
        $inputs['phone'] = filter_input(INPUT_POST, 'phone');
        $inputs['mobile'] = filter_input(INPUT_POST, 'mobile');
        $inputs['member_type'] = filter_input(INPUT_POST, 'member_type');
        $inputs['nationality'] = filter_input(INPUT_POST, 'nationality');
        $inputs['county'] = filter_input(INPUT_POST, 'county');
        $inputs['town'] = filter_input(INPUT_POST, 'town');
        $inputs['address'] = filter_input(INPUT_POST, 'address');
        $inputs['active'] = filter_input(INPUT_POST, 'active');

        $service = new MemberService();
        $model = $service->update($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();

        } else {
            Yii::app()->session['success_msg'] = '使用者資料設定更新成功';
        }

        $this->redirect('update/' . $inputs['id']);
    }


    public function actionUpdate_device_permission()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {

            if (!isset($_POST['device_permission'])) {
                $inputs["device_permission"] = array();
                $inputs["device_permission_type"] = array();
                $inputs["id"] = filter_input(INPUT_POST, "id");

            } else {
                $inputs["device_permission"] = $_POST['device_permission'];
                $inputs["device_permission_type"] = $_POST['device_permission_type'];
                //var_dump($inputs["device_permission_type"]);
                //exit();
                $inputs["id"] = filter_input(INPUT_POST, "id");
            }

            $service = new MemberService();
            $model = $service->updateDevicePermission($inputs);

            if ($model->hasErrors()) {
                Yii::app()->session['error_msg'] = $model->getErrors();
            } else {
                Yii::app()->session['success_msg'] = '使用者儀器權限修改成功';
            }

            $this->redirect('update/' . $inputs['id']);

        } else {
            $this->redirect('list');
        }
    }

    public function actionDelete()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            $id = $_POST['id'];

            $model = Member::model()->findByPk($id);

            if ($model !== null) {
                $model->delete();
                $this->redirect('list');
            }
        } else {
            $this->redirect('list');
        }
    }


    private function doPostUpdatePasswd()
    {
        if (!CsrfProtector::comparePost()) {
            $this->redirect('list');
        }
        $this->clearMsg();
        $inputs = [];
        $inputs["id"] = filter_input(INPUT_POST, "id");
        $inputs["password"] = filter_input(INPUT_POST, "password");
        $inputs["password_confirm"] = filter_input(INPUT_POST, "password_confirm");

        $service = new MemberService();
        $model = $service->updateMemberPassword($inputs);

        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '使用者密碼修改成功';
        }

        $this->redirect('update/' . $inputs['id']);

    }

    // 抓取所有子分類
    public function actionGetgrp2()
    {

        if (isset($_POST['grp1'])) {

            $service = new UsergrpService();
            $data = $service->getchild($_POST['grp1']);

            $deal_data = array();

            // 先處理成比較好解析的格式
            foreach ($data as $key => $value) {

                array_push($deal_data, ["id" => $value->id,
                    "name" => $value->name]);

            }

            echo json_encode($deal_data);
        }

    }

    public function actionGetgrp3()
    {

        if (isset($_POST['grp2'])) {

            // $_POST['grp2']=4;

            $service = new MemberService();
            $data = $service->findByProfessor($_POST['grp2']);
            $deal_data = array();

            // 先處理成比較好解析的格式
            foreach ($data as $key => $value) {

                array_push($deal_data, ["id" => $value->id,
                    "name" => $value->name]);

            }

            echo json_encode($deal_data);
        }

    }

    public function actioncardmodify()
    {

        //var_dump($_POST);

        if (strlen($_POST['card']) != 10) {

            Yii::app()->session['error_msg'] = array(array('卡片號碼應為十碼'));
            $this->redirect('update/' . $_POST['id']);
            exit;

        }

        $stcard_sv = new SycardService();
        $stcard_sv->modifyCard($_POST['card'], $_POST['ocard'], $_POST['name'], "1xxxx\r");

        $stcard_sv = new StcardService();
        $motify_res = $stcard_sv->card_motify($_POST['id'], $_POST['name'], $_POST['ocard'], $_POST['card']);


        if ($motify_res == false) {

            Yii::app()->session['error_msg'] = array(array('改變卡號失敗'));
            $this->redirect('update/' . $_POST['id']);
            exit;

        } else {

            $service = new MemberService();
            $dbcres = $service->updatecardnum($_POST['id'], $_POST['card']);

            if ($dbcres == true) {
                Yii::app()->session['success_msg'] = '改變卡號成功';
                $this->redirect('update/' . $_POST['id']);
            }

        }
    }

    public function actiondownloadtoca()
    {

        $stcard_sv = new StcardService();
        $res = $stcard_sv->st_card_download();

        echo json_encode($res);
    }
}