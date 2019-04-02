<?php
use yii\base\ErrorException;
/*
 * 繳費服務
 * ------------------------------------------------------------------------------------
 * 處理各種繳費時的相關功能,例如轉換帳單狀態,消除特殊狀況申請
 *
 * 方法列表:
 *
 *     1. 付清帳單 - billpay()
 *
 *     2. 確認上月為止帳單結清 - payoff()
 *
 *
 */

class PayService{
    
    /*
     * 付清帳單
     * ---------------------------------------------------------------------------
     * 依照所接收到的會員id,去轉換相對應的門禁以及以器帳單的付款狀態,以達到
     * 付費的效果
     *
     * 參數:
     *     1. id - 使用者id
     *
     */
    public function billpay( $id ){
        
        // 先找出所有符合的儀器帳單

        // 本月一號 00 : 00 : 00
        $start  = date("Y-m")."-01 00:00:00";
        $billdate = $beforedate = date('Y-m-d', strtotime('-1 day', strtotime($start)));
        $billdate = $billdate." 23:59:59";
        //$billdate ='2018-07-04 15:04:48';

        $billdatas = Yii::app()->db->createCommand()
                  ->select('b.id')
                  ->from('bill b')
                  ->leftjoin('device_record d','b.in_id = d.id')
                  ->where('member_id=:member_id', array(':member_id'=>$id))
                  ->andwhere('d.use_date <= :billdate',array(':billdate'=>$billdate))
                  ->queryAll();
        
        $billarr = array();
        foreach($billdatas as $billdata){
            array_push($billarr, $billdata['id']);
        }
        
        // 最後要變更狀態的帳單
        $billarr_str = implode(',',$billarr);

        
        // 找出所有符合的門禁帳單
        $doorbilldatas = Yii::app()->db->createCommand()
                       ->select('b.id')
                       ->from('bill_door b')
                       ->leftjoin('record r','b.in_id = r.id')
                       ->where('member_id=:member_id', array(':member_id'=>$id))
                       ->andwhere('r.flashDate <= :billdate',array(':billdate'=>$billdate))
                       ->queryAll();


        $doorbillarr = array();
        foreach($doorbilldatas as $doorbilldata){
            array_push($doorbillarr, $doorbilldata['id']);
        }
        // 最後要變更狀態的帳單
        $doorbillarrstr = implode(',',$doorbillarr);

       
        // 找出所有特殊情形
        $changebills = Yii::app()->db->createCommand()
                       ->select('id')
                       ->from('change_bill_apply')
                       ->where('mem_id=:mem_id', array(':mem_id'=>$id))
                       ->andwhere('bill_mon <= :bill_mon',array(':bill_mon'=>$billdate))
                       ->andwhere('status <= :status',array(':status'=>1))
                       ->queryAll();

        $changebillarr = array();

        foreach ($changebills as $changebill) {
            array_push( $changebillarr , $changebill['id']);
        }

        // 最後要變更狀態的
        $changebillarrstr = implode(',',$changebillarr);



        $transaction= Yii::app()->db->beginTransaction();
        
        try{
           
            if( !empty( $billarr_str ) ){
                
                $command = Yii::app()->db->createCommand();
                $command->update('bill', array(
                'status'=>1,
                'receive'=>Yii::app()->session['uid'],
                ), "id IN ($billarr_str)");

            }

            if( !empty( $doorbillarrstr ) ){
                
                $command2 = Yii::app()->db->createCommand();
                $command2->update('bill_door', array(
                'status'=>1,
                'receive'=>Yii::app()->session['uid'],
                ), "id IN ($doorbillarrstr)");
            }

            if( !empty( $changebillarrstr ) ){
      
                $command3 = Yii::app()->db->createCommand();
                $command3->update('change_bill_apply', array(
                'status'=>3,
                'receive'=>Yii::app()->session['uid'],
                ), "id IN ($changebillarrstr)");    
            }            

            $transaction->commit();
            return true;

        }catch(Exception $e){
            
            
            $transaction->rollback();
            return false;
        }
    }

    
    /*
     * 確認上月為止帳單結清
     * ----------------------------------------------------------------------
     *
     *
     *
     */

    public function payoff( $id ){
        
        $start  = date("Y-m")."-01 00:00:00";
        $billdate = $beforedate = date('Y-m-d', strtotime('-1 day', strtotime($start)));
        $billdate = $billdate." 23:59:59";


        $billdatas = Yii::app()->db->createCommand()
                  ->select('b.id')
                  ->from('bill b')
                  ->leftjoin('device_record d','b.in_id = d.id')
                  ->where('member_id=:member_id', array(':member_id'=>$id))
                  ->andwhere('b.status <= :status',array(':status'=>0))
                  ->andwhere('d.use_date <= :billdate',array(':billdate'=>$billdate))
                  ->queryAll();

        $doorbilldatas = Yii::app()->db->createCommand()
                       ->select('b.id')
                       ->from('bill_door b')
                       ->leftjoin('record r','b.in_id = r.id')
                       ->where('member_id=:member_id', array(':member_id'=>$id))
                       ->andwhere('b.status <= :status',array(':status'=>0))
                       ->andwhere('r.flashDate <= :billdate',array(':billdate'=>$billdate))
                       ->queryAll();

        $changebills = Yii::app()->db->createCommand()
                       ->select('id')
                       ->from('change_bill_apply')
                       ->where('mem_id=:mem_id', array(':mem_id'=>$id))
                       ->andwhere('bill_mon <= :bill_mon',array(':bill_mon'=>$billdate))
                       ->andwhere('status <= :status',array(':status'=>1))
                       ->queryAll();   

        if( empty($billdatas) && empty($doorbilldatas) && empty($changebills) ){

            return true;

        }else{

            return false;
        }
    }
    

    /*
     * 指定教授指導學生帳單全付清
     * -----------------------------------------------------------------------
     * 以教授為根據,取得所有指導學生,並且一次將所有學生之帳單付清
     * 
     */
    public function allpay( $allMember ){

        // 計算出上個月第一天,以及上個月最後一天 
        $daynum = date("t");//本月天數
        $start = date("Y-m")."-01 00:00:00";
        $end   = date('Y-m-d 23:59:59', strtotime('-1 day', strtotime($start)));
        $start = '0000-00-00 00:00:00';


        // 找出所有符合的門禁帳單
        $doorbilldatas = Yii::app()->db->createCommand()
                       ->select('b.*')
                       ->from('bill_door b')
                       ->leftjoin('record r','b.in_id = r.id')
                       //->where('member_id=:member_id', array(':member_id'=>$id))
                       ->where(array('in', 'member_id', $allMember ))
                       ->andwhere('r.flashDate <= :billdate',array(':billdate'=>$end))
                       ->queryAll();

        

        $doorbillarr = array();
        foreach($doorbilldatas as $doorbilldata){
            array_push($doorbillarr, $doorbilldata['id']);
        }

        // 最後要變更狀態的帳單
        $doorbillarrstr = implode(',',$doorbillarr);

        // 找出所有儀器帳單
        $billdatas = Yii::app()->db->createCommand()
                  ->select('b.id')
                  ->from('bill b')
                  ->leftjoin('device_record d','b.in_id = d.id')
                  //->where('member_id=:member_id', array(':member_id'=>$id))
                  ->where(array('in', 'member_id', $allMember ))
                  ->andwhere('d.use_date <= :billdate',array(':billdate'=>$end))
                  ->queryAll();
        
        $billarr = array();

        foreach($billdatas as $billdata){
            array_push($billarr, $billdata['id']);
        }  
        // 最後要變更狀態的帳單
        $billarr_str = implode(',',$billarr);

        // 找出所有特殊情形
        $changebills = Yii::app()->db->createCommand()
                       ->select('id')
                       ->from('change_bill_apply')
                       //->where('mem_id=:mem_id', array(':mem_id'=>$id))
                       ->where(array('in', 'mem_id', $allMember ))
                       ->andwhere('bill_mon <= :bill_mon',array(':bill_mon'=>$end))
                       ->andwhere('status <= :status',array(':status'=>1))
                       ->queryAll();

        $changebillarr = array();

        foreach ($changebills as $changebill) {
            array_push( $changebillarr , $changebill['id']);
        }

        // 最後要變更狀態的
        $changebillarrstr = implode(',',$changebillarr);   

        $transaction= Yii::app()->db->beginTransaction();
        
        try{
           
            if( !empty( $billarr_str ) ){
                
                $command = Yii::app()->db->createCommand();
                $command->update('bill', array(
                'status'=>1,
                'receive'=>Yii::app()->session['uid'],
                ), "id IN ($billarr_str)");

            }

            if( !empty( $doorbillarrstr ) ){
                
                $command2 = Yii::app()->db->createCommand();
                $command2->update('bill_door', array(
                'status'=>1,
                'receive'=>Yii::app()->session['uid'],
                ), "id IN ($doorbillarrstr)");
            }

            if( !empty( $changebillarrstr ) ){
      
                $command3 = Yii::app()->db->createCommand();
                $command3->update('change_bill_apply', array(
                'status'=>3,
                'receive'=>Yii::app()->session['uid'],
                ), "id IN ($changebillarrstr)");    
            }            

            $transaction->commit();
            return true;

        }catch(Exception $e){
            
            
            $transaction->rollback();
            return false;
        }             

    }
}