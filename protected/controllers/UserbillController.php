<?php
class UserbillController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function beforeAction($action)
    {
        return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
    }
   

    public function professor_member_debt($all_member_id , $end ){
        if(!empty($all_member_id)){
            // 把所有需要用的service,先new好
            $model           = new MemberService;
            $billdoorservice = new BilldoorService;
            $billservice     = new BillService;
            // 抓出所有使用者
            $all_member_id = explode(',',$all_member_id);
            $pass_member_id = array(); //通過卡號檢查的 member_id
            foreach( $all_member_id as $key => $member_id){
                $allusers = array($model->findByMemId($member_id));
                $cardarr = str_split($allusers[0]->card_number, 5);
                if( !empty($cardarr[0]) && !empty($cardarr[1]) && strlen( trim($cardarr[0])) == 5 && strlen( trim($cardarr[1])) == 5 ){
                    array_push( $pass_member_id, $member_id );
                }
            }
            $pass_member_id = implode(',',$pass_member_id);
            $allbills = $billservice->get_by_mid_in_and_month( $pass_member_id, $end );
            $alldoorbills = $billdoorservice->get_by_mid_in_and_month( $pass_member_id, $end );
            if( count($alldoorbills) ==0 && count($allbills)==0){
                //echo '此會sss員暫無帳單資料';
                return 0;
                exit;
            }
            $discountarr=array();
            $onlydoor   =array();
            $onlydev    =array();                
            $tsun = 0;
            foreach ($alldoorbills as $alldoorbillk => $alldoorbill) {
                // 價格大於0 表示為進入
                if($alldoorbill['o_price'] > 0){
                    $tsun += 1;
                    // 將進入的時間轉換為時戳
                    $doortime = strtotime($alldoorbill['usedate']);
                    // 可接受折扣之時間(使用時間+24H)
                    $candiscount = $doortime+86400;
                
                    foreach ($allbills as $allbillk=> $allbill) {                             
                        //儀器使用開始時間轉為時戳
                        $devusetime = strtotime($allbill['startuse']);

                        // 可以產生折扣
                        if($allbill['position'] == $alldoorbill['dposition'] &&
                        $doortime <= $devusetime &&
                        $candiscount >= $devusetime
                        ){  
                            $tmpdata = array();
                            $tmpdata['doorbillid'] = $alldoorbill['id'];
                            $tmpdata['devbillid']  = $allbill['id'];
                            $tmpdata['doorname']   = $alldoorbill['doorname'];
                            $tmpdata['devname']    = $allbill['devname'];
                            $tmpdata['doorprice']  = $alldoorbill['o_price'];
                            $tmpdata['devprice']   = $allbill['d_price'];
                            $tmpdata['dis']        = $alldoorbill['o_price'];
                            $tmpdata['totalprice'] = $allbill['d_price'];
                            $tmpdata['doortime']   = $alldoorbill['usedate'];
                            $tmpdata['devtime']    = $allbill['startuse'];                               
                            array_push($discountarr,$tmpdata);                                
                            unset($allbills[$allbillk]);
                            unset($alldoorbills[$alldoorbillk]);
                            break;
                        }
                    }

                }else{
                    unset($alldoorbills[$alldoorbillk]);
                }

            }           
        
            // 剩餘的單純門禁
            foreach ($alldoorbills as $$alldoorbillk => $alldoorbill) {
                $tmpdata = array();
                $tmpdata['doorbillid'] = $alldoorbill['id'];
                $tmpdata['devbillid']  = '';
                $tmpdata['doorname']   = $alldoorbill['doorname'];
                $tmpdata['devname']    = '';
                $tmpdata['doorprice']  = $alldoorbill['o_price'];
                $tmpdata['devprice']   = 0;
                $tmpdata['dis']        = 0;
                $tmpdata['totalprice'] = $alldoorbill['o_price'];
                $tmpdata['doortime']   = $alldoorbill['usedate'];
                $tmpdata['devtime']    = '';                    
                array_push($onlydoor, $tmpdata);
            }

            // 剩餘的單純儀器
            foreach ($allbills as $allbillk=> $allbill) {

                $tmpdata = array();
                $tmpdata['doorbillid'] = '';
                $tmpdata['devbillid']  = $allbill['id'];
                $tmpdata['doorname']   = '';
                $tmpdata['devname']    = $allbill['devname'];
                $tmpdata['doorprice']  = '';
                $tmpdata['devprice']   = $allbill['d_price'];
                $tmpdata['dis']        = 0;
                $tmpdata['totalprice'] = $allbill['d_price'];
                $tmpdata['doortime']   = '';
                $tmpdata['devtime']    = $allbill['startuse'];                    
                array_push($onlydev, $tmpdata);
            }
            $finaldatas = array_merge($discountarr,$onlydoor,$onlydev);
            $tabcounts = array();
            $tabcounts['doorprice_total'] = 0;
            $tabcounts['devprice_total'] = 0;
            $tabcounts['dis_total'] = 0;
            $tabcounts['totalprice_total'] = 0;

            foreach ($finaldatas as $finaldatak => $finaldata) {
                    $tabcounts['doorprice_total'] += intval($finaldata['doorprice']);
                    $tabcounts['devprice_total'] += intval($finaldata['devprice']);
                    $tabcounts['dis_total'] += intval($finaldata['dis']);
                    $tabcounts['totalprice_total'] += intval($finaldata['totalprice']);
            }
            $change_bill_apply_sv = new Change_bill_applyService();
            $cbares = $change_bill_apply_sv->get_apply_before( $pass_member_id , $end );

            $all_disc = 0;
            foreach ($cbares as $cbarek => $cbare) {
                if($cbare['status'] == 1){
                    $all_disc += $cbare['gap'];
                }
            }
            return $tabcounts['totalprice_total'] - $all_disc;
        }else{
            echo '缺少參數'; 
        }
    }

    public function billCount($datas, $accounts_receivableAmount){
        //本期門禁費 = 本期機台費 = 機台原價總和 = 機台折扣總和 = 預約未使用及未提前取消費 = 應收金額 = 上期餘額
        $doorAmount = $divAmount = $div_originalAmount = $div_disAmount = $violationAmount = 0;
        $totalAmont = $accounts_receivableAmount;
        foreach ($datas['datas'] as $key => $value){
            $memberName = Member::model()->findByPk($value['member_id'])['name'];
            if($value['doorprice'] != ''){
                $format_data['door'][] = array(
                    'create_time' => $value['doortime'],
                    'user_name' => $memberName,
                    'door_name' => $value['doorname'],
                    'door_price' => $value['doorprice'],
                );
                $doorAmount += $value['doorprice'];
                $totalAmont -= $value['doorprice'];
            }
            if($value['devprice'] != ''){
                if($value['dis'] != $value['dev_o_price'] && $value['dis'] != 0)
                    $dis_price = $value['dev_o_price'] - $value['dis'];
                else
                    $dis_price = 0;
                $format_data['dev'][] = array(
                    'create_time' => $value['devtime'],
                    'use_time' => $value['dev_usetime'],
                    'user_name' => $memberName,
                    'dev_name' => $value['devname'],
                    'dev_usetime' => $value['dev_usetime'],
                    'dev_original_price' => $value['dev_o_price'],//原價
                    'dev_dis_price' => $value['dis'],//折扣後價格
                    'dev_dis' => $dis_price, //折扣
                    'dev_price' => $value['devprice'],
                    'violation_price' => 0
                );
                $div_originalAmount += $value['dev_o_price'];
                $div_disAmount += $dis_price;
                $divAmount += $value['devprice'];
                $totalAmont -= $value['devprice'];
            }
            if($value['violation_price'] != ''){
                $format_data['dev'][] = array(
                    'create_time' => $value['devtime'],
                    'use_time' => 0,
                    'user_name' => $memberName,
                    'dev_name' => $value['devname'],
                    'dev_usetime' => 0,
                    'dev_original_price' => 0,//原價
                    'dev_dis_price' => 0,//折扣後價格
                    'dev_dis' => 0,//折扣
                    'dev_price' => $value['violation_price'],
                    'violation_price' => $value['violation_price']
                );
                $violationAmount += $value['violation_price'];
                $divAmount += $value['violation_price'];
                $totalAmont -= $value['violation_price'];
            }
        }
        return array('receivableAmount' => $accounts_receivableAmount, 'divAmount' => $divAmount, 'doorAmount' => $doorAmount, 'totalAmount' => $totalAmont, 'violationAmount' => $violationAmount);
    }

    public function actionbill_record(){
        $memberService = new MemberService();
        $usergrpService = new UsergrpService();
        $billrecordService = new BillrecordService();
        $billotherfeeService = new BillotherfeeService();
        if( isset($_POST['date_start']) && $_POST['date_start'] !='' ){                   
            $date_start = $_POST['date_start']." 00:00:00";
        }else{
            $date_start = "0000-00-00 00:00:00";
        }

        if( isset($_POST['date_end']) && $_POST['date_end'] !='' ){                   
            $date_end = $_POST['date_end']." 23:59:59" ;
        }else{
            $date_end = date('Y-m-d H:i:s');
        }
        if( count($_POST) > 0 ){
            if($_POST['grp1'] != 0 && $_POST['grp2'] != 0 &&$_POST['grp3']!=0){
                $grp_lv1 = $_POST['grp1'];
                $grp_lv2 = $_POST['grp2'];
                $professor_id = $_POST['grp3'];
                $perfessor = Member::model()->findByPk($professor_id);
                $bill_record = $billrecordService->findRecord($professor_id, $date_start, $date_end);
                //$bill_other_fee = $billotherfeeService -> findRecord($professor_id, $date_start, $date_end);
            }else{
                if($_POST['grp1'] == 0){
                    $sql = "SELECT GROUP_CONCAT(id SEPARATOR ',') as professor_id FROM `member` where professor=0 group by professor";
                    $all_professor = Yii::app()->db->createCommand($sql)->queryAll();
                }elseif($_POST['grp2'] == 0){
                    $sql = "SELECT GROUP_CONCAT(id SEPARATOR ',') as professor_id FROM `member` where professor=0 and grp_lv1=" . $_POST['grp1'] . " group by professor";
                    $all_professor = Yii::app()->db->createCommand($sql)->queryAll();
                }else{
                    $sql = "SELECT GROUP_CONCAT(id SEPARATOR ',') as professor_id FROM `member` where professor=0 and grp_lv1=" . $_POST['grp1'] . "  and grp_lv2= " . $_POST['grp2'] . "group by professor";
                    $all_professor = Yii::app()->db->createCommand($sql)->queryAll();
                }    
                $all_professor = $all_professor[0]['professor_id'];      
                $bill_record = $billrecordService->findRecord($all_professor, $date_start, $date_end);
                //$bill_other_fee = $billotherfeeService -> findRecord($all_professor, $date_start, $date_end);
            }
        }else{
            $sql = "SELECT GROUP_CONCAT(id SEPARATOR ',') as professor_id FROM `member` where professor=0 group by professor";
            $all_professor = Yii::app()->db->createCommand($sql)->queryAll();
            $all_professor = $all_professor[0]['professor_id'];      
            $bill_record = $billrecordService->findRecord($all_professor, $date_start, $date_end);
            //$bill_other_fee = $billotherfeeService -> findRecord($all_professor, $date_start, $date_end);
        }
        $result = array();
        $result_other_fee = array();
        foreach ($bill_record as $key => $value) {
            $result[] = array(
                'bill_record_id' => $value['bill_record_id'],
                'grp1_name' => $value['grp1_name'],
                'grp1_id' => $value['grp1_id'],
                'grp2_name' => $value['grp2_name'],
                'grp2_id' => $value['grp2_id'],
                'professor_id' => $value['member_id'],
                'professor_name' => $value['professor_name'],
                'checkout_time' => $value['checkout_time'],
                'opening_balance' => $value['opening_balance'],
                'ending_balance' => $value['ending_balance'],
                'pay_amount' => $value['collection_refund'],
                'other_fee' => $value['other_fee'],
                'device_fee' => $value['device_fee'],
                'door_fee' => $value['door_fee'],
                //'bill_type' => $value['bill_type'] == 0 ? "退款":"收款",
                //'memo' => $value['memo'],
            );
        }
        $usr_grp = $usergrpService->store_user_grp();
        $usr_grp = json_encode($usr_grp,JSON_UNESCAPED_UNICODE);
        $this->render('bill_record', array('usr_grp' => $usr_grp, 'result' => $result)); 
    }
    public function actionCollection_refund(){
        $memberService = new MemberService();
        $usergrpService = new UsergrpService();
        $billcollectionrefundService = new BillcollectionrefundService();
        $billrecordService = new BillrecordService();
        $billotherfeeService = new BillotherfeeService();
        if( isset($_POST['date_start']) && $_POST['date_start'] !='' ){                   
            $date_start = $_POST['date_start']." 00:00:00";
        }else{
            $date_start = "0000-00-00 00:00:00";
        }

        if( isset($_POST['date_end']) && $_POST['date_end'] !='' ){                   
            $date_end = $_POST['date_end']." 23:59:59" ;
        }else{
            $date_end = date('Y-m-d H:i:s');
        }
        if( count($_POST) > 0 ){
            if($_POST['grp1'] != 0 && $_POST['grp2'] != 0 &&$_POST['grp3']!=0){
                $grp_lv1 = $_POST['grp1'];
                $grp_lv2 = $_POST['grp2'];
                $professor_id = $_POST['grp3'];
                $perfessor = Member::model()->findByPk($professor_id);
                $billcollectionrefund = $billcollectionrefundService->findCollectionRefundRecord($professor_id, $date_start, $date_end);
                $bill_other_fee = $billotherfeeService -> findRecord($professor_id, $date_start, $date_end);
            }else{
                if($_POST['grp1'] == 0){
                    $sql = "SELECT GROUP_CONCAT(id SEPARATOR ',') as professor_id FROM `member` where professor=0 group by professor";
                    $all_professor = Yii::app()->db->createCommand($sql)->queryAll();
                }elseif($_POST['grp2'] == 0){
                    $sql = "SELECT GROUP_CONCAT(id SEPARATOR ',') as professor_id FROM `member` where professor=0 and grp_lv1=" . $_POST['grp1'] . " group by professor";
                    $all_professor = Yii::app()->db->createCommand($sql)->queryAll();
                }else{
                    $sql = "SELECT GROUP_CONCAT(id SEPARATOR ',') as professor_id FROM `member` where professor=0 and grp_lv1=" . $_POST['grp1'] . "  and grp_lv2= " . $_POST['grp2'] . "group by professor";
                    $all_professor = Yii::app()->db->createCommand($sql)->queryAll();
                }    
                $all_professor = $all_professor[0]['professor_id'];      
                $billcollectionrefund = $billcollectionrefundService->findCollectionRefundRecord($all_professor, $date_start, $date_end);
                $bill_other_fee = $billotherfeeService -> findRecord($all_professor, $date_start, $date_end);
            }
        }else{
            // $sql = "SELECT GROUP_CONCAT(id SEPARATOR ',') as professor_id FROM `member` where professor=0 group by professor";
            // $all_professor = Yii::app()->db->createCommand($sql)->queryAll();
            // $all_professor = $all_professor[0]['professor_id'];      
            $all_professor ='';
            $billcollectionrefund = $billcollectionrefundService->findCollectionRefundRecord($all_professor, $date_start, $date_end);
            $bill_other_fee = $billotherfeeService -> findRecord($all_professor, $date_start, $date_end);
        }
        $result = array();
        $result_other_fee = array();
        foreach ($billcollectionrefund as $key => $value) {
            if(!isset($result[$value['member_id']])){
                $result[$value['member_id']] = array();
            }
            $result[$value['member_id']][] = array(
                'id' => $value['bill_collection_refund_id'],
                'grp1_name' => $value['grp1_name'],
                'grp1_id' => $value['grp1_id'],
                'grp2_name' => $value['grp2_name'],
                'grp2_id' => $value['grp2_id'],
                'professor_id' => $value['member_id'],
                'professor_name' => $value['professor_name'],
                'createtime' => $value['createtime_format'],
                'checkout_time' => $value['checkout_time'],
                'collection_refund_amount' => $value['collection_refund_amount'],
                'other_fee_amount' => '',
                'bill_type' => $value['collection_or_refund'] == 0 ? "退款":"收款",
                'memo' => $value['memo'],
            );
        }
        foreach ($bill_other_fee as $key => $value) {
            if(!isset($result[$value['member_id']])){
                $result[$value['member_id']] = array();
            }
            $result[$value['member_id']][] = array(
                'id' => $value['bill_other_fee_id'],
                'grp1_name' => $value['grp1_name'],
                'grp1_id' => $value['grp1_id'],
                'grp2_name' => $value['grp2_name'],
                'grp2_id' => $value['grp2_id'],
                'professor_id' => $value['member_id'],
                'professor_name' => $value['professor_name'],
                'createtime' => $value['fee_create_time_format'],
                'checkout_time' => $value['checkout_time'],
                'collection_refund_amount' => '',
                'other_fee_amount' => $value['fee_amount'],
                'bill_type' => "扣帳",
                'memo' => $value['memo'],
            );
        }
        $usr_grp = $usergrpService->store_user_grp();
        $usr_grp = json_encode($usr_grp,JSON_UNESCAPED_UNICODE);
        $this->render('collection_refund', array('usr_grp' => $usr_grp, 'result' => $result)); 
    }
    public function actionsearch(){
        $memberService = new MemberService();
        $usergrpService = new UsergrpService();
        $billcountService = new BillcountService();
        $billrecordService = new BillrecordService();
        $billotherfeeService = new BillotherfeeService();
        $billcollectionrefundService = new BillcollectionrefundService();
        $date_start = $date_end = '';
        $accounts_receivableAmount = $other_fee = 0;
        $last_bil_record = '';
        $professor_data = $member_id = $result = array();
        if( isset($_POST['date_end']) && $_POST['date_end'] !='' ){                   
            $date_end = $_POST['date_end']." " . date('H:i:s');
        }else{
            $date_end = date('Y-m-d H:i:s');
        }

        if( count($_POST) > 0 ){
            $grp_lv1 = $_POST['grp1'];
            $grp_lv2 = $_POST['grp2'];
            $professor_id = $_POST['grp3'];
            if($_POST['grp1'] != 0 && $_POST['grp2'] != 0 &&$_POST['grp3']!=0){
                $perfessor = Member::model()->findByPk($professor_id);
                $user_grp1 = Usergrp::model()->findByPk($grp_lv1)['name'];
                $user_grp2 = Usergrp::model()->findByPk($grp_lv2)['name'];
                $perfessor_data = array('grp_lv1_id' => $grp_lv1, 'grp_lv2_id' => $grp_lv2, 'perfessor_id' => $professor_id, 'grp_lv1' => $user_grp1, 'grp_lv2' => $user_grp2, 'professor_name' => $perfessor['name']);
                $professor_member = $memberService -> findProfessorMember($professor_id);
                foreach ($professor_member as $member_key => $member_value) {
                    array_push($member_id,$member_value['id']);
                }
                $member_id = implode(',',$member_id);
                $last_bill_record_data = $billrecordService -> getLastRecord($professor_id);
                if($last_bill_record_data){
                    $last_bil_record = $last_bill_record_data->checkout_time;
                    $accounts_receivableAmount = (int)$last_bill_record_data->ending_balance;
                    // if( $last_bill_record_data->bill_type == 0) //退款
                    //     $accounts_receivableAmount = ((int)$last_bill_record_data->ending_balance - (int)$last_bill_record_data->pay_amount);
                    // else
                    //     $accounts_receivableAmount = ((int)$last_bill_record_data->ending_balance + (int)$last_bill_record_data->pay_amount);
                }
                $bill_other_fee = $billotherfeeService->get_professor_other_fee_without_record($professor_id, $date_end);
                if($bill_other_fee[0]['total'])
                    $other_fee = $bill_other_fee[0]['total']; 
                $collection_refund = $billcollectionrefundService->findCollectionRefundWithCheckout($professor_id,$date_end,0);
                $datas = $billcountService -> getProfessorMemberBill($member_id,$date_end);
                $datas = $billcountService -> billDataFormat($datas);
                $bill_data = $this -> billCount($datas,$accounts_receivableAmount);
                $bill_data['totalAmount'] -=  $other_fee;
                $bill_data['other_fee'] = $other_fee;
                $bill_data['totalAmount'] +=  $collection_refund;
                $bill_data['collection_refundAmount'] =  $collection_refund;
                $bill_data['last_checkout_time']=$last_bil_record;
                $result[] = array( 'perfessor_data' => $perfessor_data, 'bill_data' => $bill_data ); 
            }else{
                if($_POST['grp1'] == 0){
                    $all_professor = $memberService -> findAllProfessor();
                }elseif($_POST['grp2'] == 0){
                    $all_professor = Member::model()->findAll([
                        'condition' => 'grp_lv1=:grp_lv1 and professor=:professor',
                        'params' => [
                            ':professor' => 0,
                            ':grp_lv1' => $_POST['grp1'],
                        ]
                    ]);
                }else{
                    $all_professor = Member::model()->findAll([
                        'condition' => 'grp_lv1=:grp_lv1 and grp_lv2=:grp_lv2 and professor=:professor',
                        'params' => [
                            ':professor' => 0,
                            ':grp_lv1' => $_POST['grp1'],
                            ':grp_lv2' => $_POST['grp2'],
                        ]
                    ]);
                }          
                foreach ($all_professor as $key => $value) {
                    $accounts_receivableAmount = $other_fee = 0;
                    $last_bil_record = '';
                    $member_id = array();
                    $perfessor = Member::model()->findByPk($value->id);
                    $user_grp1 = Usergrp::model()->findByPk($value->grp_lv1)['name'];
                    $user_grp2 = Usergrp::model()->findByPk($value->grp_lv2)['name'];
                    $perfessor_data = array('grp_lv1_id' => $value->grp_lv1, 'grp_lv2_id' => $value->grp_lv2, 'perfessor_id' => $value->id, 'grp_lv1' => $user_grp1, 'grp_lv2' => $user_grp2, 'professor_name' => $perfessor['name']);
                    $professor_member = $memberService -> findProfessorMember($value->id);
                    foreach ($professor_member as $member_key => $member_value) {
                        array_push($member_id,$member_value['id']);
                    }
                    $member_id = implode(',',$member_id);
                    $last_bill_record_data = $billrecordService -> getLastRecord($value->id);
                    if($last_bill_record_data){
                        $accounts_receivableAmount = (int)$last_bill_record_data->ending_balance;
                        $last_bil_record = $last_bill_record_data->checkout_time;
                        // if( $last_bill_record_data->bill_type == 0) //退款
                        //     $accounts_receivableAmount = ((int)$last_bill_record_data->ending_balance - (int)$last_bill_record_data->collection_refund);
                        // else
                        //     $accounts_receivableAmount = ((int)$last_bill_record_data->ending_balance + (int)$last_bill_record_data->collection_refund);
                    }
                    $bill_other_fee = $billotherfeeService->get_professor_other_fee_without_record($value->id, $date_end);
                    if($bill_other_fee[0]['total'])
                        $other_fee = $bill_other_fee[0]['total']; 
                    $collection_refund = $billcollectionrefundService->findCollectionRefundWithCheckout($value->id,$date_end,0);
                    $datas = $billcountService -> getProfessorMemberBill( $member_id, $date_end );
                    $datas = $billcountService -> billDataFormat($datas);
                    $bill_data = $this -> billCount($datas,$accounts_receivableAmount);
                    $bill_data['totalAmount'] -=  $other_fee;
                    $bill_data['other_fee'] = $other_fee;
                    $bill_data['totalAmount'] +=  $collection_refund;
                    $bill_data['collection_refundAmount'] =  $collection_refund;
                    $bill_data['last_checkout_time']=$last_bil_record;
                    $result[] = array( 'perfessor_data' => $perfessor_data, 'bill_data' => $bill_data);
                }
            }                
        }else{
            $all_professor = $memberService -> findAllProfessor();
            foreach ($all_professor as $key => $value) {
                $accounts_receivableAmount = $other_fee = 0;
                $last_bil_record = '';
                $member_id = array();
                $perfessor = Member::model()->findByPk($value->id);
                $user_grp1 = Usergrp::model()->findByPk($value->grp_lv1)['name'];
                $user_grp2 = Usergrp::model()->findByPk($value->grp_lv2)['name'];
                $perfessor_data = array('grp_lv1_id' => $value->grp_lv1, 'grp_lv2_id' => $value->grp_lv2, 'perfessor_id' => $value->id, 'grp_lv1' => $user_grp1, 'grp_lv2' => $user_grp2, 'professor_name' => $perfessor['name']);
                $professor_member = $memberService -> findProfessorMember($value->id);
                foreach ($professor_member as $member_key => $member_value) {
                    array_push($member_id,$member_value['id']);
                }
                $member_id = implode(',',$member_id);
                $last_bill_record_data = $billrecordService -> getLastRecord($value->id);
                if($last_bill_record_data){
                    $last_bil_record = $last_bill_record_data->checkout_time;
                    $accounts_receivableAmount = (int)$last_bill_record_data->ending_balance;
                    // if( $last_bill_record_data->bill_type == 0) //退款
                    //     $accounts_receivableAmount = ((int)$last_bill_record_data->ending_balance - (int)$last_bill_record_data->pay_amount);
                    // else
                    //     $accounts_receivableAmount = ((int)$last_bill_record_data->ending_balance + (int)$last_bill_record_data->pay_amount);
                }                   
                $bill_other_fee = $billotherfeeService->get_professor_other_fee_without_record($value->id, $date_end);
                if($bill_other_fee[0]['total'])
                    $other_fee = $bill_other_fee[0]['total'];         
                $collection_refund = $billcollectionrefundService->findCollectionRefundWithCheckout($value->id,$date_end,0); 
                $datas = $billcountService -> getProfessorMemberBill( $member_id, $date_end );
                $datas = $billcountService -> billDataFormat($datas);
                $bill_data = $this -> billCount($datas,$accounts_receivableAmount);
                $bill_data['totalAmount'] -=  $other_fee;
                $bill_data['other_fee'] = $other_fee;
                $bill_data['totalAmount'] +=  $collection_refund;
                $bill_data['collection_refundAmount'] =  $collection_refund;
                $bill_data['last_checkout_time']=$last_bil_record;
                $result[] = array( 'perfessor_data' => $perfessor_data, 'bill_data' => $bill_data );
            }
        }
        $groups = ExtGroup::model()->group_list();     
        $grp_data = $usergrpService->getLevelOneAll();
        $grp_data2 = $usergrpService->getLevelTwoAll();
        $service = new MemberService();
        $professor = $service->get_all_professor(2);
        $usr_grp = $usergrpService->store_user_grp();
        $usr_grp = json_encode($usr_grp,JSON_UNESCAPED_UNICODE);
        $this->render('professor_detail_list', array('usr_grp' => $usr_grp, 'result' => $result, 'groups' => $groups, 'grp_data' => $grp_data, 'professor' => $professor, 'grp_data2' => $grp_data2, 'date_end' => $date_end)); 
    }

    public function actioncreate_other_fee(){
        if($_POST){
            $billotherfeeService = new BillotherfeeService();
            $perfessor_id = $_POST['professor_id'];
            $fee_create_time = $_POST['fee_create_time'];
            $fee_amount = $_POST['fee_amount'];
            $memo = $_POST['other_fee_memo'];
            $create_member_id = Yii::app()->session['uid'];
            $create_member_type = (Yii::app()->session['personal'])?1:0;
            $createtime = date('Y-m-d H:i:s');
            $fee_create_time = date($fee_create_time . " H:i:s");
            $bill_other_fee_create = $billotherfeeService -> create($perfessor_id,$fee_amount,$fee_create_time,$createtime,$create_member_id,$create_member_type,$memo);
            if($bill_other_fee_create){
                $result = true;
            }else{
                $result = false;
            }
        }
        echo json_encode($result);exit();
    }
    public function actioncreate_collection_refund(){
        $result = false;
        if($_POST){
            $billcollectionfundService = new BillcollectionrefundService;
            $perfessor_id = $_POST['perfessor_id'];
            $collection_or_refund = $_POST['collection_or_refund'];
            $collection_refund_type = $_POST['collection_refund_type'];
            $collection_refund_create_time = $_POST['collection_refund_create_time'];
            $collection_refund_amount = $_POST['collection_refund_amount'];
            $memo = $_POST['memo'];
            $handman_member_id = Yii::app()->session['uid'];
            $handman_member_type = (Yii::app()->session['personal'])?1:0;
            $billcollectionfundService -> create($perfessor_id,$collection_or_refund,$collection_refund_type,$collection_refund_create_time,$collection_refund_amount,$memo,$handman_member_id,$handman_member_type);
            if($billcollectionfundService){
                $result = true;
            }else{
                $result = false;
            }
        }
        echo json_encode($result);exit();
    }
    public function actioncreate_bill_record(){
        $result = false;
        if($_POST){
            $memberService = new MemberService();
            $billrecordService = new BillrecordService();
            $billotherfeeService = new BillotherfeeService();
            $billService = new BillService();
            $billdoorService = new BilldoorService();
            $billcollectionrefundService = new BillcollectionrefundService();
            $perfessor_id = $_POST['perfessor_id'];
            $opening_balance = $_POST['opening_balance'];
            $other_fee = $_POST['other_fee'];
            $device_fee = $_POST['device_fee'];
            $door_fee = $_POST['door_fee'];
            $ending_balance = $_POST['ending_balance'];
            $checkout_time = $_POST['checkout_time'];
            $collection_refund = $_POST['collection_refund'];
            $receive_member_id = Yii::app()->session['uid'];
            $receive_member_type = (Yii::app()->session['personal'])?1:0;
            $createtime = date('Y-m-d H:i:s');
            $bill_record_create = $billrecordService -> create($perfessor_id,$opening_balance,$other_fee,$device_fee,$door_fee,$ending_balance,$collection_refund,$receive_member_id,$receive_member_type,$checkout_time,$createtime);
            //var_dump($bill_record_create);exit();
            $bill_record_id = $bill_record_create->bill_record_id;
            $professor = Member::model()->findByPk($perfessor_id);
            $profess_member_sql = 'SELECT GROUP_CONCAT(id) as id FROM `member` WHERE `professor` = '.$perfessor_id;
            $professor_member = Yii::app()->db->createCommand($profess_member_sql)->queryAll();
            $member_id = $professor_member[0]['id'];
            // $professor_member = $memberService -> findProfessorMember($perfessor_id);
            // $member_id = array();
            // foreach ($professor_member as $member_key => $member_value) {
            //     array_push($member_id,$member_value['id']);
            // }
            // $member_id = implode(',',$member_id);
            $update_bill_status = $update_bill_door_status  =true;
            if(strlen($member_id)>0){
                $update_bill_status = $billService -> update_bill_status($checkout_time,$member_id,$bill_record_id);
                $update_bill_door_status = $billdoorService -> update_bill_door_status($checkout_time,$member_id,$bill_record_id);
            }
            
            $update_bill_other_fee_status = $billotherfeeService -> update_other_fee_status($checkout_time,$perfessor_id,$bill_record_id);
            $update_bill_collection_refund_status = $billcollectionrefundService -> update_collection_refund_status($checkout_time,$perfessor_id,$bill_record_id);
            if($bill_record_create && $update_bill_status && $update_bill_door_status){
                $result = true;
            }else{
                $result = false;
            }
        }
        echo $result;
    }

    public function actionsearch_bak(){

        //`$this->clearMsg();
        // 判斷有無執行找尋
        $memberService = new MemberService();
        $grp_service   = new UsergrpService();
        $date_start = $date_end = '';
        if(isset($_POST['grp1'])){
            if($_POST['grp1'] == 0 && $_POST['grp2'] == 0 &&$_POST['grp3']==0){
                if(strlen($_POST['date_start'])>0 && strlen($_POST['date_end'])>0){
                    $date_start = $_POST['date_start'];
                    $date_end = $_POST['date_end'];
                    $datas = $memberService->findMemberlistWithDeviceRecord($_POST['date_start'],$_POST['date_end']);
                }else{
                    $datas = $memberService->findMemberlistarr();
                }
            }else{
                $grparr = array();
                if($_POST['grp2'] == 0){
                    
                    $grpdatas = $grp_service->getchild($_POST['grp1']);
    
                    foreach ($grpdatas as $grpdatak => $grpdata ) {
                        array_push($grparr, $grpdata['id']);
    
                    }
                }else{
                    array_push($grparr, $_POST['grp2']);
                }
                if( $_POST['grp3'] == 0){
                    $_POST['grp3'] = '';
                }
                if(strlen($_POST['date_start'])>0 && strlen($_POST['date_end'])>0){
                    $date_start = $_POST['date_start'];
                    $date_end = $_POST['date_end'];
                    $datas = $memberService->get_mem_by_gp_with_deviceRecord($grparr, $_POST['grp3'],$_POST['date_start'],$_POST['date_end']);
                }else {
                    $datas = $memberService->get_mem_by_gp($grparr, $_POST['grp3']);
                }
         
            }
        }else{
            if((isset($_POST['date_start']) && strlen($_POST['date_start'])>0) && (isset($_POST['date_end']) && strlen($_POST['date_end'])>0)){
                $date_start = $_POST['date_start'];
                $date_end = $_POST['date_end'];
                $datas = $memberService->findMemberlistWithDeviceRecord($_POST['date_start'],$_POST['date_end']);
            }else{
                $datas = $memberService->findMemberlistarr();
            }
        }
        

        $groups = ExtGroup::model()->group_list();

        
        $grp_data = $grp_service->getLevelOneAll();
        $grp_data2 = $grp_service->getLevelTwoAll();


        $service = new MemberService();
        $professor = $service->get_all_professor(2);

        #echo json_encode($datas);exit();
        foreach ($datas as $datak => $data) {

            $isPayOff = $this->payoff($data['id']);
            
            if($isPayOff){

                $datas["$datak"]['payoff'] = true;

            }else{
                $datas["$datak"]['payoff'] = false;
            }
        }

        Yii::app()->session['professorStudentList'] = $datas;
        Yii::app()->session['professorNameList']    = $professor;

        $this->render('professor_detail_list', array('datas' => $datas, 'groups' => $groups, 'grp_data' => $grp_data, 'professor' => $professor, 'grp_data2' => $grp_data2, 'date_start' => $date_start, 'date_end' => $date_end));
    }

    public function actionbillview($id){

        if(!empty($id)){
        // 把所有需要用的service,先new好
        $model           = new MemberService;
        $billdoorservice = new BilldoorService;
        $billCountSv = new BillcountService();
        $billservice     = new BillService;
        // 抓出所有使用者
        $allusers = array($model->findByMemId($id));
        foreach ($allusers as $alluserk => $alluser) {
            // 判斷卡號10馬無誤
            $cardarr = str_split($alluser['card_number'], 5);

            if( !empty($cardarr[0]) && 
                !empty($cardarr[1]) && 
                strlen( trim($cardarr[0])) == 5 &&
                strlen( trim($cardarr[1])) == 5 && $alluser['card_number'] !='0000000000'){
                
                
                if( $_POST['date_start'] !='' ){
                    
                    $start = $_POST['date_start']." 00:00:00";
                    $end   = $_POST['date_end']." 23:59:59";
                   
                }else{

//                    $daynum = date("t");//本月天數
//                    $start  = date("Y-m")."-01 00:00:00";
//                    $end    = date("Y-m")."-$daynum 23:59:59";
                    $daynum = date("t");//本月天數
                    $start = date("Y-m")."-01 00:00:00";
                    $end   = date('Y-m-d 23:59:59', strtotime('-1 day', strtotime($start)));
                    //$start = date('Y-m-01 00:00:00', strtotime('-1 month'));
                    $start = '0000-00-00 00:00:00';
                }
                /*
                echo "本月:<br>";
                echo $start;
                echo "<br>本月:<br>";
                echo $end;
                echo "<br><br>";
                */
                $beforedate = date('Y-m-d', strtotime('-1 day', strtotime($start)));
                $userdebt =  $this->debt($id,$start,$end);
                $allbills = $billservice->get_by_mid_and_month($id,$start,$end );
                $alldoorbills = $billdoorservice->get_by_mid_and_month($id,$start,$end );
                /*echo "<br>單月開始:<br>";
                var_dump($allbills);
                echo "<br><br>";
                var_dump($alldoorbills);
                echo "------------------------------------------------<br>";*/

                /*if( count($alldoorbills) ==0 && count($allbills)==0){
                    echo '此會員暫無帳單資料';
                    exit;
                }*/


                $discountarr=array();
                $onlydoor   =array();
                $onlydev    =array();

                foreach ($alldoorbills as $alldoorbillk => $alldoorbill) {

                    // 價格大於0 表示為進入
                    if($alldoorbill['o_price'] > 0){

                        // 將進入的時間轉換為時戳
                        $doortime = strtotime($alldoorbill['usedate']);

                        // 可接受折扣之時間(使用時間+24H)
                        $candiscount = $doortime+86400;

                        foreach ($allbills as $allbillk=> $allbill) {

                            //儀器使用開始時間轉為時戳
                            $devusetime = strtotime($allbill['startuse']);

                            // 可以產生折扣

                            if($allbill['position'] == $alldoorbill['dposition'] &&
                               $doortime <= $devusetime &&
                               $candiscount >= $devusetime
                            ){
                                $tmpdata = array();
                                $tmpdata['doorbillid'] = $alldoorbill['id'];
                                $tmpdata['devbillid']  = $allbill['id'];
                                $tmpdata['doorname']   = $alldoorbill['doorname'];
                                $tmpdata['devname']    = $allbill['devname'];
                                $tmpdata['doorprice']  = $alldoorbill['o_price'];
                                $tmpdata['devprice']   = $allbill['d_price'];
                                $tmpdata['dis']        = $alldoorbill['o_price'];
                                $tmpdata['totalprice'] = $allbill['d_price'];
                                $tmpdata['doortime']   = $alldoorbill['usedate'];
                                $tmpdata['devtime']    = $allbill['startuse'];

                                array_push($discountarr,$tmpdata);

                                unset($allbills[$allbillk]);
                                unset($alldoorbills[$alldoorbillk]);
                                break;
                            }
                        }

                    }else{
                        unset($alldoorbills[$alldoorbillk]);
                    }

                }

                // 剩餘的單純門禁
                foreach ($alldoorbills as $alldoorbillk => $alldoorbill) {

                    $tmpdata = array();
                    $tmpdata['doorbillid'] = $alldoorbill['id'];
                    $tmpdata['devbillid']  = '';
                    $tmpdata['doorname']   = $alldoorbill['doorname'];
                    $tmpdata['devname']    = '';
                    $tmpdata['doorprice']  = $alldoorbill['o_price'];
                    $tmpdata['devprice']   = 0;
                    $tmpdata['dis']        = 0;
                    $tmpdata['totalprice'] = $alldoorbill['o_price'];
                    $tmpdata['doortime']   = $alldoorbill['usedate'];
                    $tmpdata['devtime']    = '';
                    array_push($onlydoor, $tmpdata);
                }


                // 剩餘的單純儀器
                foreach ($allbills as $allbillk=> $allbill) {

                    $tmpdata = array();
                    $tmpdata['doorbillid'] = '';
                    $tmpdata['devbillid']  = $allbill['id'];
                    $tmpdata['doorname']   = '';
                    $tmpdata['devname']    = $allbill['devname'];
                    $tmpdata['doorprice']  = '';
                    $tmpdata['devprice']   = $allbill['d_price'];
                    $tmpdata['dis']        = 0;
                    $tmpdata['totalprice'] = $allbill['d_price'];
                    $tmpdata['doortime']   = '';
                    $tmpdata['devtime']    = $allbill['startuse'];
                    array_push($onlydev, $tmpdata);
                }

                $finaldatas = array_merge($discountarr,$onlydoor,$onlydev);

                // 特殊狀況總金額
                $specialAmount = 0;
                $datas = $billCountSv->getBill($id,$start,$end);
                $finaldatas = $billCountSv->billDataFormat($datas);

                #echo json_encode($finaldatas);exit();
                $tablecode="<table id='printtable' class='table table-bordered'>";
                $tablecode.="<thead>
                         <tr>
                         <th>累計未繳款金額</th>
                         <th>$userdebt</th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         </tr>
                         </thead>
                         <tbody>";
                $tablecode.="<thead>
                         <tr>
                         <th>門禁名稱</th>
                         <th>門禁使用時間</th>
                         <th>門禁次數</th>
                         <th>儀器名稱</th>
                         <th>儀器使用時間</th>
                         <th>門禁費用</th>
                         <th>儀器費用</th>
                         <th>門禁抵消費用</th>
                         <th>應付金額</th>
                         </tr>
                         </thead>
                         <tbody>";

                foreach ($finaldatas['datas'] as $finaldatak => $finaldata) {
                    $tablecode .="<tr>";
                    $tablecode .="<td>$finaldata[doorname]</td>";
                    $tablecode .="<td>$finaldata[doortime]</td>";
                    $tablecode .="<td>$finaldata[in_door_count]</td>";
                    $tablecode .="<td>$finaldata[devname]</td>";
                    $tablecode .="<td>$finaldata[devtime]</td>";
                    $tablecode .="<td>$finaldata[doorprice]</td>";
                    $tablecode .="<td>$finaldata[devprice]</td>";
                    $tablecode .="<td>$finaldata[dis]</td>";
                    $tablecode .="<td>$finaldata[totalprice]</td>";
                    $tablecode .="</tr>";
                }

                // 添加帳單調整
                $tablecode.="</tbody><thead>
                         <tr>
                         <th>特殊情況申請理由</th>
                         <th>是否通過</th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th>扣除金額</th>
                         </tr>
                         </thead>
                         <tbody>";
                $specialCases = $billCountSv->getSpecilCase($id,$start,$end);
                $gaptotal = 0;
                if( count($specialCases) > 0){
                    // 將所有特殊情形印出
                    foreach ($specialCases as $specialCasek => $specialCase) {
                        switch ($specialCase['status']) {
                            case '0':
                                $specialstatus = '尚未審核';
                                break;
                            case '1':
                                $specialstatus = '審核通過';
                                break;
                            case '2':
                                $specialstatus = '審核失敗';
                                break;
                            case '3':
                                $specialstatus = '已付款';
                                break;
                        }
                        $tablecode .= "<tr>
                         <td>{$specialCase['des']}</td>
                         <td>$specialstatus</td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td>{$specialCase['gap']}</td>
                         </tr>";
                        $specialAmount +=$specialCase['gap'];
                    }

                }else{
                    $tablecode .= "<tr>
                    <td>無</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>";
                }

                $tablecode.="</tbody><thead>
                         <tr>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th>特殊情況異動</th>
                         <th>門禁費用總和</th>
                         <th>儀器費用總和</th>
                         <th>門禁抵消費用總和</th>
                         <th>應付金額總和</th>
                         </tr>
                         </thead>
                         <tbody>";

                $finaldatas['count']['amount'] -= $gaptotal;

                $final = $finaldatas['count']['amount']-$gaptotal;
                $tablecode .="<tr>";
                $tablecode .="<td></td>";
                $tablecode .="<td></td>";
                $tablecode .="<td></td>";
                $tablecode .="<td></td>";
                $tablecode .="<td>-$gaptotal</td>";
                $tablecode .="<td>".$finaldatas['count']['doorAmount']."</td>";
                $tablecode .="<td>".$finaldatas['count']['divAmount']."</td>";
                $tablecode .="<td>-".$finaldatas['count']['disAmount']."</td>";
                $tablecode .="<td>".$finaldatas['count']['amount']."</td>";
                $tablecode .="</tr>";
                Yii::app()->session['userbill'] = $finaldatas;
                Yii::app()->session['debt'] = $userdebt;
                Yii::app()->session['userbilltabel'] = $tablecode;
                Yii::app()->session['billid'] =$id;
                Yii::app()->session['billdate'] =$start;
                $tablecode .= '</tbody><table>';
                $this->render('usereport',['model'    => $data = array(),
                                   'professor'=> $proarr= array(),
                                   'device'   => $device= array(),
                                   'grp'      => $grp= array(),
                                   'tablecode'   => $tablecode,
                                   'id'=>$id
                                  ]);

            }else{
                echo '此會員尚無卡號,無法查詢';
            }
        }
        }else{
            echo '缺少參數';
        }

    }
    public function debt($id , $start , $end ){
        


        if(!empty($id)){
        // 把所有需要用的service,先new好
        $model           = new MemberService;
        $billdoorservice = new BilldoorService;
        $billservice     = new BillService;

        // 抓出所有使用者
        $allusers = array($model->findByMemId($id));

        foreach ($allusers as $alluserk => $alluser) {
            // 判斷卡號10馬無誤
            $cardarr = str_split($alluser['card_number'], 5);
            if( !empty($cardarr[0]) && 
                !empty($cardarr[1]) && 
                strlen( trim($cardarr[0])) == 5 &&
                strlen( trim($cardarr[1])) == 5 ){
                
                $daynum = date("t");//本月天數
                //$start  = date("Y-m")."-01 00:00:00";
                //$end    = date("Y-m")."-$daynum 23:59:59";
                
                $end = date('Y-m-d', strtotime('-1 day', strtotime($start)));
                $end .= ' 23:59:59';
                $start = '0000-00-00 00:00:00';
                   
                //echo $end;
                $allbills = $billservice->get_by_mid_and_month($alluser['id'],$start,$end );
                
                /*var_dump($allbills);
                echo "<br>";
                echo $start;
                echo "<br>";
                echo $end;
                echo "<br>";
                echo "<hr/>";*/
                
                /*
                echo $start;
                echo "<br>";
                echo $end;
                echo "<br>";
                */

                $alldoorbills = $billdoorservice->get_by_mid_and_month($alluser['id'],$start,$end );


                if( count($alldoorbills) ==0 && count($allbills)==0){
                    //echo '此會sss員暫無帳單資料';
                    return 0;
                    exit;
                }

                $discountarr=array();
                $onlydoor   =array();
                $onlydev    =array();
                 
                $tsun = 0;
                foreach ($alldoorbills as $alldoorbillk => $alldoorbill) {
             

                    // 價格大於0 表示為進入
                    if($alldoorbill['o_price'] > 0){
                        $tsun += 1;
                        // 將進入的時間轉換為時戳
                        $doortime = strtotime($alldoorbill['usedate']);

                        // 可接受折扣之時間(使用時間+24H)
                        $candiscount = $doortime+86400;
                        
                        foreach ($allbills as $allbillk=> $allbill) {
                              
                            //儀器使用開始時間轉為時戳
                            $devusetime = strtotime($allbill['startuse']);

                            // 可以產生折扣
                            if($allbill['position'] == $alldoorbill['dposition'] &&
                               $doortime <= $devusetime &&
                               $candiscount >= $devusetime
                            ){  
                                $tmpdata = array();
                                $tmpdata['doorbillid'] = $alldoorbill['id'];
                                $tmpdata['devbillid']  = $allbill['id'];
                                $tmpdata['doorname']   = $alldoorbill['doorname'];
                                $tmpdata['devname']    = $allbill['devname'];
                                $tmpdata['doorprice']  = $alldoorbill['o_price'];
                                $tmpdata['devprice']   = $allbill['d_price'];
                                $tmpdata['dis']        = $alldoorbill['o_price'];
                                $tmpdata['totalprice'] = $allbill['d_price'];
                                $tmpdata['doortime']   = $alldoorbill['usedate'];
                                $tmpdata['devtime']    = $allbill['startuse'];
                                
                                array_push($discountarr,$tmpdata);
                                
                                unset($allbills[$allbillk]);
                                unset($alldoorbills[$alldoorbillk]);
                                break;
                            }
                        }

                    }else{
                        unset($alldoorbills[$alldoorbillk]);
                    }

                }           
               
                // 剩餘的單純門禁
                foreach ($alldoorbills as $$alldoorbillk => $alldoorbill) {

                    $tmpdata = array();
                    $tmpdata['doorbillid'] = $alldoorbill['id'];
                    $tmpdata['devbillid']  = '';
                    $tmpdata['doorname']   = $alldoorbill['doorname'];
                    $tmpdata['devname']    = '';
                    $tmpdata['doorprice']  = $alldoorbill['o_price'];
                    $tmpdata['devprice']   = 0;
                    $tmpdata['dis']        = 0;
                    $tmpdata['totalprice'] = $alldoorbill['o_price'];
                    $tmpdata['doortime']   = $alldoorbill['usedate'];
                    $tmpdata['devtime']    = '';                    
                    array_push($onlydoor, $tmpdata);
                }

                // 剩餘的單純儀器
                foreach ($allbills as $allbillk=> $allbill) {

                    $tmpdata = array();
                    $tmpdata['doorbillid'] = '';
                    $tmpdata['devbillid']  = $allbill['id'];
                    $tmpdata['doorname']   = '';
                    $tmpdata['devname']    = $allbill['devname'];
                    $tmpdata['doorprice']  = '';
                    $tmpdata['devprice']   = $allbill['d_price'];
                    $tmpdata['dis']        = 0;
                    $tmpdata['totalprice'] = $allbill['d_price'];
                    $tmpdata['doortime']   = '';
                    $tmpdata['devtime']    = $allbill['startuse'];                    
                    array_push($onlydev, $tmpdata);
                }
                /*
                echo '折價:<br>';
                var_dump($discountarr);
                echo '<br>純門:<br>';
                var_dump($onlydoor);
                echo '<br>純儀器:<br>';
                var_dump($onlydev);
                */
                $finaldatas = array_merge($discountarr,$onlydoor,$onlydev);
                
                $tablecode="<table id='printtable' class='table table-bordered'>";
                $tablecode.="<thead>
                         <tr>
                         <th>門禁名稱</th>
                         <th>門禁使用時間</th>
                         <th>儀器名稱</th>
                         <th>儀器使用時間</th>
                         <th>門禁費用</th>
                         <th>儀器費用</th>
                         <th>門禁抵消費用</th>
                         <th>應付金額</th>
                         </tr>
                         </thead>
                         <tbody>";
                $tabcounts = array();
                $tabcounts['doorprice_total'] =0;
                $tabcounts['devprice_total']  =0;
                $tabcounts['dis_total'] =0;
                $tabcounts['totalprice_total'] =0;

                foreach ($finaldatas as $finaldatak => $finaldata) {
                        $tablecode .="<tr>";
                        $tablecode .="<td>$finaldata[doorname]</td>";
                        $tablecode .="<td>$finaldata[doortime]</td>";
                        $tablecode .="<td>$finaldata[devname]</td>";
                        $tablecode .="<td>$finaldata[devtime]</td>";
                        $tablecode .="<td>$finaldata[doorprice]</td>";
                        $tablecode .="<td>$finaldata[devprice]</td>";
                        $tablecode .="<td>-$finaldata[dis]</td>";
                        $tablecode .="<td>$finaldata[totalprice]</td>";
                        $tablecode .="</tr>";
                        $tabcounts['doorprice_total']  += $finaldata['doorprice'];
                        $tabcounts['devprice_total']   += $finaldata['devprice'];
                        $tabcounts['dis_total']        += $finaldata['dis'];
                        $tabcounts['totalprice_total'] += $finaldata['totalprice'];
                }
                $tablecode.="</tbody><thead>
                         <tr>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th>門禁費用總和</th>
                         <th>儀器費用總和</th>
                         <th>門禁抵消費用總和</th>
                         <th>應付金額總和</th>
                         </tr>
                         </thead>
                         <tbody>"; 
         
                $tablecode .="<tr>";
                $tablecode .="<td></td>";
                $tablecode .="<td></td>";
                $tablecode .="<td></td>";
                $tablecode .="<td></td>";
                $tablecode .="<td>$tabcounts[doorprice_total]</td>";
                $tablecode .="<td>$tabcounts[devprice_total]</td>";
                $tablecode .="<td>-$tabcounts[dis_total] </td>";
                $tablecode .="<td>$tabcounts[totalprice_total]</td>";
                $tablecode .="</tr>";

            
                $tablecode .= '</tbody><table>';
                $change_bill_apply_sv = new Change_bill_applyService();
                $cbares = $change_bill_apply_sv->get_apply_before( $id , $end );

                $all_disc = 0;
                foreach ($cbares as $cbarek => $cbare) {
                    if($cbare['status'] == 1){
                        $all_disc += $cbare['gap'];
                    }
                }

                return $tabcounts['totalprice_total'] - $all_disc;

            }
        }
        }else{
            echo '缺少參數'; 
        }


    }

    public function actionreport(){
        
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

            if( empty($_POST['keycol'] )){
                $_POST['keycol'] = 0;
            }
            // 抓出所有相同卡號的紀錄
            $biiservice = new BillService;
            $rcdata     = $biiservice->get_by_condition($idarr,$choosedev,$choosestart,$chooseend);

            // 抓出門禁紀錄
            $recordservice = new RecordService;

            $finaldata = array();

            foreach ($cardarrs as $cardarrk => $cardarr) {
                $getdatas = $recordservice->get_by_card_and_key($keysw,$_POST['keycol'],$cardarr,$choosestart,$chooseend,$_POST['keyword']);

                if( count($getdatas) > 0){

                    foreach ($getdatas as $getdatakey => $getdata) {

                        $temp['positionname'] = $getdata['positionname'];
                        $temp['username']     = $getdata['username'];
                        $temp['card_number']  = $getdata['card_number'];
                        $temp['usergrp']      = $getdata['usergrp'];
                        $temp['flashDate']    = $getdata['flashDate'];
                        $temp['professor']    = $getdata['professor'];
                        
                        array_push($finaldata, $temp);
                    }

                }
           
            }
            

           

            
        //}

        $data = array();
        
        // 抓出全部教授
        $p_ser = new ProfessorService;
        $professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[$value->id] = $value->name;
        }

        // 抓出全部儀器
        $d_ser = new DeviceService;
        $device = $d_ser->findDevices();
        
        // 抓出所有一級分類
        $g_ser = new UsergrpService();
        $grp   = $g_ser->getLevelOneAll();
        

        // 每次找完資料都將資料存進session 方便匯出跟列印
        Yii::app()->session['doorrec']       = $finaldata;

        $this->render('usereport',['model'    => $data,
                                   'professor'=> $proarr,
                                   'device'   => $device,
                                   'grp'      => $grp,
                                   'rcdata'   => $finaldata
                                  ]);
    }

    // 匯出excel
    function actiongetexcel(){
        
        // 查詢符合資料
        $biiservice  = new BillService;
        $model      = Yii::app()->session['userbill'];
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
        ->setCellValue('A1', '累計未繳款金額')
        ->setCellValue('B1', Yii::app()->session['debt'])
        ->setCellValue('C1', '')
        ->setCellValue('D1', '')
        ->setCellValue('E1', '')
        ->setCellValue('F1', '')
        ->setCellValue('G1', '')
        ->setCellValue('H1', '')
        ->setCellValue('I1', '');

        $objPHPExcel->setActiveSheetIndex(0)

            ->setCellValue('A2', '門禁名稱')
            ->setCellValue('B2', '門禁使用時間')
            ->setCellValue('C2', '門禁次數')
            ->setCellValue('D2', '儀器名稱')
            ->setCellValue('E2', '儀器使用時間')
            ->setCellValue('F2', '門禁費用')
            ->setCellValue('G2', '儀器費用')
            ->setCellValue('H2', '門禁抵消費用')
            ->setCellValue('I2', '應付金額');
            

        // Miscellaneous glyphs, UTF-8 設定內容資料
        $i=3;

        //$tabcounts['doorprice_total']  += $finaldata['doorprice'];
        //$tabcounts['devprice_total']   += $finaldata['devprice'];
        //$tabcounts['dis_total']        += $finaldata['dis'];
        //$tabcounts['totalprice_total'] += $finaldata['totalprice'];        
        $tabcounts = array();
        $tabcounts['doorprice_total']  = 0;
        $tabcounts['devprice_total']   = 0;
        $tabcounts['dis_total']        = 0;
        $tabcounts['totalprice_total'] = 0;            
        
        foreach($model['datas'] as $value){


            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $value['doorname'])
                ->setCellValue('B'.$i, $value['doortime'])
                ->setCellValue('C'.$i, $value['in_door_count'])
                ->setCellValue('D'.$i, $value['devname'])
                ->setCellValue('E'.$i, $value['devtime'])
                ->setCellValue('F'.$i, $value['doorprice'])
                ->setCellValue('G'.$i, $value['devprice'])
                ->setCellValue('H'.$i, $value['dis'])
                ->setCellValue('I'.$i, $value['totalprice']);
            $i++;
            
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, '申請理由')
            ->setCellValue('B'.$i, '是否通過')
            ->setCellValue('C'.$i, '')
            ->setCellValue('D'.$i, '')
            ->setCellValue('E'.$i, '')
            ->setCellValue('F'.$i, '')
            ->setCellValue('G'.$i, '')
            ->setCellValue('H'.$i, '')
            ->setCellValue('I'.$i, '扣除金額');
        $i++; 
        
        $change_bill_apply_sv = new Change_bill_applyService();
        $bill_changes = $change_bill_apply_sv->get_apply(Yii::app()->session['billid'],Yii::app()->session['billdate']);
        
        $gaptotal = 0;
        foreach ($bill_changes as $bill_changek => $bill_change ) {
            if( $bill_change['status'] == 0){
                $tmpsta = '尚未審核';
            }else if ( $bill_change['status'] == 1) {
                $tmpsta = '審核通過';
                $gaptotal += $bill_change['gap'];
            }else{
                $tmpsta = '審核失敗';
            }
            
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $bill_change['des'])
                ->setCellValue('B'.$i, $tmpsta)
                ->setCellValue('C'.$i, '')
                ->setCellValue('D'.$i, '')
                ->setCellValue('E'.$i, '')
                ->setCellValue('F'.$i, '')
                ->setCellValue('G'.$i, '')
                ->setCellValue('H'.$i, '')
                ->setCellValue('I'.$i,  $bill_change['gap']);
            $i++;  

        }        

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, '')
            ->setCellValue('B'.$i, '')
            ->setCellValue('C'.$i, '')
            ->setCellValue('D'.$i, '')
            ->setCellValue('E'.$i, '費用調整申請')
            ->setCellValue('F'.$i, '門禁費用總和')
            ->setCellValue('G'.$i, '儀器費用總和')
            ->setCellValue('H'.$i, '門禁抵消費用總和')
            ->setCellValue('I'.$i, '應付金額總和');
        $i++;
        $model['count']['amount']-= $gaptotal;
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, '')
        ->setCellValue('B'.$i, '')
        ->setCellValue('C'.$i, '')
        ->setCellValue('D'.$i, '')
        ->setCellValue('E'.$i, '-'.$gaptotal)
        ->setCellValue('F'.$i,  $model['count']['doorAmount'])
        ->setCellValue('G'.$i, $model['count']['divAmount'])
        ->setCellValue('H'.$i, '-'.$model['count']['disAmount'])
        ->setCellValue('I'.$i, $model['count']['amount']);
        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-使用者帳單');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-使用者帳單".".xls");
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
        $model       = Yii::app()->session['userbilltabel'];
        
        $p_ser = new ProfessorService;
        $professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[$value->id] = $value->name;
        }

        $this->render('usereport_print',['model'    => $model,
                                    
                                  ]);


    }
    
    // 計算單人帳單後,回傳
    public function professor_debt( $id ){

        if(!empty($id)){
        // 把所有需要用的service,先new好
        $model           = new MemberService;
        $billdoorservice = new BilldoorService;
        $billservice     = new BillService;

        // 抓出所有使用者
        $allusers = array($model->findByMemId($id));

        foreach ($allusers as $alluserk => $alluser) {
            // 判斷卡號10馬無誤
            $cardarr = str_split($alluser['card_number'], 5);
            


            if( !empty($cardarr[0]) && 
                !empty($cardarr[1]) && 
                strlen( trim($cardarr[0])) == 5 &&
                strlen( trim($cardarr[1])) == 5 ){
                
                $daynum = date("t");//本月天數
                $start  = date("Y-m")."-01 00:00:00";
                $end    = date("Y-m")."-$daynum 23:59:59";
                
                $end = date('Y-m-d', strtotime('-1 day', strtotime($start)));
                $start = '0000-00-00 00:00:00';
                $allbills = $billservice->get_by_mid_and_month($alluser['id'],$start,$end );
                $alldoorbills = $billdoorservice->get_by_mid_and_month($alluser['id'],$start,$end );

                if( count($alldoorbills) ==0 && count($allbills)==0){

                    return 0;
                    exit;
                }

                $discountarr=array();
                $onlydoor   =array();
                $onlydev    =array();

                foreach ($alldoorbills as $alldoorbillk => $alldoorbill) {


                    // 價格大於0 表示為進入
                    if($alldoorbill['o_price'] > 0){
                        
                        // 將進入的時間轉換為時戳
                        $doortime = strtotime($alldoorbill['usedate']);

                        // 可接受折扣之時間(使用時間+24H)
                        $candiscount = $doortime+86400;
                        
                        foreach ($allbills as $allbillk=> $allbill) {

                            //儀器使用開始時間轉為時戳
                            $devusetime = strtotime($allbill['startuse']);

                            // 可以產生折扣
                            if($allbill['position'] == $alldoorbill['dposition'] &&
                               $doortime <= $devusetime &&
                               $candiscount >= $devusetime
                            ){  
                                $tmpdata = array();
                                $tmpdata['doorbillid'] = $alldoorbill['id'];
                                $tmpdata['devbillid']  = $allbill['id'];
                                $tmpdata['doorname']   = $alldoorbill['doorname'];
                                $tmpdata['devname']    = $allbill['devname'];
                                $tmpdata['doorprice']  = $alldoorbill['o_price'];
                                $tmpdata['devprice']   = $allbill['d_price'];
                                $tmpdata['dis']        = $alldoorbill['o_price'];
                                $tmpdata['totalprice'] = $allbill['d_price'];
                                $tmpdata['doortime']   = $alldoorbill['usedate'];
                                $tmpdata['devtime']    = $allbill['startuse'];
                                
                                array_push($discountarr,$tmpdata);
                                
                                unset($allbills[$allbillk]);
                                unset($alldoorbills[$alldoorbillk]);
                                break;
                            }
                        }

                    }else{
                        unset($alldoorbills[$alldoorbillk]);
                    }

                }           
                
                // 剩餘的單純門禁
                foreach ($alldoorbills as $$alldoorbillk => $alldoorbill) {

                    $tmpdata = array();
                    $tmpdata['doorbillid'] = $alldoorbill['id'];
                    $tmpdata['devbillid']  = '';
                    $tmpdata['doorname']   = $alldoorbill['doorname'];
                    $tmpdata['devname']    = '';
                    $tmpdata['doorprice']  = $alldoorbill['o_price'];
                    $tmpdata['devprice']   = 0;
                    $tmpdata['dis']        = 0;
                    $tmpdata['totalprice'] = $alldoorbill['o_price'];
                    $tmpdata['doortime']   = $alldoorbill['usedate'];
                    $tmpdata['devtime']    = '';                    
                    array_push($onlydoor, $tmpdata);
                }

                // 剩餘的單純儀器
                foreach ($allbills as $allbillk=> $allbill) {

                    $tmpdata = array();
                    $tmpdata['doorbillid'] = '';
                    $tmpdata['devbillid']  = $allbill['id'];
                    $tmpdata['doorname']   = '';
                    $tmpdata['devname']    = $allbill['devname'];
                    $tmpdata['doorprice']  = '';
                    $tmpdata['devprice']   = $allbill['d_price'];
                    $tmpdata['dis']        = 0;
                    $tmpdata['totalprice'] = $allbill['d_price'];
                    $tmpdata['doortime']   = '';
                    $tmpdata['devtime']    = $allbill['startuse'];                    
                    array_push($onlydev, $tmpdata);
                }

                $finaldatas = array_merge($discountarr,$onlydoor,$onlydev);
                
                $tablecode="<table id='printtable' class='table table-bordered'>";
                $tablecode.="<thead>
                         <tr>
                         <th>門禁名稱</th>
                         <th>門禁使用時間</th>
                         <th>儀器名稱</th>
                         <th>儀器使用時間</th>
                         <th>門禁費用</th>
                         <th>儀器費用</th>
                         <th>門禁抵消費用</th>
                         <th>應付金額</th>
                         </tr>
                         </thead>
                         <tbody>";
                $tabcounts = array();
                $tabcounts['doorprice_total'] =0;
                $tabcounts['devprice_total']  =0;
                $tabcounts['dis_total'] =0;
                $tabcounts['totalprice_total'] =0;

                foreach ($finaldatas as $finaldatak => $finaldata) {
                        $tablecode .="<tr>";
                        $tablecode .="<td>$finaldata[doorname]</td>";
                        $tablecode .="<td>$finaldata[doortime]</td>";
                        $tablecode .="<td>$finaldata[devname]</td>";
                        $tablecode .="<td>$finaldata[devtime]</td>";
                        $tablecode .="<td>$finaldata[doorprice]</td>";
                        $tablecode .="<td>$finaldata[devprice]</td>";
                        $tablecode .="<td>-$finaldata[dis]</td>";
                        $tablecode .="<td>$finaldata[totalprice]</td>";
                        $tablecode .="</tr>";
                        $tabcounts['doorprice_total']  += $finaldata['doorprice'];
                        $tabcounts['devprice_total']   += $finaldata['devprice'];
                        $tabcounts['dis_total']        += $finaldata['dis'];
                        $tabcounts['totalprice_total'] += $finaldata['totalprice'];
                }
                $tablecode.="</tbody><thead>
                         <tr>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th>門禁費用總和</th>
                         <th>儀器費用總和</th>
                         <th>門禁抵消費用總和</th>
                         <th>應付金額總和</th>
                         </tr>
                         </thead>
                         <tbody>"; 
         
                $tablecode .="<tr>";
                $tablecode .="<td></td>";
                $tablecode .="<td></td>";
                $tablecode .="<td></td>";
                $tablecode .="<td></td>";
                $tablecode .="<td>$tabcounts[doorprice_total]</td>";
                $tablecode .="<td>$tabcounts[devprice_total]</td>";
                $tablecode .="<td>-$tabcounts[dis_total] </td>";
                $tablecode .="<td>$tabcounts[totalprice_total]</td>";
                $tablecode .="</tr>";

            
                $tablecode .= '</tbody><table>';

                return array('detail'      => $finaldatas,
                             'door_price'  => $tabcounts['doorprice_total'],
                             'dev_price'   => $tabcounts['devprice_total'],
                             'dis_total'   => $tabcounts['dis_total'],
                             'price_total' => $tabcounts['totalprice_total']);

            }
        }
        }else{
            echo '缺少參數';
        }
    }

    /*
     * 學生個人帳單查詢
     * -------------------------------------------------------------
     *
     *
     *
     */

    public function actionmybill(){
        
        $id = Yii::app()->session['uid'];
        
        if(!empty($id)){
        // 把所有需要用的service,先new好
        $model           = new MemberService;
        $billdoorservice = new BilldoorService;
        $billservice     = new BillService;

        // 抓出所有使用者
        $allusers = array($model->findByMemId($id));
        
        foreach ($allusers as $alluserk => $alluser) {
            
            // 判斷卡號10馬無誤
            $cardarr = str_split($alluser['card_number'], 5);


            if( !empty($cardarr[0]) && 
                !empty($cardarr[1]) && 
                strlen( trim($cardarr[0])) == 5 &&
                strlen( trim($cardarr[1])) == 5 ){
                

                if( !empty( $_POST['date_start']) ){
                    
                    $start = $_POST['date_start']."-01 00:00:00";
                    $end   = $_POST['date_start']."-31 23:59:59";
                   
                }else{

                    $daynum = date("t");//本月天數
                    $start  = date("Y-m")."-01 00:00:00";
                    $end    = date("Y-m")."-$daynum 23:59:59";
                }

                $beforedate = date('Y-m-d', strtotime('-1 day', strtotime($start)));
                $userdebt =  $this->debt($id , $start ,$end );
                
               
                $allbills = $billservice->get_by_mid_and_month($alluser['id'],$start,$end );
               

                $alldoorbills = $billdoorservice->get_by_mid_and_month($alluser['id'],$start,$end );

                /*
                if( count($alldoorbills) ==0 && count($allbills)==0){
                    echo '此會員暫無帳單資料';
                    exit;
                }
                */

                $discountarr=array();
                $onlydoor   =array();
                $onlydev    =array();

                foreach ($alldoorbills as $alldoorbillk => $alldoorbill) {

                    // 價格大於0 表示為進入
                    if($alldoorbill['o_price'] > 0){
                        
                        // 將進入的時間轉換為時戳
                        $doortime = strtotime($alldoorbill['usedate']);

                        // 可接受折扣之時間(使用時間+24H)
                        $candiscount = $doortime+86400;
                        
                        foreach ($allbills as $allbillk=> $allbill) {

                            //儀器使用開始時間轉為時戳
                            $devusetime = strtotime($allbill['startuse']);

                            // 可以產生折扣
                            if($allbill['position'] == $alldoorbill['dposition'] &&
                               $doortime <= $devusetime &&
                               $candiscount >= $devusetime
                            ){  
                                $tmpdata = array();
                                $tmpdata['doorbillid'] = $alldoorbill['id'];
                                $tmpdata['devbillid']  = $allbill['id'];
                                $tmpdata['doorname']   = $alldoorbill['doorname'];
                                $tmpdata['devname']    = $allbill['devname'];
                                $tmpdata['doorprice']  = $alldoorbill['o_price'];
                                $tmpdata['devprice']   = $allbill['d_price'];
                                $tmpdata['dis']        = $alldoorbill['o_price'];
                                $tmpdata['totalprice'] = $allbill['d_price'];
                                $tmpdata['doortime']   = $alldoorbill['usedate'];
                                $tmpdata['devtime']    = $allbill['startuse'];
                                
                                array_push($discountarr,$tmpdata);
                                
                                unset($allbills[$allbillk]);
                                unset($alldoorbills[$alldoorbillk]);
                                break;
                            }
                        }

                    }else{
                        unset($alldoorbills[$alldoorbillk]);
                    }

                }           
                
                // 剩餘的單純門禁
                foreach ($alldoorbills as $$alldoorbillk => $alldoorbill) {

                    $tmpdata = array();
                    $tmpdata['doorbillid'] = $alldoorbill['id'];
                    $tmpdata['devbillid']  = '';
                    $tmpdata['doorname']   = $alldoorbill['doorname'];
                    $tmpdata['devname']    = '';
                    $tmpdata['doorprice']  = $alldoorbill['o_price'];
                    $tmpdata['devprice']   = 0;
                    $tmpdata['dis']        = 0;
                    $tmpdata['totalprice'] = $alldoorbill['o_price'];
                    $tmpdata['doortime']   = $alldoorbill['usedate'];
                    $tmpdata['devtime']    = '';                    
                    array_push($onlydoor, $tmpdata);
                }

                // 剩餘的單純儀器
                foreach ($allbills as $allbillk=> $allbill) {

                    $tmpdata = array();
                    $tmpdata['doorbillid'] = '';
                    $tmpdata['devbillid']  = $allbill['id'];
                    $tmpdata['doorname']   = '';
                    $tmpdata['devname']    = $allbill['devname'];
                    $tmpdata['doorprice']  = '';
                    $tmpdata['devprice']   = $allbill['d_price'];
                    $tmpdata['dis']        = 0;
                    $tmpdata['totalprice'] = $allbill['d_price'];
                    $tmpdata['doortime']   = '';
                    $tmpdata['devtime']    = $allbill['startuse'];                    
                    array_push($onlydev, $tmpdata);
                }

                $finaldatas = array_merge($discountarr,$onlydoor,$onlydev);
                
                $tablecode="<table id='printtable' class='table table-bordered'>";
                $tablecode.="<thead>
                         <tr>
                         <th>累計未繳款金額</th>
                         <th>$userdebt</th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         </tr>
                         </thead>
                         <tbody>";
                $tablecode.="<thead>
                         <tr>
                         <th>門禁名稱</th>
                         <th>門禁使用時間</th>
                         <th>儀器名稱</th>
                         <th>儀器使用時間</th>
                         <th>門禁費用</th>
                         <th>儀器費用</th>
                         <th>門禁抵消費用</th>
                         <th>應付金額</th>
                         </tr>
                         </thead>
                         <tbody>";
                $tabcounts = array();
                $tabcounts['doorprice_total'] =0;
                $tabcounts['devprice_total']  =0;
                $tabcounts['dis_total'] =0;
                $tabcounts['totalprice_total'] =0;

                foreach ($finaldatas as $finaldatak => $finaldata) {
                        $tablecode .="<tr>";
                        $tablecode .="<td>$finaldata[doorname]</td>";
                        $tablecode .="<td>$finaldata[doortime]</td>";
                        $tablecode .="<td>$finaldata[devname]</td>";
                        $tablecode .="<td>$finaldata[devtime]</td>";
                        $tablecode .="<td>$finaldata[doorprice]</td>";
                        $tablecode .="<td>$finaldata[devprice]</td>";
                        $tablecode .="<td>-$finaldata[dis]</td>";
                        $tablecode .="<td>$finaldata[totalprice]</td>";
                        $tablecode .="</tr>";
                        $tabcounts['doorprice_total']  += $finaldata['doorprice'];
                        $tabcounts['devprice_total']   += $finaldata['devprice'];
                        $tabcounts['dis_total']        += $finaldata['dis'];
                        $tabcounts['totalprice_total'] += $finaldata['totalprice'];
                }


                // 添加帳單調整
                $change_bill_apply_sv = new Change_bill_applyService();
                $bill_changes = $change_bill_apply_sv->get_apply($id,$start);
                
                $tablecode.="</tbody><thead>
                         <tr>
                         <th>申請理由</th>
                         <th>是否通過</th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th>扣除金額</th>
                         </tr>
                         </thead>
                         <tbody>"; 

                $gaptotal = 0;
                foreach ($bill_changes as $bill_changek => $bill_change ) {
                    if( $bill_change['status'] == 0){
                        $tmpsta = '尚未審核';
                    }else if ( $bill_change['status'] == 1) {
                        $tmpsta = '審核通過';
                        $gaptotal += $bill_change['gap'];
                    }else{
                        $tmpsta = '審核失敗';
                    }
                    $tablecode .="<td>$bill_change[des]</td>";
                    $tablecode .="<td>$tmpsta</td>";
                    $tablecode .="<td></td>";
                    $tablecode .="<td></td>";
                    $tablecode .="<td></td>";
                    $tablecode .="<td></td>";
                    $tablecode .="<td></td>";
                    $tablecode .="<td>$bill_change[gap]</td>";
                    $tablecode .="</tr>";
                }



                $tablecode.="</tbody><thead>
                         <tr>
                         <th></th>
                         <th></th>
                         <th></th>
                         <th>費用調整申請</th>
                         <th>門禁費用總和</th>
                         <th>儀器費用總和</th>
                         <th>門禁抵消費用總和</th>
                         <th>應付金額總和</th>
                         </tr>
                         </thead>
                         <tbody>"; 
         
                $tabcounts['totalprice_total'] -= $gaptotal;


                $tablecode .="<td></td>";
                $tablecode .="<td></td>";
                $tablecode .="<td></td>";
                $tablecode .="<td>-$gaptotal</td>";
                $tablecode .="<td>$tabcounts[doorprice_total]</td>";
                $tablecode .="<td>$tabcounts[devprice_total]</td>";
                $tablecode .="<td>-$tabcounts[dis_total] </td>";
                $tablecode .="<td>$tabcounts[totalprice_total]</td>";
                $tablecode .="</tr>";

                Yii::app()->session['userbill'] = $finaldatas;
                Yii::app()->session['debt'] = $userdebt;
                Yii::app()->session['userbilltabel'] = $tablecode;
                
                $tablecode .= '</tbody><table>';
                $this->render('usereportself',['model'    => $data = array(),
                                   'professor'=> $proarr= array(),
                                   'device'   => $device= array(),
                                   'grp'      => $grp= array(),
                                   'tablecode'   => $tablecode,
                                   'mid' => $id,
                                   'start'=>$start
                                  ]);

            }
        }
        }else{
            echo '缺少參數';
        }
    }
    // 學生個人帳單結束

    /*
     * 帳單異動申請
     * ----------------------------------------------------------------
     * 當使用者對帳單有疑問時,可藉由此功能提出審核
     *
     */
    public function actionchange_bill_apply(){

        if (!CsrfProtector::comparePost()){
            $this->redirect('mybill');
        }
        
        //$month = date("Y-m")."-01 00:00:00";
        $change_bill_apply_sv = new Change_bill_applyService();
        $res = $change_bill_apply_sv->create( $_POST['mid'] , $_POST['des'] , $_POST['start'] );
        
        if($res == true){
            Yii::app()->session['success_msg'] = '帳單調整申請已進入流程,請等待審核流程';
        }else{
            Yii::app()->session['error_msg'] = array(array("過程出現錯誤,請稍後再嘗試"));
        }
        $this->redirect('mybill');
    }
    // 帳單異動申請結束


    /*
     * 價格調整申請
     * -------------------------------------------------------------------
     * 將所有尚未審核的申請全部列出
     *
     */
    public function actionconfirm_apply(){

        $change_bill_apply_sv = new Change_bill_applyService();
        $data = $change_bill_apply_sv->get_all_apply();
        
        $member_sv = new MemberService();
        $mems = $member_sv->findMemberlist();
        

        foreach ($mems as $key => $mem) {
             $memlist[$mem->id] =  $mem->name;
        }
        
        //var_dump($data);
        $this->render('comfirmlist',['rcdata' => $data,
                                     'mem'    =>$memlist
                                    ]);

    }
    // 價格調整申請結束
    
    /*
     * 審核特殊狀況之表單
     * ------------------------------------------------------------------
     * 撈出指定申請ID之相關資料,並且以表單形式以供管理者審核
     *
     *
     */
    public function actioncomfirmform($id){

        $change_bill_apply_sv = new Change_bill_applyService();
        $data = $change_bill_apply_sv->get_by_id($id);
        
        $member_sv = new MemberService();
        $mem = $member_sv->findByMemId($data['mem_id']);
       
        $this->render('comfirmform',['rcdata' => $data,'mem'=>$mem]);

    }
    // 審核特殊狀況之表單結束

    /*
     * 審核資料更正
     * -------------------------------------------------------------------
     * 將管理者審核結果寫入資料庫,正式對帳單產生修改
     *
     *
     */
    public function actioncomfirmdio(){

        if (!CsrfProtector::comparePost()){
            $this->redirect('mybill');
        }
        
        //var_dump($_POST);
        
        $change_bill_apply_sv = new Change_bill_applyService();
        $res = $change_bill_apply_sv->comfirmdo($_POST);
        
        if($res){

            Yii::app()->session['success_msg'] = '審核已完成';

        }else{
            
            Yii::app()->session['error_msg'] = array(array("過程出現錯誤,請稍後再嘗試"));
        }
        
        $this->redirect('confirm_apply');
    }
    // 審核資料更正結束

    
    /*
     * 使用者帳單結帳
     * --------------------------------------------------------------------
     * 將對應使用者,上所有累積到上個月的帳單統一產出
     *
     *
     */

    public function actionbillpay($id){
        
       
        if(!empty($id)){
        // 把所有需要用的service,先new好
        $model           = new MemberService;
        $billdoorservice = new BilldoorService;
        $billservice     = new BillService;
        $billCountSv = new BillcountService();
        // 抓出所有使用者
        $allusers = array($model->findByMemId($id));

        foreach ($allusers as $alluserk => $alluser) {
            // 判斷卡號10馬無誤
            $cardarr = str_split($alluser['card_number'], 5);
            


            if( !empty($cardarr[0]) && 
                !empty($cardarr[1]) && 
                strlen( trim($cardarr[0])) == 5 &&
                strlen( trim($cardarr[1])) == 5 ){
                

                
                // 取出上個月第一天以及上個月最後一天

//                $daynum = date("t");//本月天數
//                $start  = date("Y-m")."-01 00:00:00";
//                $end    = date("Y-m")."-$daynum 23:59:59";
//                $beforedate = date('Y-m-d', strtotime('-1 day', strtotime($start)));
                // 計算至上個月最後一天
//                $end   = $beforedate." 00:00:00";
//                $start = date('Y-m-01', strtotime('-1 month'));

                if($_POST['date_end'] != ''){
                    $end = $_POST['date_end']." 23:59:59";
                }else{
                    $daynum = date("t");//本月天數
                    $start  = date("Y-m")."-01 00:00:00";
                    $end    = date("Y-m")."-$daynum 23:59:59";
                    $beforedate = date('Y-m-d', strtotime('-1 day', strtotime($start)));
                    // 計算至上個月最後一天
                    $end   = $beforedate." 00:00:00";
                }

                if($_POST['date_start'] != ''){
                    $start = $_POST['date_start']." 00:00:00";
                }else{
                    $start = date('Y-m-01', strtotime('-1 month'));
                }
                $userdebt =  $this->debt($id,$start,$end);

                $allbills = $billservice->get_by_mid_and_month($alluser['id'],$start,$end );
                $alldoorbills = $billdoorservice->get_by_mid_and_month($alluser['id'],$start,$end );

                /*if( count($alldoorbills) ==0 && count($allbills)==0){
                    echo '此會員暫無帳單資料';
                    exit;
                }*/

                $tablecode  = "<table id='printtable' class='table table-bordered'>";
                $amount = 0;
                $datas = $billCountSv->getBill($id,$start,$end);
                $datas = $billCountSv->billDataFormat($datas);
                $billdata = $billCountSv->billviewToTable( $userdebt , $datas , $id , $start , $end );
                $tablecode .= $billdata[0];
                $amount +=  $billdata[1];
                $tablecode .= '</tbody><table>';
                $isPayOff = $this->payoff($id);

                $this->render('usereportpay',['model'    => $data = array(),
                                   'professor'=> $proarr= array(),
                                   'device'   => $device= array(),
                                   'grp'      => $grp= array(),
                                   'tablecode'   => $tablecode,
                                   'id'=>$id,
                                   'isPayOff'=>$isPayOff
                                  ]);

            }
        }
        }else{
            echo '缺少參數';
        }

    }
    /*
     * 繳費功能實作
     * -----------------------------------------------------------------
     * 
     * 
     */
    public function actionbillpaydo($id){

        $pay_sv  = new PayService();
        $pay_res = $pay_sv->billpay( $id );
        
        if( $pay_res ){
            
            Yii::app()->session['success_msg'] = '帳單付款成功';

        }else{
             
            Yii::app()->session['error_msg'] = array(array('帳單付款過程失敗,請稍後再試'));
        }
        

        $this->redirect(Yii::app()->createUrl('/userbill/search'));
    }

    /*
     * 計算是否有積欠帳單
     * ----------------------------------------------------------------
     * 計算id在上個月是否有積欠帳單,將結果回傳方便操作者識別
     *
     * true  - 已繳清
     * false - 尚未繳清
     * 
     */
    public function payoff( $id ){

        $paySv = new PayService();
        $res   = $paySv ->payoff($id);
        
        return $res;
    } 

    /*
     * 清單匯出excel
     * ----------------------------------------------------------------
     *
     *
     *
     */
    function actiongetexcelList(){
        
        // 查詢符合資料
        $biiservice  = new BillService;
        $model          = Yii::app()->session['professorStudentList'];
        $professorLists = Yii::app()->session['professorNameList'];
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
        ->setCellValue('A1', '教授姓名')
        ->setCellValue('B1', '學員姓名')
        ->setCellValue('C1', '登入帳號')
        ->setCellValue('D1', '連絡電話')
        ->setCellValue('E1', 'Email')
        ->setCellValue('F1', '帳單狀態'); 
    
        $i=2;
        foreach($model as $value){
            
            foreach($professorLists as $professorList){
                if($value['professor'] == $professorList->id){
                    $tmpProfessor = $professorList->name;
                }
            }

            if($value['payoff']== true){
                $tmpPayOff = '已繳清';
            }else{
                $tmpPayOff = '未繳清';
            }

            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $tmpProfessor)
            ->setCellValue('B'.$i, $value['name'])
            ->setCellValue('C'.$i, $value['account'])
            ->setCellValue('D'.$i, $value['phone1'])
            ->setCellValue('E'.$i, $value['email1'])
            ->setCellValue('F'.$i, $tmpPayOff);

            $i++;
            
        }

        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-使用者帳單');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-使用者帳單".".xls");
        ob_end_clean();
        header("Content-type: text/html; charset=utf-8");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=".$filename);
        $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    /*
     * 清單列印
     * ----------------------------------------------------------
     * 將查詢過後結列印出來
     *
     *
     */
    function actionprinterList(){

        $this->layout = "back_end_cls";
        
        // 查詢符合資料
        $biiservice  = new BillService;
        $model       = Yii::app()->session['professorStudentList'];
        $professorLists = Yii::app()->session['professorNameList'];
        
        $p_ser = new ProfessorService;
        $professor = $p_ser->allprofessor();
        foreach ($professor as $key => $value) {
            $proarr[$value->id] = $value->name;
        }

        $this->render('usereport_printList',['datas'    => $model,'professor'=>$professorLists
                                    
                                  ]);


    }
    
    /*
     * 全部成員帳單
     * -----------------------------------------------------------------
     * 以教授為單位,將其底下所有的學生帳單都計算出來,並且以表格形式
     * 呈現
     *
     *
     */

    public function actionallMemberBill(){
        
        // 擷取出會員id,並且存放至allMember中

        $allMemberTmp = Yii::app()->session['professorStudentList'];
        $allMembers   = array();
        foreach ($allMemberTmp as $value) {

            array_push($allMembers,$value['id']);
        
        }

        // 計算出上個月第一天,以及上個月最後一天 
        $daynum = date("t");//本月天數
//        $start = date("Y-m")."-01 00:00:00";
//        $end   = date('Y-m-d 23:59:59', strtotime('-1 day', strtotime($start)));
        //$start = date('Y-m-01 00:00:00', strtotime('-1 month'));
//        $start = '0000-00-00 00:00:00';
        if($_POST['date_end'] != ''){
            $end = $_POST['date_end']." 23:59:59";
        }else{
            $start = date("Y-m")."-01 00:00:00";
            $end   = date('Y-m-d 23:59:59', strtotime('-1 day', strtotime($start)));
        }

        if($_POST['date_start'] != ''){
            $start = $_POST['date_start']." 00:00:00";
        }else{
            $start = '0000-00-00 00:00:00';
        }

        $billCountSv = new BillcountService();

        $tablecode  = "<table id='printtable' class='table table-bordered'>";

        $amount = 0;

        $payBtnSw = 0;
        // 將每個會員個別帶入,計算其帳單
        foreach ($allMembers as $allMemberk => $allMember) {
            $accounts_receivable = $this -> debt($allMember,$start,$end);
            $datas = $billCountSv->getBill($allMember,$start,$end);
            if( count($datas[0]) > 0 || count($datas[1]) > 0 || count($datas[2]) > 0){
                
                $payBtnSw = 1;

            }
            $datas = $billCountSv->billDataFormat($datas);
            $billdata = $billCountSv->billToTable( $datas , $allMember , $start , $end );

            $tablecode .= $billdata[0];
            
            $amount +=  $billdata[1];

            //echo $a;
        }
        
        
        $allMemberStr = implode(",",$allMembers);

        $tablecode  .= $billCountSv->allStudentAmount($amount);
        $tablecode  .= "</table>";
        
        Yii::app()->session['allStudentPrint'] = $tablecode;

        $this->render('allstudentbill',['model'    => $data = array(),
                                        'professor'=> $proarr= array(),
                                        'device'   => $device= array(),
                                        'grp'      => $grp= array(),
                                        'tablecode'=> $tablecode,
                                        'payBtnSw' => $payBtnSw,
                                        'allMemberStr' => $allMemberStr
                                   ]);
    }

    /*
     * 全學生帳單列印功能
     * --------------------------------------------------------------------------
     * 提供將指定教授之下所屬的所有學生帳單列印功能
     *
     */

    public function actionallstudenprinter(){
        
        $this->layout = "back_end_cls";
        $this->render('allstudentprint',['model' =>Yii::app()->session['allStudentPrint'] ]);

    }

    public function actionallstudenthistoryexcel(){
        $billotherfeeService = new BillotherfeeService();
        $deviceServicesv =  new DeviceService();
        $doorSv    = new DoorService();
        $billCountSv = new BillcountService();
        $billrecordService = new BillrecordService();
        $memberSv   = new MemberService();
        $bill_record_id = $_POST['bill_record_id'];
        $professor_id = $_POST['professor_id'];
        $other_fee = 0;
        $devLists = $deviceServicesv ->findDeviceList();       
        foreach ($devLists as $value) {
            $tmp[$value['id']] = $value['name'];
        }
        // 儀器名稱陣列
        $devLists = $tmp;
        $doorLists = $doorSv->get_all();
        
        foreach ($doorLists as $doorListk => $doorList) {
           $tmp[$doorList['id']] = $doorList['name'];
        }
        // 門禁名稱陣列
        $doorLists = $tmp;
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
        
        //本期門禁費 = 本期機台費 = 機台原價總和 = 機台折扣總和 = 預約未使用及未提前取消費 = 應收金額 = 上期餘額
        $doorAmount = $divAmount = $div_originalAmount = $div_disAmount = $violationAmount = 0;
        $datas = $billCountSv -> getProfessorMemberBillHistory( $bill_record_id );
        $datas = $billCountSv -> billDataFormat($datas);
        $bill_record = $billrecordService -> findRecordByPk($bill_record_id);
        $accounts_receivableAmount = $bill_record->opening_balance;
        $totalAmont = $accounts_receivableAmount;
        $bill_other_fee = $billotherfeeService->findByBillRecordId($bill_record_id);
        if($bill_other_fee[0]['total'])
            $other_fee = $bill_other_fee[0]['total'];
        #echo json_encode($datas);exit();
        $format_data = array();
        $format_data['door'] = $format_data['dev'] = array();
        foreach ($datas['datas'] as $key => $value){
            $memberName = $memberSv->findByMemId(  $value['member_id'] )->name;
            if($value['doorprice'] != '' && $value['doorprice'] != 0){
                $format_data['door'][] = array(
                    'create_time' => $value['doortime'],
                    'user_name' => $memberName,
                    'door_name' => $value['doorname'],
                    'door_price' => $value['doorprice'],
                );
                $doorAmount += $value['doorprice'];
                $totalAmont -= $value['doorprice'];
            }
            if($value['devprice'] != ''){
                if($value['devprice'] != $value['dev_o_price'])
                    $dis_price = $value['dev_o_price'] - $value['devprice'];
                else
                    $dis_price = 0;
                $format_data['dev'][] = array(
                    'create_time' => $value['devtime'],
                    'use_time' => $value['dev_usetime'],
                    'user_name' => $memberName,
                    'dev_name' => $value['devname'],
                    'dev_usetime' => $value['dev_usetime'],
                    'dev_original_price' => $value['dev_o_price'],//原價
                    'dev_dis_price' => $value['devprice'],//折扣後價格
                    'dev_dis' => $dis_price, //折扣
                    'dev_price' => $value['devprice'],
                    'violation_price' => 0
                );
                $div_originalAmount += $value['dev_o_price'];
                $div_disAmount += $dis_price;
                $divAmount += $value['devprice'];
                $totalAmont -= $value['devprice'];
            }
            if($value['violation_price'] != ''){ //預約為使用費
                $format_data['dev'][] = array(
                    'create_time' => $value['devtime'],
                    'use_time' => 0,
                    'user_name' => $memberName,
                    'dev_name' => $value['devname'],
                    'dev_usetime' => 0,
                    'dev_original_price' => 0,//原價
                    'dev_dis_price' => 0,//折扣後價格
                    'dev_dis' => 0,//折扣
                    'dev_price' => $value['violation_price'],
                    'violation_price' => $value['violation_price']
                );
                $violationAmount += $value['violation_price'];
                $divAmount += $value['violation_price'];
                $totalAmont -= $value['violation_price'];
            }
        }
        $totalAmont -=$other_fee;
        $totalAmont += $bill_record->collection_refund;
        // if($bill_record->bill_type == 0) $totalAmont -= $bill_record->pay_amount;
        // else $totalAmont += $bill_record->pay_amount;
        $i = 1;
        $professorData = $memberSv->findByMemId(  $professor_id );
        $professor_name = $professorData->name;
        $user_grp = Usergrp::model()->findByPk($professorData->grp_lv1)['name'];
        //}
        if(count($format_data['dev'])>0){
            foreach ($format_data['dev'] as $key => $row)
            {
                $format_data_sort[$key] = $row['create_time'];
            }
            array_multisort($format_data_sort, SORT_ASC, $format_data['dev']);
        }
        if(count($format_data['door'])>0){
            foreach ($format_data['door'] as $key => $row)
            {
                $format_data_door_sort[$key] = $row['create_time'];
            }
            array_multisort($format_data_door_sort, SORT_ASC, $format_data['door']);
        }
        //$totalAmont += $accounts_receivableAmount;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,$user_grp)
            ->setCellValue('B'.$i,"合作教授 - " . $professor_name)
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,'')
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'本期結帳日')
            ->setCellValue('B'.$i,"上期餘額")
            ->setCellValue('C'.$i,'本期收退款')
            ->setCellValue('D'.$i,'本期其他費用')
            ->setCellValue('E'.$i,'本期機台費')
            ->setCellValue('F'.$i,'本期門禁費')
            ->setCellValue('G'.$i,'本期餘額')
            ->setCellValue('H'.$i,'應收金額');
        $i++;
        $end = explode(' ',$bill_record->checkout_time);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,$end[0])
            ->setCellValue('B'.$i,$accounts_receivableAmount)
            ->setCellValue('C'.$i,$bill_record->collection_refund)
            ->setCellValue('D'.$i,$other_fee)
            ->setCellValue('E'.$i,$divAmount)
            ->setCellValue('F'.$i,$doorAmount)
            ->setCellValue('G'.$i,$totalAmont)
            ->setCellValue('H'.$i,$totalAmont);
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'')
            ->setCellValue('B'.$i,'')
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,'')
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'機台使用明細表')
            ->setCellValue('B'.$i,'')
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,'')
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'時間')
            ->setCellValue('B'.$i,'使用者')
            ->setCellValue('C'.$i,'機台名稱')
            ->setCellValue('D'.$i,'使用時數')
            ->setCellValue('E'.$i,'原價')
            ->setCellValue('F'.$i,'折扣')
            ->setCellValue('G'.$i,'預約未使用及未提前取消費')
            ->setCellValue('H'.$i,'折扣後應收金額');
        $i++;
        if(isset($format_data['dev'])){
            foreach ($format_data['dev'] as $dev_key => $dev_value){
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,$dev_value['create_time'])
                    ->setCellValue('B'.$i,$dev_value['user_name'])
                    ->setCellValue('C'.$i,$dev_value['dev_name'])
                    ->setCellValue('D'.$i,str_replace('<br>','',$dev_value['use_time']))
                    ->setCellValue('E'.$i,$dev_value['dev_original_price'])
                    ->setCellValue('F'.$i,$dev_value['dev_dis'])
                    ->setCellValue('G'.$i,$dev_value['violation_price'])
                    ->setCellValue('H'.$i,$dev_value['dev_price']);
                $i++;
            }
        }


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'')
            ->setCellValue('B'.$i,'')
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,'機台使用費合計')
            ->setCellValue('E'.$i,$div_originalAmount)
            ->setCellValue('F'.$i,$div_disAmount)
            ->setCellValue('G'.$i,$violationAmount)
            ->setCellValue('H'.$i,$divAmount);
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'')
            ->setCellValue('B'.$i,'')
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,'')
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'門禁費明細表')
            ->setCellValue('B'.$i,'')
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,'')
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'日期')
            ->setCellValue('B'.$i,'使用者')
            ->setCellValue('C'.$i,'門禁名稱')
            ->setCellValue('D'.$i,'門禁費')
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');
        $i++;
        if(isset($format_data['door'])){
            foreach ($format_data['door'] as $door_key => $door_value){
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,$door_value['create_time'])
                    ->setCellValue('B'.$i,$door_value['user_name'])
                    ->setCellValue('C'.$i,$door_value['door_name'])
                    ->setCellValue('D'.$i,$door_value['door_price'])
                    ->setCellValue('E'.$i,'')
                    ->setCellValue('F'.$i,'')
                    ->setCellValue('G'.$i,'')
                    ->setCellValue('H'.$i,'');
                $i++;
            }
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'')
            ->setCellValue('B'.$i,'')
            ->setCellValue('C'.$i,'門禁費用合計')
            ->setCellValue('D'.$i,$doorAmount)
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');

        //$tablecode  .= $billCountSv->allStudentAmount($amount);
        //$tablecode  .= "</table>";
        


        /****************/
        // 查詢符合資料
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);


        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-使用者帳單');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-使用者帳單".".xls");
        ob_end_clean();
        header("Content-type: text/html; charset=utf-8");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=".$filename);
        $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        $objWriter->save('php://output');
        exit;
        /****************/
    }
    /*
     * 全學生帳單匯出
     * ---------------------------------------------------------------------------
     * 根據教授抓出全部學生,並且將所有學生的未付清帳單轉換成Excel以供下載
     *
     *
     */
    public function actionallstudentexcel(){
        $billrecordService = new BillrecordService();
        $billotherfeeService = new BillotherfeeService();
        $deviceServicesv =  new DeviceService();
        $devLists = $deviceServicesv ->findDeviceList();
        
        foreach ($devLists as $value) {
            $tmp[$value['id']] = $value['name'];
        }
        // 儀器名稱陣列
        $devLists = $tmp;

        unset($tmp);
        $doorSv    = new DoorService();
        $doorLists = $doorSv->get_all();
        
        foreach ($doorLists as $doorListk => $doorList) {
           $tmp[$doorList['id']] = $doorList['name'];
        }
        // 門禁名稱陣列
        $doorLists = $tmp;

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
        // 擷取出會員id,並且存放至allMember中
        $memberSv   = new MemberService();
        $professor_id = $_POST['perfessor_id'];
        $grp_lv1 = $_POST['grp_lv1'];
        $grp_lv2 = $_POST['grp_lv2'];
        $allMembers   = array();
        $professor_member = $memberSv -> findProfessorMember($professor_id);
        foreach ($professor_member as $member_key => $member_value) {
            array_push($allMembers,$member_value['id']);
        }
        $allMembers = implode(',',$allMembers);
        // $allMemberTmp = Yii::app()->session['professorStudentList'];
        // $professor_id = $allMemberTmp[0]['professor'];
        $professorData = $memberSv->findByMemId(  $professor_id );
        $professor_name = $professorData->name;
        $user_grp = Usergrp::model()->findByPk($professorData->grp_lv1)['name'];
        

        // foreach ($allMemberTmp as $value) {

        //     array_push($allMembers,$value['id']);
        
        // }
        
        // 計算出上個月第一天,以及上個月最後一天 
        $daynum = date("t");//本月天數
        if($_POST['date_end'] != ''){
            $end = $_POST['date_end']." 23:59:59";
        }else{
            $end = date('Y-m-d') . " 23:59:59";
        }

        $billCountSv = new BillcountService();

        //$tablecode  = "<table id='printtable' class='table table-bordered'>";
        
        $amount = 0;
        // 將每個會員個別帶入,計算其帳單

        $i = 1;

        $pf = 0;//教授帳單總和
        $ps = 0;
        $format_data = array();
        //本期門禁費 = 本期機台費 = 機台原價總和 = 機台折扣總和 = 預約未使用及未提前取消費 = 應收金額 = 上期餘額
        $doorAmount = $divAmount = $div_originalAmount = $div_disAmount = $violationAmount = 0;
        $datas = $billCountSv -> getProfessorMemberBill( $allMembers, $end );
        $datas = $billCountSv -> billDataFormat($datas);
        $accounts_receivableAmount = $other_fee = 0;
        $last_bill_record = $billrecordService -> getLastRecord($professor_id);
        if($last_bill_record){
            $accounts_receivableAmount = (int)$last_bill_record->ending_balance;
            // if( $last_bill_record->bill_type == 0) //退款
            //     $accounts_receivableAmount = ((int)$last_bill_record->ending_balance - (int)$last_bill_record->pay_amount);
            // else
            //     $accounts_receivableAmount = ((int)$last_bill_record->ending_balance + (int)$last_bill_record->pay_amount);
        }                 
        $totalAmont = $accounts_receivableAmount;
        $bill_other_fee = $billotherfeeService->get_professor_other_fee_without_record($professor_id, $end);
        if($bill_other_fee[0]['total'])
            $other_fee = $bill_other_fee[0]['total'];
        $format_data['door'] = $format_data['dev'] = array();
        foreach ($datas['datas'] as $key => $value){
            $memberName = $memberSv->findByMemId(  $value['member_id'] )->name;
            if($value['doorprice'] != '' && $value['doorprice'] != 0){
                $format_data['door'][] = array(
                    'create_time' => $value['doortime'],
                    'user_name' => $memberName,
                    'door_name' => $value['doorname'],
                    'door_price' => $value['doorprice'],
                );
                $doorAmount += $value['doorprice'];
                $totalAmont -= $value['doorprice'];
            }
            if($value['devprice'] != ''){
                if($value['devprice'] != $value['dev_o_price'])
                    $dis_price = $value['dev_o_price'] - $value['devprice'];
                else
                    $dis_price = 0;
                $format_data['dev'][] = array(
                    'create_time' => $value['devtime'],
                    'use_time' => $value['dev_usetime'],
                    'user_name' => $memberName,
                    'dev_name' => $value['devname'],
                    'dev_usetime' => $value['dev_usetime'],
                    'dev_original_price' => $value['dev_o_price'],//原價
                    'dev_dis_price' => $value['devprice'],//折扣後價格
                    'dev_dis' => $dis_price, //折扣
                    'dev_price' => $value['devprice'],
                    'violation_price' => 0
                );
                $div_originalAmount += $value['dev_o_price'];
                $div_disAmount += $dis_price;
                $divAmount += $value['devprice'];
                $totalAmont -= $value['devprice'];
            }
            if($value['violation_price'] != ''){ //預約為使用費
                $format_data['dev'][] = array(
                    'create_time' => $value['devtime'],
                    'use_time' => 0,
                    'user_name' => $memberName,
                    'dev_name' => $value['devname'],
                    'dev_usetime' => 0,
                    'dev_original_price' => 0,//原價
                    'dev_dis_price' => 0,//折扣後價格
                    'dev_dis' => 0,//折扣
                    'dev_price' => $value['violation_price'],
                    'violation_price' => $value['violation_price']
                );
                $violationAmount += $value['violation_price'];
                $divAmount += $value['violation_price'];
                $totalAmont -= $value['violation_price'];
            }
        }
        
        if(count($format_data['dev'])>0){
            foreach ($format_data['dev'] as $key => $row)
            {
                $format_data_sort[$key] = $row['create_time'];
            }
            array_multisort($format_data_sort, SORT_ASC, $format_data['dev']);
        }
        if(count($format_data['door'])>0){
            foreach ($format_data['door'] as $key => $row)
            {
                $format_data_door_sort[$key] = $row['create_time'];
            }
            array_multisort($format_data_door_sort, SORT_ASC, $format_data['door']);
        }
        #echo json_encode($format_data);exit();
        //}
        //$totalAmont += $accounts_receivableAmount;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,$user_grp)
            ->setCellValue('B'.$i,"合作教授 - " . $professor_name)
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,'')
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'本期結帳日')
            ->setCellValue('B'.$i,"上期餘額")
            ->setCellValue('C'.$i,'本期收退款')
            ->setCellValue('D'.$i,'本期其他費用')
            ->setCellValue('E'.$i,'本期機台費')
            ->setCellValue('F'.$i,'本期門禁費')
            ->setCellValue('G'.$i,'本期餘額')
            ->setCellValue('H'.$i,'應收金額');
        $i++;
        $end = explode(' ',$end);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,$end[0])
            ->setCellValue('B'.$i,$accounts_receivableAmount)
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,$other_fee)
            ->setCellValue('E'.$i,$divAmount)
            ->setCellValue('F'.$i,$doorAmount)
            ->setCellValue('G'.$i,$totalAmont)
            ->setCellValue('H'.$i,$totalAmont);
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'')
            ->setCellValue('B'.$i,'')
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,'')
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'機台使用明細表')
            ->setCellValue('B'.$i,'')
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,'')
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'時間')
            ->setCellValue('B'.$i,'使用者')
            ->setCellValue('C'.$i,'機台名稱')
            ->setCellValue('D'.$i,'使用時數')
            ->setCellValue('E'.$i,'原價')
            ->setCellValue('F'.$i,'折扣')
            ->setCellValue('G'.$i,'預約未使用及未提前取消費')
            ->setCellValue('H'.$i,'折扣後應收金額');
        $i++;
        if(isset($format_data['dev'])){
            foreach ($format_data['dev'] as $dev_key => $dev_value){
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,$dev_value['create_time'])
                    ->setCellValue('B'.$i,$dev_value['user_name'])
                    ->setCellValue('C'.$i,$dev_value['dev_name'])
                    ->setCellValue('D'.$i,str_replace('<br>','',$dev_value['use_time']))
                    ->setCellValue('E'.$i,$dev_value['dev_original_price'])
                    ->setCellValue('F'.$i,$dev_value['dev_dis'])
                    ->setCellValue('G'.$i,$dev_value['violation_price'])
                    ->setCellValue('H'.$i,$dev_value['dev_price']);
                $i++;
            }
        }


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'')
            ->setCellValue('B'.$i,'')
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,'機台使用費合計')
            ->setCellValue('E'.$i,$div_originalAmount)
            ->setCellValue('F'.$i,$div_disAmount)
            ->setCellValue('G'.$i,$violationAmount)
            ->setCellValue('H'.$i,$divAmount);
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'')
            ->setCellValue('B'.$i,'')
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,'')
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'門禁費明細表')
            ->setCellValue('B'.$i,'')
            ->setCellValue('C'.$i,'')
            ->setCellValue('D'.$i,'')
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'日期')
            ->setCellValue('B'.$i,'使用者')
            ->setCellValue('C'.$i,'門禁名稱')
            ->setCellValue('D'.$i,'門禁費')
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');
        $i++;
        if(isset($format_data['door'])){
            foreach ($format_data['door'] as $door_key => $door_value){
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,$door_value['create_time'])
                    ->setCellValue('B'.$i,$door_value['user_name'])
                    ->setCellValue('C'.$i,$door_value['door_name'])
                    ->setCellValue('D'.$i,$door_value['door_price'])
                    ->setCellValue('E'.$i,'')
                    ->setCellValue('F'.$i,'')
                    ->setCellValue('G'.$i,'')
                    ->setCellValue('H'.$i,'');
                $i++;
            }
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,'')
            ->setCellValue('B'.$i,'')
            ->setCellValue('C'.$i,'門禁費用合計')
            ->setCellValue('D'.$i,$doorAmount)
            ->setCellValue('E'.$i,'')
            ->setCellValue('F'.$i,'')
            ->setCellValue('G'.$i,'')
            ->setCellValue('H'.$i,'');

        //$tablecode  .= $billCountSv->allStudentAmount($amount);
        //$tablecode  .= "</table>";
        


        /****************/
        // 查詢符合資料
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);


        // Rename worksheet 表單名稱
        $objPHPExcel->getActiveSheet()->setTitle('清大門禁系統-使用者帳單');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        //目前支援xls匯出
        $filename = urlencode("清大門禁系統-使用者帳單".".xls");
        ob_end_clean();
        header("Content-type: text/html; charset=utf-8");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=".$filename);
        $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        $objWriter->save('php://output');
        exit;
        /****************/
    }

    
    
    /*
     * 所有學生繳費
     * -----------------------------------------------------------------------
     * 將教授所指導芝所有學生帳單進行一次性繳費
     *
     */
    public function actionallstudentbillpaydo(){
        
        // 如果有接收到會員才開始做
        if( !empty($_POST['allMember']) ){

            $allMembers = explode(",",$_POST['allMember']);
            
            $paySv   = new PayService();
            $pay_res = $paySv ->allpay( $allMembers );
            
            if( $pay_res ){
            
                Yii::app()->session['success_msg'] = '帳單付款成功';

            }else{
              
                Yii::app()->session['error_msg'] = array(array('帳單付款過程失敗,請稍後再試'));
            }
        

            $this->redirect(Yii::app()->createUrl('/userbill/search'));
        }
    }
}