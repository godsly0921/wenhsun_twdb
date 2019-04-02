<?php
// 寫入門禁帳單
class Cronjob5Command extends CConsoleCommand{
    
    // 相差時間參數
    public static function timediff( $time1 , $time2 ){
        
        $firstTime = strtotime($time1);
        $lastTime  = strtotime($time2);
        $timeDiff  = abs($lastTime - $firstTime);

        // 回應單位為分鐘數
        return ceil($timeDiff/60)."<br>";

    }	
    
	public function run(){
        //var_dump('test');exit();
        // 把所有需要用的service,先new好
        $model = new MemberService;
        //$calcfee_model = new CalculationfeeService;
        //$device_model  = new DeviceService;
        $discount      = new LevelonediscountService;
        $billdoormodel     = new BilldoorService;
        $doorserice = new DoorService;

        // 抓出所有使用者
        $data  = $model->findMemberlist();
        
        // 如果有卡號的使用者就去抓
        foreach ($data as $key => $value) {

            if( !empty($value->card_number) ){

                $model = new RecordService;
                // 需要新增沒抓過的條件
                $data2 = $model->get_by_card( $value->card_number );
        
                if( count($data2) > 0){

                    
                    // 寫入帳單
                    foreach ($data2 as $doork => $doorv) {

                        $doorprice = $doorserice->get_door_price($doorv->reader_num);
                        $doorid    = $doorserice->get_door_id($doorv->reader_num);
                        //用卡機站號撈價錢
                        if($doorv->tobill == 0){//假如該門禁尚未轉成帳單

                            $daily_bill = $billdoormodel->get_door_daily_price($value->id,$doorv->id);
                            if($daily_bill["id"] == NULL){
                                $memid  = $value->id;
                                $in     = $doorv->id;
                                $out    = '';
                                $price  = $doorprice;
                                $doorid = $doorid;
                                $billdoormodel->create($memid,$in,$out,$price,$doorid);

                            }elseif($daily_bill['total'] > $daily_bill['price']){//假如今天門的總計大於門禁費用價格 則後續每一筆都為0元

                                $memid  = $value->id;
                                $in     = $doorv->id;
                                $out    = '';
                                $price  = 0;
                                $doorid = $doorid;
                                $billdoormodel->create($memid,$in,$out,$price,$doorid);
                            }else{

                                $memid  = $value->id;
                                $in     = $doorv->id;
                                $out    = '';
                                $price  = 0;
                                $doorid = $doorid;
                                $billdoormodel->create($memid,$in,$out,$price,$doorid);

                            }

                        }
                    }

                }// 如果會員有資料結束

            }

        }
      
	}

}

?>