<?php
class ScheduleService
{
    public function findAllScheduleShift(){
        $all_schedule_shift = array();
        $all_schedule_shift = Scheduleshift::model()->findAll();
        return $all_schedule_shift;
    }
    public function findAllScheduleActive(){
        $all_schedule_active = array();
        $all_schedule_active = Scheduleactive::model()->findAll();
        return $all_schedule_active;
    }
    public function shift_create( $input ){
        $model = new Scheduleshift();
        $model->store_id = $input['store_id'];
        $model->in_out = $input['in_out'];
        $model->class = $input['class'];
        $model->is_special = $input['is_special'];
        $model->start_time = $input['start_time'].":00";
        $model->end_time = $input['end_time'].":00";
        $model->update_time = date('Y-m-d H:i:s');
        $model->update_id = Yii::app()->session['uid'];
        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                return array(true,'建立成功',$model);         
            }else{      
                return array(false,$model->getErrors());
            }
        }       
        return $model;
    }
    public function shift_update( $input ){
        $model = Scheduleshift::model()->findByPk($input['shift_id']);
        $model->store_id = $input['store_id'];
        $model->in_out = $input['in_out'];
        $model->class = $input['class'];
        $model->is_special = $input['is_special'];
        $model->start_time = $input['start_time'].":00";
        $model->end_time = $input['end_time'].":00";
        $model->update_time = date('Y-m-d H:i:s');
        $model->update_id = Yii::app()->session['uid'];
        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                return array(true,'修改成功',$model);         
            }else{      
                return array(false,$model->getErrors());
            }
        }       
        return $model;
    }

    public function shift_delete($id){
        $post = Scheduleshift::model()->findByPk( $id );
        if($post->delete()){
            return array(true,'刪除成功');
        }else{
            return array(false,$post->getErrors());
        }
    }

    public function active_create( $input ){
        $model = new Scheduleactive();
        $model->active_name = $input['active_name'];
        $model->active_date = $input['active_date'];
        $model->update_date = date('Y-m-d H:i:s');
        $model->update_id = Yii::app()->session['uid'];
        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                return array(true,'建立成功',$model);         
            }else{      
                return array(false,$model->getErrors());
            }
        }       
        return $model;
    }

    public function active_update( $input ){
        $model = Scheduleactive::model()->findByPk($input['active_id']);
        $model->active_id = $input['active_id'];
        $model->active_name = $input['active_name'];
        $model->active_date = $input['active_date'];
        $model->update_date = date('Y-m-d H:i:s');
        $model->update_id = Yii::app()->session['uid'];
        if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                return array(true,'修改成功',$model);         
            }else{      
                return array(false,$model->getErrors());
            }
        }       
        return $model;
    }

    public function active_delete($id){
        $post = Scheduleactive::model()->findByPk( $id );
        if($post->delete()){
            return array(true,'刪除成功');
        }else{
            return array(false,$post->getErrors());
        }
    }
    public function findByStatus($status)
    {
        $result = Schedule::model()->findAll([
            'condition' => 'status=:status',
            'params' => [
                ':status' => $status,
            ]
        ]);
        return $result;
    }
    public function findScheduleAll($empolyee_id)
    {
        $result = Schedule::model()->findAll([
            'condition' => 'empolyee_id=:empolyee_id and status=:status',
            'params' => [
                ':empolyee_id' => $empolyee_id, ':status' => 0,
            ]
        ]);
        return $result;
    }
    public function schedule_create(array $inputs)
    {
        $model = new Schedule();

      //  var_dump(Yii::app()->session['personal']);
      //  exit();

        $model->empolyee_id = $inputs['empolyee_id'];
        $model->store_id = $inputs['store_id'];
        $model->in_out = $inputs['in_out'];
        $model->class = $inputs['class'];
        $model->start_time = $inputs['start_time'];
        $model->end_time = $inputs['end_time'];
        $model->builder = Yii::app()->session['uid'];//這邊只能讓使用者建立預約{管理者無法}
        $model->builder_type = (int)(Yii::app()->session['personal'])?1:0;//這邊只能讓使用者建立預約{管理者無法}
        $model->status = 0;
        $model->create_time = date("Y-m-d H:i:s");
        $model->modify_time = date("Y-m-d H:i:s");
        $model->remark = '';

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
    public function findByIDByUserID($user_id,$id)
    {
        $result = Schedule::model()->find([
            'condition' => 'builder =:builder and id =:id',
            'params' => [
                ':builder' => $user_id,
                ':id' => $id,
            ]
        ]);
        return $result;
    }
    // 轉換預約狀態
    public function editScheduleStatus( $id , $status ){

        $schedule           = Schedule::model()->findByPk( $id );
        $schedule->status   = $status;
        $schedule->canceler = $_SESSION['uid'];
        $schedule->canceler_type= Yii::app()->session['personal']?0:1;
        if( $schedule->save() ){
            
            return true;

        }else{
            return false;
        }

    }
}
