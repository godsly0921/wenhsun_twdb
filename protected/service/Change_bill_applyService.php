<?php

/*
 * 帳單調整申請
 * ---------------------------------------------------------
 * 
 * 1. 新增帳單調整申請 - create()
 *
 * 2. 取得指定月份之所有申請 - get_apply()
 *
 * 3. 抓出所有尚未審核之申請 - get_all_apply()
 *
 * 4. 撈出特定ID之特殊狀況申請 - get_by_id()
 *
 * 5. 審核特殊狀況申請 -comfirmdo()
 *
 */

class Change_bill_applyService
{   
    /*
     * 新增帳單調整申請
     * ------------------------------------------------------------------
     * 
     * 相關參數:
     * 
     * 1. mid - 會員id
     *
     * 2. des - 申請理由描述
     *
     * 3. month - 帳單月份
     *
     */

    public function create( $mid , $des , $month ){
        
        $command = Yii::app()->db->createCommand();
        
        $res = $command->insert('change_bill_apply', array(
        'mem_id'      => $mid,
        'des'         => $des,
        'status'      => 0,
        'bill_mon'    => $month,
        'agreeer'     => '',
        'gap'         => 0,
        'create_date' => date("Y-m-d H:i:s")
        ));
        
        return $res;
        
    }
    /*
     * 取得指定月份之所有申請 
     * -----------------------------------------------------------
     *
     * 1. mid - 會員id
     *
     * 2. month - 帳單月份
     *
     */
    public function get_apply( $mid , $month ){
        

        $data = Yii::app()->db->createCommand()
        ->select('*')
        ->from('change_bill_apply')
        ->where('bill_mon = :bill_mon', array(':bill_mon'=>$month))
        ->andwhere('mem_id=:mem_id', array(':mem_id'=>$mid))
        ->queryAll();
 
        return $data;
    }

    /*
     * 抓出所有尚未審核之申請
     * -----------------------------------------------------------
     *
     * 不須提供任何參數,只會將尚未審核的特述情況申請全部撈出
     *
     */
    public function get_all_apply(){

        $data = Yii::app()->db->createCommand()
        ->select('*')
        ->from('change_bill_apply')
        ->where('status=:status', array(':status'=>0))
        ->queryAll();

        return $data;
    }
    
    /*
     * 撈出特定ID之特殊狀況申請
     * -----------------------------------------------------------
     * 
     * 根據id去資料庫撈出特定的特殊狀況申請資料
     *
     * 參數:
     *
     * 1. id - 特殊狀況表(change_bill_apply)中所使用的id
     *
     */
    public function get_by_id($id){

        $data = Yii::app()->db->createCommand()
        ->select('*')
        ->from('change_bill_apply')
        ->where('id=:id', array(':id'=>$id))
        ->queryRow();

        return $data;
    }
    
    /*
     * 審核特殊狀況申請
     * -----------------------------------------------------------
     *
     * 將管理者所審核的結果寫入資料庫中
     *
     * 參數:
     *
     * 1. $data - 管理員審核之所有結果(為array)
     *
     */
    public function comfirmdo( $data ){
        $command = Yii::app()->db->createCommand();
        $res = $command->update('change_bill_apply', array(
        'status' => $data['status'],
        'gap'     => $data['discount'],
        'agreeer' => Yii::app()->session['uid'],
        ), 'id=:id', array(':id'=>$data['id']));

        return $res;
    }
    // 審核特殊狀況申請結束

    /*
     * 取得指定日期以前之所有申請 
     * -----------------------------------------------------------
     *
     * 1. mid - 會員id
     *
     * 2. month - 帳單月份
     *
     */
    public function get_apply_before( $mid , $month ){       
        $data = Yii::app()->db->createCommand()
        ->select('*')
        ->from('change_bill_apply')
        ->where('bill_mon <= :bill_mon', array(':bill_mon'=>$month))
        ->andwhere('mem_id=:mem_id', array(':mem_id'=>$mid))
        ->queryAll();
 
        return $data;
    }    
    public function get_apply_before_member_in( $mid , $month ){       
        $data = Yii::app()->db->createCommand()
        ->select('*')
        ->from('change_bill_apply')
        ->where('bill_mon <= :bill_mon', array(':bill_mon'=>$month))
        ->andWhere(array('in', 'mem_id', $mid))
        ->queryAll();
 
        return $data;
    }   
}