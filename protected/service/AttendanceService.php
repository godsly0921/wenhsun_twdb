<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2019/4/27
 * Time: 下午 04:35
 */
class AttendanceService
{
    public static function findAttendance()
    {
        $result = Attendance::model()->findAll([
        ]);
        return $result;
    }

    /**
     * @param array $input
     * @return contact
     */
    public function create(array $inputs)
    {
        $model = new Attendance();
        $model->day = $inputs['day'];
        $model->type = (int)$inputs['type'];
        $model->description =  $inputs['description'];
        $model->create_at = date("Y-m-d H:i:s");
        $model->update_at = date("Y-m-d H:i:s");

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
    public function DoCreateAttendance()
    {
        $model = Attendance::model()->findAll();

        if(count($model)!=0){
            return $model;
        }else{
            return false;
        }
    }

    public function updateAttendance(array $inputs)
    {
        $model = Attendance::model()->findByPk($inputs["id"]);

        $model->id = $model->id;
        $model->day = $inputs['day'];
        $model->type = (int)$inputs['type'];
        $model->description = $inputs['description'];
        $model->create_at  = date("Y-m-d H:i:s");
        $model->update_at  = date("Y-m-d H:i:s");

        if ($model->validate()) {
            $model->update();
        }

        return $model;
    }

    public function del( $id ){

    $post = Attendance::model()->findByPk($id);
    $post->delete();
    
    }
}