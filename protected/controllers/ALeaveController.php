<?php

declare(strict_types=1);

use Wenhsun\Leave\Application\EmployeeLeaveService;
use Wenhsun\Leave\Application\LeaveApplyCommand;
use Wenhsun\Leave\Application\LeaveApplyService;
use Employee as EmployeeORM;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Service\EmployeeLeaveCalculator;
use Wenhsun\Leave\Infra\MySQLLeaveApplyRepository;

class ALeaveController extends Controller
{
    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex(): void
    {
        $employeeOrmEnt = EmployeeORM::model()->findByPk($_SESSION['uid']);

        $employee = new Employee(new EmployeeId($employeeOrmEnt->id), $employeeOrmEnt->onboard_date);

        $employeeLeaveCalculator = new EmployeeLeaveCalculator();
        $annualLeaveMinutes = $employeeLeaveCalculator->calcAnnualLeaveInRecentYear(new DateTime(), $employee);
        $personalLeaveAnnualMinutes = $employeeLeaveCalculator->personalLeaveAnnualMinutes();
        $sickLeaveAnnualMinutes = $employeeLeaveCalculator->sickLeaveAnnualMinutes();

        $commanLeaveStartDateTime = new DateTime();
        $commonLeaveStartDate = $commanLeaveStartDateTime->format('Y-m-d') . ' 00:00:00';
        $commonLeaveEndDateTime = new DateTime();
        $commonLeaveEndDateTime->add(DateInterval::createFromDateString('1 year'));
        $commonLeaveEndDate = $commonLeaveEndDateTime->format('Y-m-d') . ' 00:00:00';

        $attendanceRecordServ = new AttendancerecordService();
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

        $employeeLeaveServ = new EmployeeLeaveService();
        $annualLeaveUsedMinutes = $employeeLeaveServ->queryEmployeeLeaveSum(new DateTime(), new DateTime(), '');

        $list = [
            [
                'category' => '年假(特別休假)',
                'leave_applied' => 100,
                'leave_available' => ($annualLeaveMinutes->minutesValue() - $annualLeaveUsedMinutes->minutesValue()) / 60,
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

        $this->render('index', ['list' => $list]);
    }

    public function actionNew()
    {
        $this->render('new', ['documentTypes' => '']);
    }

    public function actionApply()
    {
        $employeeId = $_SESSION['uid'];

        $leaveApplyCommand = new LeaveApplyCommand();
        $leaveApplyCommand->employeeId = $employeeId;
        $leaveApplyCommand->type = $_POST['leave_type'];
        $leaveApplyCommand->startDate = "{$_POST['start_date']} {$_POST['start_time']}";
        $leaveApplyCommand->endDate = "{$_POST['end_date']} {$_POST['end_time']}";
        $leaveApplyCommand->memo = $_POST['memo'];

        $leaveApplyRepository = new MySQLLeaveApplyRepository();
        $leaveApplyServ = new LeaveApplyService($leaveApplyRepository);
        $leaveApplyServ->applyLeave($leaveApplyCommand);
    }
}