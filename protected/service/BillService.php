<?php
class BillService{
    
    // 新增一筆紀錄
    public function create( $memid , $in , $out , $app , $disid , $dis , $oprice , $dprice ,$dev_id){

      $transaction = Yii::app()->db->beginTransaction();
      try {

        $post = new Bill;
        $post->member_id   = $memid;
        $post->in_id       = $in;
        $post->out_id      = $out;
        $post->applicable  = $app;
        $post->discount    = $disid;
        $post->o_price     = $oprice;
        $post->d_price     = $dprice;
        $post->percentage  = $dis;
        $post->dev_id      = $dev_id;
        $post->create_date = date("Y-m-d H:i:s");
        $post->edit_date   = date("Y-m-d H:i:s");
        $post->save();

        $in_up=Device_record::model()->findByPk($in);
        $in_up->tobill=1;
        $in_up->save();

        $out_up=Device_record::model()->findByPk($out);
        $out_up->tobill=1;
        $out_up->save();

        $transaction->commit();
      }
      catch (Exception $e) {
        $transaction->rollback();
        echo  $e->getTraceAsString();
      }
    }
    public function create_with_reservation( $memid , $in , $out , $app , $disid , $dis , $oprice , $dprice ,$dev_id){

        $transaction = Yii::app()->db->beginTransaction();
        try {

            $post = new Bill;
            $post->member_id   = $memid;
            $post->in_id       = $in;
            $post->out_id      = $out;
            $post->applicable  = $app;
            $post->discount    = $disid;
            $post->o_price     = $oprice;
            $post->d_price     = $dprice;
            $post->percentage  = $dis;
            $post->dev_id      = $dev_id;
            $post->create_date = date("Y-m-d H:i:s");
            $post->edit_date   = date("Y-m-d H:i:s");
            $post->save();

            $transaction->commit();
        }
        catch (Exception $e) {
            $transaction->rollback();
            echo  $e->getTraceAsString();
        }
    }
    // 全取
    public function getall(){
        
        $data = Yii::app()->db->createCommand()
        ->select('b.*,m.name as mname,d.use_date as in,d2.use_date as out,dv.name as dvname')
        ->from('bill b')
        ->leftjoin('member m', 'b.member_id = m.id')
        ->leftjoin('device_record d', 'b.in_id = d.id')
        ->leftjoin('device_record d2','b.out_id = d2.id')
        ->leftjoin('device dv','b.dev_id = dv.id')
        ->queryAll();
        
        return $data;
        //return ( Bill::model()->findAll() );
        /*b.member_id(bill.使用者id) = m.id(member.會員流水編號id)
          b.in_id(bill.進入資料id) = d.id(device_record.每日儀器使用id)
          b.out_id(bill.離開資料id) = d2.id(device_record.每日儀器使用id)
          b.dev_id(bill.儀器id) = dv.id(device.每日儀器使用狀況id)*/
    }
    
    /*----------------------------------------------------------------
     |依照條件找
     |----------------------------------------------------------------
     | $memid - 會員id陣列
     | $choosedev - 儀器陣列
     | $star - 開始時間
     | $end  - 結束時間
     |
     */
    public function get_by_condition($memid,$choosedev,$star,$end){

        $data = Yii::app()->db->createCommand()
        ->select('*,d.use_date as used,m.name as username,m.professor as mp,de.name as dename,d2.use_date as usestart,d3.use_date as useend')
        ->from('bill b')
        ->where('1=1')
        ->leftjoin("device_record d","b.in_id = d.id")
        ->leftjoin("member m","b.member_id = m.id")
        ->leftjoin("device de","b.dev_id = de.id")
        ->leftjoin("device_record d2","b.in_id  = d2.id")
        ->leftjoin("device_record d3","b.out_id = d3.id")
        ->andWhere(array('in', 'member_id', $memid))
        ->andWhere(array('in', 'dev_id',$choosedev))
        ->andWhere("d.use_date > '$star'")
        ->andWhere("d.use_date < '$end'")
        ->queryAll();
        
        return $data;

    }
    /*----------------------------------------------------------------
     | 計算特定儀器使用次數
     |----------------------------------------------------------------
     | 1.$memid - 符合使用者array
     | 2.$star - 開始時間
     | 3.$end - 結束時間
     | 4.$devid - 儀器代碼
     */
     public function get_use_count($memid,$star,$end,$devid){

        $data = Yii::app()->db->createCommand()
        ->select('count(dev_id) usecount')
        ->from('bill b')
        ->leftjoin("device_record dr","b.in_id = dr.id")
        ->where('1=1')
        ->andWhere(array('in', 'b.member_id', $memid))
        ->andWhere("dr.use_date > '$star'")
        ->andWhere("dr.use_date < '$end'")
        ->andWhere("dev_id = $devid")
        ->queryRow();

        return $data['usecount'];

     }
    /*----------------------------------------------------------------
     | 計算特定儀器使用時間
     |----------------------------------------------------------------
     | 1.$memid - 符合使用者array
     | 2.$star - 開始時間
     | 3.$end - 結束時間
     | 4.$devid - 儀器代碼
     */
     public function get_use_time($memid,$star,$end,$devid){

        $data = Yii::app()->db->createCommand()
        ->select('d2.use_date as intime , d3.use_date as outtime')
        ->from('bill b')
        ->leftjoin("device_record dr","b.in_id = dr.id")
        ->leftjoin("device_record d2","b.in_id  = d2.id")
        ->leftjoin("device_record d3","b.out_id = d3.id")        
        ->where('1=1')
        ->andWhere(array('in', 'b.member_id', $memid))
        ->andWhere("dr.use_date > '$star'")
        ->andWhere("dr.use_date < '$end'")
        ->andWhere("b.dev_id = $devid")
        ->queryAll();
        
        $tmptime = 0;
        foreach ($data as $key => $value) {
            // 計算使用時間

            $seconds    = abs(strtotime($value['outtime']) - strtotime($value['intime']) );
            $tmptime   += $seconds;
        }
       
        $hours      = floor($tmptime / 3600);
        $mins       = floor($tmptime / 60 % 60);
        $secs       = floor($tmptime % 60);
            
        return $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
         
     }     
    /*----------------------------------------------------------------
     | 計算特定儀器使用總金額
     |----------------------------------------------------------------
     | 1.$memid - 符合使用者array
     | 2.$star - 開始時間
     | 3.$end - 結束時間
     | 4.$devid - 儀器代碼
     */
     public function get_use_price($memid,$star,$end,$devid){

        $data = Yii::app()->db->createCommand()
        ->select('sum(b.d_price) total')
        ->from('bill b')
        ->leftjoin("device_record dr","b.in_id = dr.id")
        ->where('1=1')
        ->andWhere(array('in', 'b.member_id', $memid))
        ->andWhere("dr.use_date > '$star'")
        ->andWhere("dr.use_date < '$end'")
        ->andWhere("dev_id = $devid")
        ->queryRow();

        return $data['total'];

     }   

    /*----------------------------------------------------------------
     | 根據指定教授,及儀器抓出
     |----------------------------------------------------------------
     | 1.$pro - 教授ID
     | 2.$star - 開始時間
     | 3.$end - 結束時間
     | 4.$devid - 儀器代碼
     */
     public function get_list_by_pd($pro,$star,$end,$devid){

        $data = Yii::app()->db->createCommand()
        ->select('d.name,d.codenum,count(*) as usetime,d.id as devid,sum(d_price ) as total')
        ->from('bill b')
        ->leftjoin("device d","b.dev_id = d.id")
        ->leftjoin("device_record d1","b.in_id = d1.id")
        ->leftjoin("device_record d2","b.in_id = d2.id")
        ->leftjoin("member m","b.member_id = m.id")
        ->where('1=1')
        ->andWhere("m.professor = $pro ")
        ->andWhere(array('in', 'b.dev_id', $devid))
        ->andWhere("d1.use_date > '$star'")
        ->andWhere("d2.use_date < '$end'")
        ->group('dev_id')
        ->queryAll();
        return $data;
     } 

    /*----------------------------------------------------------------
     | 計算特定儀器使用時間-條件為教授
     |----------------------------------------------------------------
     | 1.$pro - 符合之教授
     | 2.$star - 開始時間
     | 3.$end - 結束時間
     | 4.$devid - 儀器代碼
     */
     public function get_usetime_bypd($pro,$star,$end,$devid){
        /*echo "<br>".$pro;
        echo "<br><br/>";
        var_dump($devid);*/
        $data = Yii::app()->db->createCommand()
        ->select('d2.use_date as intime , d3.use_date as outtime')
        ->from('bill b')
        ->leftjoin("device_record d2","b.in_id  = d2.id")
        ->leftjoin("device_record d3","b.out_id = d3.id")        
        ->leftjoin("member m","b.member_id = m.id")
        ->where('1=1')
        ->andWhere("m.professor = $pro")
        /*->andWhere(array('in', 'b.member_id', $memid))*/
        ->andWhere("d2.use_date > '$star'")
        ->andWhere("d3.use_date < '$end'")
        ->andWhere("b.dev_id = $devid")
        ->queryAll();
        
        $tmptime = 0;
        foreach ($data as $key => $value) {
            // 計算使用時間

            $seconds    = abs(strtotime($value['outtime']) - strtotime($value['intime']) );
            $tmptime   += $seconds;
        }
       

        $hours      = floor($tmptime / 3600);
        $mins       = floor($tmptime / 60 % 60);
        $secs       = floor($tmptime % 60);

        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        return array($timeFormat,$tmptime);
         
     } 
     
    /*----------------------------------------------------------------
     | 找出指定年月的所有資料
     |----------------------------------------------------------------
     |
     |
     */

    public function get_by_mid_and_month($mid , $start , $end ){
        
        $data = Yii::app()->db->createCommand()
        ->select('b.*,
            d1.use_date as d1d,
                 d1.use_date as startuse,
                 d2.use_date as enduse,
                 dv.position as position,
                 dv.name as devname')
        ->from('bill b')
        ->leftjoin('device_record d1', 'b.in_id  = d1.id')
        ->leftjoin('device_record d2', 'b.out_id = d2.id')
        ->leftjoin('device dv','b.dev_id = dv.id')
        ->where("d1.use_date >= '$start'")
        ->andWhere("d1.use_date <= '$end'")
        ->andwhere('b.member_id=:id', array(':id'=>$mid))
        ->order('d1.use_date asc')
        ->queryAll();
        return $data;
    }

    public function get_by_mid_in_and_month($mid , $end ){
        
        $data = Yii::app()->db->createCommand()
        ->select('b.*,
            d1.use_date as d1d,
                 d1.use_date as startuse,
                 d2.use_date as enduse,
                 dv.position as position,
                 dv.name as devname')
        ->from('bill b')
        ->leftjoin('device_record d1', 'b.in_id  = d1.id')
        ->leftjoin('device_record d2', 'b.out_id = d2.id')
        ->leftjoin('device dv','b.dev_id = dv.id')
        ->where("d1.use_date <= '$end'")
        ->andWhere(array('in', 'b.member_id', $mid))
        ->order('d1.use_date asc')
        ->queryAll();
        return $data;
    }

    public function update_bill_status($checkout_time,$member_id,$bill_record_id){
        $devBill_sql = 'SELECT b.*,r.use_date as use_date , r.station as station,r.des FROM `bill` b LEFT JOIN device_record r on b.in_id=r.id WHERE r.use_date <= "'.$checkout_time.'" and b.status = 0 and b.member_id in('.$member_id.')';
        $devBills = Yii::app()->db->createCommand($devBill_sql)->queryAll();
        // $devBills = Yii::app()->db->createCommand()
        // ->select('b.*,r.use_date as use_date , r.station as station,r.des')
        // ->from('bill b')
        // ->leftjoin('device_record r', 'b.in_id=r.id')
        // ->where(array('in', 'b.member_id', $member_id))
        // ->andwhere('r.use_date <=:end',array(':end'=>$checkout_time))
        // ->andwhere('b.status = 0')
        // ->queryall();
        $update_status = true;
        foreach ($devBills as $devBillk => $devBill) {
            $bill = Bill::model()->findByPk($devBill['id']);
            $bill->status = 1;
            $bill->bill_record_id = $bill_record_id;
            if(!$bill->save()) $update_status = false;
        }
        return $update_status;
    }
    /* 
     * 付費完整之帳單
     * -----------------------------------------------------------------------------------
     * 不管今天是甚麼時候,都只抓到前一個月的帳單,然後進行更改狀態
     *
     *
     */

    public function payprogress( $id ){
        
        // 抓取所有門禁帳單
        

    }
}
?>