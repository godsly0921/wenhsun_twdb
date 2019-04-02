<?php
class DoorpermissionService{

    public function findTimePermission($time_permission_id){
        return $result = Doorpermission::model()->find(array(
            'select' => '*',
            'condition'=>'id=:time_permission',
            'params'=>array(
                ':time_permission' => $time_permission_id,
            )
        ));

    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function update(array $inputs)
    {
        $model = Doorpermission::model()->findByPk($inputs["id"]);

        if (count($inputs["weeks"]) > 1 || $inputs['start_hors'] != "" || $inputs['start_minute'] != "" || $inputs['end_hors'] != "" || $inputs['end_minute'] != "") {
            $model->id = $inputs['id'];

            if ($inputs['weeks'] !== null) {
                $model->weeks = json_encode($inputs['weeks']);
            } else {
                $model->addError('error', '錯誤');
                Yii::app()->session['error_msg'] = $model->getErrors();
            }

            $model->name = $inputs['name'];
            $model->start_hors = $inputs['start_hors'];
            $model->start_minute = $inputs['start_minute'];
            $model->end_hors = $inputs['end_hors'];
            $model->end_minute = $inputs['end_minute'];
            $model->builder = Yii::app()->session['uid'];
            $model->edit_time = date("Y-m-d H:i:s");

            if (!$model->validate()) {
                return $model;
            }

            if (!$model->hasErrors()) {
                $model->save();
                Yii::app()->session['success'] = '時段成功修改';
                return $model;
            } else {
                $model->addError('error', '錯誤');
                Yii::app()->session['error_msg'] = $model->getErrors();
            }
        } else {
            $model->addError('error', '欄位未正確填寫');
            Yii::app()->session['error_msg'] = $model->getErrors();
        }


    }


    public function create(array $inputs){
        $model = new Doorpermission;

        if ($inputs['weeks'] !== null) {
            $model->weeks = json_encode($inputs['weeks']);
        } else {
            $model->addError('error', '錯誤');
            Yii::app()->session['error_msg'] = $model->getErrors();
        }

        $model->name = $inputs['name'];
        $model->start_hors = $inputs['start_hors'];
        $model->start_minute = $inputs['start_minute'];
        $model->end_hors = $inputs['end_hors'];
        $model->end_minute = $inputs['end_minute'];
        $model->builder = Yii::app()->session['uid'];
        $model->edit_time = date("0000-00-00 00:00:00");
        $model->create_time = date("Y-m-d H:i:s");

        $model->insert();
        return $model;
    }

    //
    public function findTimePermissionAll(){
        return $result = Doorpermission::model()->findALL(array(
            'select' => '*',
            //'condition'=>'id=:time_permission',
            //'params'=>array(
            //    ':time_permission' => $time_permission_id,
           // )
        ));

    }

    //找出儀器權限ID
    public function findTimePermissionById($time_permission_id){
        return $result = Doorpermission::model()->find(array(
            'select' => '*',
            'condition'=>'id=:time_permission',
            'params'=>array(
                ':time_permission' => $time_permission_id,
            )
        ));
    }



}
?>