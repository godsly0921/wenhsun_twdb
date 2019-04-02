<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 下午 05:07
 */
class DevicestatusService
{
    public static function findDevicestatus()
    {
        $result = Devicestatus::model()->findAll([
            'condition' => "status=:status",
            'params' => [':status' => '1']
        ]);
        return $result;
    }

    /**
     * @param array $input
     * @return contact
     */
    public function create(array $inputs)
    {
        $model = new Devicestatus();
        $model->name = $inputs['name'];
        $model->status = (int)$inputs['status'];
        $model->create_date    = date("Y-m-d H:i:s");
        $model->edit_date      = date("Y-m-d H:i:s");

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
    public function updateDevicestatus(array $inputs)
    {
        $model = Devicestatus::model()->findByPk($inputs["id"]);

        $model->id = $model->id;
        $model->name = $inputs['name'];
        $model->status = (int)$inputs['status'];
        $model->create_date    = date("Y-m-d H:i:s");
        $model->edit_date      = date("Y-m-d H:i:s");

        if ($model->validate()) {
            $model->update();
        }

        return $model;
    }
}