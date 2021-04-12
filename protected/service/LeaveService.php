<?php

class LeaveService
{
    private $leaveMap = [
        1 => 7,
        2 => 10,
        3 => 14,
        4 => 14,
        5 => 15,
        6 => 15,
        7 => 15,
        8 => 15,
        9 => 15,
        10 => 16,
        11 => 17,
        12 => 18,
        13 => 19,
        14 => 20,
        15 => 21,
        16 => 22,
        17 => 23,
        18 => 24,
        19 => 25,
        20 => 26,
        21 => 27,
        22 => 28,
        23 => 29,
        24 => 30,
        25 => 30,
    ];
    public const SICK_LEAVE = '1'; //普通傷病假
    public const PERSONAL_LEAVE = '2'; //事假
    public const PUBLIC_AFFAIRS_LEAVE = '3'; //公假
    public const OCCUPATIONAL_SICKNESS_LEAVE = '4'; //公傷病假
    public const ANNUAL_LEAVE = '5'; //特別休假
    public const MATERNITY_LEAVE = '6'; //分娩假含例假日
    public const MARITAL_LEAVE = '7'; //婚假
    public const FUNERAL_LEAVE = '8'; //喪假
    public const COMPENSATORY_LEAVE = '9'; //補休假
    public const MENSTRUAL_LEAVE = '10'; //生理假
    public const OVERTIME = '11'; //加班
    public const PATERNITY_LEAVE = '16'; //陪產假
    public const MISCARRIAGE_LEAVE = '17'; //流產假
    public const PRENATAL_LEAVE = '18'; //產檢假
    public const STATUS_APPLY = '0'; // 未審核
    public const STATUS_APPROVE = '1'; // 已審核
    public const STATUS_REJECT = '2'; // 退回
    public const STATUS_DELETE = '3'; // 刪除

    public function calcAnnualLeaveSummaryOnBoardDate_FiscalYear(DateTime $now,  $employee){
        try {
            if(!empty($employee->onboard_date)){
                $now->setTime(0, 0, 0); //yyyy-mm-dd 當年度的第一天ex.2020-01-01
                $now_endTime = new DateTime($now->format('Y').'-12-31'); //yyyy-mm-dd 當年度的最後一天ex.2020-01-01
                $now_endTime->setTime(0, 0, 0);
                $onBoardDate = new DateTime($employee->onboard_date);
                $onBoardDate->setTime(0,0,0);

                $dDiff = $now->diff($onBoardDate);
                $diffYear = $dDiff->format('%y');
                $seniority_month = new DateTime($employee->onboard_date);
                if($onBoardDate->format('d') !=1){
                    $month_day = $onBoardDate->format('t');
                }else{
                    $month_day = $seniority_month->format('t');
                }
                if($diffYear < 1){
                    if ($dDiff->format('%m') < 6) {
                        $diffEndTime = $now_endTime->diff($onBoardDate);
                        if($diffEndTime->format('%m') >= 6 || $diffEndTime->format('%y')> 0){
                            $seniority_month = $onBoardDate->format('m')-1;
                            $special_leave = ceil(sprintf('%.2f',(
                                (3 + 
                                (
                                    $this->leaveMap[1]-
                                    ((($seniority_month+(($onBoardDate->format('d')-1)/$month_day))/12 * $this->leaveMap[1]))
                                ))))*10)/10 * 8 * 60;
                            $this->create_Specialleaveyear($employee, $now->format('Y')."-01-01", $now->format('Y')."-12-31", ($dDiff->format('%y')*12 + $dDiff->format('%m')),  $special_leave);
                        }
                    }
                    if ($dDiff->format('%m') >= 6) {
                        $seniority_month = $onBoardDate->format('m')-1;
                        $halfyear = new DateTime($employee->onboard_date);
                        $halfyear = $halfyear->add(new DateInterval('P6M'));
                        $onBoardDateYear = new DateTime($onBoardDate->format('Y-m-d'));
                        $diffHalfYear = $onBoardDateYear->diff($halfyear);
                        // 到職日當年滿 半年的特休
                        $special_leave = ceil(sprintf('%.2f',(3 - ((($seniority_month+(($onBoardDate->format('d')-1)/$month_day))/6 * 3))))*10)/10  * 8 * 60;
                        $this->create_Specialleaveyear($employee, $halfyear->format('Y-m-d'), $halfyear->format('Y')."-12-31", ($dDiff->format('%y')*12 + $dDiff->format('%m')),  $special_leave);
                        // 到職日滿 半年未滿 1 年的特休
                        $special_leave = ceil(sprintf('%.2f',(
                            (($seniority_month + ($onBoardDate->format('d')-1)/$month_day)/6 * 3) + 
                            ($this->leaveMap[1]-(($seniority_month+($onBoardDate->format('d')-1)/$month_day)/12 * $this->leaveMap[1]))
                            ))*10)/10 * 8 * 60;
                        $this->create_Specialleaveyear($employee, $now->format('Y')."-01-01", $now->format('Y')."-12-31", ($dDiff->format('%y')*12 + $dDiff->format('%m')),  $special_leave);
                    }
                }else{
                    // 到職日滿 1 年 不滿 25 年
                    if (isset($this->leaveMap[$diffYear])) {
                        $seniority_month = $onBoardDate->format('m')-1;
                        $special_leave = ceil(sprintf('%.2f',(
                            (($seniority_month + ($onBoardDate->format('d')-1)/$month_day)/12 * $this->leaveMap[$diffYear]) + 
                            ($this->leaveMap[($diffYear+1)]-(($seniority_month+($onBoardDate->format('d')-1)/$month_day)/12 * $this->leaveMap[($diffYear+1)]))
                            ))*10)/10 * 8 * 60;
                        $this->create_Specialleaveyear($employee, $now->format('Y')."-01-01", $now->format('Y')."-12-31", ($dDiff->format('%y')*12 + $dDiff->format('%m')),  $special_leave);
                    }else{// 到職日滿 25 年
                        $special_leave = ceil(sprintf('%.2f',(
                            (($seniority_month->format('m') + ($onBoardDate->format('d')-1)/$month_day)/12 * 30) + 
                            (30-(($seniority_month->format('m')+($onBoardDate->format('d')-1)/$month_day)/12 * 30))
                            ))*10)/10 * 8 * 60;
                        $this->create_Specialleaveyear($employee, $now->format('Y')."-01-01", $now->format('Y')."-12-31", ($dDiff->format('%y')*12 + $dDiff->format('%m')),  $special_leave);
                    }
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            Yii::log(date("Y-m-d H:i:s").' AnnualLeaveCommand employee_id => ' . $employee->id . "；nowDate => " . $now->format('Y-m-d') . "；errorMsg => " . $e->getMessage(), CLogger::LEVEL_INFO);
        }
    }

    public function SpecialLeaveYearIdInit($year,$employee){
        try{
            $sql = "SELECT * FROM special_leave_year WHERE employee_id='" . $employee->id . "' AND YEAR(start_date)='" . $year . "'";
            $data = Yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($data)){
                $sql = "UPDATE `attendance_record` SET special_leave_year_id='" . $data[0]["id"] . "' WHERE employee_id='" . $employee->id . "' AND YEAR(day)='" . $year . "' AND take='5'";
                $data = Yii::app()->db->createCommand($sql)->execute();
            }
            
        } catch (Exception $e) {
            echo $e->getMessage();
            Yii::log(date("Y-m-d H:i:s").' SpecialLeaveYearIdInit employee_id => ' . $employee->id . "year => " . $year . "；errorMsg => " . $e->getMessage(), CLogger::LEVEL_INFO);
        }
    }

    public function calcHistoryAnnualLeaveSummaryOnBoardDate_FiscalYear($employee_id){
        $now = new DateTime();
        $now->setTime(0, 0, 0);
        $now->modify('-1 year');
        $data = array();
        $sql = "SELECT * FROM special_leave_year WHERE employee_id = '" . $employee_id . "' AND YEAR(start_date) <= " . $now->format('Y');
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        return $data;
    }

    public function calcAnnualLeaveSummaryYear_FiscalYear($employee_id, $year){
        $data = array();
        $sql = "SELECT * FROM special_leave_year WHERE employee_id = '" . $employee_id . "' AND YEAR(start_date) = " . $year;
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        return $data;
    }

    public function getEmployeeLeaves_FiscalYear($special_leave_year_id, $employee_id){
        $data = array();
        $sql = "SELECT SUM(leave_minutes) AS summary_leave_minutes FROM `attendance_record` WHERE employee_id = '" . $employee_id . "' AND status='1' AND take='5' AND special_leave_year_id = " . $special_leave_year_id;
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        if(empty($data)){
            return 0;
        }else{
            return $data[0]["summary_leave_minutes"];
        }
        
    }

    public function create_Specialleaveyear($employee, $start_date, $end_date, $seniority, $special_leave){
        if($special_leave>0){
            $sql_start_date = new DateTime($start_date);
            $sql = "SELECT * FROM special_leave_year WHERE employee_id = '" . $employee->id . "' AND YEAR(start_date) = " . $sql_start_date->format('Y') . " AND is_close='0' LIMIT 1";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($result)){
                $model = Specialleaveyear::model()->findByPK($result[0]["id"]);
            }else{
                $model = new Specialleaveyear();
            }
            $model->employee_id = $employee->id;
            $model->start_date = $start_date;
            $model->end_date = $end_date;
            $model->seniority = $seniority;
            $model->special_leave = $special_leave;
            if(!$model->save()){
                var_dump($model);
            }
        }
    }

    public function getYearLeaves_FiscalYear($year, $special_leave_year_id="", $uid="") {
        $start = $year . "-01-01";
        $end = $year . "-12-31";
        $sql = "(
            SELECT * FROM `attendance_record` WHERE ";
        if(!empty($uid))
            $sql .= "   employee_id='".$uid."' AND ";
        $sql .= "  leave_time >= '".$start."'
            AND leave_time <= '".$end."'
            AND status <> '"  . LeaveService::STATUS_DELETE . "'
            AND take <> '"  . Attendance::ANNUAL_LEAVE . "' AND take <> '"  . Attendance::OVERTIME . "'
        ) UNION 
        (
            SELECT * FROM `attendance_record` WHERE ";
        if(!empty($uid))
            $sql .= "   employee_id='".$uid."' AND ";
        $sql .= " (
                (";
        if(!empty($special_leave_year_id))
            $sql .= "   special_leave_year_id = '".$special_leave_year_id."' AND ";
        $sql .= "   status <> '"  . LeaveService::STATUS_DELETE . "'
                    AND take = '" . Attendance::ANNUAL_LEAVE . "'
                ) OR (
                    leave_time >= '".$start."'
                    AND leave_time <= '".$end."'
                    AND status = '"  . LeaveService::STATUS_APPLY . "'
                    AND take = '" . Attendance::ANNUAL_LEAVE . "'
                )
            )
        )";
        
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        return $result;
    }
    // 歷史特休管理 - 查詢全部在職正職員工
    public function findAllEmployeeHistoryLeave($AnnualLeaveType){
        $empService = new EmployeeService();
        $leaveService = new LeaveService();
        $emp = $empService->findEmployeeInRolesListObject([2,5,26]);//列出文訊正職員工狀態為啟用中的
        $sumary = array();
        if($AnnualLeaveType == "2"){
            if($emp){
                foreach($emp as $key => $value) {
                    $employee = Employee::model()->findByPk($value["id"]);
                    $onBoardDate = new DateTime($employee->onboard_date);
                    $nowDate->setTime(0, 0, 0);
                    $onBoardDate->setTime(0, 0, 0);
                    $Year_Diff = $nowDate->diff($onBoardDate);
                    $Year_Diff = $Year_Diff->format('%y');
                    for ($i=1; $i <= $Year_Diff; $i++) {
                        $onBoardDate = new DateTime($employee->onboard_date);
                        $onBoardDate->setTime(0, 0, 0);
                        $runDate = $onBoardDate->modify('+' . $i . ' year');
                        $runDate = new DateTime($runDate->format('Y').'-01-01'); //yyyy-mm-dd 
                        $annualLeaveMinutes = $leaveService->calcAnnualLeaveSummaryOnBoardDate($runDate, $employee);
                        $commonLeaveStartDateTime = new DateTime("{$runDate->format('Y')}/01/01 00:00:00");
                        $commonLeaveStartDate = $commonLeaveStartDateTime->format('Y/m/d H:i:s');
                        $commonLeaveEndDateTime = new DateTime("{$runDate->format('Y')}/01/01 00:00:00");
                        $commonLeaveEndDateTime->add(DateInterval::createFromDateString('1 year'));
                        $commonLeaveEndDate = $commonLeaveEndDateTime->format('Y/m/d H:i:s');
                        $appliedAnnualLeave = $leaveService->summaryMinutesByPeriodOfTimeAndLeaveType(
                            $employee->id,
                            $commonLeaveStartDate,
                            $commonLeaveEndDate,
                            Attendance::ANNUAL_LEAVE
                        );
                        $sumary[] = array(
                            "AnnualLeaveType" => $AnnualLeaveType,
                            "employee_id" => $id,
                            "employee_name" => $empName,
                            "start_date" => $commonLeaveStartDateTime->format('Y-m-d'),
                            "end_date" => $commonLeaveEndDateTime->format('Y-m-d'),
                            "leave_applied" => $appliedAnnualLeave / 60,
                            "leave_available" => $annualLeaveMinutes / 60 - $appliedAnnualLeave / 60,
                        );
                    }
                }
            }    
        }else{
            if($emp){
                foreach($emp as $key => $value) {
                    $employee = Employee::model()->findByPk($value["id"]);
                    $annualLeaveMinutes = $leaveService->calcHistoryAnnualLeaveSummaryOnBoardDate_FiscalYear($employee->id);
                    foreach ($annualLeaveMinutes as $annualLeave_key => $annualLeave_value) {
                        $appliedAnnualLeave = $leaveService->getEmployeeLeaves_FiscalYear(
                            $annualLeave_value["id"],
                            $employee->id
                        );
                        $sumary[] = array(
                            "id" => $annualLeave_value["id"],
                            "AnnualLeaveType" => $AnnualLeaveType,
                            "is_close" => $annualLeave_value["is_close"],
                            "employee_id" => $employee->id,
                            "employee_name" => $employee->name,
                            "start_date" => $annualLeave_value["start_date"],
                            "end_date" => $annualLeave_value["end_date"],
                            "leave_applied" => $appliedAnnualLeave / 60,
                            "leave_available" => $annualLeave_value["special_leave"] / 60 - $appliedAnnualLeave / 60,
                        );
                    }  
                }
            }
        }
        return $sumary;
    }
    // 歷史特休管理 - 查詢指定員工
    public function findEmployeeHistoryLeave($emp,$AnnualLeaveType){
        $empService = new EmployeeService();
        $leaveService = new LeaveService();
        $employee = Employee::model()->findByPk($emp->id);
        $id = $emp->id;
        $empName = $emp->name;
        $userName = $emp->user_name;
        $sumary = array();
        if($AnnualLeaveType == "2"){
            $onBoardDate = new DateTime($employee->onboard_date);
            $nowDate->setTime(0, 0, 0);
            $onBoardDate->setTime(0, 0, 0);
            $Year_Diff = $nowDate->diff($onBoardDate);
            $Year_Diff = $Year_Diff->format('%y');
            for ($i=1; $i <= $Year_Diff; $i++) {
                $onBoardDate = new DateTime($employee->onboard_date);
                $onBoardDate->setTime(0, 0, 0);
                $runDate = $onBoardDate->modify('+' . $i . ' year');
                $runDate = new DateTime($runDate->format('Y').'-01-01'); //yyyy-mm-dd 
                $annualLeaveMinutes = $leaveService->calcAnnualLeaveSummaryOnBoardDate($runDate, $employee);
                $commonLeaveStartDateTime = new DateTime("{$runDate->format('Y')}/01/01 00:00:00");
                $commonLeaveStartDate = $commonLeaveStartDateTime->format('Y/m/d H:i:s');
                $commonLeaveEndDateTime = new DateTime("{$runDate->format('Y')}/01/01 00:00:00");
                $commonLeaveEndDateTime->add(DateInterval::createFromDateString('1 year'));
                $commonLeaveEndDate = $commonLeaveEndDateTime->format('Y/m/d H:i:s');
                $appliedAnnualLeave = $leaveService->summaryMinutesByPeriodOfTimeAndLeaveType(
                    $employee->id,
                    $commonLeaveStartDate,
                    $commonLeaveEndDate,
                    Attendance::ANNUAL_LEAVE
                );
                $sumary[] = array(
                    "AnnualLeaveType" => $AnnualLeaveType,
                    "employee_id" => $id,
                    "employee_name" => $empName,
                    "start_date" => $commonLeaveStartDateTime->format('Y-m-d'),
                    "end_date" => $commonLeaveEndDateTime->format('Y-m-d'),
                    "leave_applied" => $appliedAnnualLeave / 60,
                    "leave_available" => $annualLeaveMinutes / 60 - $appliedAnnualLeave / 60,
                );
            }            
        }else{
            $annualLeaveMinutes = $leaveService->calcHistoryAnnualLeaveSummaryOnBoardDate_FiscalYear($id);
            foreach ($annualLeaveMinutes as $key => $value) {
                $appliedAnnualLeave = $leaveService->getEmployeeLeaves_FiscalYear(
                    $value["id"],
                    $employee->id
                );
                $sumary[] = array(
                    "id" => $value["id"],
                    "AnnualLeaveType" => $AnnualLeaveType,
                    "is_close" => $value["is_close"],
                    "employee_id" => $id,
                    "employee_name" => $empName,
                    "start_date" => $value["start_date"],
                    "end_date" => $value["end_date"],
                    "leave_applied" => $appliedAnnualLeave / 60,
                    "leave_available" => $value["special_leave"] / 60 - $appliedAnnualLeave / 60,
                );
            }  
        }
        return $sumary;
    }
}
?>