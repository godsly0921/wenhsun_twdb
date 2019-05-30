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

    public function getAttxendanceData($day)
    {
        try {

            $employee_service = new EmployeeService();

            // 抓出所有員工d
            $data = $employee_service->findEmployeelist();


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
                        }else{
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

                        if ($special_attendance == true) {//特殊情況
                            if ($special_attendance_type == 1) {//特殊出勤日
                                if ($this->get_chinese_weekday($day) == "星期日") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 0;//正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;//正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;//異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期一") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 0;//正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;//正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;//異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期二") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 0;//正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;//正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;//異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期三") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 0;//正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;//正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;//異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期四") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 0;//正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;//正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;//異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期五") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 0;//正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;//正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;//異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期六") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 0;//正常
                                        $abnormal .= '特殊出勤日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;//正常
                                        $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;//異常需填寫異常單
                                        $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊出勤日 異常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                }
                            } elseif ($special_attendance_type == 0) {//休假日
                                if ($this->get_chinese_weekday($day) == "星期日") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期一") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;//異常需填寫異常單
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期二") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;//異常需填寫異常單
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期三") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期四") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期五") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                } else if ($this->get_chinese_weekday($day) == "星期六") {
                                    $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                    if ($diff_time > 32400 && $diff_time <= 39600) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time < 32400 && $diff_time > 2) {
                                        $abnormal_type = 1;//異常需填寫異常單
                                        $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } elseif ($diff_time == 0) {
                                        $abnormal_type = 1;
                                        $abnormal .= '特殊休假日 異常，僅一筆刷卡紀錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    } else {
                                        $abnormal_type = 0;
                                        $abnormal .= '特殊休假日 正常，沒有任何記錄';
                                        $abnormal .= ' ' . $special_attendance_description;
                                    }
                                }
                            }
                        } elseif ($special_attendance == false) {
                            if ($this->get_chinese_weekday($day) == "星期日") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400 && $diff_time <= 39600) {
                                    $abnormal_type = 1;
                                    $abnormal .= '非出勤日 異常上班八小時';
                                } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                    $abnormal_type = 1;
                                    $abnormal .= '非出勤日 異常上班，上班時數超過十小時';
                                } elseif ($diff_time < 32400 && $diff_time > 2) {
                                    $abnormal_type = 1;
                                    $abnormal .= '非出勤日 異常上班，上班時數小於上班八小時';
                                } elseif ($diff_time == 0) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，僅一筆刷卡紀錄';
                                } else {
                                    $abnormal_type = 0;
                                    $abnormal .= '休假日 正常，沒有任何記錄';
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期一") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time >= 32400 && $diff_time <= 39600) {
                                    $abnormal_type = 0;
                                    $abnormal .= '出勤日 上班八小時';
                                } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，上班時數超過十小時';
                                } elseif ($diff_time < 32400 && $diff_time >= 1) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                                } elseif ($diff_time == 0) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，僅一筆刷卡紀錄';
                                } else {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，沒有任何記錄';
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期二") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time >= 32400 && $diff_time <= 39600) {
                                    $abnormal_type = 0;
                                    $abnormal .= '出勤日 上班八小時';
                                } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，上班時數超過十小時';
                                } elseif ($diff_time < 32400 && $diff_time >= 1) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                                } elseif ($diff_time == 0) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，僅一筆刷卡紀錄';
                                } else {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，沒有任何記錄';
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期三") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time >= 32400 && $diff_time <= 39600) {
                                    $abnormal_type = 0;
                                    $abnormal .= '出勤日 上班八小時';
                                } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，上班時數超過十小時';
                                } elseif ($diff_time < 32400 && $diff_time >= 1) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                                } elseif ($diff_time == 0) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，僅一筆刷卡紀錄';
                                } else {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，沒有任何記錄';
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期四") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time >= 32400 && $diff_time <= 39600) {
                                    $abnormal_type = 0;
                                    $abnormal .= '出勤日 上班八小時';
                                } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，上班時數超過十小時';
                                } elseif ($diff_time < 32400 && $diff_time >= 1) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                                } elseif ($diff_time == 0) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，僅一筆刷卡紀錄';
                                } else {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，沒有任何記錄';
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期五") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time >= 32400 && $diff_time <= 39600) {
                                    $abnormal_type = 0;
                                    $abnormal .= '出勤日 上班八小時';
                                } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，上班時數超過十小時';
                                } elseif ($diff_time < 32400 && $diff_time >= 1) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                                } elseif ($diff_time == 0) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，僅一筆刷卡紀錄';
                                } else {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，沒有任何記錄';
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期六") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400 && $diff_time <= 39600) {
                                    $abnormal_type = 1;
                                    $abnormal .= '非出勤日 異常上班八小時';
                                } elseif ($diff_time > 32400 && $diff_time > 39601) {
                                    $abnormal_type = 1;
                                    $abnormal .= '非出勤日 異常上班，上班時數超過十小時';
                                } elseif ($diff_time < 32400 && $diff_time > 2) {
                                    $abnormal_type = 1;
                                    $abnormal .= '非出勤日 異常上班，上班時數小於上班八小時';
                                } elseif ($diff_time == 0) {
                                    $abnormal_type = 1;
                                    $abnormal .= '出勤日 異常，僅一筆刷卡紀錄';
                                } else {
                                    $abnormal_type = 0;
                                    $abnormal .= '休假日 正常，沒有任何記錄';
                                }
                            };
                        }

                        if($diff_time != 1){//0 2~以上
                            //假如第一筆時間大於9:30 //加註 遲到
                            if(strtotime($first_time) >= strtotime(date($day . ' 09:31:00'))){
                                $abnormal .= ' 遲到';
                            }

                            //假如第一筆時間小於18:00 //加註 早退
                            if(strtotime($last_time) < strtotime(date($day . ' 18:00:00'))){
                                $abnormal .= ' 早退';
                            }

                        }

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
                        $model = $attendance_record_service->create($employee_id, $day, $first_time, $last_time, $abnormal_type, $abnormal);
                        $mail = new MailService();
                        $mail_type = $mail->sendMail($abnormal_type,$employee_email,$abnormal,$model->id,$employee_name);
                        if($mail_type){
                            Yii::log(date("Y-m-d H:i:s").'Attendance Record RECORD ID'.$model->id, CLogger::LEVEL_INFO);
                        }else{
                            Yii::log(date("Y-m-d H:i:s").'Attendance Error Record RECORD ID'.$model->id, CLogger::LEVEL_INFO);
                        }



                }else{

                    $msg = date("Y-m-d H:i:s").$value->id."該員工卡號設定異常";
                    Yii::log($msg, CLogger::LEVEL_INFO);
                    $mail = new MailService();
                    $mail->sendAdminMail(0,$msg);
                    continue;

                }

            }
        } catch (Exception $e) {
            $msg = Yii::log("AttxendanceData write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
            $mail = new MailService();
            $mail->sendAdminMail(0,$msg);
        }

    }


    public function getPartTimeData($day)
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
                    }else{
                        $first_time = '0000-00-00 00:00:00';
                        $last_time = '0000-00-00 00:00:01';
                    }


                    $ParttimeService = new ParttimeService();
                    $result = $ParttimeService->findPartTimeDayAllAndDevice($employee_id,$day);//假如今天有排班計錄

                    if($result != false){//假如今天時間沒有排班紀錄
                        foreach($result as $key =>$value){
                            $start_record= strtotime($value->start_time);
                            $end_record = strtotime($value->end_time);
                            $last_time = strtotime($last_time);
                            $first_time = strtotime($first_time);

                            $abnormal .= '排班編號：'.$value->id;

                            //第一筆打卡時間小於排班開始時間 最後一筆大於等於 排班結束
                            if($first_time <= $start_record && $last_time >= $end_record){
                                $abnormal_type = 0;
                                $abnormal .= '正常，排班日出勤正常';
                            }

                            if($first_time > $start_record ){
                                $abnormal_type = 1;
                                $abnormal .= '異常： 排班日遲到 ';
                            }

                            if($last_time < $end_record){
                                $abnormal_type = 1;
                                $abnormal .= '異常 排班日早退 ';
                            }


                            $attendance_record_service = new AttendancerecordService();
                            $model = $attendance_record_service->create($employee_id, $day, $first_time, $last_time, $abnormal_type, $abnormal);
                            $mail = new MailService();
                            $mail_type = $mail->sendMail($abnormal_type,$employee_email,$abnormal,$model->id,$employee_name);
                            if($mail_type){
                                Yii::log(date("Y-m-d H:i:s").'Attendance PT Record RECORD ID'.$model->id, CLogger::LEVEL_INFO);
                            }else{
                                Yii::log(date("Y-m-d H:i:s").'Attendance PT RECORD ID'.$model->id, CLogger::LEVEL_INFO);
                            }


                        }
                    }else{

                        $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間

                        if ($diff_time > 32400 && $diff_time > 39601) {
                            $abnormal_type = 1;
                            $abnormal .= '異常，非排班日 上班時數超過十小時';
                        } elseif ($diff_time < 32400 && $diff_time >= 1) {
                            $abnormal_type = 1;//異常需填寫異常單
                            $abnormal .= '異常，非排班日 上班時數小於上班八小時';
                        } elseif ($diff_time == 0) {
                            $abnormal_type = 1;
                            $abnormal .= '異常，非排班日有一筆刷卡紀錄';
                        } else {
                            $abnormal_type = 0;
                            $abnormal .= '正常，排班日沒有任何記錄';
                        }

                        $attendance_record_service = new AttendancerecordService();
                        $model = $attendance_record_service->create($employee_id, $day, $first_time, $last_time, $abnormal_type, $abnormal);
                        $mail = new MailService();
                        $mail_type = $mail->sendMail($abnormal_type,$employee_email,$abnormal,$model->id,$employee_name);
                        if($mail_type){
                            Yii::log(date("Y-m-d H:i:s").'Attendance Record RECORD ID'.$model->id, CLogger::LEVEL_INFO);
                        }else{
                            Yii::log(date("Y-m-d H:i:s").'Attendance Error Record RECORD ID'.$model->id, CLogger::LEVEL_INFO);
                        }

                    }


                }else{

                    $msg = date("Y-m-d H:i:s").$value->id."該兼職員工卡號設定異常";
                    Yii::log($msg, CLogger::LEVEL_INFO);
                    $mail = new MailService();
                    $mail->sendAdminMail(0,$msg);
                    continue;

                }

            }
        } catch (Exception $e) {
            $msg = Yii::log("Part time attxendance data write exception {$e->getTraceAsString()}", CLogger::LEVEL_INFO);
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
}