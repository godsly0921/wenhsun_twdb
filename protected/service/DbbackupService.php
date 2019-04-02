<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 下午 05:07
 */
class DbbackupService
{
    public function findDbbackup()
    {
        $result = Dbbackup::model()->findAll();
        return $result;
    }

    /**
     * @param array $input
     * @return contact
     */
    public function create(array $inputs)
    {
        $model = new Dbbackup();
        $model->title = $inputs['title'];
        $model->member_id = (int)$inputs['member_id'];
        $model->application_time = $inputs['application_time'];
        $model->category = $inputs['category'];
        $model->approval_status = $inputs['approval_status'];
        $model->approval_time = $inputs['approval_time'];
        $model->approval_account_id = $inputs['approval_account_id'];
        $model->member_ip = $inputs['member_ip'];
        $model->msg = $inputs['msg'];

        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            $success = $model->save();
        }

        if ($success === false) {
            $model->addError('save_fail', '新增失敗');
            return $model;
        }

        return $model;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateDbbackup(array $inputs)
    {
        $model = Dbbackup::model()->findByPk($inputs["id"]);

        $model->id = $model->id;
        $model->title = $inputs['title'];
        $model->member_id = $inputs['member_id'];
        $model->application_time = $inputs['application_time'];
        $model->category = $inputs['category'];
        $model->approval_status = $inputs['approval_status'];
        $model->approval_time = $inputs['approval_time'];
        $model->approval_account_id = $inputs['approval_account_id'];
        $model->member_ip = $inputs['member_ip'];
        $model->msg = $inputs['msg'];

        if ($model->validate()) {
            $model->update();
        }

        return $model;
    }
}