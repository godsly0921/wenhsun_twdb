<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 下午 05:07
 */
class DepositService
{
    public static function findDeposit()
    {
        $result = Deposit::model()->findAll();
        return $result;
    }

    public function findDepositByMemId($mem_id)
    {
        $result = Deposit::model()->findAll([
                    'condition'=>'mem_id=:mem_id',
                    'params'=>[':mem_id'=>$mem_id]]);
        return $result;
    }

    /**
     * @param array $input
     * @return News
     */
    public function create(array $inputs)
    {
        $deposit = new Deposit();
        $deposit->type = $inputs['type'];
        $deposit->number = $inputs['number'];
        $deposit->mem_id = $inputs['mem_id'];
        $deposit->personid = $inputs['personid'];
        $deposit->amount = $inputs['amount'];
        $deposit->start_date = $inputs['start_date'];
        $deposit->end_date = $inputs['end_date'];
        $deposit->consultant_id = $inputs['consultant_id'];
        $deposit->status = $inputs['status'];
        $deposit->createdate = date("Y-m-d H:i:s");
        $deposit->deletedate = 'null';

        if (!$deposit->validate()) {
            return $deposit;
        }

        if (!$deposit->hasErrors()) {
            $success = $deposit->save();
        }

        if ($success === false) {
            $deposit->addError('save_fail', '新增存單失敗');
            return $deposit;
        }

        return $deposit;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateDeposit(array $inputs)
    {
        $deposit = Deposit::model()->findByPk($inputs["id"]);
        $deposit->id = $deposit->id;
        $deposit->type = $inputs['type'];
        $deposit->number = $inputs['number'];
        $deposit->mem_id = $inputs['mem_id'];
        $deposit->personid = $inputs['personid'];
        $deposit->amount = $inputs['amount'];
        $deposit->start_date = $inputs['start_date'];
        $deposit->end_date = $inputs['end_date'];
        $deposit->consultant_id = $inputs['consultant_id'];
        $deposit->status = $inputs['status'];

        if ($deposit->validate()) {
            $deposit->update();
        }

        return $deposit;
    }
}