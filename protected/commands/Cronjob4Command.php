<?php
// 寫入儀器帳單
class Cronjob4Command extends CConsoleCommand{

    private $discount = 100;//設定初始值這台儀器的最高折扣
    
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
        $memberService = new MemberService;
        $device_recordService = new Device_recordService;
        $calcfee_model = new CalculationfeeService;
        $device_model  = new DeviceService;
        $discount      = new LevelonediscountService;
        $billmodel     = new BillService;

        // 抓出所有使用者
        $member_list  = $memberService->findMemberlist();

        // 如果有卡號的使用者就去抓
        foreach ($member_list as $key => $value) {
            if( !empty($value->card_number) ){
                // 需要新增沒抓過的條件
                $device_record = $device_recordService->get_by_card( $value->card_number );
                if( count($device_record) > 0){
                    #------------------------------------------------
                    # 將不同站號的儀器,分開存入不同陣列,方便後續的費
                    # 計算
                    #------------------------------------------------

                    $all_dev_rec = array();
                    for ($i = 0 ; $i<count($device_record) ; $i++ ) {                        
                        // 先判斷在$all_dev_rec中有無該鍵值,如果有就直接push
                        // 如果沒有就新增後再push
                        $create_date_format = $device_record[$i]['create_date_format'];
                        $device_station = trim($device_record[$i]['station']);
                        if(!array_key_exists( $create_date_format , $all_dev_rec )){
                            $all_dev_rec[$create_date_format] = array();
                        }
                        if(!array_key_exists( $device_station , $all_dev_rec["$create_date_format"])){
                            $all_dev_rec[$create_date_format][$device_station] = array();
                        }
                        if($device_record[$i]['wnum'] == '開始儀器'){
                            $next_row = ($i + 1);
                            if(isset($device_record[$next_row])){           
                                if($device_record[$next_row]['create_date_format'] == $create_date_format && $device_record[$next_row]['station'] == $device_station && $device_record[$next_row]['wnum'] == '關閉儀器' ){
                                    $all_dev_rec[$create_date_format][$device_station][] = array(
                                        "open_id" => $device_record[$i]['id'],
                                        "out_id" => $device_record[$next_row]['id'],
                                        'open_date_time' => $device_record[$i]['use_date'],
                                        'close_date_time' =>  $device_record[$next_row]['use_date'],
                                        'station' => $device_record[$i]['station'],
                                    );
                                    $i++;
                                }
                            }
                            
                        }else{
                            $i++;
                        }
                    }

                    foreach ($all_dev_rec as $create_date => $date_device_record) {
                        foreach ($date_device_record as $device_station => $device_station_record) {
                            foreach ($device_station_record as $key => $each_device_record) {
                                $usemin = self::timediff( $each_device_record['open_date_time'] , $each_device_record['close_date_time']);                      
                                // 利用站號抓儀器id
                                $dev_id = $device_model->get_by_station( (int)$each_device_record['station'] )[1];
                                // 如果有取到id , 就接著抓價格
                                if( !empty($dev_id) ){

                                    $calc_rule = $calcfee_model->get_by_IdLv($dev_id , $value->grp_lv1)[1];//沒有設定儀器費用的記錄不會轉至bill表

                                    if( !empty($calc_rule) ){

                                        $use_basenmu = ceil($usemin/$calc_rule->base_minute);

                                        if( $use_basenmu < $calc_rule->start_base_charge){

                                            $use_basenmu = $calc_rule->start_base_charge;
                                        }

                                        //echo "應付:".$use_basenmu*$calc_rule->base_charge;
                                        //echo "<br/>";

                                        #---------------------------------------------------
                                        # 開始計算優惠條件
                                        #
                                        #---------------------------------------------------

                                        // 取出指定機台與教授的優惠
                                        if($value-> user_group == 2 and $value->professor = 0){
                                            $all_dis = $discount->get_by_devid_and_professor( $dev_id, $value->id );
                                        }else{
                                            $all_dis = $discount->get_by_devid_and_professor( $dev_id, $value->professor );
                                        }
                                        
                                        if(count($all_dis) > 0){
                                            foreach ($all_dis as $discount_key => $discount_value) {
                                                //先判斷所屬分類是否吻合
                                                $tmp_level = json_decode( $discount_value->level );
                                                //先判斷所屬教授是否吻合
                                                $tmp_professor = json_decode( $discount_value->professor );
                                                //比較星期幾是否吻合
                                                $in_sta = strtotime( $each_device_record['open_date_time'] );
                                                $in_week = date('w',$in_sta);
                                                $tmp_week = json_decode( $discount_value->weeks );
                                                //使用判斷使用天的時段
                                                $use_time = strtotime(date("Y-m-d H:i:s",$in_sta));
                                                $in_time = strtotime(date("Y-m-d",$in_sta).' '.$discount_value->start_hors.':'.$discount_value->start_minute.':00');
                                                $out_time = strtotime(date("Y-m-d",$in_sta).' '.$discount_value->end_hors.':'.$discount_value->end_minute.':59');
                                                //比較今天日期是否在期間內
                                                $record_today = strtotime($each_device_record['open_date_time']);
                                                $in_day = strtotime( $discount_value->discount_start_time );
                                                $out_day = strtotime( $discount_value->discount_end_time );
                                                if((in_array( $value->grp_lv1 ,$tmp_level ) || in_array( $value->grp_lv2 ,$tmp_level ) || in_array( $value->professor ,$tmp_professor )) && in_array( $in_week ,$tmp_week ) && $use_time >= $in_time && $use_time <= $out_time && $record_today >= $in_day && $record_today <= $out_day ){
                                                    $this->discount  = $discount_value->discount;
                                                    $disid = $discount_value->id;
                                                    $dis = $this->discount;
                                                }else{
                                                    $disid = 0;
                                                    $dis = 100;
                                                }
                                            }
                                        }else{
                                            $disid = 0;
                                            $dis = 100;
                                        }
                                        $memid  = $value->id;
                                        $in     = $each_device_record['open_id'];
                                        $out    = $each_device_record['out_id'];
                                        $app    = json_encode(array($disid));

                                        $oprice = $use_basenmu*$calc_rule->base_charge;
                                        $dprice = $oprice*($dis/100);
                                        $billmodel->create($memid,$in,$out,$app,$disid,$dis,$oprice,$dprice,$dev_id);
                                    }
                                }
                            }
                        }
                        
                    }
                    #------------------------------------------------
                    # 依照不同站號兩筆筆合併,並且算出時間
                    #
                    #------------------------------------------------
                }
            }
        }   
	}

}

?>