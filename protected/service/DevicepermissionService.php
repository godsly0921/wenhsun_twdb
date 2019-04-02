<?php
class DevicepermissionService{

    // 根據日期或關鍵字抓出所有符合之異常紀錄
    public function findDevicepermissionAndConditionDayAll($inputs){
        $criteria = new CDbCriteria;

        //------------------------
        if($inputs["device"]==="0" && $inputs["weeks"]==="0,1,2,3,4,5,6" && $inputs["start_hors"]==="00" && $inputs["start_minute"]==="00"&& $inputs["end_hors"]==="00" && $inputs["end_minute"]==="00"){
           /* echo '1';*/
            $criteria->select = "*";

            /*$criteria->condition = "delete_datetime  >= :start AND delete_datetime  <= :end AND delete_datetime !=:delete_datetime";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end'],':delete_datetime' =>'0000-00-00 00:00:00'));*/

            $criteria ->order = "device_id ASC";

        }

        if($inputs["device"]==="0" && is_array($inputs["weeks"])){
            /*echo '2';*/
            $criteria->select = "*";
            $condition_in = ['weeks'=>$inputs["weeks"]];
            if(count($inputs["weeks"])>=1){
                foreach($condition_in as $key=>$value){
                    $criteria->addInCondition($key, $value);
                }
            }
            $criteria->condition = "start_hors >= :start AND start_minute >= :start_minute AND end_hors <=:end_hors AND end_minute <=:end_minute";
            $criteria->params=(array(':start' => $inputs["start_hors"],':start_minute' =>$inputs['start_minute'],':end_hors' =>$inputs["end_hors"],':end_minute' =>$inputs["end_minute"]));
            $criteria ->order = "device_id ASC";

        }
        if($inputs["device"]!=="0" && is_array($inputs["weeks"])){
           /* echo '3';*/
            $criteria->select = "*";
            $condition_in = ['weeks'=>$inputs["weeks"]];
            if(count($inputs["weeks"])>=1){
                foreach($condition_in as $key=>$value){
                    $criteria->addInCondition($key, $value);
                }
            }
            $criteria->condition = "device_id = :device AND start_hors >= :start AND start_minute >= :start_minute AND end_hors <=:end_hors AND end_minute <=:end_minute";
            $criteria->params=(array(':start' => $inputs["start_hors"],':start_minute' =>$inputs['start_minute'],':end_hors' =>$inputs["end_hors"],':end_minute' =>$inputs["end_minute"],':device'=>$inputs["device"]));
            $criteria ->order = "device_id ASC";



        }

        if($inputs["device"]==="0" && $inputs["weeks"]==NULL){
            /*echo '4';*/
            $criteria->select = "*";
            $criteria->condition = "start_hors >= :start AND start_minute >= :start_minute AND end_hors <=:end_hors AND end_minute <=:end_minute";
            $criteria->params=(array(':start' => $inputs["start_hors"],':start_minute' =>$inputs['start_minute'],':end_hors' =>$inputs["end_hors"],':end_minute' =>$inputs["end_minute"]));
            $criteria ->order = "device_id ASC";


        }

        if($inputs["device"]!=="0" && $inputs["weeks"]==NULL){
            /*echo '5';*/
            $criteria->select = "*";
            $criteria->condition = "device_id = :device AND start_hors >= :start AND start_minute >= :start_minute AND end_hors <=:end_hors AND end_minute <=:end_minute";
            $criteria->params=(array(':start' => $inputs["start_hors"],':start_minute' =>$inputs['start_minute'],':end_hors' =>$inputs["end_hors"],':end_minute' =>$inputs["end_minute"],':device'=>$inputs["device"]));
            $criteria ->order = "device_id ASC";


        }

        $tmp =  Devicepermission::model()->findAll($criteria);

        //var_dump($tmp);
        return $tmp;

    }

    // 根據日期或關鍵字抓出所有符合之異常紀錄
    public function findDevicepermissionByWeekTime($inputs){
        $condition = "";
        if(is_array($inputs["weeks"])){
            foreach ($inputs["weeks"] as $key => $value){
                if(strlen($condition) == 0){
                    $condition .= " weeks like '%" . $value . "%'";
                }else{
                    $condition .= " AND weeks like '%" . $value . "%'";
                }
            }
        }
        if(strlen($condition) > 0) $condition .= " AND ";
        $condition .= "start_hors >= " .$inputs["start_hors"]. " AND start_minute >= " .$inputs["start_minute"]. " AND end_hors <= " .$inputs["end_hors"]. " AND end_minute <= " .$inputs["end_minute"];
        $criteria = new CDbCriteria;
        $criteria->condition = $condition;
        $criteria ->order = "id ASC";
        $tmp =  Devicepermission::model()->findAll($criteria);

        return $tmp;
    }

    // 根據日期或關鍵字抓出所有符合之異常紀錄
    public function findTimePermission($time_permission_id){
        return $result = Devicepermission::model()->find(array(
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
        $model = Devicepermission::model()->findByPk($inputs["id"]);

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
        $model = new Devicepermission;

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
        return $result = Devicepermission::model()->findALL(array(
            'select' => '*',
            //'condition'=>'id=:time_permission',
            //'params'=>array(
            //    ':time_permission' => $time_permission_id,
           // )
        ));

    }

    //找出儀器權限ID
    public function findTimePermissionById($time_permission_id){
        return $result = Devicepermission::model()->find(array(
            'select' => '*',
            'condition'=>'id=:time_permission',
            'params'=>array(
                ':time_permission' => $time_permission_id,
            )
        ));
    }



}
?>