<?php

class RegisterController extends Controller
{
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

        $inputs['account'] = filter_input(INPUT_POST, 'account');
        $inputs['password'] = filter_input(INPUT_POST, 'password');
        $inputs['password_confirm'] = filter_input(INPUT_POST, 'password_confirm');
        $inputs['name'] = filter_input(INPUT_POST, 'name');
        $inputs['sex'] = filter_input(INPUT_POST, 'sex');
        $inputs['phone1'] = filter_input(INPUT_POST, 'phone1');
        $inputs['phone2'] = filter_input(INPUT_POST, 'phone2');
        $inputs['tel_no1'] = filter_input(INPUT_POST, 'tel_no1');
        $inputs['tel_no2'] = filter_input(INPUT_POST, 'tel_no2');
        $inputs['email1'] = filter_input(INPUT_POST, 'email1');
        $inputs['email2'] = filter_input(INPUT_POST, 'email2');
        $inputs['status'] = filter_input(INPUT_POST, 'status');
        
        /*
        $inputs['stop_card_datetime'] = filter_input(INPUT_POST, 'stop_card_datetime');
        $inputs['stop_card_remark'] = filter_input(INPUT_POST, 'stop_card_remark');
        $inputs['stop_card_people'] = filter_input(INPUT_POST, 'stop_card_people');
        */
        
        $inputs['year'] = filter_input(INPUT_POST, 'year');
        $inputs['month'] = filter_input(INPUT_POST, 'month');
        $inputs['user_group'] = filter_input(INPUT_POST, 'level');
        $inputs['day'] = filter_input(INPUT_POST, 'day');
        $inputs['card_number'] = filter_input(INPUT_POST, 'card_number');
        $inputs['address'] = filter_input(INPUT_POST, 'address');
        $inputs['grp_lv1'] = filter_input(INPUT_POST, 'grp1');
        $inputs['grp_lv2'] = filter_input(INPUT_POST, 'grp2');
        $inputs['professor'] = filter_input(INPUT_POST, 'professor');
        
        $big5Name   = iconv("UTF-8","big5",$inputs['name']);
        $big5Lenght = strlen($big5Name);
        if( $big5Lenght > 20){

            Yii::app()->session['error_msg'] = array(array('姓名中文限制為10個字,英文為20個字'));
            $this->redirect('/chingda/admin/register');
            exit;
            
        }

        $service = new MemberService();
        $model = $service->createregister($inputs);


        if ($model->hasErrors()) {
            Yii::app()->session['error_msg'] = $model->getErrors();

            $this->redirect('/chingda/admin/register');
            return;
        } else {

            $stcard_sv = new StcardService();
            $cardexist = $stcard_sv->if_cardnum_exist($inputs['card_number']);

            //$sycard_sv = new SycardService();
            //$sycard_sv->modifyCard( '',$inputs['card_number'] ,$inputs['name'], "1xxxx\r" );

            if( !$cardexist && strlen($inputs['card_number'])== 10){
                $createcard_res = $stcard_sv->create_cardnum( $inputs['card_number'] , $inputs['name'] );
            }
            
            Yii::app()->session['success_msg'] = '新增使用者帳號成功';
            
            $this->redirect('/chingda/admin/login');
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

        $this->render('create', ['years' => $years, 'months' => $months, 'days' => $days, 'grp_data' => $grp_data, 'professor' => $professor, 'grp_data2' => $grp_data2,'accounts'=>$accounts]);
        $this->clearMsg();
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

}