<?php

//寫入每天早上9:30計算 昨天出勤狀況
class AttendanceCommand extends CConsoleCommand
{

    public function run()
    {
        $model = new EmployeeService();

        $attendance_service = new AttendanceService();
        $attendance_record_service = new AttendancerecordService();


        // 抓出所有員工
        $data = $model->findEmployeelist();


        foreach ($data as $key => $value) {
            if (!empty($value->door_card_num)) { // 如果有員工有設定卡號的使用者就去抓
                $model = new RecordService;
                $day = date("Y-m-d", strtotime('-1 day'));
                $start_date = $day . '00:00:00';
                $end_date = $day . '23:59:59';
                $record = $model->get_by_card($value->door_card_nu, $start_date, $end_date);//找出所有的刷卡紀錄

                if (!empty($record)) {
                    //---------------整理預計要寫入的參數資料
                    $employee_id = $value->id
                    $first_time = 0;
                    $last_time = 0;
                    $abnormal_type = 0;//正常
                    $abnormal = '';

                    $i = 1;
                    $total = count($record);
                    foreach ($record as $k => $v) {
                        if($i==1 && $total != 1){//第一筆
                            $first_time = $v->flashDate;
                        }
                        if($i==$total){//最後一筆
                            $last_time = $v->flashDate;
                        }
                        if($i==1 && $i==$total){//最後一筆
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


                    $special_attendance = false; // false 表示為一般出勤日
                    $special_attendance_type = '';
                    $special_attendance_description = '';
                    $attendance_day = $attendance_service->findAttendance();
                    foreach ($attendance_day as $k => $v) {
                        if ($day == $v->day) {
                            $special_attendance == true;
                            $special_attendance_type == $v->type;
                            $special_attendance_description == $v->description;
                        }
                    }

                    if ($special_attendance == true) {//特殊情況
                        if ($special_attendance_type == 0) {//表示228要上班
                            if ($this->get_chinese_weekday($day) == "星期日") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 0;//正常
                                    $abnormal .= '特殊出勤日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;//正常
                                    $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，沒有上班紀錄';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期一") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 0;//正常
                                    $abnormal .= '特殊出勤日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;//正常
                                    $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，沒有上班紀錄';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期二") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 0;//正常
                                    $abnormal .= '特殊出勤日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;//正常
                                    $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，沒有上班紀錄';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期三" {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 0;//正常
                                    $abnormal .= '特殊出勤日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;//正常
                                    $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，沒有上班紀錄';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期四" {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 0;//正常
                                    $abnormal .= '特殊出勤日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;//正常
                                    $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，沒有上班紀錄';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期五") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 0;//正常
                                    $abnormal .= '特殊出勤日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;//正常
                                    $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，沒有上班紀錄';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期六") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 0;//正常
                                    $abnormal .= '特殊出勤日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;//正常
                                    $abnormal .= '特殊出勤日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊出勤日 異常，沒有上班紀錄';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else {
                                echo "你輸入有誤!!";
                            };
                        } elseif ($special_attendance_type == 1) {//表示5/1不用上班
                            if ($this->get_chinese_weekday($day) == "星期日") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 0;//異常需填寫異常單
                                    $abnormal .= '特殊休假日';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期一") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 0;//異常需填寫異常單
                                    $abnormal .= '特殊休假日';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期二") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 0;//異常需填寫異常單
                                    $abnormal .= '特殊休假日';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期三" {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 0;//異常需填寫異常單
                                    $abnormal .= '特殊休假日';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期四" {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 0;//異常需填寫異常單
                                    $abnormal .= '特殊休假日';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期五") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 0;//異常需填寫異常單
                                    $abnormal .= '特殊休假日';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else if ($this->get_chinese_weekday($day) == "星期六") {
                                $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                                if ($diff_time > 32400000 && $diff_time < 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                    $abnormal_type = 1;
                                    $abnormal .= '特殊休假日 加班，上班時數超過十小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '特殊休假日 異常，上班時數小於上班八小時';
                                    $abnormal .= ' ' . $special_attendance_description;
                                } else {
                                    $abnormal_type = 0;
                                    $abnormal .= '特殊休假日';
                                    $abnormal .= ' ' . $special_attendance_description;
                                }
                            } else {
                                echo "你輸入有誤!!";
                            };
                        }
                    } elseif ($special_attendance == false) {
                        if ($this->get_chinese_weekday($day) == "星期日") {
                            $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                            if ($diff_time > 32400000 && $diff_time < 39600000) {
                                $abnormal_type = 1;
                                $abnormal .= '非出勤日 異常上班八小時';
                            } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                $abnormal_type = 1;
                                $abnormal .= '非出勤日 異常上班，上班時數超過十小時';
                            } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                $abnormal_type = 1;
                                $abnormal .= '非出勤日 異常上班，上班時數小於上班八小時';
                            } elseif ($diff_time < 32400000 && $diff_time == 0) {
                                $abnormal_type = 0;
                                $abnormal .= '非出勤日';
                            }
                        } else if ($this->get_chinese_weekday($day) == "星期一") {
                            $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                            if ($diff_time > 32400000 && $diff_time < 39600000) {
                                $abnormal_type = 0;//正常
                                $abnormal .= '出勤日 上班八小時';
                            } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                $abnormal_type = 1;//正常
                                $abnormal .= '出勤日 異常，上班時數超過十小時';
                            } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                    $abnormal_type = 1;//異常需填寫異常單
                                    $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                            } else {
                                $abnormal_type = 1;
                                $abnormal .= '出勤日 沒有出勤紀錄';
                            }
                        } else if ($this->get_chinese_weekday($day) == "星期二") {
                            $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                            if ($diff_time > 32400000 && $diff_time < 39600000) {
                                $abnormal_type = 0;//正常
                                $abnormal .= '出勤日 上班八小時';
                            } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                $abnormal_type = 0;//正常
                                $abnormal .= '出勤日 異常，上班時數超過十小時';
                            } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                $abnormal_type = 1;//異常需填寫異常單
                                $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                            } else {
                                $abnormal_type = 1;
                                $abnormal .= '出勤日 沒有出勤紀錄';
                            }
                        } else if ($this->get_chinese_weekday($day) == "星期三" {
                            $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                            if ($diff_time > 32400000 && $diff_time < 39600000) {
                                $abnormal_type = 0;//正常
                                $abnormal .= '出勤日 上班八小時';
                            } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                $abnormal_type = 0;//正常
                                $abnormal .= '出勤日 異常，上班時數超過十小時';
                            } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                $abnormal_type = 1;//異常需填寫異常單
                                $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                            } else {
                                $abnormal_type = 1;
                                $abnormal .= '出勤日 沒有出勤紀錄';
                            }
                        } else if ($this->get_chinese_weekday($day) == "星期四" {
                            $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                            if ($diff_time > 32400000 && $diff_time < 39600000) {
                                $abnormal_type = 0;
                                $abnormal .= '出勤日 上班八小時';
                            } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                $abnormal_type = 1;
                                $abnormal .= '出勤日 異常，上班時數超過十小時';
                            } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                $abnormal_type = 1;//異常需填寫異常單
                                $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                            } else {
                                $abnormal_type = 1;
                                $abnormal .= '出勤日 沒有出勤紀錄';
                            }
                        } else if ($this->get_chinese_weekday($day) == "星期五") {
                            $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                            if ($diff_time > 32400000 && $diff_time < 39600000) {
                                $abnormal_type = 0;
                                $abnormal .= '出勤日 上班八小時';
                            } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                $abnormal_type = 1;
                                $abnormal .= '出勤日 異常，上班時數超過十小時';
                            } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                $abnormal_type = 1;//異常需填寫異常單
                                $abnormal .= '出勤日 異常，上班時數小於上班八小時';
                            } else {
                                $abnormal_type = 1;
                                $abnormal .= '出勤日 沒有出勤紀錄';
                            }
                        } else if ($this->get_chinese_weekday($day) == "星期六") {
                            $diff_time = strtotime($last_time) - strtotime($first_time);//這個員工一整天上班時間
                            if ($diff_time > 32400000 && $diff_time < 39600000) {
                                $abnormal_type = 1;
                                $abnormal .= '非出勤日 上班八小時';
                            } elseif ($diff_time > 32400000 && $diff_time > 39600000) {
                                $abnormal_type = 1;
                                $abnormal .= '非出勤日 加班，上班時數超過十小時';
                            } elseif ($diff_time < 32400000 && $diff_time != 0) {
                                $abnormal_type = 1;
                                $abnormal .= '非出勤日 異常上班，上班時數小於上班八小時';
                            } elseif ($diff_time < 32400000 && $diff_time == 0) {
                                $abnormal_type = 0;
                                $abnormal .= '非出勤日';
                            }
                        } else {
                            echo "你輸入有誤!!";
                        };
                    }


                    $attendance_record_service->create($employee_id, $day, $first_time, $last_time, $abnormal_type, $abnormal);
                }

            }

        }

    }

    function get_chinese_weekday($datetime)
    {
        $weekday = date('w', strtotime($datetime));
        $weeklist = array('日', '一', '二', '三', '四', '五', '六');
        return '星期' . $weeklist[$weekday];
    }


}

?>