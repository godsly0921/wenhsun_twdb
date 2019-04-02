<?php
// 預約了儀器但未使用,寫入儀器帳單
class Cronjob6Command extends CConsoleCommand{

    private $discount = 100;//設定初始值這台儀器的最高折扣
    private $cost_percentage = 0.4; // 預約了儀器但未使用,將收取的費用百分比
    
    // 相差時間參數
    public static function timediff( $time1 , $time2 ){
        
        $firstTime = strtotime($time1);
        $lastTime  = strtotime($time2);
        $timeDiff  = abs($lastTime - $firstTime);

        // 回應單位為分鐘數
        return ceil($timeDiff/60)."<br>";

    }
     	
	public function run(){

        // 把所有需要用的service,先new好
        $model = new MemberService;
        $device_record = new Device_recordService();
        $reservation = new ReservationService();
        $calcfee_model = new CalculationfeeService;
        $device_model  = new DeviceService;
        $discount      = new LevelonediscountService;
        $billmodel     = new BillService;
        $today = date('Y-m-d');
        // 抓出預約狀態是用戶預約中的紀錄
        $data = $reservation->findReservationByStatusAndDate(0,$today);
        foreach ($data as $key => $value){
            $member  = $model->findByMemId($value->builder);
            if($member != NULL){
                if( !empty($member->card_number) ) {
                    // 需要新增沒抓過的條件
                    $device_station = $device_model->get_one_device($value->device_id);
                    if($device_station != NULL){
                        $check_device_record = $device_record->chk_use_reservation($member->card_number,$value->start_time,$value->end_time,$device_station->station);
                        if(count($check_device_record) == 0){
                            $usemin = self::timediff( $value->start_time,$value->end_time);
                            $dev_id = $value->device_id;
                            if( !empty($dev_id) ) {
                                $calc_rule = $calcfee_model->get_by_IdLv($dev_id, $member->grp_lv1)[1];
                                if( !empty($calc_rule) ){
                                    $top_five = substr($member->card_number, 0, 5);
                                    $after_five = substr($member->card_number, 5, 5);
                                    $card_number_string = $top_five . ':' . $after_five;
                                    $result = $device_record->chk_count(date("Y-m-d 00:00:00"), date("Y-m-d 23:59:59"));
                                    $total = (int)$result['total'] + 1;
                                    $inputs["day_num"] = (string)str_pad($total, 3, '0', STR_PAD_LEFT);//每日編號
                                    $inputs["use_date"] = $value->start_time;
                                    $inputs["station"] = (string)str_pad($device_station->station, 3, '0', STR_PAD_LEFT);
                                    $inputs["num"] = '0000';
                                    $inputs["name"] = $member->name;
                                    $inputs["dep1"] = $member->grp_lv1;
                                    $inputs["dep2"] = $member->grp_lv1;
                                    $inputs["des"] = '預約未用';
                                    $inputs["detail"] = $card_number_string;
                                    $inputs["card"] = $member->card_number;
                                    $inputs["tobill"] = 1;
                                    $device_record_id = $device_record->create_record_return_id($inputs);
                                    $use_basenmu = ceil($usemin/$calc_rule->base_minute);
                                    if( $use_basenmu < $calc_rule->start_base_charge){

                                        $use_basenmu = $calc_rule->start_base_charge;
                                    }

                                    #---------------------------------------------------
                                    # 開始計算優惠條件
                                    #
                                    #---------------------------------------------------

                                    // 取出所有對於指定機台的優惠
                                    $all_dis = $discount->get_by_devid( $dev_id );
                                    $applicable_dis = array();

                                    //$discount = 100;
                                    foreach ($all_dis as $discount_key => $discount_value) {


                                        //先判斷所屬分類是否吻合
                                        $tmp_level = json_decode( $discount_value->level );
                                        if(in_array( $member->grp_lv1 ,$tmp_level ) ){
                                            $this->discount = $discount_value->discount;
                                        }

                                        if(in_array( $member->grp_lv2 ,$tmp_level ) ){
                                            if($this->discount > $discount_value->discount){
                                                $this->discount = $discount_value->discount;
                                            }
                                        }

                                        //這邊判斷教授
                                        if(!$discount_value->professor != null){
                                            $tmp_professor = json_decode( $discount_value->professor );
                                            if( in_array( $member->professor ,$tmp_level ) ){
                                                if($this->discount > $discount_value->discount){
                                                    $this->discount = $discount_value->discount;
                                                }
                                            }
                                        }

                                        //比較星期幾是否吻合
                                        $in_sta = strtotime( $value->start_time );
                                        $in_week = date('w',$in_sta);
                                        $tmp_week = json_decode( $discount_value->weeks );
                                        if( in_array( $in_week ,$tmp_week ) ){
                                            //使用判斷使用天的時段
                                            $use_time = strtotime(date("Y-m-d H:i:s",$in_sta));
                                            $in_time = strtotime(date("Y-m-d",$in_sta).' '.$discount_value->start_hors.':'.$discount_value->start_minute.':00');
                                            $out_time = strtotime(date("Y-m-d",$in_sta).' '.$discount_value->end_hors.':'.$discount_value->end_minute.':59');

                                            if( $use_time >= $in_time && $use_time <= $out_time ){
                                                if($this->discount > $discount_value->discount){
                                                    $this->discount  = $discount_value->discount;
                                                }
                                            }

                                        }

                                        //比較今天日期是否在期間內
                                        $record_today = strtotime($value->start_time);
                                        $in_day = strtotime( $discount_value->discount_start_time );
                                        $out_day = strtotime( $discount_value->discount_end_time );
                                        if( $record_today >= $in_day && $record_today <= $out_day ){

                                            if($this->discount > $discount_value->discount){
                                                $this->discount = $discount_value->discount;
                                            }
                                        }

                                        /*array_push($applicable_dis, $discount_value);*/
                                        $applicable_dis["$discount_value->id"] = $this->discount;
                                        $this->discount = 100;
                                    }

                                    $memid  = $value->builder;
                                    $in     = $device_record_id;
                                    $out    = $device_record_id;
                                    $app    = json_encode(array_keys($applicable_dis));

                                    asort($applicable_dis);

                                    if( count($applicable_dis) > 0){
                                        $disid  = array_keys($applicable_dis)[0];

                                        $dis    = array_values($applicable_dis)[0];
                                    }else{
                                        $disid = 0;
                                        $dis = 100;
                                    }

                                    $oprice = round($use_basenmu*$calc_rule->base_charge*$this->cost_percentage);

                                    $dprice = $oprice*($dis/100);

                                    $billmodel->create_with_reservation($memid,$in,$out,$app,$disid,$dis,$oprice,$dprice,$dev_id);

                                    $in_up=Reservation::model()->findByPk($value->id);
                                    $in_up->tobill=1;
                                    $in_up->status=2;
                                    $in_up->save();
                                }
                            }
                        }
                    }
                }
            }
        }
	}

}

?>