<?php
class BillotherfeeService{
    
    // 新增一筆紀錄
    public function create($member_id,$fee_amount,$fee_create_time,$createtime,$create_member_id,$create_member_type,$memo){
      try {
        $post = new Billotherfee;        
        $post->member_id = $member_id;
        $post->fee_amount = $fee_amount;
        $post->fee_create_time = $fee_create_time;
        $post->createtime = $createtime;
        $post->create_member_id = $create_member_id;
        $post->create_member_type = $create_member_type;
        $post->memo = $memo==''?'NULL':$memo;
        $post->save();
        return $post;
      }catch (Exception $e) {
        echo  $e->getTraceAsString();
        return false;
      }
    }
    public function get_professor_other_fee_without_record( $member_id, $end_date ){
      $sql = "select sum(fee_amount) as total from bill_other_fee where member_id=".$member_id." and fee_create_time<='".$end_date."' and checkout=0";
      $bill_other_fee = yii::app()->db->createCommand($sql)->queryAll();
      return $bill_other_fee;
    }

    public function get_professor_other_fee( $member_id, $end_date ){
      $sql = "select *,(select sum(fee_amount) from bill_other_fee where member_id=".$member_id." and fee_create_time<='".$end_date ."') as total from bill_other_fee where member_id=".$member_id." and checkout=0 and createtime<='".$end_date."'";
      $bill_other_fee = yii::app()->db->createCommand($sql)->queryAll();
      return $bill_other_fee;
    }
    public function update_other_fee_status( $checkout_time,$member_id,$bill_record_id ){
      $sql = "update bill_other_fee set checkout=1,bill_record_id=" . $bill_record_id . " where fee_create_time<='".$checkout_time."' and checkout=0 and member_id in(".$member_id.")";
      $bill_other_fees = yii::app()->db->createCommand($sql)->query();
      $update_status = true;
      return $update_status;
    }
    public function findByBillRecordId($bill_record_id){
      $sql = "select sum(fee_amount) as total from bill_other_fee where bill_record_id=".$bill_record_id;
      $bill_other_fee = yii::app()->db->createCommand($sql)->queryAll();
      return $bill_other_fee;
    }
    public function findRecord($member_id, $date_start, $date_end){
      $base_sql = "SELECT b.*,DATE_FORMAT(b.fee_create_time, '%Y-%m-%d') as fee_create_time_format,m.name as professor_name,m.grp_lv1 as grp1_id,m.grp_lv2 as grp2_id,(case when create_member_type = 0 THEN (select account_name from account m where b.create_member_id= m.id) else (select name from member m where b.create_member_id= m.id) end) as receive_member_name,(case when bill_record_id != 0 THEN (select checkout_time from bill_record m where b.bill_record_id= m.bill_record_id) else ('') end) as checkout_time,(select name from user_grp u where m.grp_lv1 = u.id) as grp1_name,(select name from user_grp u where m.grp_lv2 = u.id) as grp2_name FROM `bill_other_fee` b left join member m on m.id = b.member_id";
      if(strlen($member_id)>0){
        $condition = " where member_id in(".$member_id.") and fee_create_time >= '". $date_start . "' and fee_create_time<='".$date_end."'";
      }else{
        $condition = " where fee_create_time >= '". $date_start . "' and fee_create_time<='".$date_end."'";
      }     
      $order = " order by fee_create_time,member_id asc";
      $sql = $base_sql . $condition . $order;
      $bill_other_fee = Yii::app()->db->createCommand($sql)->queryAll();
      return $bill_other_fee;
    }
}
?>