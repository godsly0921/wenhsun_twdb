<?php

declare(strict_types=1);

use Employee as EmployeeORM;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Service\EmployeeLeaveCalculator;

class EmployeeController extends Controller
{
    private $leaveMap = [
        '1' => '病假',
        '2' => '事假',
        '5' => '特休假',
        '9' => '補休假',
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

        $employee = new Employee(new EmployeeId($employeeOrmEnt->id), $employeeOrmEnt->onboard_date);

        $employeeLeaveCalculator = new EmployeeLeaveCalculator();
        $annualLeaveMinutes = $employeeLeaveCalculator->calcAnnualLeaveSummaryOnBoardDate(new DateTime(), $employee);

        $attendanceRecordServ = new AttendancerecordService();
        $tomorrow = new DateTime();
        $tomorrow->add(DateInterval::createFromDateString('1 day'));
        $appliedAnnualLeave = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
            $employee->getEmployeeId()->value(),
            $employee->getOnBoardDate() . ' 00:00:00',
            $tomorrow->format('Y-m-d 00:00:00'),
            '5'
        );

        $personalLeaveAnnualMinutes = $employeeLeaveCalculator->personalLeaveAnnualMinutes();
        $sickLeaveAnnualMinutes = $employeeLeaveCalculator->sickLeaveAnnualMinutes();

        $commonLeaveStartDateTime = new DateTime("{$year}/01/01 00:00:00");
        $commonLeaveStartDate = $commonLeaveStartDateTime->format('Y/m/d H:i:s');
        $commonLeaveEndDateTime = new DateTime("{$year}/01/01 00:00:00");
        $commonLeaveEndDateTime->add(DateInterval::createFromDateString('1 year'));
        $commonLeaveEndDate = $commonLeaveEndDateTime->format('Y/m/d H:i:s');

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

        $sum = [
            [
                'category' => '年假(特別休假)',
                'leave_applied' => $appliedAnnualLeave / 60,
                'leave_available' => $annualLeaveMinutes->minutesValue() / 60,
            ],
            [
                'category' => '事假',
                'leave_applied' => $personalLeavedMinutes / 60,
                'leave_available' => $personalLeaveAnnualMinutes / 60,
            ],
            [
                'category' => '病假',
                'leave_applied' => $sickLeavedMinutes / 60,
                'leave_available' => $sickLeaveAnnualMinutes / 60,
            ],
            [
                'category' => '補休假',
                'leave_applied' => $compensatoryLeavedMinutes / 60,
                'leave_available' => '-',
            ],

        ];

        $serv = new AttendancerecordService();
        $list = $serv->getEmployeeLeaveList($employee->getEmployeeId()->value(), $year);

        if (!empty($list)) {
            foreach ($list as $idx => $row) {
                if (isset($this->leaveMap[$row['take']])) {
                    $list[$idx]['take'] = $this->leaveMap[$row['take']];
                }
            }
        }

        $this->render('list', [
            'list' => $list,
            'sum' => $sum,
            'year' => $year,
        ]);
    }
}