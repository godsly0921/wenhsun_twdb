<?php
class BillrecordService{
    
    // 新增一筆紀錄
    public function create( $member_id,$opening_balance,$other_fee,$device_fee,$door_fee,$ending_balance,$collection_refund,$receive_member_id,$receive_member_type,$checkout_time,$createtime){

      // $transaction = Yii::app()->db->beginTransaction();
      // try {   
        $post = new Billrecord;
        $post->member_id = $member_id;
        $post->opening_balance = $opening_balance;
        $post->other_fee = $other_fee;
        $post->device_fee = $device_fee;
        $post->door_fee = $door_fee;
        $post->ending_balance = $ending_balance;
        $post->collection_refund = $collection_refund;
        // $post->bill_type = $bill_type;
        // $post->pay_type = $pay_type;
        $post->receive_member_id = $receive_member_id;
        $post->receive_member_type = $receive_member_type;
        $post->checkout_time = $checkout_time;
        $post->createtime = $createtime;
        $post->save();
        // $transaction->commit();
        return $post;
      // }
      // catch (Exception $e) {
      //   $transaction->rollback();
      //   echo  $e->getTraceAsString();
      // }
    }

    public function getLastRecord( $member_id ){
        $bill_record = Billrecord::model()->find(array(
            'condition' => 'member_id=:member_id',
            'params' => array(
                ':member_id' => $member_id,
            ),
            'order' => 'bill_record_id desc'
        ));
        return $bill_record;
    }

    public function findRecord($member_id, $date_start, $date_end){
      $base_sql = "SELECT b.*,m.name as professor_name,m.grp_lv1 as grp1_id,m.grp_lv2 as grp2_id,(case when receive_member_type = 0 THEN (select account_name from account m where b.receive_member_id= m.id) else (select name from member m where b.receive_member_id= m.id) end) as receive_member_name,(select name from user_grp u where m.grp_lv1 = u.id) as grp1_name,(select name from user_grp u where m.grp_lv2 = u.id) as grp2_name FROM `bill_record` b left join member m on m.id = b.member_id";
      $condition = " where member_id in(".$member_id.") and checkout_time >= '". $date_start . "' and checkout_time<='".$date_end."'";
      $order = " order by checkout_time,member_id asc";
      $sql = $base_sql . $condition . $order;
      $bill_record = Yii::app()->db->createCommand($sql)->queryAll();
      return $bill_record;
    }

    public function findRecordByPk($bill_record_id){
      return Billrecord::model()->findByPk($bill_record_id);
    }
}
?>