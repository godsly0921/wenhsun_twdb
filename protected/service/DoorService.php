<?php
class DoorService{
    
    public function get_door_price( $station ){
        
        $data = Yii::app()->db->createCommand()
        ->select('price')
        ->from('door d')
        ->where('station=:station', array(':station'=>$station))
        ->queryRow();

        return  $data['price'];

    }
    
    public function get_door_id( $station ){
        
        $data = Yii::app()->db->createCommand()
        ->select('id')
        ->from('door d')
        ->where('station=:station', array(':station'=>$station))
        ->queryRow();

        return  $data['id'];


    }    

    public function get_all(){
        $data = Yii::app()->db->createCommand()
        ->select('d.*,l.name as lname')
        ->from('door d')
        ->leftjoin('local l', 'd.position=l.id')
        ->where('d.status=:status', array(':status'=>0))
        ->order('name asc')
        ->queryall();

        return $data;

    }

    public static function findDoor()
    {
        $result = Door::model()->findAll([
            'condition' => "status=:status",
            'params' => [':status' => '0']
        ]);
        return $result;
    }

    /**
     * @param array $input
     * @return contact
     */
    public function create(array $inputs)
    {
        $model = new Door();
        $model->name = $inputs['name'];
        $model->en_name = $inputs['en_name'];
        $model->position    = $inputs['position'];
        $model->status      = $inputs['status'];
        $model->station = $inputs['station'];
        $model->price = $inputs['price'];
        $model->name = $inputs['name'];
        $model->status = $inputs['status'];
        $model->create_date    = date("Y-m-d H:i:s");
        $model->edit_date      = "0000-00-00 00:00:00";
        $model->builder      = Yii::app()->session['uid'];

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
    public function updateDoor(array $inputs)
    {
        $model = Door::model()->findByPk($inputs["id"]);

        $model->id = $model->id;
        $model->name = $inputs['name'];
        $model->en_name = $inputs['en_name'];
        $model->position    = $inputs['position'];
        $model->status      = $inputs['status'];
        $model->station = $inputs['station'];
        $model->price = $inputs['price'];
        $model->name = $inputs['name'];
        $model->status = $inputs['status'];
        $model->create_date    = date("Y-m-d H:i:s");
        $model->edit_date      = "0000-00-00 00:00:00";
        $model->builder      = Yii::app()->session['uid'];

        if ($model->validate()) {
            $model->update();
        }

        return $model;

    }
}
?>