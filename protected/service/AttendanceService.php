<?php
define("NINE_HOUR", 32400);//上班八小時
define("TEN_HOUR", 39600);//上班十小時
define("OVER_TEN_HOUR", 39601);//上班時數超過十小時
define("ZERO_SECOND", 0);//僅一筆刷卡紀錄
define("TWO_SECOND", 2);//上班時數小於上班八小時
define("ONE_SECOND", 1);//1秒未出勤預設相差時間
class AttendanceService
{
    public function getArriveLateTime($day)
    {
        return strtotime(date($day . ' 09:31:00'));
    }

    public function getLeaveEarlyTime($day)
    {
        return strtotime(date($day . ' 18:30:00'));
    }

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
        $model->type = $inputs['type'];
        $model->description = $inputs['description'];
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

        if (count($model) != 0) {
            return $model;
        } else {
            return false;
        }
    }

    public function updateAttendance(array $inputs)
    {
        $model = Attendance::model()->findByPk($inputs["id"]);

        $model->day = $inputs['day'];
        $model->type = $inputs['type'];
        $model->description = $inputs['description'];
        $model->create_at = date("Y-m-d H:i:s");
        $model->update_at = date("Y-m-d H:i:s");

        if ($model->validate()) {
            $model->update();
        }

        return $model;
    }

    public function getAttxendanceData($day,$send_mail)
    {
        try {
            $employee_service = new EmployeeService();

            // 抓出所有不是PT的員工
            $data = $employee_service->findEmployeeNotInRolesListObject([7,37,38,39,40,43,44,45]);

            $attendanceRecordService = new AttendancerecordService();
            $leaveList = $attendanceRecordService->getLeaveHoursByDate($day);
            $leaveService = new LeaveService();
            foreach ($data as $key => $value) {
                $leaveHours = isset($leaveList[$value['id']]) ? $leaveList[$value['id']] : 0;
                $leave_data = $leaveService->findEmployeeLeaveByDay($value['id'], $day);
                if (!empty($value->door_card_num) || $value->door_card_num == "0000000000") { // 如果有員工有設定卡號的使用者就去抓

                    $start_date = $day . ' 00:00:00';
                    $end_date = $day . ' 23:59:59';

                    $model = new RecordService;
                    $record = $model->get_by_card($value->door_card_num, $start_date, $end_date);//找出所有的刷卡紀錄
                    $first_time = 0;
                    $last_time = 0;
                    $abnormal_type = 0;//0正常 1異常
                    $abnormal = '';
                    count($record);
                    $employee_id = $value->id;
                    $employee_email = $value->email;
                    $employee_name = $value->name;


                    if (!empty($record)) {
                        //---------------整理預計要寫入的參數資料

                        $i = 1;
                        $total = count($record);
                        foreach ($record as $k => $v) {
                            if ($i == 1 && $total != 1) {//第一筆
                                $first_time = $v->flashDate;
                            }
                            if ($i == $total) {//最後一筆
                                $last_time = $v->flashDate;
                            }
                            if ($i == 1 && $i == $total) {//最後一筆
                                $first_time = $v->flashDate;
                                $last_time = $v->flashDate;
                            }
                            $i++;
                        }

                        //避免奇怪的錯誤發生 在轉換一次
                        if (strtotime($first_time) > strtotime($last_time)) {
                            $first_tmp = $first_time;
                            $last_tmp = $last_time;
                            $first_time = $last_tmp;
                            $last_time = $first_tmp;

                        }
                    } else {
                        //如果沒有記錄預設的差
                        $first_time = '0000-00-00 00:00:00';
                        $last_time = '0000-00-00 00:00:01';
                    }


                    $special_attendance = false; // false 表示為一般出勤日
                    $special_attendance_type = '';
                    $special_attendance_description = '';
                    $attendance_day = $this->findAttendance();

                    //var_dump($attendance_day);
                    foreach ($attendance_day as $k => $v) {
                        if ($day == $v->day) {
                            $special_attendance = true;
                            $special_attendance_type = $v->type;
                            $special_attendance_description = $v->description;
                        }
                    }

                    $diff_time = strtotime($last_time) - strtotime($first_time);
                    $includingLeaveTime = $diff_time + $leaveHours;
                    $check_NINE_HOUR = true;
                    $check_leave_late = true;
                    $check_leave_early = true;
                    if(!empty($leave_data)){
                        $check_NINE_HOUR = false;
                        foreach ($leave_data as $leave_key => $leave_value) {
                            // 請整天
                            if(date('H',strtotime($leave_value['start_time'])) == '9' && date('H',strtotime($leave_value['end_time'])) != '18'){
                                $check_NINE_HOUR = false;
                                $check_leave_late = false;
                            }
                            if(date('H',strtotime($leave_value['end_time'])) == '18'){
                                $check_leave_early = false;
                            }
                        }
                    }
                    // 請假時數小於8時才判斷出勤
                    if ($leaveHours < 8) {
                        if ($special_attendance == true) { //特殊情況
                            if ($special_attendance_type == 1) { //特殊出勤日
                                if ($this->get_chinese_weekday($day) == "星期日") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 0; //正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1; //正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1; //異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期一") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 0; //正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1; //正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1; //異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期二") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 0; //正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1; //正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1; //異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期三") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 0; //正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1; //正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1; //異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期四") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 0; //正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1; //正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1; //異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期五") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 0; //正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1; //正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $check_NINE_HOUR) {
                                        $abnormal_type = 1; //異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期六") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 0; //正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1; //正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1; //異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                }
                            } elseif ($special_attendance_type == 0) { //休假日
                                if ($this->get_chinese_weekday($day) == "星期日") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期一") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1; //異常需填寫異常單
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期二") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1; //異常需填寫異常單
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期三") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期四") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期五") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期六") {
                                    if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                        $abnormal_type = 1; //異常需填寫異常單
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ZERO_SECOND) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == ONE_SECOND) {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                }
                            }
                        } elseif ($special_attendance == false) {
                            if ($this->get_chinese_weekday($day) == "星期日") {
                                if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                    $abnormal_type = 1;
                                    $abnormal .= '休假日 異常上班八小時';
                                } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                    $abnormal_type = 1;
                                    $abnormal .= '休假日 異常上班，上班時數超過十小時';
                                } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                    $abnormal_type = 1;
                                    $abnormal .= '休假日 異常上班，上班時數小於上班八小時';
                                } elseif ($diff_time == ZERO_SECOND) {
                                    $abnormal_type = 1;
                                    $abnormal .= '休假日 異常，僅一筆刷卡紀錄';
                                } elseif ($diff_time == ONE_SECOND) {
                                    $abnormal_type = 0;
                                    $abnormal .= '休假日 正常';
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期一") {
                                if ($diff_time >= NINE_HOUR && $diff_time <= TEN_HOUR) {
                                    $abnormal_type = 0;
                                    $abnormal .= '出勤日 上班八小時';
                                } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，上班時數超過十小時';
                                } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                    $abnormal_type = 1; //異常需填寫異常單
                                    $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                                } elseif ($diff_time == ZERO_SECOND) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，僅一筆刷卡紀錄';
                                } elseif ($diff_time == ONE_SECOND) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，缺席';
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期二") {
                                if ($diff_time >= NINE_HOUR && $diff_time <= TEN_HOUR) {
                                    $abnormal_type = 0;
                                    $abnormal .= '出勤日 上班八小時';
                                } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，上班時數超過十小時';
                                } elseif ($diff_time < NINE_HOUR && $diff_time >= TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                    $abnormal_type = 1; //異常需填寫異常單
                                    $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                                } elseif ($diff_time == ZERO_SECOND) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，僅一筆刷卡紀錄';
                                } elseif ($diff_time == ONE_SECOND) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，缺席';
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期三") {
                                if ($diff_time >= NINE_HOUR && $diff_time <= TEN_HOUR) {
                                    $abnormal_type = 0;
                                    $abnormal .= '出勤日 上班八小時';
                                } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，上班時數超過十小時';
                                } elseif ($diff_time < NINE_HOUR && $diff_time >= TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                    $abnormal_type = 1; //異常需填寫異常單
                                    $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                                } elseif ($diff_time == ZERO_SECOND) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，僅一筆刷卡紀錄';
                                } elseif ($diff_time == ONE_SECOND) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，缺席';
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期四") {
                                if ($diff_time >= NINE_HOUR && $diff_time <= TEN_HOUR) {
                                    $abnormal_type = 0;
                                    $abnormal .= '出勤日 上班八小時';
                                } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，上班時數超過十小時';
                                } elseif ($diff_time < NINE_HOUR && $diff_time >= TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                    $abnormal_type = 1; //異常需填寫異常單
                                    $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                                } elseif ($diff_time == ZERO_SECOND) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，僅一筆刷卡紀錄';
                                } elseif ($diff_time == ONE_SECOND) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，缺席';
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期五") {
                                if ($diff_time >= NINE_HOUR && $diff_time <= TEN_HOUR) {
                                    $abnormal_type = 0;
                                    $abnormal .= '出勤日 上班八小時';
                                } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，上班時數超過十小時';
                                } elseif ($diff_time < NINE_HOUR && $diff_time >= TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                    $abnormal_type = 1; //異常需填寫異常單
                                    $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                                } elseif ($diff_time == ZERO_SECOND) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，僅一筆刷卡紀錄';
                                } elseif ($diff_time == ONE_SECOND) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，缺席';
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期六") {
                                if ($diff_time > NINE_HOUR && $diff_time <= TEN_HOUR) {
                                    $abnormal_type = 1;
                                    $abnormal .= '休假日 異常上班八小時';
                                } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                    $abnormal_type = 1;
                                    $abnormal .= '休假日 異常上班，上班時數超過十小時';
                                } elseif ($diff_time < NINE_HOUR && $diff_time > TWO_SECOND && $includingLeaveTime < 8 && $check_NINE_HOUR) {
                                    $abnormal_type = 1;
                                    $abnormal .= '休假日 異常上班，上班時數小於上班八小時';
                                } elseif ($diff_time == ZERO_SECOND) {
                                    $abnormal_type = 1;
                                    $abnormal .= '休假日 異常，僅一筆刷卡紀錄';
                                } elseif ($diff_time == ONE_SECOND) {
                                    $abnormal_type = 0;
                                    $abnormal .= '休假日 正常，缺席';
                                }
                            };
                        }

                        if ($diff_time != 1) { //0 2~以上
                            if ($diff_time != 0 && $leaveHours == 0) {
                                //假如第一筆時間大於9:30 //加註 遲到
                                if (strtotime($first_time) >= $this->getArriveLateTime($day) and $diff_time < NINE_HOUR && $check_leave_late) {
                                    $abnormal_type = 1;
                                    $abnormal .= '|遲到|';
                                }


                                //假如第一筆時間小於18:30 //加註 早退
                                if (strtotime($first_time) < $this->getLeaveEarlyTime($day) and $diff_time < NINE_HOUR && $check_leave_early) {
                                    $abnormal_type = 1;
                                    $abnormal .= '|早退|';
                                }
                            }
                        }

                        if ($diff_time == ONE_SECOND) {
                            $diff_time = 0;
                        }

                        $abnormal .= ' 總時數：' . $this->get_second_to_his($diff_time);


                        /*
                        var_dump('----start----');
                        var_dump($day);
                        var_dump($abnormal);
                        var_dump($abnormal_type);
                        var_dump($special_attendance);
                        var_dump($special_attendance_type);//出勤日
                        var_dump($special_attendance_description);
                        var_dump($first_time);
                        var_dump($last_time);
                        var_dump($diff_time);
                        var_dump('----end-----');
                        */

                        $attendance_record_service = new AttendancerecordService();
                        $model = $attendance_record_service->create($employee_id, $day, $first_time, $last_time, $abnormal_type,     $abnormal);

                        if ($send_mail == true && $value->role != 40 && $value->delete_status != 1) {
                            $mail = new MailService();
                            $mail_type = $mail->sendMail($abnormal_type, $employee_email, $abnormal, $model->id, $employee_name);
                        }

                        if (isset($mail_type)) {
                            Yii::log(date("Y-m-d H:i:s") . 'Attendance Record RECORD ID' . $model->id, CLogger::LEVEL_INFO);
                        } else {
                            Yii::log(date("Y-m-d H:i:s") . 'Attendance Error Record RECORD ID' . $model->id, CLogger::LEVEL_INFO);
                        }
                    }
                } else {

                    $msg = date("Y-m-d H:i:s") . $value->id . "該員工卡號設定異常";
                    Yii::log($msg, CLogger::LEVEL_INFO);
                    // $mail = new MailService();
                    // $mail->sendAdminMail(0, $msg);
                    continue;
                }
            }
        } catch (Exception $e) {
            $msg = Yii::log("AttxendanceData write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
            // $mail = new MailService();
            // $mail->sendAdminMail(0, $msg);
        }

    }


    public function getPartTimeData($day,$send_mail)
    {
        try {

            $employee_service = new EmployeeService();

            // 抓出所有PT員工
            $data = $employee_service->getPTEmployee(7);


            foreach ($data as $key => $value) {
                if (!empty($value->door_card_num) || $value->door_card_num == "0000000000") { // 如果有員工有設定卡號的使用者就去抓

                    $start_date = $day . ' 00:00:00';
                    $end_date = $day . ' 23:59:59';

                    $model = new RecordService;
                    $record = $model->get_by_card($value->door_card_num, $start_date, $end_date);//找出所有的刷卡紀錄
                    $first_time = 0;
                    $last_time = 0;
                    $abnormal_type = 0;//0正常 1異常
                    $abnormal = '';
                    count($record);
                    $employee_id = $value->id;
                    $employee_email = $value->email;
                    $employee_name = $value->name;


                    if (!empty($record)) {
                        //---------------整理預計要寫入的參數資料

                        $i = 1;
                        $total = count($record);
                        foreach ($record as $k => $v) {
                            if ($i == 1 && $total != 1) {//第一筆
                                $first_time = $v->flashDate;
                            }
                            if ($i == $total) {//最後一筆
                                $last_time = $v->flashDate;
                            }
                            if ($i == 1 && $i == $total) {//最後一筆
                                $first_time = $v->flashDate;
                                $last_time = $v->flashDate;
                            }
                            $i++;
                        }

                        //避免奇怪的錯誤發生 在轉換一次
                        if (strtotime($first_time) > strtotime($last_time)) {
                            $first_tmp = $first_time;
                            $last_tmp = $last_time;
                            $first_time = $last_tmp;
                            $last_time = $first_tmp;

                        }
                    } else {
                        $first_time = '0000-00-00 00:00:00';
                        $last_time = '0000-00-00 00:00:01';
                    }


                    $ParttimeService = new ParttimeService();
                    $result = $ParttimeService->findPartTimeDayAllAndDevice($employee_id, $day);//假如今天有排班的記錄
                    if (!empty($result)) {
                        //假如今天時間有排班紀錄
                        foreach ($result as $k => $v) {
                            $start_record = strtotime($v->start_time);//上班時間
                            $end_record = strtotime($v->end_time);//下班時間
                            $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間

                            $abnormal .= '排班編號：' . $v->id . ' |';


                            //兼職員工依排班時間，若不足，算早退，若超過8小時，不用算異常
                            //第一筆打卡時間小於排班開始時間 最後一筆大於等於 排班結束
                            if (strtotime($first_time) <= $start_record && strtotime($last_time) >= $end_record) {
                                $abnormal_type = 0;
                                $abnormal .= '排班日 班表內，';
                            } else {
                                $abnormal .= '排班日 班表外，';
                            }


                            if ($diff_time >= NINE_HOUR && $diff_time <= TEN_HOUR) {
                                $abnormal_type = 0;
                                $abnormal .= '上班八小時';
                            } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                $abnormal_type = 1;
                                $abnormal .= '上班時數超過十小時';
                            } elseif ($diff_time == ONE_SECOND) {
                                $abnormal_type = 1;
                                $abnormal .= '缺席';
                            } elseif ($diff_time == ZERO_SECOND) {
                                $abnormal_type = 1;
                                $abnormal .= '僅一筆刷卡紀錄';

                            }


                            if ($diff_time != 1) {//0 2~以上
                                if ($diff_time != 0) {
                                    //假如第一筆時間大於9:30 //加註 遲到
                                    if (strtotime($first_time) >= $start_record and $diff_time >= NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 0;
                                        $abnormal = '上班八小時';

                                    }

                                    //第一筆打卡時間 大於 班表排班時間 
                                    if (strtotime($first_time) >= $start_record and $diff_time < NINE_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '|遲到|';
                                    }


                                    //假如最後一筆時間排班時間 //加註早退
                                    //兼職員工依排班時間，若不足，算早退，若超過8小時，不用算異常
                                    if (strtotime($last_time) < $end_record and $diff_time >= NINE_HOUR && $diff_time <= TEN_HOUR) {
                                        $abnormal_type = 0;
                                        $abnormal = '上班八小時';
                                    }

                                    if (strtotime($last_time) < $end_record and $diff_time < NINE_HOUR) {
                                        $abnormal_type = 1;
                                        $abnormal .= '|早退|';
                                    }

                                }

                            }

                            /*

                            if($diff_time != ONE_SECOND) {//0 2~以上
                                if ($diff_time != ZERO_SECOND) {
                                    if (strtotime($first_time) >= $this->getArriveLateTime($day)) {
                                        $abnormal .= '|遲到|';
                                        //$abnormal_type = 1;

                                    }
                                    if(strtotime($last_time) < $end_record){
                                        $abnormal .= '|早退|';
                                        //$abnormal_type = 1;
                                    }
                                }
                            }*/

                            if ($diff_time == ONE_SECOND) {
                                $diff_time = 0;
                            }
                            $abnormal .= ' 總時數：' . $this->get_second_to_his($diff_time);
                            $attendance_record_service = new AttendancerecordService();
                            $model = $attendance_record_service->create($employee_id, $day, $first_time, $last_time, $abnormal_type, $abnormal);
							
							if($send_mail==true && $value->delete_status != 1 && $value->role != 40){
								$mail = new MailService();
								$mail_type = $mail->sendMail($abnormal_type,$employee_email,$abnormal,$model->id,$employee_name);
							}
							
							
							
                            
                            if(isset($mail_type)){
                                Yii::log(date("Y-m-d H:i:s").'Attendance PT Record RECORD ID'.$model->id, CLogger::LEVEL_INFO);
                             }else{
                                 Yii::log(date("Y-m-d H:i:s").'Attendance PT RECORD ID'.$model->id, CLogger::LEVEL_INFO);
                             }


                        }
                    } else {

                        $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間


                        if ($diff_time >= NINE_HOUR && $diff_time <= TEN_HOUR) {
                            $abnormal_type = 1;
                            $abnormal .= '非排班日 異常，上班時數超過八小時';
                        } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                            $abnormal_type = 1;
                            $abnormal .= '非排班日 異常，上班時數超過十小時';
                        } elseif ($diff_time < NINE_HOUR && $diff_time > 2) {
                            $abnormal_type = 1;
                            $abnormal .= '非排班日 異常，上班時數小於上班八小時';
                        } elseif ($diff_time == ONE_SECOND) {
                            $abnormal_type = 0;
                            $abnormal .= '非排班日 正常';
                        } elseif ($diff_time == ZERO_SECOND) {
                            $abnormal_type = 1;
                            $abnormal .= '非排班日 異常，僅一筆刷卡紀錄';
                        } else {
                            $abnormal_type = 0;
                            $abnormal .= '非排班日 正常';
                        }


                        if ($diff_time == ONE_SECOND) {
                            $diff_time = 0;
                        }
                        $abnormal .= ' 總時數：' . $this->get_second_to_his($diff_time);

                        $attendance_record_service = new AttendancerecordService();
                        $model = $attendance_record_service->create($employee_id, $day, $first_time, $last_time, $abnormal_type, $abnormal);
                        if($send_mail == true && $value->delete_status != 1 && $value->role != 40){
                            $mail = new MailService();
                            $mail_type = $mail->sendMail($abnormal_type, $employee_email, $abnormal, $model->id, $employee_name);
                        }

                        if (isset($mail_type)) {
                            Yii::log(date("Y-m-d H:i:s") . 'Attendance Record RECORD ID' . $model->id, CLogger::LEVEL_INFO);
                        } else {
                            Yii::log(date("Y-m-d H:i:s") . 'Attendance Error Record RECORD ID' . $model->id, CLogger::LEVEL_INFO);
                        }

                    }


                } else {

                    $msg = date("Y-m-d H:i:s") . $value->id . "該PT員工卡號設定異常";
                    Yii::log($msg, CLogger::LEVEL_INFO);
                    $mail = new MailService();
                    $mail->sendAdminMail(0, $msg);
                    continue;

                }

            }
        } catch (Exception $e) {
            $msg = Yii::log("Part time attxendance data write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
            $mail = new MailService();
            $mail->sendAdminMail(0, $msg);
        }

    }

    public function getScheduleData($day)
    {
        try {

            $employee_service = new EmployeeService();

            // 抓出所有紀州庵員工與管理員
            $data = $employee_service->findEmployeeInRolesListObject([38,40,43,44,45]);

            foreach ($data as $key => $value) {
                if (!empty($value->door_card_num) || $value->door_card_num == "0000000000") { // 如果有員工有設定卡號的使用者就去抓

                    $start_date = $day . ' 00:00:00';
                    $end_date = $day . ' 23:59:59';

                    $model = new RecordService;
                    $record = $model->get_by_card($value->door_card_num, $start_date, $end_date);//找出所有的刷卡紀錄
                    $first_time = 0;
                    $last_time = 0;
                    $abnormal_type = 0;//0正常 1異常
                    $abnormal = '';
                    $abnormal_title = '';
                    count($record);
                    $employee_id = $value->id;
                    $employee_email = $value->email;
                    $employee_name = $value->name;


                    if (!empty($record)) {
                        //---------------整理預計要寫入的參數資料

                        $i = 1;
                        $total = count($record);
                        foreach ($record as $k => $v) {
                            if ($i == 1 && $total != 1) {//第一筆
                                $first_time = $v->flashDate;
                            }
                            if ($i == $total) {//最後一筆
                                $last_time = $v->flashDate;
                            }
                            if ($i == 1 && $i == $total) {//最後一筆
                                $first_time = $v->flashDate;
                                $last_time = $v->flashDate;
                            }
                            $i++;
                        }

                        //避免奇怪的錯誤發生 在轉換一次
                        if (strtotime($first_time) > strtotime($last_time)) {
                            $first_tmp = $first_time;
                            $last_tmp = $last_time;
                            $first_time = $last_tmp;
                            $last_time = $first_tmp;

                        }
                    } else {
                        $first_time = '0000-00-00 00:00:00';
                        $last_time = '0000-00-00 00:00:01';
                    }

                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間

                    $abnormal_title= '紀州庵正職人員 | '; 


                    //兼職員工依排班時間，若不足，算早退，若超過8小時，不用算異常
                    //第一筆打卡時間小於排班開始時間 最後一筆大於等於 排班結束

                    if ($this->get_chinese_weekday($day) != "星期一"){
                        if ($diff_time >= NINE_HOUR) {
                            $abnormal_type = 0;
                            $abnormal = '出勤日，上班八小時';
                        } elseif ($diff_time < NINE_HOUR && $diff_time >= TWO_SECOND) {
                            $abnormal_type = 1;//異常需填寫異常單
                            $abnormal = '出勤日，上班時數小於八小時';
                        } elseif ($diff_time == ZERO_SECOND) {
                            $abnormal_type = 1;
                            $abnormal = '出勤日，僅有一筆打卡紀錄';
                        }elseif ($diff_time == ONE_SECOND) {
                            $abnormal_type = 1;
                            $abnormal = '出勤日，缺席';
                        }
                        if ($diff_time != 1) {//0 2~以上
                            if ($diff_time != 0) {
                                //假如第一筆時間大於9:30 //加註 遲到
                                if ($diff_time >= NINE_HOUR) {
                                    $abnormal_type = 0;
                                    $abnormal = '出勤日，上班八小時';

                                }

                                //假如第一筆時間小於18:30 //加註 早退
                                if ($diff_time < NINE_HOUR) {
                                    $abnormal_type = 1;
                                    $abnormal = '出勤日，，上班時數小於八小時';
                                }
                            }

                        }
                        $abnormal = $abnormal_title . $abnormal;
                        if ($diff_time == ONE_SECOND) {
                            $diff_time = 0;
                        }
                        $abnormal .= ' 總時數：' . $this->get_second_to_his($diff_time);
                        $attendance_record_service = new AttendancerecordService();
                        $model = $attendance_record_service->create($employee_id, $day, $first_time, $last_time, $abnormal_type, $abnormal);
                    }else{
                        if (!empty($record)) {
                            $abnormal_type = 1;
                            $abnormal = '休假日，出勤';
                            $abnormal .= ' 總時數：' . $this->get_second_to_his($diff_time);
                            $attendance_record_service = new AttendancerecordService();
                            $model = $attendance_record_service->create($employee_id, $day, $first_time, $last_time, $abnormal_type, $abnormal);
                        }
                    }
                   


                    
                    /*

                    if($diff_time != ONE_SECOND) {//0 2~以上
                        if ($diff_time != ZERO_SECOND) {
                            if (strtotime($first_time) >= $this->getArriveLateTime($day)) {
                                $abnormal .= '|遲到|';
                                //$abnormal_type = 1;

                            }
                            if(strtotime($last_time) < $end_record){
                                $abnormal .= '|早退|';
                                //$abnormal_type = 1;
                            }
                        }
                    }*/

                    
                  /*  $mail = new MailService();
                    $mail_type = $mail->sendMail($abnormal_type,$employee_email,$abnormal,$model->id,$employee_name);
                    if($mail_type){
                        Yii::log(date("Y-m-d H:i:s").'Attendance PT Record RECORD ID'.$model->id, CLogger::LEVEL_INFO);
                    }else{
                        Yii::log(date("Y-m-d H:i:s").'Attendance PT RECORD ID'.$model->id, CLogger::LEVEL_INFO);
                    }*/




                } else {

                    $msg = date("Y-m-d H:i:s") . $value->id . "紀州庵員工卡號設定異常";
                    Yii::log($msg, CLogger::LEVEL_INFO);
                    $mail = new MailService();
                    $mail->sendAdminMail(0, $msg);
                    continue;

                }

            }
        } catch (Exception $e) {
            $msg = Yii::log("紀州庵 attxendance data write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
            $mail = new MailService();
            $mail->sendAdminMail(0, $msg);
        }

    }

    public function getScheduleData_PT($day)
    {
        try {

            $employee_service = new EmployeeService();

            // 抓出所有紀州庵員工與管理員
            $data = $employee_service->findEmployeeInRolesListObject([37,39]);

            foreach ($data as $key => $value) {
                if (!empty($value->door_card_num) || $value->door_card_num == "0000000000") { // 如果有員工有設定卡號的使用者就去抓

                    $start_date = $day . ' 00:00:00';
                    $end_date = $day . ' 23:59:59';

                    $model = new RecordService;
                    $record = $model->get_by_card($value->door_card_num, $start_date, $end_date);//找出所有的刷卡紀錄
                    $first_time = 0;
                    $last_time = 0;
                    $abnormal_type = 0;//0正常 1異常
                    $abnormal = '';
                    $abnormal_title = '';
                    count($record);
                    $employee_id = $value->id;
                    $employee_email = $value->email;
                    $employee_name = $value->name;


                    if (!empty($record)) {
                        //---------------整理預計要寫入的參數資料

                        $i = 1;
                        $total = count($record);
                        foreach ($record as $k => $v) {
                            if ($i == 1 && $total != 1) {//第一筆
                                $first_time = $v->flashDate;
                            }
                            if ($i == $total) {//最後一筆
                                $last_time = $v->flashDate;
                            }
                            if ($i == 1 && $i == $total) {//最後一筆
                                $first_time = $v->flashDate;
                                $last_time = $v->flashDate;
                            }
                            $i++;
                        }

                        //避免奇怪的錯誤發生 在轉換一次
                        if (strtotime($first_time) > strtotime($last_time)) {
                            $first_tmp = $first_time;
                            $last_tmp = $last_time;
                            $first_time = $last_tmp;
                            $last_time = $first_tmp;

                        }
                    } else {
                        $first_time = '0000-00-00 00:00:00';
                        $last_time = '0000-00-00 00:00:01';
                    }


                    $service = new ScheduleService();
                    $result = $service->findScheduleDayAllAndDevice($employee_id, $day);//假如今天有排班的記錄
                    if (!empty($result)) {
                        //假如今天時間有排班紀錄
                        foreach ($result as $k => $v) {
                            $start_record = strtotime($v->start_time);
                            $end_record = strtotime($v->end_time);
                            $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間

                            $abnormal_title .= '紀州庵兼職排班編號：' . $v->id . ' | ';


                            //兼職員工依排班時間，若不足，算早退，若超過8小時，不用算異常
                            //第一筆打卡時間小於排班開始時間 最後一筆大於等於 排班結束
                            if (strtotime($first_time) <= $start_record && strtotime($last_time) >= $end_record) {
                                $abnormal_type = 0;
                                $abnormal = '排班日，正常，';
                            } else {
                                $abnormal_type = 1;
                                $abnormal = '排班日，異常，遲到或早退，';
                            }


                            if ($diff_time == ONE_SECOND) {
                                $abnormal_type = 1;
                                $abnormal = '排班日，異常，缺席';
                            } elseif ($diff_time == ZERO_SECOND) {
                                $abnormal_type = 1;
                                $abnormal = '排班日，異常，僅一筆刷卡紀錄';

                            }


                            if ($diff_time != 1) {//0 2~以上
                                if ($diff_time != 0) {
                                    //假如第一筆時間大於9:30 //加註 遲到
                                    if (strtotime($first_time) >= $start_record) {
                                        $abnormal_type = 1;
                                        $abnormal = '排班日，異常，遲到';
                                    }

                                    if (strtotime($last_time) < $end_record) {
                                        $abnormal_type = 1;
                                        $abnormal = '排班日，異常，早退';
                                    }

                                }

                            }
                            $abnormal = $abnormal_title . $abnormal;
                            /*

                            if($diff_time != ONE_SECOND) {//0 2~以上
                                if ($diff_time != ZERO_SECOND) {
                                    if (strtotime($first_time) >= $this->getArriveLateTime($day)) {
                                        $abnormal .= '|遲到|';
                                        //$abnormal_type = 1;

                                    }
                                    if(strtotime($last_time) < $end_record){
                                        $abnormal .= '|早退|';
                                        //$abnormal_type = 1;
                                    }
                                }
                            }*/

                            if ($diff_time == ONE_SECOND) {
                                $diff_time = 0;
                            }
                            $abnormal .= ' 總時數：' . $this->get_second_to_his($diff_time);
                            $attendance_record_service = new AttendancerecordService();
                            $model = $attendance_record_service->create($employee_id, $day, $first_time, $last_time, $abnormal_type, $abnormal);
                          /*  $mail = new MailService();
                            $mail_type = $mail->sendMail($abnormal_type,$employee_email,$abnormal,$model->id,$employee_name);
                            if($mail_type){
                                Yii::log(date("Y-m-d H:i:s").'Attendance PT Record RECORD ID'.$model->id, CLogger::LEVEL_INFO);
                            }else{
                                Yii::log(date("Y-m-d H:i:s").'Attendance PT RECORD ID'.$model->id, CLogger::LEVEL_INFO);
                            }*/


                        }
                    } else {

                        $abnormal_title = '紀州庵排班編號：' . '無' . ' | ';
                        if (!empty($record)) {
                            $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                             if ($diff_time >= NINE_HOUR && $diff_time <= TEN_HOUR) {
                                $abnormal_type = 1;
                                $abnormal = '非排班日，出勤';
                            } elseif ($diff_time > NINE_HOUR && $diff_time > OVER_TEN_HOUR) {
                                $abnormal_type = 1;
                                $abnormal = '非排班日，出勤';
                            } elseif ($diff_time < NINE_HOUR && $diff_time > 2) {
                                $abnormal_type = 1;
                                $abnormal = '非排班日，出勤';
                            } elseif ($diff_time == ONE_SECOND) {
                                $abnormal_type = 1;
                                $abnormal = '非排班日，出勤';
                            } elseif ($diff_time == ZERO_SECOND) {
                                $abnormal_type = 1;
                                $abnormal = '非排班日，出勤';
                            } else {
                                $abnormal_type = 1;
                                $abnormal = '非排班日，出勤';
                            }
                            $abnormal = $abnormal_title . $abnormal;

                            if ($diff_time == ONE_SECOND) {
                                $diff_time = 0;
                            }

                            $abnormal .= ' 總時數：' . $this->get_second_to_his($diff_time);

                            $attendance_record_service = new AttendancerecordService();
                            $attendance_record_service->create($employee_id, $day, $first_time, $last_time, $abnormal_type, $abnormal);
                        }
                        //紀州庵不寄信
                        /* $mail = new MailService();
                        $mail_type = $mail->sendMail($abnormal_type, $employee_email, $abnormal, $model->id, $employee_name);
                        if ($mail_type) {
                            Yii::log(date("Y-m-d H:i:s") . 'Attendance Record RECORD ID' . $model->id, CLogger::LEVEL_INFO);
                        } else {
                            Yii::log(date("Y-m-d H:i:s") . 'Attendance Error Record RECORD ID' . $model->id, CLogger::LEVEL_INFO);
                        }*/

                    }


                } else {

                    $msg = date("Y-m-d H:i:s") . $value->id . "紀州庵員工卡號設定異常";
                    Yii::log($msg, CLogger::LEVEL_INFO);
                    $mail = new MailService();
                    $mail->sendAdminMail(0, $msg);
                    continue;

                }

            }
        } catch (Exception $e) {
            $msg = Yii::log("紀州庵 attxendance data write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
            $mail = new MailService();
            $mail->sendAdminMail(0, $msg);
        }

    }



    function checkAttxendanceDay($day){
        $attendance_day = $this->findAttendance();
        $getWeekday = date("w");
        foreach ($attendance_day as $k => $v) {
            if ($day == $v->day) {
                if($v->type == 1)
                    return true;
                else
                    return false;
            }
        }
        if ($getWeekday > 0 && $getWeekday <= 5){
            return true;
        }
        return false;
    }
    function getAttxendanceAbnormal($day){
        try {
            $employee_service = new EmployeeService();
            $start_date = $day . ' 00:00:00';
            $end_date = $day . ' 09:31:00';
            $pt_start_date = $day . ' 00:00:00';
            $pt_end_date = $day . ' 09:31:00';
            //找出所有的刷卡紀錄
            $data = $this->getAttxendanceAndCheckPT($start_date,$end_date,$pt_start_date,$pt_end_date);
            foreach ($data as $key => $value) {
                $checkattendancerecord = $this->checkAttendanceRecordStartTime($day . ' 09:30:00',$value['employee_id']);
                $leaveService = new LeaveService();
                $leave_data = $leaveService->findEmployeeLeaveByDay($value['employee_id'], $day);
                $leave = false;
                if(!empty($leave_data)){
                    foreach ($leave_data as $leave_key => $leave_value) {
                        // 早上有請假
                        if(date('H',strtotime($leave_value['start_time'])) == '9'){
                            $leave = true;
                        }
                    }
                }
                if (empty($value['flashDate']) && empty($checkattendancerecord) && $leave == false) {
                    $abnormal_type = 2;
                    $employee_email = $value['email'];
                    $employee_name = $value['name'];
                    $mail = new MailService();
                    $mail_type = $mail->sendMail($abnormal_type,$employee_email,null,null,$employee_name);
                    if($mail_type){
                        Yii::log(date("Y-m-d H:i:s").'Attendance Abnormal member_id'.$value['employee_id'], CLogger::LEVEL_INFO);
                    }else{
                        Yii::log(date("Y-m-d H:i:s").'Attendance Abnormal Error member_id'.$value['employee_id'],  CLogger::LEVEL_INFO);
                    }
                }
            }
        } catch (Exception $e) {
            $msg = Yii::log("Attxendance abnormal exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
            $mail = new MailService();
            $mail->sendAdminMail(0,$msg);
        }
    }
    function checkAttendanceRecordStartTime($start_time,$employee_id){
        $sql = "SELECT * FROM attendance_record WHERE '" . $start_time . "' BETWEEN start_time AND end_time AND employee_id='" . $employee_id . "' AND take='0'";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        return $data;
    }
    function getAttxendanceAndCheckPT($start_date,$end_date,$pt_start_date,$pt_end_date){
        //37,38,39,40,43,44,45
        //檢查 pt 當天有沒有排班，有班的才列
        $checkPTtime = $apartments2 = Yii::app()->db->createCommand()
        ->select("e.name,e.id as employee_id,e.email,e.user_name,e.door_card_num,r.flashDate,r.memol,r.id")
        ->from('employee e')
        ->rightjoin('part_time pt',"e.id=pt.part_time_empolyee_id and pt.start_time BETWEEN '".$pt_start_date."' and '".$pt_end_date."'")
        ->leftjoin('record r', "SUBSTRING(e.door_card_num,1,5) = r.start_five and SUBSTRING(e.door_card_num,6)=r.end_five and r.flashDate BETWEEN '".$start_date."' and '".$end_date."'")
        ->where('e.role=7 and e.role <> 37 and e.role <> 38 and e.role <> 39 and e.role <> 40 and e.role <> 43 and e.role <> 44 and e.role <> 45 and e.role <> 42 and e.delete_status <> 1 and e.user_name NOT LIKE "KS%"')
        ->getText();
        $data = Yii::app()->db->createCommand()
        ->select('e.name,e.id as employee_id,e.email,e.user_name,e.door_card_num,r.flashDate,r.memol,r.id')
        ->from('employee e')
        ->leftjoin('record r', "SUBSTRING(e.door_card_num,1,5) = r.start_five and SUBSTRING(e.door_card_num,6)=r.end_five and r.flashDate BETWEEN '".$start_date."' and '".$end_date."'")
        ->where('e.role <> 7 and e.role <> 37 and e.role <> 38 and e.role <> 39 and e.role <> 40 and e.role <> 43 and e.role <> 44 and e.role <> 45 and e.role <> 42 and e.delete_status <> 1 and e.user_name NOT LIKE "KS%"')
        ->union($checkPTtime)
        ->queryAll();
        return $data;
    }
    function getAttxendanceReport($day){
        try {
            $start_date = $day . ' 00:00:00';
            $end_date = $day . ' 09:30:59';
            $pt_start_date = $day . ' 00:00:00';
            $pt_end_date = $day . ' 23:59:59';
            $data = $this->getAttxendanceAndCheckPT($start_date,$end_date,$pt_start_date,$pt_end_date);
            $table = '<h2>正常出勤記錄明細</h2><table border="1" style="color:black;border-color:black;"><tr><th>員工帳號</th><th>員工姓名</th><th>卡號</th><th>刷卡時間</th><th>刷卡狀態</th><th>原廠紀錄編號</th></tr>';
            $table_abnormal = '<h2>異常出勤記錄明細</h2><table border="1" style="color:black;border-color:black;"><tr><th>員工帳號</th><th>員工姓名</th><th>卡號</th><th>刷卡時間</th><th>刷卡狀態</th><th>原廠紀錄編號</th></tr>';
            foreach ($data as $key => $value) {
                if(!empty($value['flashDate'])){
                    $table .= "<tr><td>" . $value['user_name'] . "</td><td>" . $value['name'] . "</td><td>" . $value['door_card_num'] . "</td><td>" . $value['flashDate'] . "</td><td>" . $value['memol'] . "</td><td>" . $value['id'] . "</td></tr>"; 
                }else{
                    $table_abnormal .= "<tr><td>" . $value['user_name'] . "</td><td>" . $value['name'] . "</td><td>" . $value['door_card_num'] . "</td><td>" . $value['flashDate'] . "</td><td>" . $value['memol'] . "</td><td>" . $value['id'] . "</td></tr>"; 
                }
            }
            $table .= "</table>";
            $table_abnormal .= "</table>";
            $employee_service = new EmployeeService();
            // 抓出人事主管／會計
            $account = $employee_service->getEmployeeByRole(26);
            $abnormal_type = 3;
            foreach ($account as $key => $value) {
                $employee_email = $value->email;
                $employee_name = $value->name;
                $mail = new MailService();
                $mail_type = $mail->sendMail($abnormal_type,$employee_email,$table.$table_abnormal,null,$employee_name);
                if($mail_type){
                    Yii::log(date("Y-m-d H:i:s").'Attendance Report employee_id'.$value->id, CLogger::LEVEL_INFO);
                }else{
                    Yii::log(date("Y-m-d H:i:s").'Attendance Report Error employee_id'.$value->id,  CLogger::LEVEL_INFO);
                }
            }
        } catch (Exception $e) {
            $msg = Yii::log("Attxendance Report exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
            $mail = new MailService();
            $mail->sendAdminMail(0,$msg);
        }
    }

    function get_chinese_weekday($datetime)
    {
        $weekday = date('w', strtotime($datetime));
        $weeklist = array('日', '一', '二', '三', '四', '五', '六');
        return '星期' . $weeklist[$weekday];
    }

    public function del($id)
    {

        $post = Attendance::model()->findByPk($id);
        $post->delete();

    }

    function get_second_to_his($s)
    {
        return str_pad(floor(($s % 86400) / 3600), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor((($s % 86400) % 3600) / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor((($s % 86400) % 3600) % 60), 2, '0', STR_PAD_LEFT);
    }

}
