<?php
class AttendancerecordService{
    
    // 新增一筆紀錄
    public function create( $memid , $in , $out , $oprice ,$door_id){


      
      $transaction = Yii::app()->db->beginTransaction();
      try {

        $post = new Bill_door;
        $post->member_id   = $memid;
        $post->in_id       = $in;
        $post->out_id      = $out;
        $post->o_price     = $oprice;
        $post->door_id     = $door_id;
        $post->create_date = date("Y-m-d H:i:s");
        $post->edit_date   = date("Y-m-d H:i:s");
        $post->save();
        
        
        $in_up=Record::model()->findByPk($in);
        $in_up->tobill=1;
        $in_up->save();
        /*
        $out_up=Record::model()->findByPk($out);
        $out_up->tobill=1;
        $out_up->save();*/
        

        $transaction->commit();
      }
      catch (Exception $e) {
        $transaction->rollback();
        echo  $e->getTraceAsString();
      }
    }

    /*----------------------------------------------------------------
     | 找出指定每天的門禁帳單資料
     |----------------------------------------------------------------
     |
     |*/
    public function get_door_daily_price($member_id,$door_id){
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');
        $data = Yii::app()->db->createCommand()
            ->select('b.*,sum(b.o_price) as total,d.price as price')
            ->from('bill_door b')
            ->where('b.door_id =:door_id', array(':door_id'=>$door_id))
            ->leftjoin('door d', 'b.door_id = d.id')
            ->andwhere('b.member_id = :member_id',array(':member_id'=> $member_id ))
            ->andwhere('b.create_date >= :start',array(':start'=>$start))
            ->andwhere('b.create_date <= :end',array(':end'=>$end))
            ->queryRow();

        return $data;

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
                 r.flashDate as usedate,
                 d.position as dposition,
                 d.name as doorname')
        ->from('bill_door b')
        ->leftjoin('record r', 'b.in_id  = r.id')
        ->leftjoin('door d', 'r.reader_num  = d.station')
        ->where("r.flashDate >= '$start'")
        ->andWhere("r.flashDate <= '$end'")
        ->andwhere('b.member_id=:id', array(':id'=>$mid))
        ->order('r.flashDate asc')
        ->queryAll();

        return $data;
    }    
    public function get_by_mid_in_and_month($mid , $end ){
      $data = Yii::app()->db->createCommand()
      ->select('b.*,
               r.flashDate as usedate,
               d.position as dposition,
               d.name as doorname')
      ->from('bill_door b')
      ->leftjoin('record r', 'b.in_id  = r.id')
      ->leftjoin('door d', 'r.reader_num  = d.station')
      ->where("r.flashDate <= '$end'")
      ->andWhere(array('in', 'b.member_id', $mid))
      ->order('r.flashDate asc')
      ->queryAll();

      return $data;
  }  
  public function update_bill_door_status($checkout_time,$member_id,$bill_record_id){
    //$doorBill_sql = 'SELECT b.* from bill_door b LEFT JOIN record r on b.in_id=r.id where b.member_id in('.$member_id.') and r.flashDate <="'.$checkout_time.'" and b.status = 0';
    
    $update_sql = 'update bill_door b LEFT JOIN record r on b.in_id=r.id set b.status=1,b.bill_record_id='.$bill_record_id.' where r.flashDate <="'.$checkout_time.'" and b.status = 0 and b.member_id in('.$member_id.')';
    //update bill_door b LEFT JOIN record r on b.in_id=r.id set b.status=1,b.bill_record_id=1 where b.member_id in(208,209,210,211) and r.flashDate <="2019-03-22 00:14:14" and b.status = 0
    $doorBills = Yii::app()->db->createCommand($update_sql)->query();
    $update_status = true;
    // foreach ($doorBills as $doorBillk => $doorBill) {
    //   $bill = Bill_door::model()->findByPk($doorBill['id']);
    //   $bill->status = 1;
    //   $bill->bill_record_id = $bill_record_id;
    //   if(!$bill->save()) $update_status = false;
    // }
    return $update_status;
  }


    /*----------------------------------------------------------------
     |依照條件找
     |----------------------------------------------------------------
     | $employee_id - 員工id陣列
     | $star - 開始時間
     | $end  - 結束時間
     |
     */
    public function get_by_condition($employee_id,$star,$end){

            $data = Yii::app()->db->createCommand()
            ->select('a.*,e.*')
            ->from('attendance_record a')
            ->leftjoin('employee e','a.employee_id = e.id')
            ->andWhere(array('in', 'a.employee_id', $employee_id))
            ->andWhere("a.day >= '$star'")
            ->andWhere("a.day <= '$end'")
            ->queryAll();

        return $data;

    }

    /*----------------------------------------------------------------
     |依照條件找 門禁費用總計
     |----------------------------------------------------------------
     | $memid - 會員id陣列
     | $choose_door - 門禁陣列
     | $star - 開始時間
     | $end  - 結束時間
     |
     */
    public function get_by_condition_total($memid,$star,$end){

        $data = Yii::app()->db->createCommand()
            ->select('b.*,sum(o_price) as total_count')
            ->from('bill_door b')
            ->where('1=1')
            ->andWhere(array('in', 'member_id', $memid))
            ->andWhere("b.create_date > '$star'")
            ->andWhere("b.create_date < '$end'")
            ->queryAll();

        return $data;

    }
}
?>