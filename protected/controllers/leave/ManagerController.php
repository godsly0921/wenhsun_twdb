<?php

declare(strict_types=1);

use Employee as EmployeeORM;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Service\EmployeeLeaveCalculator;

class ManagerController extends Controller
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
        $employees = EmployeeORM::model()->findAll();

        $this->render('index', ['employees' => $employees]);
    }

    public function actionHist(): void
    {
        $employeeId = $_GET['employee_id'];
        $year = $_GET['year'];

        $employeeOrmEnt = EmployeeORM::model()->findByPk($employeeId);

        if ($employeeOrmEnt === null) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '查無員工';
            $this->redirect('index');
        }

        $serv = new AttendancerecordService();
        $list = $serv->getEmployeeLeaveList($employeeId, $year);

        if (!empty($list)) {
            foreach ($list as $idx => $row) {
                if (isset($this->leaveMap[$row['take']])) {
                    $list[$idx]['take'] = $this->leaveMap[$row['take']];
                }
            }
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

        $summary = [
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

        $this->render('hist', [
            'employeeId' => $employeeId,
            'employeeName' => $employeeOrmEnt->name,
            'year' => $year,
            'list' => $list,
            'sum' => $summary,
        ]);
    }

    public function actionNew(): void
    {
        $employees = EmployeeORM::model()->findAll();

        $this->render('new', [
            'employees' => $employees,
            'leaveMap' => $this->leaveMap,
        ]);
    }

    public function actionCreate(): void
    {
        $this->checkCSRF('index');

        try {

            $leaveDate = $_POST['leave_date'];


            $now = Common::now();
            $attendanceRecord = new Attendancerecord();
            $attendanceRecord->employee_id = $_POST['employee_id'];
            $attendanceRecord->create_at = $now;
            $attendanceRecord->update_at = $now;
            $attendanceRecord->day = $leaveDate;
            $attendanceRecord->first_time = '1970-01-01 00:00:00';
            $attendanceRecord->last_time = '1970-01-01 00:00:00';
            $attendanceRecord->abnormal_type = '0';
            $attendanceRecord->abnormal = '請假';
            $attendanceRecord->take = $_POST['leave_type'];
            $attendanceRecord->leave_time = $leaveDate;
            $attendanceRecord->leave_minutes = $_POST['leave_minutes'];
            $attendanceRecord->reply_description = '';
            $attendanceRecord->reply_update_at = '1970-01-01 00:00:00';
            $attendanceRecord->save();

            if ($attendanceRecord->hasErrors()) {
                Yii::log(serialize($attendanceRecord->getErrors()), CLogger::LEVEL_ERROR);
                Yii::app()->session[Controller::ERR_MSG_KEY] = '新增失敗';
                $this->redirect('new');
            }

            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '新增成功';
            $this->redirect('new');

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = $ex->getMessage();
            $this->redirect('new');
        }
    }

    public function actionEdit(): void
    {
        $id = $_GET['id'];

        $attendanceRecord = Attendancerecord::model()->findByPk($id);

        if (!$attendanceRecord) {
            $this->redirect('index');
        }

        $year = new DateTime($attendanceRecord->leave_time);

        $this->render('edit', [
            'attendanceRecord' => $attendanceRecord,
            'leaveMap' => $this->leaveMap,
            'year' => $year->format('Y'),
        ]);
    }

    public function actionUpdate(): void
    {
        $this->checkCSRF('index');

        try {

            $id = $_POST['id'];

            $attendanceRecord = Attendancerecord::model()->findByPk($id);

            $attendanceRecord->day = $_POST['leave_date'];
            $attendanceRecord->leave_time = $_POST['leave_date'];
            $attendanceRecord->take = $_POST['leave_type'];
            $attendanceRecord->leave_minutes = $_POST['leave_minutes'];

            $attendanceRecord->update();

            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '修改成功';
            $this->redirect('edit?id=' . $_POST['id']);

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = $ex->getMessage();
            $this->redirect('edit?id=' . $_POST['id']);
        }
    }
}