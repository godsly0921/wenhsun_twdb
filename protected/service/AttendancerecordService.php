<?php
class AttendancerecordService{

    // 新增一筆紀錄
    public function create($employee_id , $day , $first_time , $last_time ,$abnormal_type,$abnormal){
      $transaction = Yii::app()->db->beginTransaction();
      try {
        $post = new Attendancerecord();
        $post->employee_id   = $employee_id;
        $post->day       = $day;
        $post->first_time      = $first_time;
        $post->last_time     = $last_time;
        $post->abnormal_type     = $abnormal_type;
        $post->abnormal = $abnormal;
        $post->take = 0;
        $post->reply_description = '';
        $post->reply_update_at = date("Y-m-d H:i:s");
        $post->create_at =  date("Y-m-d H:i:s");
        $post->update_at =  date("Y-m-d H:i:s");
        $post->save();


        $transaction->commit();
          return $post;
      }
      catch (Exception $e) {
        $transaction->rollback();
        Yii::log("Attendance write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
      }
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function update(array $inputs)
    {
        $model = Attendancerecord::model()->findByPk($inputs["id"]);


       /* Yii::app()->session['uid'] = $sys_account->id;//使用者帳號ID
        Yii::app()->session['pid'] = $sys_account->user_name;//使用者帳號
        Yii::app()->session['personal'] = true;*/


        if($model->employee_id != Yii::app()->session['uid'] && Yii::app()->session['personal'] == true){
            $model->addErrors(['employee_id'=>'You can\'t change this record.']);
            return $model;
        }

        $model->id = $model->id;
        $model->take = $inputs['take'];
        $model->abnormal_type = 2;//員工回覆後 自動改為正常
        $model->reply_description    = $inputs['reply_description'];
        $model->reply_update_at = date('Y-m-d H:i:s');
        $model->leave_minutes = $inputs['leave_minutes'];
        $model->first_time = '0000-00-00 00:00:00';
        $model->last_time = '0000-00-00 00:00:01';

        if ($model->validate()) {
            $model->update();
        }

        return $model;

    }

    // 全取
    public function getall(){

        $data = Yii::app()->db->createCommand()
        ->select('b.*,m.name as mname,d.use_date as in,d2.use_date as out,dv.name as dvname')
        ->from('bill b')
        ->leftjoin('member m', 'b.member_id = m.id')
        ->leftjoin('device_record d', 'b.in_id = d.id')
        ->leftjoin('device_record d2','b.out_id = d2.id')
        ->leftjoin('device dv','b.dev_id = dv.id')
        ->queryAll();

        return $data;
        //return ( Bill::model()->findAll() );

    }
    /*----------------------------------------------------------------
     | 找出指定年月的所有資料
     |----------------------------------------------------------------
     |
     |
     */
    public function get_by_mid_and_month($mid , $start , $end ){
        $data = Yii::app()->db->createCommand()
        ->select('b.*,
                 r.flashDate as usedate,
                 d.position as dposition,
                 d.name as doorname')
        ->from('bill_door b')
        ->leftjoin('record r', 'b.in_id  = r.id')
        ->leftjoin('door d', 'r.reader_num  = d.station')
        ->where("r.flashDate >= '$start'")
        ->andWhere("r.flashDate <= '$end'")
        ->andwhere('b.member_id=:id', array(':id'=>$mid))
        ->order('r.flashDate asc')
        ->queryAll();

        return $data;
    }
    public function get_by_mid_in_and_month($mid , $end ){
      $data = Yii::app()->db->createCommand()
      ->select('b.*,
               r.flashDate as usedate,
               d.position as dposition,
               d.name as doorname')
      ->from('bill_door b')
      ->leftjoin('record r', 'b.in_id  = r.id')
      ->leftjoin('door d', 'r.reader_num  = d.station')
      ->where("r.flashDate <= '$end'")
      ->andWhere(array('in', 'b.member_id', $mid))
      ->order('r.flashDate asc')
      ->queryAll();

      return $data;
  }
  public function update_bill_door_status($checkout_time,$member_id,$bill_record_id){
    //$doorBill_sql = 'SELECT b.* from bill_door b LEFT JOIN record r on b.in_id=r.id where b.member_id in('.$member_id.') and r.flashDate <="'.$checkout_time.'" and b.status = 0';

    $update_sql = 'update bill_door b LEFT JOIN record r on b.in_id=r.id set b.status=1,b.bill_record_id='.$bill_record_id.' where r.flashDate <="'.$checkout_time.'" and b.status = 0 and b.member_id in('.$member_id.')';
    //update bill_door b LEFT JOIN record r on b.in_id=r.id set b.status=1,b.bill_record_id=1 where b.member_id in(208,209,210,211) and r.flashDate <="2019-03-22 00:14:14" and b.status = 0
    $doorBills = Yii::app()->db->createCommand($update_sql)->query();
    $update_status = true;
    // foreach ($doorBills as $doorBillk => $doorBill) {
    //   $bill = Bill_door::model()->findByPk($doorBill['id']);
    //   $bill->status = 1;
    //   $bill->bill_record_id = $bill_record_id;
    //   if(!$bill->save()) $update_status = false;
    // }
    return $update_status;
  }


    /*----------------------------------------------------------------
     |依照條件找
     |----------------------------------------------------------------
     |
     | $star - 開始時間
     | $end  - 結束時間
     |   <option value="0">姓名</option>
     |   <option value="2">員工帳號</option>
     |   <option value="1">卡號</option>

    $temp['user_name'] = $value['user_name'];
                $temp['attendance_record_id'] = $value['attendance_record_id'];
                $temp['name'] = $value['name'];
                $temp['day'] = $value['day'];
                $temp['first_time'] = $value['first_time'];
                $temp['last_time'] = $value['last_time'];
                switch ($value['abnormal_type']) {
                    case "0":
                        $value['abnormal_type'] = "正常";
                        break;
                    case "1":
                        $value['abnormal_type'] = "異常";
                        break;
                    case "2":
                        $value['abnormal_type'] = "用戶已回覆，正常";
                        break;
                }
                $temp['abnormal_type'] = $value['abnormal_type'];
                $temp['abnormal'] = $value['abnormal'];
                $temp['reply_description'] = $value['reply_description'];
                $temp['take'] = $this->fake[$value['take']];
                $temp['att_create_at'] = $value['att_create_at'];
                $temp['update_at'] = $value['update_at'];
     */
    public function get_by_condition($keyword_selected,$keyword,$key_column, $choose_start, $choose_end ){
        if($keyword_selected == 1){
            //echo '1';
            if($key_column == 0){
                echo '2';
                $data = Yii::app()->db->createCommand()
                    ->select('e.*,a.*,a.create_at as att_create_at,a.id as attendance_record_id')
                    ->from('employee e')
                    ->leftjoin('attendance_record a','a.employee_id = e.id')
                    ->where(array('like', 'e.name', "%$keyword%"))
                    ->andWhere("a.day >= '$choose_start'")
                    ->andWhere("a.day <= '$choose_end'")
                    ->order('e.user_name DESC,CONVERT(e.name using big5) ASC,a.day ASC')
                    ->queryAll();
                return $data;


            }else if($key_column == 1){ //卡號
                //echo '2';
                $data = Yii::app()->db->createCommand()
                    ->select('a.*,e.*,a.create_at as att_create_at,a.id as attendance_record_id')
                    ->from('employee e')
                    ->leftjoin('attendance_record a','a.employee_id = e.id')
                    ->where('e.door_card_num = :door_card_num', array(':door_card_num'=>$keyword))
                    ->andWhere("a.day >= '$choose_start'")
                    ->andWhere("a.day <= '$choose_end'")
                    ->order('e.user_name DESC,CONVERT(e.name using big5) ASC,a.day ASC')
                    ->queryAll();
                return $data;

            }else if($key_column == 2){ //帳號
                //echo '3';
                $data = Yii::app()->db->createCommand()
                    ->select('a.*,e.*,a.create_at as att_create_at,a.id as attendance_record_id')
                    ->from('employee e')
                    ->leftjoin('attendance_record a','a.employee_id = e.id')
                    ->where('e.user_name = :user_name', array(':user_name'=>$keyword))
                    ->andWhere("a.day >= '$choose_start'")
                    ->andWhere("a.day <= '$choose_end'")
                    ->order('e.user_name DESC,CONVERT(e.name using big5) ASC,a.day ASC')
                    ->queryAll();
                return $data;
            }else if($key_column == 3){ //個人查詢
                //echo '3';
                $data = Yii::app()->db->createCommand()
                    ->select('a.*,e.*,a.create_at as att_create_at,a.id as attendance_record_id')
                    ->from('employee e')
                    ->leftjoin('attendance_record a','a.employee_id = e.id')
                    ->where('e.id = :employee_id', array(':employee_id'=>$keyword))
                    ->andWhere("a.day >= '$choose_start'")
                    ->andWhere("a.day <= '$choose_end'")
                    ->order('e.user_name DESC,CONVERT(e.name using big5) ASC,a.day ASC')
                    ->queryAll();
                return $data;
            }

        }else{
            $data = Yii::app()->db->createCommand()
                ->select('a.*,e.*,a.create_at as att_create_at,a.id as attendance_record_id')
                ->from('employee e')
                ->leftjoin('attendance_record a','a.employee_id = e.id')
                ->andWhere("a.day >= '$choose_start'")
                ->andWhere("a.day <= '$choose_end'")
                ->order('e.user_name DESC,CONVERT(e.name using big5) ASC,a.day ASC')
                ->queryAll();
            return $data;
        }
    }

    /*----------------------------------------------------------------
     |依照條件找 門禁費用總計
     |----------------------------------------------------------------
     | $memid - 會員id陣列
     | $choose_door - 門禁陣列
     | $star - 開始時間
     | $end  - 結束時間
     |
     */
    public function get_by_condition_total($memid,$star,$end){

        $data = Yii::app()->db->createCommand()
            ->select('b.*,sum(o_price) as total_count')
            ->from('bill_door b')
            ->where('1=1')
            ->andWhere(array('in', 'member_id', $memid))
            ->andWhere("b.create_date > '$star'")
            ->andWhere("b.create_date < '$end'")
            ->queryAll();

        return $data;

    }

    public function summaryMinutesByPeriodOfTimeAndLeaveType(
        string $employeeId,
        string $startDateTime,
        string $endDateTime,
        string $leaveType): int
    {
        $r = Yii::app()->db->createCommand(
            '
              SELECT SUM(leave_minutes) AS summary_leave_minutes FROM attendance_record
              WHERE employee_id = :employee_id
              AND take = :leave_type
              AND leave_time >= :start_time
              AND leave_time < :end_time
            '
        )->bindValues([
            ':employee_id' => $employeeId,
            ':leave_type' => $leaveType,
            ':start_time' => $startDateTime,
            ':end_time' => $endDateTime,
        ])->queryRow();

        if (empty($r)) {
            return 0;
        }

        return (int) $r['summary_leave_minutes'];
    }

    public function getEmployeeLeaveList($employeeId, $year): array
    {
        $startDateTime = "{$year}-01-01 00:00:00";
        $yearEndDT = new DateTime($startDateTime);
        $yearEndDT->add(DateInterval::createFromDateString('1 year'));
        $endDateTime = $yearEndDT->format('Y-m-d') . ' 00:00:00';

        return Yii::app()->db->createCommand(
            '
              SELECT * FROM attendance_record
              WHERE employee_id = :employee_id
              AND leave_time >= :start_time
              AND leave_time < :end_time
              ORDER BY leave_time DESC
            '
        )->bindValues([
            ':employee_id' => $employeeId,
            ':start_time' => $startDateTime,
            ':end_time' => $endDateTime,
        ])->queryAll();
    }

    public function getEmployeeLeaveListHoliday($employeeId, $year): array
    {
        $startDateTime = "{$year}-01-01 00:00:00";
        $yearEndDT = new DateTime($startDateTime);
        $yearEndDT->add(DateInterval::createFromDateString('1 year'));
        $endDateTime = $yearEndDT->format('Y-m-d') . ' 00:00:00';

        $list = Yii::app()->db->createCommand(
            '
              SELECT * FROM attendance_record
              WHERE employee_id = :employee_id
              AND leave_time >= :start_time
              AND leave_time < :end_time
              AND take != 9 AND take != 11
              ORDER BY leave_time DESC
            '
        )->bindValues([
            ':employee_id' => $employeeId,
            ':start_time' => $startDateTime,
            ':end_time' => $endDateTime,
        ])->queryAll();

        $listArr = array();
        foreach ($list as $value) {
           $listArr[$value['id']] = $value;
        }

        return $listArr;
    }

    public function getEmployeeLeaveListOvertime($employeeId, $year): array
    {
        $startDateTime = "{$year}-01-01 00:00:00";
        $yearEndDT = new DateTime($startDateTime);
        $yearEndDT->add(DateInterval::createFromDateString('1 year'));
        $endDateTime = $yearEndDT->format('Y-m-d') . ' 00:00:00';

        $list = Yii::app()->db->createCommand(
            '
              SELECT * FROM attendance_record
              WHERE employee_id = :employee_id
              AND leave_time >= :start_time
              AND leave_time < :end_time
              AND take IN (9, 11)
              ORDER BY leave_time DESC
            '
        )->bindValues([
            ':employee_id' => $employeeId,
            ':start_time' => $startDateTime,
            ':end_time' => $endDateTime,
        ])->queryAll();

        $listArr = array();
        foreach ($list as $value) {
           $listArr[$value['id']] = $value;
        }

        return $listArr;
    }
}
