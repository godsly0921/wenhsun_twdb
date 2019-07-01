<?php

declare(strict_types=1);

use Wenhsun\Leave\Application\EmployeeLeaveService;
use Employee as EmployeeORM;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Service\EmployeeLeaveCalculator;

class EmployeeController extends Controller
{
    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex(): void
    {
        $employeeOrmEnt = EmployeeORM::model()->findByPk($_SESSION['uid']);

        $employee = new Employee(new EmployeeId($employeeOrmEnt->id), $employeeOrmEnt->create_at);

        $employeeLeaveCalculator = new EmployeeLeaveCalculator();
        $annualLeaveMinutes = $employeeLeaveCalculator->calcAnnualLeaveInRecentYear(new DateTime(), $employee);


        $annualStartDate = $employeeLeaveCalculator->getEmployeeNearlyAnnualLeaveStartDate(
            $employee,
            new DateTime()
        );

        $annualEndDate = new DateTime($annualStartDate);
        $annualEndDate->add(DateInterval::createFromDateString('1 year'));
        $annualEndDate->add(DateInterval::createFromDateString('1 day'));
        $annualEndDate = $annualEndDate->format('Y-m-d');

        $personalLeaveAnnualMinutes = $employeeLeaveCalculator->personalLeaveAnnualMinutes();
        $sickLeaveAnnualMinutes = $employeeLeaveCalculator->sickLeaveAnnualMinutes();

        $commonLeaveStartDateTime = new DateTime();
        $commonLeaveStartDate = $commonLeaveStartDateTime->format('Y-m-d') . ' 00:00:00';
        $commonLeaveEndDateTime = new DateTime();
        $commonLeaveEndDateTime->add(DateInterval::createFromDateString('1 year'));
        $commonLeaveEndDate = $commonLeaveEndDateTime->format('Y-m-d') . ' 00:00:00';

        $attendanceRecordServ = new AttendancerecordService();

        $applyedAnnualLeave = $attendanceRecordServ->summaryMinutesByPeriodOfTimeAndLeaveType(
            $employee->getEmployeeId()->value(),
            $annualStartDate . ' 00:00:00',
            $annualEndDate . ' 00:00:00',
            '5'
        );

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
                'leave_applied' => $applyedAnnualLeave / 60,
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

        $this->render('list', ['list' => $list]);
    }
}