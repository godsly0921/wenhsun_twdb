<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 下午 05:07
 */
class LocalService
{
    public static function findLocal()
    {
        $result = Local::model()->findAll([
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
        $model = new Local();
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
    public function DoCreateLocal()
    {
        $model = Local::model()->findAll();

        if(count($model)!=0){
            return $model;
        }else{
            return false;
        }
    }

    public function updateLocal(array $inputs)
    {
        $model = Local::model()->findByPk($inputs["id"]);

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

    public function del( $id ){

    $post = Local::model()->findByPk( $id ); 
    $post->delete();
    
    }
}