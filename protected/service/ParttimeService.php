<?php
class ParttimeService
{
    public function findPartTimeDayAll($start_time, $end_time)
    {
        $result = PartTime::model()->findAll([
            'condition' => 'start_time>=:start_time and end_time<=:end_time',
            'params' => [
                ':start_time' => $start_time,
                ':end_time' => $end_time,
            ]
        ]);
        return $result;
    }

    public function findPartTimeDayAllWithoutCancel($start_time, $end_time)
    {
        $result = PartTime::model()->findAll([
            'condition' => 'start_time>=:start_time and end_time<=:end_time and status!=:status',
            'params' => [
                ':start_time' => $start_time,
                ':end_time' => $end_time,
                ':status' => 3,
            ]
        ]);
        return $result;
    }

    public function findPartTimeDayAllByUserID($start_time, $end_time,$user_id,$id)
    {
        $result = PartTime::model()->findAll([
            'condition' => 'start_time>=:start_time and end_time<=:end_time and builder =:builder and id =:id',
            'params' => [
                ':start_time' => $start_time,
                ':end_time' => $end_time,
                ':builder' => $user_id,
                ':id' => $id,
            ]
        ]);
        return $result;
    }

    public function findPartTimeByUserIDWithTime($user_id,$startime,$endtime)
    {
        $result = PartTime::model()->findAll([
            'condition' => '(builder =:builder and start_time >=:startime and start_time <:endtime) or end_time>= DATE_SUB(NOW(), interval 30 minute);',
            'params' => [
                ':builder' => $user_id,
                ':startime' => $startime,
                ':endtime' => $endtime,
            ]
        ]);
        return $result;
    }

    //找出使用者目前『操作關閉時間』的儀器，是已超過別人預約開始的時間
    public function findPartTimeByUserTimeAndDeviceId($device_id,$user_time,$use_id)
    {
		$res = Yii::app()->db->createCommand()
        ->select('*')
        ->from('reservation')
        ->where('builder != :use_id and device_id = :device_id and start_time <= :user_time and end_time >= :user_time', array(':device_id'=>$device_id,':user_time'=>$user_time,':use_id'=>$use_id))
        ->queryRow();
		return ($res);
    }

    public function findPartTimeIDByUserID($user_id,$id)
    {
        $result = PartTime::model()->find([
            'condition' => 'builder =:builder and id =:id',
            'params' => [
                ':builder' => $user_id,
                ':id' => $id,
            ]
        ]);
        return $result;
    }

    public function findPartTimeIDByID($id)
    {
        $result = PartTime::model()->find([
            'condition' => 'id =:id',
            'params' => [
                ':id' => $id,
            ]
        ]);
        return $result;
    }

    public function findPartTimeDayAllForStation($station_id,$datetime)
    {
        $res = Yii::app()->db->createCommand()
            ->select('d.*, r.*')
            ->from('device d')
            ->where('station =:station and r.start_time <= :datetime and r.end_time >= :datetime', array(':station'=>$station_id,':datetime'=>$datetime))
            ->join('reservation r', 'r.device_id = d.id')
            ->queryAll();
        //2018-11-09 08:00:00 >= 2018-12-17 22:22:00  and 2018-11-10 08:00:00 <= 2018-12-17 22:22:00
        return ($res);
    }

    public function findPartTimeCancelDayAll($start_time, $end_time)
    {
        $result = PartTime::model()->findAll([
            'condition' => 'start_time>=:start_time and end_time<=:end_time and status=:status',
            'params' => [
                ':start_time' => $start_time,
                ':end_time' => $end_time,
                ':status' => 3,
            ]
        ]);
        return $result;
    }

    public function findPartTimeDeviceIDAll($device_id,$start_time, $end_time)
    {
        $result = PartTime::model()->findAll([
            'condition' => '((:start_time >= start_time AND :start_time < end_time)
                              OR (:end_time > start_time AND :end_time <= end_time))
                              and status=:status and device_id=:device_id',
            'params' => [
                ':start_time' => $start_time,
                ':end_time' => $end_time,
                ':device_id' => $device_id,
                ':status' => 0,
            ]
        ]);
        return $result;
    }

    public function findPartTimeCancelAndConditionDayAll($inputs)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '*';

        if ($inputs['device_id'] === "0" && $inputs['start_date'] !== "" && $inputs['end_date'] !== "") {
            $criteria->condition = 'start_time >= :start_time and end_time <= :end_time and status=:status';
            $criteria->params = array(':start_time' => $inputs['start_date'] . ' 00:00:00', ':end_time' => $inputs['end_date'] . ' 23:59:59', ':status' => 3);

        }

        if ($inputs['device_id'] !== "" && $inputs['start_date'] === "" && $inputs['end_date'] === "") {
            $criteria->condition = 'device_id = :device_id and status=:status';
            $criteria->params = array(':device_id' => $inputs['device_id'], ':status' => 3);

        }

        if ($inputs['device_id'] !== "0" && $inputs['start_date'] !== "" && $inputs['end_date'] !== "") {
            $criteria->condition = 'start_time >= :start_time and end_time <= :end_time and device_id = :device_id and status=:status';
            $criteria->params = array(':start_time' => $inputs['start_date'] . ' 00:00:00', ':end_time' => $inputs['end_date'] . ' 23:59:59', ':device_id' => $inputs['device_id'], ':status' => 3);

        }

        // }

        $datas = PartTime::model()->findAll($criteria);

        if (count($datas) == 0) {
            $datas = false;
        }

        return $datas;
    }


    public function findPartTimeDayAllAndDevice($empolyee_id,$day)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '*';
        $criteria->condition = 'status = :status and part_time_empolyee_id = :part_time_empolyee_id and start_time >= :start_time and end_time <= :end_time';
        $criteria->params = array(':status' => 0,':part_time_empolyee_id' => $empolyee_id,':start_time' => $day. ' 00:00:00', ':end_time' => $day. ' 23:59:59');

        $datas = PartTime::model()->findAll($criteria);

        if (count($datas) == 0) {
            $datas = false;
        }

        return $datas;

    }

    public function findPartTimeAll($part_time_empolyee_id)
    {
        $result = PartTime::model()->findAll([
            'condition' => 'part_time_empolyee_id=:part_time_empolyee_id and status=:status',
            'params' => [
                ':part_time_empolyee_id' => $part_time_empolyee_id, ':status' => 0,
            ]
        ]);
        return $result;
    }

    public function findPartTime()
    {
        $result = PartTime::model()->findAll();
        return $result;
    }

    public function findPartTimeStatus()
    {
        $result = PartTime::model()->findAll([
            'condition' => 'status=:status',
            'params' => [
                ':status' => 0,
            ]
        ]);
        return $result;
    }

    public function findPartTimeByStatusAndDate($status,$date)
    {
        $result = PartTime::model()->findAll([
            'condition' => 'status=:status and builder_type=:builder_type and start_time like :start_time',
            'params' => [
                ':status' => $status,
                ':builder_type' => 1,
                ':start_time' => $date.'%'
            ]
        ]);
        return $result;
    }
    /**
     * @param array $input
     * @return contact
     */
    public function create(array $inputs)
    {
        $model = new PartTime();

      //  var_dump(Yii::app()->session['personal']);
      //  exit();

        $model->part_time_empolyee_id = $inputs['part_time_empolyee_id'];
        $model->start_time = $inputs['start_date_time'];
        $model->end_time = $inputs['end_date_time'];
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

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updatePartTime(array $inputs)
    {
        $model = PartTime::model()->findByPk($inputs["id"]);

        $inputs["id"] = filter_input(INPUT_POST, "id");
        $inputs["device_id"] = filter_input(INPUT_POST, "device_id");
        $inputs["start_time"] = filter_input(INPUT_POST, "start_time");
        $inputs["end_time"] = filter_input(INPUT_POST, "end_time");
        $inputs["status"] = filter_input(INPUT_POST, "status");
        $inputs["remark"] = filter_input(INPUT_POST, "remark");
        $inputs["builder"] = filter_input(INPUT_POST, "builder");
        $inputs["canceler"] = filter_input(INPUT_POST, "canceler");

        $model->id = $model->id;
        $model->device_id = $inputs['device_id'];
        $model->start_time = $inputs['start_time'];
        $model->end_time = $inputs['end_time'];
        $model->status = $inputs['status'];
        $model->remark = $inputs['remark'];
        $model->builder = $inputs['builder'];
        $model->canceler = $inputs['canceler'];
        $model->modify_time = date("Y-m-d H:i:s");

        if ($model->validate()) {
            $model->update();
        }

        return $model;
    }

    // 尋找對應時間的預約紀錄
    public function get_reservation_by_time($mid,$start,$end){

        $data = Yii::app()->db->createCommand()
        ->select('*')
        ->from('reservation')
        ->where('builder=:builder', array(':builder'=>$mid))
        ->andWhere('start_time >= :start_time',array(':start_time'=>$start))
        ->andWhere('end_time <= :end_time',array(':end_time'=>$end))

        ->queryAll();

        return $data;
    }

    // 轉換預約狀態
    public function editPartTimeStatus( $id , $status ){

        $PartTime           = PartTime::model()->findByPk( $id );
        $PartTime->status   = $status;
        $PartTime->canceler = $_SESSION['uid'];
        $PartTime->canceler_type= Yii::app()->session['personal'];
        if( $PartTime->save() ){
            
            return true;

        }else{
            
            return false;
        }

    }

    public function findPartTimeById($id)
    {
        $result = PartTime::model()->findAll([
            'condition' => 'id=:id',
            'params' => [
                ':id' => $id
            ]
        ]);
        return $result;
    }
}
