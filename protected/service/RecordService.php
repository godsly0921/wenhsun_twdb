<?php
/*----------------------------------------------------------------
 | 原始ST紀錄相關服務
 |----------------------------------------------------------------
 |
 |
 */

class RecordService{   
    
    // 根據不同卡號抓出所有紀錄
    public function get_by_card( $card,$start_date,$end_date ){
        
        // $car_arr[0] = 前5碼
        // $car_arr[1] = 後5碼
        $car_arr = str_split( $card , 5 );

        if( !isset($car_arr[1])){
            
            $car_arr[1] = '';
        
        }

        
        $criteria = new CDbCriteria; 

        $criteria->condition = "start_five = :start_five AND end_five = :end_five AND flashDate >= :start_date AND flashDate <= :end_date";

        $criteria->params=(array(':start_five' => $car_arr[0],':end_five' =>$car_arr[1],':start_date'=>$start_date,':end_date'=>$end_date));

        $criteria ->order = "flashDate ASC";

        $tmp =  Record::model()->findAll($criteria);
        
        //var_dump($tmp);
        return $tmp;
        

    }

    public function create_record(array $inputs)
    {

        $post = new Record;
        $post->reader_num = (int)$inputs["reader_num"];
        $post->start_five =  $inputs["start_five"];
        $post->end_five  = $inputs["end_five"];
        $post->flashDate   = $inputs["flashDate"];
        $post->memol = $inputs["memol"];
        $post->doorgroup = $inputs["doorgroup"];
        $post->timezone  = $inputs["timezone"];
        $post->name  = $inputs["name"];
        $post->doorstatus = $inputs["doorstatus"];
        $post->Temperature  = $inputs["Temperature"];

        if ($post->save()) {
            return true;
        } else {
            return false;
        }

    }

    // 根據日期或關鍵字抓出所有符合之異常紀錄
    public function findAbnormalRecordAndConditionDayAll($inputs){
        $criteria = new CDbCriteria;

      /*  var_dump($inputs);*/
       /* exit();*/

        //預設查詢
        if($inputs["start"]!=="" && $inputs["end"]!==" 23:59:59" && $inputs["keyword"]==="" && $inputs["status"] ==="1"){

            $criteria->condition = "date >= :start AND date <= :end";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end']));

            $criteria ->order = "user_name ASC";


        }

        //無關鍵字僅日期
        if($inputs["start"]!==" 00:00:00" && $inputs["end"]!==" 23:59:59" && $inputs["keyword"]==="" && $inputs["status"]  ==="0"){

            $criteria->condition = "date >= :start AND date <= :end";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end']));

            $criteria ->order = "user_name ASC";


        }

        //關鍵字：使用者姓名＆日期
        if($inputs["start"]!==" 00:00:00" && $inputs["end"]!==" 23:59:59" && $inputs["keyword"]!=="" && $inputs["status"]  ==="0"){

            $criteria->condition = "date >= :start AND date <= :end AND user_name like :keyword";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end'],':keyword' =>'%'.$inputs['keyword'].'%'));

            $criteria ->order = "user_name ASC";


        }

        //關鍵字：卡號 ＆日期
        if($inputs["start"]!==" 00:00:00" && $inputs["end"]!==" 23:59:59" && $inputs["keyword"]!=="" && $inputs["status"] ==="1"){

            $criteria->condition = "date >= :start AND date <= :end AND card_number like :keyword";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end'],':keyword' =>'%'.$inputs['keyword'].'%'));

            $criteria ->order = "user_name ASC";


        }

        //僅關鍵字 : 使用者姓名
        if($inputs["start"]===" 00:00:00" && $inputs["end"]===" 23:59:59" && $inputs["keyword"]!=="" && $inputs["status"] ==="0"){

            $criteria->condition = "user_name like :keyword";

            $criteria->params=(array(':keyword' =>'%'.$inputs['keyword'].'%'));

            $criteria ->order = "user_name ASC";



        }

        //僅關鍵字 : 卡號
        if($inputs["start"]===" 00:00:00" && $inputs["end"]=== " 23:59:59" && $inputs["keyword"]!=="" && $inputs["status"] ==="1"){

            $criteria->condition = "card_number like :keyword";

            $criteria->params=(array(':keyword' =>'%'.$inputs['keyword'].'%'));

            $criteria ->order = "user_name ASC";



        }


        $tmp =  Doorabnormal::model()->findAll($criteria);

        //var_dump($tmp);
        return $tmp;





    }



    /*----------------------------------------------------------------
     | 根據卡號及關鍵字抓取資料
     |----------------------------------------------------------------
     | $key_sw - 是否要使用關鍵字
     | $key_col - 關鍵字查詢欄位
     | $card - 卡號
     | $start - 開始時間
     | $end - 結束時間
     |
     */
    public function get_by_card_and_key( $key_sw , $key_col , $card , $start , $end ,$keyword){
        // 優先判斷卡號       
        $car_arr = str_split( $card , 5 );

        if( !isset($car_arr[1])){            
            $car_arr[1] = '';        
        }

        // 如果卡號可以分成前五後五才繼續
        if(!empty($car_arr[0]) && !empty($car_arr[1])){

            if( $key_sw == 1){//有關鍵字
                // 找姓名
                if($key_col == 0){
                    $data = Yii::app()->db->createCommand()
                    ->select('l.name as position_name,r.memol as memol,r.id as id,e.user_name as e_user_name,
                              e.name as username,
                              e.door_card_num as card_number,
                              r.*')
                    ->from('record r')
                    ->leftjoin('door d', 'r.reader_num = d.station')
                    ->leftjoin('local l', 'd.position  = l.id')
                        ->leftjoin('employee e', 'start_five=:start_five and end_five=:end_five')
                    ->where('r.start_five = :start_five', array(':start_five'=>$car_arr[0]))
                    ->andwhere('r.end_five = :end_five', array(':end_five'=>$car_arr[1]))
                    ->andwhere('e.door_card_num = :door_card_num', array(':door_card_num'=>$car_arr[0].$car_arr[1]))
                    ->andwhere(array('like', 'e.name', "%$keyword%"))
                    ->andwhere('r.flashDate >= :start', array(':start'=>$start))
                    ->andwhere('r.flashDate <= :end', array(':end'=>$end))
                    ->order('e.user_name DESC,CONVERT(e.name using big5) ASC')
                    ->queryAll();
                    return $data;


                }else if($key_col == 1){ //卡號
                    $data = Yii::app()->db->createCommand()
                        ->select('l.name as position_name,r.memol as memol,r.id as id,e.user_name as e_user_name,
                              e.name as username,
                              e.door_card_num as card_number,
                              r.*')
                        ->from('record r')
                        ->leftjoin('door d', 'r.reader_num = d.station')
                        ->leftjoin('local l', 'd.position  = l.id')
                        ->leftjoin('employee e', 'start_five=:start_five and end_five=:end_five')
                        ->where('r.start_five = :start_five', array(':start_five'=>$car_arr[0]))
                        ->andwhere('r.end_five = :end_five', array(':end_five'=>$car_arr[1]))
                        ->andwhere('e.door_card_num = :door_card_num', array(':door_card_num'=>$car_arr[0].$car_arr[1]))
                        ->andwhere(array('like', 'e.door_card_num', "%$keyword%"))
                        ->andwhere('r.flashDate >= :start', array(':start'=>$start))
                        ->andwhere('r.flashDate <= :end', array(':end'=>$end))
                        ->order('e.user_name DESC,CONVERT(e.name using big5) ASC')
                        ->queryAll();
                    return $data;

                }else if($key_col == 2){ //帳號
                    echo '2';
                    $data = Yii::app()->db->createCommand()
                        ->select('l.name as position_name,r.memol as memol,r.id as id,e.user_name as e_user_name,
                              e.name as username,
                              e.door_card_num as card_number,
                              r.*')
                        ->from('record r')
                        ->leftjoin('door d', 'r.reader_num = d.station')
                        ->leftjoin('local l', 'd.position  = l.id')
                        ->leftjoin('employee e', 'start_five=:start_five and end_five=:end_five')
                        ->where('r.start_five = :start_five', array(':start_five'=>$car_arr[0]))
                        ->andwhere('r.end_five = :end_five', array(':end_five'=>$car_arr[1]))
                        ->andwhere('e.door_card_num = :door_card_num', array(':door_card_num'=>$car_arr[0].$car_arr[1]))
                        ->andwhere(array('like', 'e.user_name', "%$keyword%"))
                        ->andwhere('r.flashDate >= :start', array(':start'=>$start))
                        ->andwhere('r.flashDate <= :end', array(':end'=>$end))
                        ->order('e.user_name DESC,CONVERT(e.name using big5) ASC')
                        ->queryAll();
                    return $data;

                }

            }else{

                $data = Yii::app()->db->createCommand()
                ->select('l.name as position_name,r.memol as memol,r.id as id,e.user_name as e_user_name,
                              e.name as username,
                              e.door_card_num as card_number,flashDate')
                ->from('record r')
                ->leftjoin('employee e', 'start_five=:start_five and end_five=:end_five')
                ->leftjoin('door d', 'r.reader_num = d.station')
                ->leftjoin('local l', 'd.position = l.id')
                ->where('r.start_five = :start_five', array(':start_five'=>$car_arr[0]))
                ->andwhere('r.end_five = :end_five', array(':end_five'=>$car_arr[1]))
                ->andwhere('e.door_card_num = :door_card_num', array(':door_card_num'=>$car_arr[0].$car_arr[1]))
                ->andwhere('flashDate >:start', array(':start'=>$start))
                ->andwhere('flashDate <:end', array(':end'=>$end))
                ->queryAll();


                return $data;
            }
        } 
    }

    /*----------------------------------------------------------------
     | 根據教授會員抓出使用紀錄
     |----------------------------------------------------------------
     |
     |
     |
     */

     public function get_by_card_bd( $card , $start , $end ){
        $car_arr = str_split( $card , 5 );

        if( !isset($car_arr[1])){            
            $car_arr[1] = '';        
        }

        // 如果卡號可以分成前五後五才繼續
        if( !empty($car_arr[0]) && !empty($car_arr[1]) ){

            $data = Yii::app()->db->createCommand()
            ->select('*,count(*) as total_count')
            ->from('record r')
            //->join('member m', 'r.mem_num = m.id')
            //->join('professor p', 'm.professor = p.id')
            ->where('1=1')
            ->andwhere('start_five=:start_five', array(':start_five'=>$car_arr[0]))
            ->andwhere('end_five=:end_five', array(':end_five'=>$car_arr[1]))  
            ->andwhere('flashDate >:start', array(':start'=>$start))
            ->andwhere('flashDate <:end', array(':end'=>$end))
            ->queryAll();
            return $data;

        }      
     }

     /*-----------------------------------------------------------
     | 依照條件列出相關紀錄
     |------------------------------------------------------------
     |
     */
     public function get_record_for_env( $start , $end , $position , $cardnum , $name){
        
        $car_arr = str_split( $cardnum , 5 );

        if( !isset($car_arr[1])){            
            $car_arr[1] = '';        
        }
        
        if( !empty($car_arr[1]) && !empty($car_arr[0]) ){
            
            $datas = Yii::app()->db->createCommand()
            ->select('r.*,l.name as lname,d.name as dname')
            ->from('record r')
            ->leftjoin('door d', 'r.reader_num=d.id')
            ->leftjoin('local l', 'd.position=l.id')
            ->where('flashDate >:start', array(':start'=>$start))
            ->andwhere('flashDate <:end', array(':end'=>$end))
            ->andwhere('flashDate <:end', array(':end'=>$end))
            ->andwhere('start_five=:start_five', array(':start_five'=>$car_arr[0]))
            ->andwhere('end_five=:end_five', array(':end_five'=>$car_arr[1])) 
            ->andwhere(array('like', 'r.name', "%$name%"))
            ->andwhere(array('like', 'l.name', "%$position%"))
            ->queryAll();

        }else{

            $datas = Yii::app()->db->createCommand()
            ->select('r.*,l.name as lname,d.name as dname')
            ->from('record r')
            ->leftjoin('door d', 'r.reader_num=d.id')
            ->leftjoin('local l', 'd.position=l.id')
            ->where('flashDate >:start', array(':start'=>$start))
            ->andwhere('flashDate <:end', array(':end'=>$end))
            ->andwhere('flashDate <:end', array(':end'=>$end))
            ->andwhere(array('like', 'r.name', "%$name%"))
            ->andwhere(array('like', 'l.name', "%$position%"))
            ->queryAll();            
        }
        return $datas;
     }

    public function get_today_record()
    {
      /* $start_time = date('Y-02-22 00:00:00');
        $end_time = date('Y-02-23 23:59:59');*/


       $start_time = date("Y-m-d",strtotime("-1 day"));
        $end_time = date('Y-m-d 23:59:59');


        //16=16,18 黃光區
        //17=1,2 白光區
        //18=17,19 化學區
        $result = record::model()->findAll([
            'condition' => 'flashDate >= :start_time and flashDate <= :end_time',
            'params' => [
                ':start_time' => $start_time, ':end_time' => $end_time,
            ],
            'order' => 'flashDate ASC',
        ]);




        //先根據卡號群組



        $data = [];

        //1表示刷卡進
        //0表示刷卡出

        $tmp_count_16 = [];
        $count_16_in = [];
        $count_16_out = [];

        $tmp_count_17 = [];
        $count_17_in = [];
        $count_17_out = [];

        $tmp_count_18 = [];
        $count_18_in = [];
        $count_18_out = [];
        $tmp_count = [];

        foreach($result as $key => $value){
            if($value['reader_num'] == 16 || $value['reader_num'] == 18){
                if($value['memol']=='正常進出'){
                    if($value['reader_num'] == '16'){
                        $tmp_count_16[$value['name']][] = "IN,".$value['flashDate'];
                        $tmp_count[$value['name']][] = $value['name'].",黃光區,16,IN,".$value['flashDate'];
                        $count_16_in[] = [$value['name']=>$value['flashDate']];

                    }
                    if($value['reader_num'] == '18'){
                        $tmp_count_16[$value['name']][] = "OUT,".$value['flashDate'];
                        $tmp_count[$value['name']][] = $value['name'].",黃光區,16,OUT,".$value['flashDate'];
                        $count_16_out[] = [$value['name']=>$value['flashDate']];
                    }
                }
            }

            if($value['reader_num'] == 1 || $value['reader_num'] == 2){
                if($value['memol']=='正常進出'){
                    if($value['reader_num'] == '1'){
                        $tmp_count_17[$value['name']][] = "IN,".$value['flashDate'];
                        $tmp_count[$value['name']][] = $value['name'].",白光區,1,IN,".$value['flashDate'];
                        $count_17_in[] = [$value['name']=>$value['flashDate']];
                    }
                    if($value['reader_num'] == '2'){
                        $tmp_count_17[$value['name']][] = "OUT,".$value['flashDate'];
                        $tmp_count[$value['name']][] = $value['name'].",白光區,2,OUT,".$value['flashDate'];
                        $count_17_out[] = [$value['name']=>$value['flashDate']];
                    }
                }
            }

            if($value['reader_num'] == 17 || $value['reader_num'] == 19){
                if($value['memol']=='正常進出'){
                    if($value['reader_num'] == '17'){
                        $tmp_count_18[$value['name']][] = "IN,".$value['flashDate'];
                        $tmp_count[$value['name']][] = $value['name'].",化學區,17,IN,".$value['flashDate'];
                        $count_18_in[] = [$value['name']=>$value['flashDate']];
                    }
                    if($value['reader_num'] == '19'){
                        $tmp_count_18[$value['name']][] = "OUT,".$value['flashDate'];
                        $tmp_count[$value['name']][] = $value['name'].",化學區,19,OUT,".$value['flashDate'];
                        $count_18_out[] = [$value['name']=>$value['flashDate']];
                    }
                }
            }
        }

//        var_dump($tmp_count);

      //  var_dump($tmp_count);
     //   exit();

        //16=16,18 黃光區
        //17=1,2 白光區
        //18=17,19 化學區

        $in_16_count = 0;
        $in_16_list = '';
        $in_17_count = 0;
        $in_17_list = '';
        $in_18_count = 0;
        $in_18_list = '';

       foreach($tmp_count as $key=> $value){

           $in_out_type = explode(",",end($value));
           if($in_out_type[1]=="黃光區" && $in_out_type[3]=="IN"){
               if($in_16_count==0){
                   $in_16_list = $key;
               }else{
                   $in_16_list = $in_16_list.','.$in_out_type[0];
               }
               $in_16_count++;
           }

           if($in_out_type[1]=="白光區" && $in_out_type[3]=="IN"){

               if($in_17_count==0){
                   $in_17_list = $key;
               }else{
                   $in_17_list = $in_17_list.','.$in_out_type[0];
               }
               $in_17_count ++;
           }

           if($in_out_type[1]=="化學區" && $in_out_type[3]=="IN"){
               if($in_18_count==0){
                   $in_18_list = $key;
               }else{
                   $in_18_list = $in_18_list.','.$in_out_type[0];
               }
               $in_18_count++;
           }
        }


        /*
        $count_16 = [];
        foreach($tmp_count_16 as $key=>$value){
            $diff_in_time  = "";//判斷這個人是否在實驗室 初始值為0
            $diff_out_time  = "";//判斷這個人是否在實驗室 初始值為0
            foreach($value as $k=>$v){//某某人整天的出勤紀錄
                $tmp = explode(",",$v);
                if($tmp[0]=='IN' and $diff_in_time == "" and  $diff_out_time == ""){
                    $diff_in_time = $tmp[1];
                    $count_16[$key]=true;
                }elseif($tmp[0]=='IN' and $diff_in_time !="" and  $diff_out_time == ""){
                    if($diff_in_time < $tmp[1]){
                        $diff_in_time = $tmp[1];
                        $count_16[$key]=true;
                    }
                    if($diff_in_time > $tmp[1]){
                        $diff_in_time = $tmp[1];
                        $count_16[$key] = true;
                    }
                }elseif($tmp[0]=='IN' and $diff_in_time =="" and  $diff_out_time !=""){
                    if($diff_out_time < $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_16[$key]= true;
                    }

                    if($diff_out_time > $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_16[$key]= false;
                    }

                }elseif($tmp[0]=='IN' and $diff_in_time !="" and  $diff_out_time !=""){
                    if($diff_in_time < $tmp[1] and $diff_out_time < $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_16[$key]=true;
                    }
                    if($diff_in_time > $tmp[1] and $diff_out_time > $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_16[$key]=false;
                    }
                    if($diff_in_time < $tmp[1] and $diff_out_time > $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_16[$key]=true;
                    }
                    if($diff_in_time < $tmp[1] and $diff_out_time < $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_16[$key]=true;
                    }
                }elseif($tmp[0]=='OUT' and $diff_in_time == "" and  $diff_out_time == ""){
                    $diff_out_time = $tmp[1];
                    $count_16[$key]=false;
                }elseif($tmp[0]=='OUT' and $diff_in_time !="" and  $diff_out_time ==""){
                    if($diff_in_time < $tmp[1]){
                        $diff_out_time = $tmp[1];
                        $count_16[$key]=false;
                    }
                    if($diff_in_time > $tmp[1]){//假如這個人的上一筆進場時間大於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_16[$key]=false;
                    }
                }elseif($tmp[0]=='OUT' and $diff_in_time =="" and  $diff_out_time !=""){
                    if($diff_out_time < $tmp[1]){//假如這個人的上一筆出場時間小於現在出場時間
                        $diff_in_time = $tmp[1];
                        $count_16[$key]=false;
                    }
                }elseif($tmp[0]=='OUT' and $diff_in_time !="" and  $diff_out_time !=""){
                    if($diff_in_time < $tmp[1] and $diff_out_time < $tmp[1]){//假如這個人的上一筆進場時間小於現在出場時間 ＡＮＤ 假如這個人的上一筆出場時間小於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_16[$key]=false;
                    }
                    if($diff_in_time > $tmp[1] and $diff_out_time > $tmp[1]){//假如這個人的上一筆進場時間大於現在出場時間 ＡＮＤ 假如這個人的上一筆出場時間大於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_16[$key]=false;
                    }
                    if($diff_in_time < $tmp[1] and $diff_out_time > $tmp[1]){//假如這個人的上一筆進場時間小於現在出場時間 ＡＮＤ 假如這個人的上一筆出場時間大於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_16[$key]=false;
                    }
                    if($diff_in_time < $tmp[1] and $diff_out_time < $tmp[1]){//假如這個人的上一筆進場時間小於現在出場時間 ＡＮＤ 假如這個人的上一筆出場時間小於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_16[$key]=false;
                    }
                }
            }
        }



        $in_16_count = 0;
        $in_16_list = '';
        $i=0;
        foreach($count_16 as $key=> $value){
            if($value==true){
                $in_16_count++;
                if($i!=0){
                    $in_16_list = $in_16_list.','.$key;
                }else{
                    $in_16_list = $key;
                }
                $i++;
            }
        }


        $count_17 = [];
        foreach($tmp_count_17 as $key=>$value){
            $diff_in_time  = "";//判斷這個人是否在實驗室 初始值為0
            $diff_out_time  = "";//判斷這個人是否在實驗室 初始值為0
            foreach($value as $k=>$v){//某某人整天的出勤紀錄
                $tmp = explode(",",$v);
                if($tmp[0]=='IN' and $diff_in_time == "" and  $diff_out_time == ""){
                    $diff_in_time = $tmp[1];
                    $count_17[$key]=true;
                }elseif($tmp[0]=='IN' and $diff_in_time !="" and  $diff_out_time == ""){
                    if($diff_in_time < $tmp[1]){
                        $diff_in_time = $tmp[1];
                        $count_17[$key]=true;
                    }
                    if($diff_in_time > $tmp[1]){
                        $diff_in_time = $tmp[1];
                        $count_17[$key] = true;
                    }
                }elseif($tmp[0]=='IN' and $diff_in_time =="" and  $diff_out_time !=""){
                    if($diff_out_time < $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_17[$key]= true;
                    }

                    if($diff_out_time > $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_17[$key]= false;
                    }

                }elseif($tmp[0]=='IN' and $diff_in_time !="" and  $diff_out_time !=""){
                    if($diff_in_time < $tmp[1] and $diff_out_time < $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_17[$key]=true;
                    }
                    if($diff_in_time > $tmp[1] and $diff_out_time > $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_17[$key]=false;
                    }
                    if($diff_in_time < $tmp[1] and $diff_out_time > $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_17[$key]=true;
                    }
                    if($diff_in_time < $tmp[1] and $diff_out_time < $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_17[$key]=true;
                    }
                }elseif($tmp[0]=='OUT' and $diff_in_time == "" and  $diff_out_time == ""){
                    $diff_out_time = $tmp[1];
                    $count_17[$key]=false;
                }elseif($tmp[0]=='OUT' and $diff_in_time !="" and  $diff_out_time ==""){
                    if($diff_in_time < $tmp[1]){
                        $diff_out_time = $tmp[1];
                        $count_17[$key]=false;
                    }
                    if($diff_in_time > $tmp[1]){//假如這個人的上一筆進場時間大於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_17[$key]=false;
                    }
                }elseif($tmp[0]=='OUT' and $diff_in_time =="" and  $diff_out_time !=""){
                    if($diff_out_time < $tmp[1]){//假如這個人的上一筆出場時間小於現在出場時間
                        $diff_in_time = $tmp[1];
                        $count_17[$key]=false;
                    }
                }elseif($tmp[0]=='OUT' and $diff_in_time !="" and  $diff_out_time !=""){
                    if($diff_in_time < $tmp[1] and $diff_out_time < $tmp[1]){//假如這個人的上一筆進場時間小於現在出場時間 ＡＮＤ 假如這個人的上一筆出場時間小於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_17[$key]=false;
                    }
                    if($diff_in_time > $tmp[1] and $diff_out_time > $tmp[1]){//假如這個人的上一筆進場時間大於現在出場時間 ＡＮＤ 假如這個人的上一筆出場時間大於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_17[$key]=false;
                    }
                    if($diff_in_time < $tmp[1] and $diff_out_time > $tmp[1]){//假如這個人的上一筆進場時間小於現在出場時間 ＡＮＤ 假如這個人的上一筆出場時間大於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_17[$key]=false;
                    }
                    if($diff_in_time < $tmp[1] and $diff_out_time < $tmp[1]){//假如這個人的上一筆進場時間小於現在出場時間 ＡＮＤ 假如這個人的上一筆出場時間小於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_17[$key]=false;
                    }
                }
            }
        }
        $in_17_count = 0;
        $in_17_list = '';
        $i=0;
        foreach($count_17 as $key=> $value){
            if($value==true ){
                $in_17_count++;
                if($i!=0){
                    $in_17_list = $in_17_list.','.$key;
                }else{
                    $in_17_list = $key;
                }
                $i++;
            }
        }





        $count_18 = [];
        foreach($tmp_count_18 as $key=>$value){
            $diff_in_time  = "";//判斷這個人是否在實驗室 初始值為0
            $diff_out_time  = "";//判斷這個人是否在實驗室 初始值為0
            foreach($value as $k=>$v){//某某人整天的出勤紀錄
                $tmp = explode(",",$v);
                if($tmp[0]=='IN' and $diff_in_time == "" and  $diff_out_time == ""){
                    $diff_in_time = $tmp[1];
                    $count_18[$key]=true;
                }elseif($tmp[0]=='IN' and $diff_in_time !="" and  $diff_out_time == ""){
                    if($diff_in_time < $tmp[1]){
                        $diff_in_time = $tmp[1];
                        $count_18[$key]=true;
                    }
                    if($diff_in_time > $tmp[1]){
                        $diff_in_time = $tmp[1];
                        $count_18[$key] = true;
                    }
                }elseif($tmp[0]=='IN' and $diff_in_time =="" and  $diff_out_time !=""){
                    if($diff_out_time < $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_18[$key]= true;
                    }

                    if($diff_out_time > $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_18[$key]= false;
                    }

                }elseif($tmp[0]=='IN' and $diff_in_time !="" and  $diff_out_time !=""){
                    if($diff_in_time < $tmp[1] and $diff_out_time < $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_18[$key]=true;
                    }
                    if($diff_in_time > $tmp[1] and $diff_out_time > $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_18[$key]=false;
                    }
                    if($diff_in_time < $tmp[1] and $diff_out_time > $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_18[$key]=true;
                    }
                    if($diff_in_time < $tmp[1] and $diff_out_time < $tmp[1]){//之前的時間 小於
                        $diff_in_time = $tmp[1];
                        $count_18[$key]=true;
                    }
                }elseif($tmp[0]=='OUT' and $diff_in_time == "" and  $diff_out_time == ""){
                    $diff_out_time = $tmp[1];
                    $count_18[$key]=false;
                }elseif($tmp[0]=='OUT' and $diff_in_time !="" and  $diff_out_time ==""){
                    if($diff_in_time < $tmp[1]){
                        $diff_out_time = $tmp[1];
                        $count_18[$key]=false;
                    }
                    if($diff_in_time > $tmp[1]){//假如這個人的上一筆進場時間大於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_18[$key]=false;
                    }
                }elseif($tmp[0]=='OUT' and $diff_in_time =="" and  $diff_out_time !=""){
                    if($diff_out_time < $tmp[1]){//假如這個人的上一筆出場時間小於現在出場時間
                        $diff_in_time = $tmp[1];
                        $count_18[$key]=false;
                    }
                }elseif($tmp[0]=='OUT' and $diff_in_time !="" and  $diff_out_time !=""){
                    if($diff_in_time < $tmp[1] and $diff_out_time < $tmp[1]){//假如這個人的上一筆進場時間小於現在出場時間 ＡＮＤ 假如這個人的上一筆出場時間小於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_18[$key]=false;
                    }
                    if($diff_in_time > $tmp[1] and $diff_out_time > $tmp[1]){//假如這個人的上一筆進場時間大於現在出場時間 ＡＮＤ 假如這個人的上一筆出場時間大於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_18[$key]=false;
                    }
                    if($diff_in_time < $tmp[1] and $diff_out_time > $tmp[1]){//假如這個人的上一筆進場時間小於現在出場時間 ＡＮＤ 假如這個人的上一筆出場時間大於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_18[$key]=false;
                    }
                    if($diff_in_time < $tmp[1] and $diff_out_time < $tmp[1]){//假如這個人的上一筆進場時間小於現在出場時間 ＡＮＤ 假如這個人的上一筆出場時間小於現在出場時間
                        $diff_out_time = $tmp[1];
                        $count_18[$key]=false;
                    }
                }
            }
        }
        $in_18_count = 0;
        $in_18_list = '';
        $i=0;
        foreach($count_18 as $key=> $value){
            if($value==true ){
                $in_18_count++;
                if($i!=0){
                    $in_18_list = $in_18_list.','.$key;
                }else{
                    $in_18_list = $key;
                }
                $i++;
            }
        }*/






        $data = array();
        $data['0'] =  end($count_16_in);
        $data['1'] =  end($count_16_out);
        $data['2'] =  $in_16_count;
        $data['3'] =  $in_16_list;

        $data['4'] =  end($count_17_in);
        $data['5'] =  end($count_17_out);
        $data['6'] =  $in_17_count;
        $data['7'] =  $in_17_list;

        $data['8'] =  end($count_18_in);
        $data['9'] =  end($count_18_out);
        $data['10'] =  $in_18_count;
        $data['11'] =  $in_18_list;

        //var_dump($tmp_count_16_in);
       // var_dump($tmp_count_16_out);
      //  var_dump($people_counting_16);
      // var_dump($data);
       //exit();





        echo $data = json_encode($data);
        exit();
        /*

        $arr[0] = "Mark Reed";
        $arr[1] = "34";
        $arr[2] = "Australia";

        echo json_encode($arr);
        exit();


        return $JData;*/
    }

    public function getlastrecord($station){

    $datas = Yii::app()->db->createCommand()
        ->select('*')
        ->from('record r')
        ->where('reader_num =:station', array(':station'=>$station))
        ->andwhere('is_record =1')
        ->order('flashDate desc')
        ->queryRow();

    return $datas;

    }


    public function getlocal(){

        $lists = [16,17,18];
        $criteria = new CDbCriteria();
        $criteria -> select= 'id,name';
        $criteria -> addInCondition('id',  $lists);
        $criteria -> order = 'id ASC';
        $result = Local::model() -> findAll($criteria);

        return $result;


    }
}
?>