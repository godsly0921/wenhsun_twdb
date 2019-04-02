<?php
class BillcollectionrefundService{
    
    // 新增一筆紀錄
    public function create( $perfessor_id,$collection_or_refund,$collection_refund_type,$collection_refund_create_time,$collection_refund_amount,$memo,$handman_member_id,$handman_member_type){

      $transaction = Yii::app()->db->beginTransaction();
      try {   
        $post = new Billcollectionrefund;
        $post->member_id = $perfessor_id;
        $post->collection_refund_amount = $collection_refund_amount;
        $post->collection_or_refund = $collection_or_refund;
        $post->collection_refund_type = $collection_refund_type;
        $post->createtime = date($collection_refund_create_time . " H:i:s");
        $post->handman_member_id = $handman_member_id;
        $post->handman_member_type = $handman_member_type;
        $post->memo = $memo==''?'NULL':$memo;
        $post->save();
        $transaction->commit();
        return $post;
      }
      catch (Exception $e) {
        $transaction->rollback();
        echo  $e->getTraceAsString();
      }
    }

    public function getLastRecord( $member_id ){
        $bill_record = Billcollectionrefund::model()->find(array(
            'condition' => 'member_id=:member_id',
            'params' => array(
                ':member_id' => $member_id,
            ),
            'order' => 'bill_record_id desc'
        ));
        return $bill_record;
    }

    public function findCollectionRefundWithCheckout($member_id,$date_end,$checkout){
      $base_sql = "SELECT b.*,DATE_FORMAT(b.createtime, '%Y-%m-%d') as createtime_format,m.name as professor_name,m.grp_lv1 as grp1_id,m.grp_lv2 as grp2_id,(case when handman_member_type = 0 THEN (select account_name from account m where b.handman_member_id= m.id) else (select name from member m where b.handman_member_id= m.id) end) as handman_member_name,(case when bill_record_id != 0 THEN (select checkout_time from bill_record m where b.bill_record_id= m.bill_record_id) else ('') end) as checkout_time,(select name from user_grp u where m.grp_lv1 = u.id) as grp1_name,(select name from user_grp u where m.grp_lv2 = u.id) as grp2_name FROM `bill_collection_refund` b left join member m on m.id = b.member_id";
      if(strlen($member_id)>0){
        $condition = " where member_id in(".$member_id.")  and createtime<='".$date_end."' and 	checkout=".$checkout;
      }else{
        $condition = " where createtime<='".$date_end."' and 	checkout=".$checkout;
      }      
      $order = " order by createtime,member_id asc";
      $sql = $base_sql . $condition . $order;
      $bill_collection_refund = Yii::app()->db->createCommand($sql)->queryAll();
      $total_amount = 0;
      foreach ($bill_collection_refund as $key => $value) {
        if($value['collection_or_refund'] ==1) $total_amount += $value['collection_refund_amount'];
        else $total_amount -= $value['collection_refund_amount'];
      }
      return $total_amount;
    }

    public function findCollectionRefundRecord($member_id, $date_start, $date_end){
      $base_sql = "SELECT b.*,DATE_FORMAT(b.createtime, '%Y-%m-%d') as createtime_format,m.name as professor_name,m.grp_lv1 as grp1_id,m.grp_lv2 as grp2_id,(case when handman_member_type = 0 THEN (select account_name from account m where b.handman_member_id= m.id) else (select name from member m where b.handman_member_id= m.id) end) as handman_member_name,(case when bill_record_id != 0 THEN (select checkout_time from bill_record m where b.bill_record_id= m.bill_record_id) else ('') end) as checkout_time,(select name from user_grp u where m.grp_lv1 = u.id) as grp1_name,(select name from user_grp u where m.grp_lv2 = u.id) as grp2_name FROM `bill_collection_refund` b left join member m on m.id = b.member_id";
      if(strlen($member_id)>0){
        $condition = " where member_id in(".$member_id.") and createtime >= '". $date_start . "' and createtime<='".$date_end."'";
      }else{
        $condition = " where createtime >= '". $date_start . "' and createtime<='".$date_end."'";
      }      
      $order = " order by createtime,member_id asc";
      $sql = $base_sql . $condition . $order;
      $bill_collection_refund = Yii::app()->db->createCommand($sql)->queryAll();
      return $bill_collection_refund;
    }
    public function update_collection_refund_status( $checkout_time,$member_id,$bill_record_id ){
      $sql = "update bill_collection_refund set checkout=1,bill_record_id=" . $bill_record_id . " where createtime<='".$checkout_time."' and checkout=0 and member_id in(".$member_id.")";
      $bill_other_fees = yii::app()->db->createCommand($sql)->query();
      $update_status = true;
      return $update_status;
    }
    public function findRecordByPk($bill_collection_refund_id){
      return Billcollectionrefund::model()->findByPk($bill_collection_refund_id);
    }
}
?>