<?php

declare(strict_types=1);

use Employee as EmployeeORM;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Service\EmployeeLeaveCalculator;

class EmployeeController extends Controller
{
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

    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex(): void
    {
        if (!empty($_GET['year'])) {
            $year = $_GET['year'];
        } else {
            $yearDT = new DateTime();
            $year = $yearDT->format('Y');
        }

        $employeeOrmEnt = EmployeeORM::model()->findByPk(Yii::app()->session['uid']);
        if($employeeOrmEnt == NULL || Yii::app()->session['personal'] == false){
            Yii::app()->session['page_msg']  =
                '<SCRIPT type="text/javascript">
                 alert("查不到員工帳號或帳號是系統帳戶，請洽文訊人資，系統將幫你轉到公告頁。");
                 </SCRIPT>';
            $this->redirect( Yii::app()->createUrl('/news/list'));
            exit;
        }

        if($employeeOrmEnt->onboard_date === NULL){
            Yii::app()->session['page_msg']  =
                '<SCRIPT type="text/javascript">
                 alert("您的到職日尚未設定，請洽文訊人資，系統將幫你轉到公告頁。");
                 </SCRIPT>';
            $this->redirect( Yii::app()->createUrl('/news/list'));
            exit;
        }
        $configService = new ConfigService();
        $AnnualLeaveType = $configService->findByConfigName("AnnualLeaveType");
        if(!empty($AnnualLeaveType)){
            $AnnualLeaveType = $AnnualLeaveType[0]['config_value'];
        }else{
            $AnnualLeaveType = 1;
        }
        // $employee = new Employee(new EmployeeId($employeeOrmEnt->id), $employeeOrmEnt->onboard_date);
        $leaveService = new LeaveService();

        $employee = new Employee(new EmployeeId($employeeOrmEnt->id), $employeeOrmEnt->onboard_date);

        $employeeLeaveCalculator = new EmployeeLeaveCalculator();
        

        $attendanceRecordServ = new AttendancerecordService();
        $tomorrow = new DateTime();
        $tomorrow->add(DateInterval::createFromDateString('1 day'));

        $personalLeaveAnnualMinutes = $employeeLeaveCalculator->personalLeaveAnnualMinutes();
        $sickLeaveAnnualMinutes = $employeeLeaveCalculator->sickLeaveAnnualMinutes();

        $commonLeaveStartDateTime = new DateTime("{$year}/01/01 00:00:00");
        $commonLeaveStartDate = $commonLeaveStartDateTime->format('Y/m/d H:i:s');
        $commonLeaveEndDateTime = new DateTime("{$year}/01/01 00:00:00");
        $commonLeaveEndDateTime->add(DateInterval::createFromDateString('1 year'));
        $commonLeaveEndDate = $commonLeaveEndDateTime->format('Y/m/d H:i:s');

        $serv = new AttendancerecordService();

        if($AnnualLeaveType==2){
            $holidayList = $serv->getEmployeeLeaveListHoliday($employee->getEmployeeId()->value(), $year);
            $annualLeaveMinutes = $employeeLeaveCalculator->calcAnnualLeaveSummaryOnBoardDate(new DateTime(), $employee);
            // $annualLeaveMinutes = $leaveService->calcAnnualLeaveSummaryOnBoardDate(new DateTime(), $employee);
            $appliedAnnualLeave = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
                $employee->getEmployeeId()->value(),
                $commonLeaveStartDate,
                $commonLeaveEndDate,
                '5'
            );
            $annualLeaveMinutes = $annualLeaveMinutes->minutesValue();
        }else{
            // 該年度可請特休數
            $annualLeaveMinutes = $leaveService->calcAnnualLeaveSummaryYear_FiscalYear($employee->getEmployeeId()->value(), $year);
            $holidayList = array();
            if(!empty($annualLeaveMinutes)){
                $annualLeaveMinutes = $annualLeaveMinutes[0];
                $holidayList = $leaveService->getYearLeaves_FiscalYear($year,$annualLeaveMinutes["id"], $employee->getEmployeeId()->value());
                // 該年度已請且審核通過特休數
                $appliedAnnualLeave = $leaveService->getEmployeeLeaves_FiscalYear(
                    $annualLeaveMinutes["id"],
                    $employee->getEmployeeId()->value()
                );
                $annualLeaveMinutes = $annualLeaveMinutes["special_leave"];
            }else{
                $appliedAnnualLeave = 0;
                $annualLeaveMinutes = 0;
            }
        }
        
        
        $personalLeavedMinutes = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
            $employee->getEmployeeId()->value(),
            $commonLeaveStartDate,
            $commonLeaveEndDate,
            '2'
        );

        $sickLeavedMinutes = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
            $employee->getEmployeeId()->value(),
            $commonLeaveStartDate,
            $commonLeaveEndDate,
            '1'
        );

        $compensatoryLeavedMinutes = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
            $employee->getEmployeeId()->value(),
            $commonLeaveStartDate,
            $commonLeaveEndDate,
            '9'
        );

        $overtimeMins = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
            $employee->getEmployeeId()->value(),
            $commonLeaveStartDate,
            $commonLeaveEndDate,
            Attendance::OVERTIME
        );
        $sum = [
            [
                'category' => '年假(特別休假)',
                'leave_applied' => round($appliedAnnualLeave / 60, 2),
                'leave_available' => round($annualLeaveMinutes / 60 - $appliedAnnualLeave / 60, 2),
            ],
            [
                'category' => '事假',
                'leave_applied' => $personalLeavedMinutes / 60,
                'leave_available' => $personalLeaveAnnualMinutes / 60 - $personalLeavedMinutes / 60,
            ],
            [
                'category' => '病假',
                'leave_applied' => $sickLeavedMinutes / 60,
                'leave_available' => $sickLeaveAnnualMinutes / 60 - $sickLeavedMinutes / 60,
            ],
            [
                'category' => '補休假',
                'leave_applied' => $compensatoryLeavedMinutes / 60,
                'leave_available' => '-',
            ],
            [
                'category' => '加班',
                'leave_applied' => $overtimeMins / 60,
                'leave_available' => '-',
            ],

        ];

        $overtimeList = $serv->getEmployeeLeaveListOvertime($employee->getEmployeeId()->value(), $year);

        if (!empty($overtimeList)) {
            foreach ($overtimeList as $idx => $row) {
                if (isset($this->leaveMap[$row['take']])) {
                    $overtimeList[$idx]['take'] = $this->leaveMap[$row['take']];
                }
            }
        }
        if (!empty($holidayList)) {
            foreach ($holidayList as $idx => $row) {
                if (isset($this->leaveMap[$row['take']])) {
                    $holidayList[$idx]['take'] = $this->leaveMap[$row['take']];
                }
            }
        }
        $this->render('list', [
            'leaveMap' => $this->leaveMap,
            'holidayList' => $holidayList,
            'overtimeList' => $overtimeList,
            'sum' => $sum,
            'year' => $year,
            'name' => $employeeOrmEnt->name . '(' . $employeeOrmEnt->user_name . ')'
        ]);
    }

    public function actionView() {
        $attendanceRecord = Attendancerecord::model()->findByPk(filter_input(INPUT_GET, 'id'));
        $attendanceRecord->take = $this->leaveMap[$attendanceRecord->take];
        $employeeService = new EmployeeService();
        $empArr = $employeeService->getEmployeeNameArray();
        if ($attendanceRecord->agent != '') {
            if(isset($empArr[$attendanceRecord->agent])){
                $attendanceRecord->agent = $empArr[$attendanceRecord->agent];
            }else{
                $attendanceRecord->agent = "";
            }  
        }
        if ($attendanceRecord->manager != '') {
            if(isset($empArr[$attendanceRecord->manager])){
                $attendanceRecord->manager = $empArr[$attendanceRecord->manager];
            }else{
                $attendanceRecord->manager = "";
            }
        }

        $emp = EmployeeORM::model()->findByPk($attendanceRecord->employee_id);

        $this->render('view', [
            'record' => $attendanceRecord,
            'name' => $empArr[$attendanceRecord->employee_id],
            'account' => $emp->user_name
        ]);
    }

}
