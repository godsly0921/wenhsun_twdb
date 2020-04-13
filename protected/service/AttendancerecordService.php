<?php

use Employee as EmployeeORM;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Service\EmployeeLeaveCalculator;
use PHPUnit\Framework\Exception;

class AttendancerecordService{

    public $normal_take = [3,4,5,6,7,8,9,10,16,17,18]; // 不扣全勤的假

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
        //$model->first_time = '0000-00-00 00:00:00';
        //$model->last_time = '0000-00-00 00:00:01';

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

    public function summaryMinutesByPeriodOfTimeAndLeaveType(string $employeeId,string $startDateTime,string $endDateTime,string $leaveType)
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

    public function getEmployeeLeaveList($employeeId, $year)
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

    public function getEmployeeLeaveListHoliday($employeeId, $year)
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
              AND take != 11
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

    public function getEmployeeLeaveListOvertime($employeeId, $year)
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
              AND take = 11
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

    private $leaveMap = [
        '1' => '普通傷病假',
        '2' => '事假',
        '3' => '公假',
        '4' => '公傷病假',
        '5' => '特別休假',
        '6' => '分娩假含例假日',
        '7' => '婚假',
        '8' => '喪假',
        '9' => '補休',
        '10' => '生理假',
        '11' => '加班',
        '12' => '非請假(早退)',
        '13' => '非請假(遲到加早退)',
        '14' => '非請假(遲到)',
        '15' => '非請假(忘記刷卡)',
        '16' => '陪產假',
        '17' => '流產假',
        '18' => '產檢假',
    ];

    public function sendApproveMail($start_time, $end_time, $leave_hours, $id)
    {
        try {
            $leave = Attendancerecord::model()->findByPk($id);
            $emp = EmployeeORM::model()->findByPk($leave->employee_id);
            $employee = new Employee(new EmployeeId($emp->id), $emp->onboard_date);
            $year = date('Y', strtotime($leave->leave_time));
            $agent = EmployeeORM::model()->findByPk($leave->agent);
            $manager = EmployeeORM::model()->findByPk($leave->manager);

            $employeeLeaveCalculator = new EmployeeLeaveCalculator();
            $annualLeaveMinutes = $employeeLeaveCalculator->calcAnnualLeaveSummaryOnBoardDate(new DateTime(), $employee);

            $attendanceRecordServ = new AttendancerecordService();
            $tomorrow = new DateTime();
            $tomorrow->add(DateInterval::createFromDateString('1 day'));
            $appliedAnnualLeave = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $employee->getOnBoardDate() . ' 00:00:00',
                $tomorrow->format('Y-m-d 00:00:00'),
                Attendance::ANNUAL_LEAVE
            );

            $personalLeaveAnnualMinutes = $employeeLeaveCalculator->personalLeaveAnnualMinutes();
            $sickLeaveAnnualMinutes = $employeeLeaveCalculator->sickLeaveAnnualMinutes();

            $commonLeaveStartDateTime = new DateTime("{$year}/01/01 00:00:00");
            $commonLeaveStartDate = $commonLeaveStartDateTime->format('Y/m/d H:i:s');
            $commonLeaveEndDateTime = new DateTime("{$year}/01/01 00:00:00");
            $commonLeaveEndDateTime->add(DateInterval::createFromDateString('1 year'));
            $commonLeaveEndDate = $commonLeaveEndDateTime->format('Y/m/d H:i:s');

            $sickLeavedMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                Attendance::SICK_LEAVE
            );

            $personalLeavedMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                Attendance::PERSONAL_LEAVE
            );

            $publicAffairsLeaveMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                Attendance::PUBLIC_AFFAIRS_LEAVE
            );

            $occupationalSicknessLeaveMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                Attendance::OCCUPATIONAL_SICKNESS_LEAVE
            );

            $maternityLeaveMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                Attendance::MATERNITY_LEAVE
            );

            $maritalLeaveMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                Attendance::MARITAL_LEAVE
            );

            $funeralLeaveMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                Attendance::FUNERAL_LEAVE
            );

            $compensatoryLeavedMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                Attendance::COMPENSATORY_LEAVE
            );

            $menstrualLeaveMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                Attendance::MENSTRUAL_LEAVE
            );

            $paternityLeaveMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                Attendance::PATERNITY_LEAVE
            );

            $miscarriageLeaveMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                Attendance::MISCARRIAGE_LEAVE
            );

            $prenatalLeaveMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                Attendance::PRENATAL_LEAVE
            );

            $summary = [
                [
                    'category' => '普通傷病假',
                    'leave_applied' => $sickLeavedMins / 60,
                    'leave_available' => $sickLeaveAnnualMinutes / 60 - $sickLeavedMins / 60,
                ],
                [
                    'category' => '事假',
                    'leave_applied' => $personalLeavedMins / 60,
                    'leave_available' => $personalLeaveAnnualMinutes / 60 - $personalLeavedMins / 60,
                ],
                [
                    'category' => '公假',
                    'leave_applied' => $publicAffairsLeaveMins / 60,
                    'leave_available' => '-',
                ],
                [
                    'category' => '公傷病假',
                    'leave_applied' => $occupationalSicknessLeaveMins / 60,
                    'leave_available' => '-',
                ],
                [
                    'category' => '年假(特別休假)',
                    'leave_applied' => $appliedAnnualLeave / 60,
                    'leave_available' => $annualLeaveMinutes->minutesValue() / 60 - $appliedAnnualLeave / 60,
                ],
                [
                    'category' => '分娩假含例假日',
                    'leave_applied' => $maternityLeaveMins / 60,
                    'leave_available' => '-',
                ],
                [
                    'category' => '婚假',
                    'leave_applied' => $maritalLeaveMins / 60,
                    'leave_available' => '-',
                ],
                [
                    'category' => '喪假',
                    'leave_applied' => $funeralLeaveMins / 60,
                    'leave_available' => '-',
                ],
                [
                    'category' => '補休假',
                    'leave_applied' => $compensatoryLeavedMins / 60,
                    'leave_available' => '-',
                ],
                [
                    'category' => '生理假',
                    'leave_applied' => $menstrualLeaveMins / 60,
                    'leave_available' => '-',
                ],
                [
                    'category' => '陪產假',
                    'leave_applied' => $paternityLeaveMins / 60,
                    'leave_available' => '-',
                ],
                [
                    'category' => '流產假',
                    'leave_applied' => $miscarriageLeaveMins / 60,
                    'leave_available' => '-',
                ],
                [
                    'category' => '產檢假',
                    'leave_applied' => $prenatalLeaveMins / 60,
                    'leave_available' => '-',
                ],
            ];

            $service = new AttendancerecordService();

            if ($leave->take == 11) {
                $subject = $emp->name . '(' . $emp->user_name . ') - 加班申請';

                $body = "<h2>" . $subject . "</h2><br>" .
                    "<table style='border: 1px solid black; border-style: collapse;'>
                            <thead>
                                <tr style='border: 1px solid black; padding: 10px;'>
                                    <th style='border: 1px solid black; padding: 10px;'>申請日期</th>
                                    <th style='border: 1px solid black; padding: 10px;'>事由</th>
                                    <th style='border: 1px solid black; padding: 10px;'>加班日期</th>
                                    <th style='border: 1px solid black; padding: 10px;'>時間</th>
                                    <th style='border: 1px solid black; padding: 10px;'>申請時數</th>
                                    <th style='border: 1px solid black; padding: 10px;'>審核狀態</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style='border: 1px solid black; padding: 10px;'>
                                    <td style='border: 1px solid black; padding: 10px;'>" . substr($leave->create_at, 0, 10) . "</td>
                                    <td style='border: 1px solid black; padding: 10px;'>" . $leave->reason . "</td>
                                    <td style='border: 1px solid black; padding: 10px;'>" . substr($start_time, 0, 10) . "</td>
                                    <td style='border: 1px solid black; padding: 10px;'>" . $start_time . " - " . $end_time . "</td>
                                    <td style='border: 1px solid black; padding: 10px;'>" . $leave_hours . "</td>
                                    <td style='border: 1px solid black; padding: 10px;'>" . ($leave->status == 0 ? "未審核" : "已審核") . "</td>
                                </tr>
                            </tbody>
                         </table>";
            } else {
                $subject = $emp->name . '(' . $emp->user_name . ') - 請假申請';

                $body = "<h2>" . $subject . "</h2><br>" .
                    "<table style='border: 1px solid black; border-style: collapse;'>
                            <thead>
                                <tr style='border: 1px solid black; padding: 10px;'>
                                    <th style='border: 1px solid black; padding: 10px;'>申請日期</th>
                                    <th style='border: 1px solid black; padding: 10px;'>假別</th>
                                    <th style='border: 1px solid black; padding: 10px;'>事由</th>
                                    <th style='border: 1px solid black; padding: 10px;'>請假日期</th>
                                    <th style='border: 1px solid black; padding: 10px;'>時間</th>
                                    <th style='border: 1px solid black; padding: 10px;'>申請時數</th>
                                    <th style='border: 1px solid black; padding: 10px;'>工作交辦</th>
                                    <th style='border: 1px solid black; padding: 10px;'>審核狀態</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style='border: 1px solid black; padding: 10px;'>
                                    <td style='border: 1px solid black; padding: 10px;'>" . substr($leave->create_at, 0, 10) . "</td>
                                    <td style='border: 1px solid black; padding: 10px;'>" . $this->leaveMap[$leave->take] . "</td>
                                    <td style='border: 1px solid black; padding: 10px;'>" . $leave->reason . "</td>
                                    <td style='border: 1px solid black; padding: 10px;'>" . substr($start_time, 0, 10) . "</td>
                                    <td style='border: 1px solid black; padding: 10px;'>" . $start_time . " - " . $end_time . "</td>
                                    <td style='border: 1px solid black; padding: 10px;'>" . $leave_hours . "</td>
                                    <td style='border: 1px solid black; padding: 10px;'>" . $leave->remark . "</td>
                                    <td style='border: 1px solid black; padding: 10px;'>" . ($leave->status == 0 ? "未審核" : "已審核") . "</td>
                                </tr>
                            </tbody>
                         </table>
                         <br>
                         <br>
                         <table style='border: 1px solid black; border-style: collapse;'>
                            <thead>
                                <tr style='border: 1px solid black; padding: 10px;'>
                                    <th style='border: 1px solid black; padding: 10px;'>假別</th>
                                    <th style='border: 1px solid black; padding: 10px;'>已請時數(小時)</th>
                                    <th style='border: 1px solid black; padding: 10px;'>可請時數(小時)</th>
                                </tr>
                            </thead>
                            <tbody>";

                foreach ($summary as $row) {
                    $body .= "<tr style='border: 1px solid black; padding: 10px;'>";
                    $body .= "<td style='border: 1px solid black; padding: 10px;'>" . $row['category'] . "</td>";
                    $body .= "<td style='border: 1px solid black; padding: 10px;'>" . $row['leave_applied'] . "</td>";
                    $body .= "<td style='border: 1px solid black; padding: 10px;'>" . $row['leave_available'] . "</td>";
                    $body .= "</tr>";
                }

                $body .= "</tbody></table><br><br>";
                $body .= "<table style='border: 1px solid black; border-style: collapse;'>
                              <thead>
                                  <tr style='border: 1px solid black; padding: 10px;'>
                                      <th style='border: 1px solid black; padding: 10px;'>申請日期</th>
                                      <th style='border: 1px solid black; padding: 10px;'>假別</th>
                                      <th style='border: 1px solid black; padding: 10px;'>事由</th>
                                      <th style='border: 1px solid black; padding: 10px;'>請假日期</th>
                                      <th style='border: 1px solid black; padding: 10px;'>時間</th>
                                      <th style='border: 1px solid black; padding: 10px;'>申請時數</th>
                                      <th style='border: 1px solid black; padding: 10px;'>工作交辦</th>
                                      <th style='border: 1px solid black; padding: 10px;'>審核狀態</th>
                                  </tr>
                              </thead>
                         <tbody>";

                $holidayList = $service->getEmployeeLeaveListHoliday($emp->id, $year);

                $body .= "</tbody>";

                foreach ($holidayList as $row) {
                    $body .= "<tr style='border: 1px solid black; padding: 10px;'>";
                    $body .= "<td style='border: 1px solid black; padding: 10px;'>" . substr($row['create_at'], 0, 10) . "</td>";
                    $body .= "<td style='border: 1px solid black; padding: 10px;'>" . $this->leaveMap[$row['take']] . "</td>";
                    $body .= "<td style='border: 1px solid black; padding: 10px;'>" . $row['reason'] . "</td>";
                    $body .= "<td style='border: 1px solid black; padding: 10px;'>" . substr($row['leave_time'], 0, 10) . "</td>";
                    $body .= "<td style='border: 1px solid black; padding: 10px;'>" . substr($row['start_time'], 11, 8) . " - " . substr($row['end_time'], 11, 8) . "</td>";
                    $body .= "<td style='border: 1px solid black; padding: 10px;'>" . (float) $row['leave_minutes'] / 60 . "</td>";
                    $body .= "<td style='border: 1px solid black; padding: 10px;'>" . $row['remark'] . "</td>";
                    $body .= "<td style='border: 1px solid black; padding: 10px;'>" . ($row['status'] == 0 ? "未審核" : "已審核") . "</td>";
                    $body .= "</tr>";
                }

                $body .= "</tbody></table>";
            }
            $body .=  '<a href="'.'http://192.168.0.160/wenhsun_hr/leave/manager/hist?type=1&user_name='.$emp->user_name.'&name=&year='.$year.'">內網請點擊審核</a>';
            $body .= '<hr size="8px" align="center" width="100%">';
            $body .=  '<a href="'.'http://203.69.216.186/wenhsun_hr/leave/manager/hist?type=1&user_name='.$emp->user_name.'&name=&year='.$year.'">外網請點擊審核</a>';

            $inputs = array();
            $inputs['subject'] = $subject;
            $inputs['body'] = $body;
            $inputs['to'] = $emp->email;
            $inputs['agent'] = $agent == null ? '' : $agent->email;
            $inputs['manager'] = $manager->email;
            //$inputs['agent'] = 'godsly0921@gmail.com';
            //$inputs['manager'] = 'godsly0921@gmail.com';

            $mailService = new MailService();
            $mailService->sendApproveMail($inputs);

            return true;
        } catch (Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = $e->getMessage();
            return false;
        }
    }

    public function getLeaveHoursByDate($date) {
        $start_time = $date . ' 00:00:00';
        $end_time = $date . ' 23:59:59';
        $list = Yii::app()->db->createCommand(
            '
              SELECT employee_id, SUM(leave_minutes) minutes FROM attendance_record
               WHERE leave_time >= :start_time
                 AND leave_time < :end_time
                 AND take != 11
               GROUP BY employee_id
            '
        )->bindValues([
            ':start_time' => $start_time,
            ':end_time' => $end_time
        ])->queryAll();

        $listArr = array();
        foreach ($list as $value) {
           $listArr[$value['employee_id']] = (float) $value['minutes'] / 60;
        }

        return $listArr;
    }

    public function queryFullAttendanceRecord($startDate, $endDate) {
        $start_time = $startDate;
        $end_time = $endDate;
        // 計算名單：文訊主管(2)、文訊正職(5)、文訊人事主管／會計(26)、社長(27)、文訊企畫編輯(33)
        $roleList = array(2,5,26,27,33);
        $list = Yii::app()->db->createCommand(
            '
              SELECT a.id, a.name, b.day, b.first_time, b.last_time, b.abnormal_type, b.take FROM employee a, attendance_record b
               WHERE b.day >= :start_time
                 AND b.day <= :end_time
                 AND a.id = b.employee_id
                 AND a.role IN ('. implode(',',$roleList) . ')
            '
        )->bindValues([
            ':start_time' => date('Y-m-d', strtotime($start_time)),
            ':end_time' => date('Y-m-d', strtotime($end_time))
        ])->queryAll();
        $attendanceDays = $this->getAttendanceDayList($start_time, $end_time);
        $resultList = $this->organizeFullAttendanceRecord($list, $attendanceDays);
        return $resultList;
    }

    private function organizeFullAttendanceRecord($list, $attendanceDays) {
        $resultList = array();
        foreach ($list as $record) {
          if(array_key_exists($record["id"], $resultList)) { // 個人第二筆之後出勤
              $r = $resultList[$record["id"]];
              if(strtotime($record["day"]) < $r->start_date) {
                  $r->start_date = strtotime($record["day"]);
              }
              if(strtotime($record["day"]) > $r->end_date) {
                  $r->end_date = strtotime($record["day"]);
              }
              if($this->isAttendanceDay($record["day"], $attendanceDays)) { // 是出勤日
                  if ("1" == $record["take"] || "2" == $record["take"]) { // 普通傷病假 or 事假
                      $r->absence = true;
                  } else if (in_array((int)$record["take"], $this->normal_take)){ // 不扣全勤的假
                      array_push($r->leaveDays, $record["day"]);
                  } else {
                      if(strtotime($record["first_time"]) >= 0){ //有出勤
                          $diff_time = strtotime($record["last_time"]) - strtotime($record["first_time"]);
                          if($diff_time < (60 * 60 * 8)) {
                              if(in_array($record["day"], $r->leaveDays)) {
                                  // 有請假, do nothing
                              } else {
                                  $r->absenceDays[$record["day"]] = true;
                              }
                          }
                          $fullAttendanceType = $this->getFullAttendanceType($record["day"], $record["first_time"]);
                          if("A" == $fullAttendanceType) {
                              $r->a_normal_take += 1;
                          } else if("B" == $fullAttendanceType) {
                              $r->b_normal_take += 1;
                          } else {
                              if(in_array($record["day"], $r->leaveDays)) {
                                  // 有請假, do nothing
                              } else {
                                  $r->absenceDays[$record["day"]] = true;
                              }
                          }
                      } else {
                          if(in_array($record["day"], $r->leaveDays) || "11" == $record["take"]) {
                              // 有請假或是加班(take == 11),do nothing
                          } else {
                              $r->absenceDays[$record["day"]] = true;
                          }
                      }
                  }
              }
          } else { // 個人第一筆出勤
              $r = new stdClass();
              $r->id = $record["id"];
              $r->name = $record["name"];
              $r->start_date = strtotime($record["day"]);
              $r->end_date = strtotime($record["day"]);
              $r->a_normal_take = 0;
              $r->b_normal_take = 0;
              $r->absence = false;
              $r->leaveDays = array(); // 記錄有請假的日子
              $r->absenceDays = array(); //記錄有缺勤的日子，缺勤為 true ，有補請假為 false
              if($this->isAttendanceDay($record["day"], $attendanceDays)) { // 是出勤日
                  if ("1" == $record["take"] || "2" == $record["take"]) { // 普通傷病假 or 事假
                      $r->absence = true;
                  } else if (in_array((int)$record["take"], $this->normal_take)){ // 不扣全勤的假
                      // 記錄於 leaveDays array
                      array_push($r->leaveDays, $record["day"]);
                  } else {
                      if(strtotime($record["first_time"]) >= 0){ //有出勤
                          $diff_time = strtotime($record["last_time"]) - strtotime($record["first_time"]);
                          if($diff_time < (60 * 60 * 8)) {
                              // 工作未滿並八小時
                              if(in_array($record["day"], $r->leaveDays)) {
                                  // 有請假, do nothing

                              } else {
                                  $r->absenceDays[$record["day"]] = true;
                              }
                          }
                          $fullAttendanceType = $this->getFullAttendanceType($record["day"], $record["first_time"]);
                          if("A" == $fullAttendanceType) {
                              $r->a_normal_take = 1;
                          } else if("B" == $fullAttendanceType) {
                              $r->b_normal_take = 1;
                          } else {
                              $r->absenceDays[$record["day"]] = true;
                          }

                      } else {
                            if(in_array($record["day"], $r->leaveDays) || "11" == $record["take"]) {
                                // 有請假或是加班(take == 11),do nothing
                            } else {
                                $r->absenceDays[$record["day"]] = true;
                        }
                      }
                  }
              }
              $resultList[$record["id"]] = $r;
            }
        }
        foreach ($resultList as $result) {
            foreach ($result->leaveDays as $leaveDay) {
                if(array_key_exists($leaveDay, $result->absenceDays)) {
                    $result->absenceDays[$leaveDay] = false;
                }
            }
            foreach ($result->absenceDays as $absenceDay) {
                if($absenceDay) {
                    $result->absence = true;
                }
            }
        }
        return $resultList;
    }
    public function getAttendanceDayList($start_time, $end_time) {
        $list = Yii::app()->db->createCommand(
            '
              SELECT day, type FROM attendance
               WHERE day >= :start_time
                 AND day <= :end_time
            '
        )->bindValues([
            ':start_time' => date('Y-m-d', strtotime($start_time)),
            ':end_time' => date('Y-m-d', strtotime($end_time))
        ])->queryAll();
        $resultList = array();
        foreach ($list as $record) {
            $resultList[$record["day"]] = $record["type"];
        }
        return $resultList;
    }

    public function isAttendanceDay($day, $attendanceDayList) {
        if(array_key_exists($day, $attendanceDayList)) {
            if($attendanceDayList[$day] == "0") {
               return false;
            }
            return true;
        }
        $weekday = date('w', strtotime($day));
        if(6 == $weekday || 0 == $weekday) {
            return false;
        }
        return true;
    }

    public function getFullAttendanceType($day, $first_time) {
        $a_period = strtotime($day . ' 09:01:00');
        $b_period = strtotime($day . ' 09:31:00');
        if(strtotime($first_time) <= $a_period) {
            return "A";
        } else if (strtotime($first_time) >= $a_period && strtotime($first_time) <= $b_period) {
            return "B";
        }
        return "C";
    }

    public function getDayCount($start_date, $end_date) {
        $attendanceDayList = $this->getAttendanceDayList($start_date, $end_date);
        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            new DateTime($end_date)
        );
        $count = 0;
        foreach ($period as $day) {
            if($this->isAttendanceDay($day->format('Y-m-d'), $attendanceDayList)) {
                $count += 1;
            }
        }
        return $count;
    }
}
