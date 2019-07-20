<?php

declare(strict_types=1);

use Employee as EmployeeORM;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Service\EmployeeLeaveCalculator;

class ManagerController extends Controller
{
    private $leaveMap = [
        Attendance::SICK_LEAVE => '普通傷病假',
        Attendance::PERSONAL_LEAVE => '事假',
        Attendance::PUBLIC_AFFAIRS_LEAVE => '公假',
        Attendance::OCCUPATIONAL_SICKNESS_LEAVE => '公傷病假',
        Attendance::ANNUAL_LEAVE => '特休假',
        Attendance::MATERNITY_LEAVE => '分娩假含例假日',
        Attendance::MARITAL_LEAVE => '婚假',
        Attendance::FUNERAL_LEAVE => '喪假',
        Attendance::COMPENSATORY_LEAVE => '補休假',
        Attendance::MENSTRUAL_LEAVE => '生理假',
        Attendance::PATERNITY_LEAVE => '陪產假',
        Attendance::MISCARRIAGE_LEAVE => '流產假',
        Attendance::PRENATAL_LEAVE => '產檢假',
    ];

    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex(): void
    {
        $employees = EmployeeORM::model()->findAll();

        $userNameSearchWord = $this->buildUsernameSearchWord($employees);

        $this->render('index', ['userNameSearchWord' => $userNameSearchWord]);
    }

    private function buildUsernameSearchWord($employees): string
    {
        $loginIds = [];
        foreach ($employees as $employee) {
            $loginIds[] = $employee->user_name;
        }

        $userNameSearchWord = implode('","', $loginIds);

        return '"' . $userNameSearchWord . '"';
    }

    public function actionHist(): void
    {
        $employeeUserName = $_GET['user_name'];
        $year = $_GET['year'];

        $employeeOrmEnt = EmployeeORM::model()->find(
            'user_name=:user_name',
            [':user_name' => $employeeUserName]
        );

        if ($employeeOrmEnt === null) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '查無員工';
            $this->redirect('index');
        }

        $serv = new AttendancerecordService();
        $list = $serv->getEmployeeLeaveList($employeeOrmEnt->id, $year);

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
                'leave_available' => $sickLeaveAnnualMinutes / 60,
            ],
            [
                'category' => '事假',
                'leave_applied' => $personalLeavedMins / 60,
                'leave_available' => $personalLeaveAnnualMinutes / 60,
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
                'leave_available' => $annualLeaveMinutes->minutesValue() / 60,
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

        $this->render('hist', [
            'employeeId' => $employeeOrmEnt->id,
            'employeeUserName' => $employeeOrmEnt->user_name,
            'employeeName' => $employeeOrmEnt->name,
            'year' => $year,
            'list' => $list,
            'sum' => $summary,
        ]);
    }

    public function actionNew(): void
    {
        $employees = EmployeeORM::model()->findAll();

        $userNameSearchWord = $this->buildUsernameSearchWord($employees);

        $this->render('new', [
            'userNameSearchWord' => $userNameSearchWord,
            'leaveMap' => $this->leaveMap,
        ]);
    }

    public function actionCreate(): void
    {
        $this->checkCSRF('index');

        try {

            $leaveDate = $_POST['leave_date'];
            $employeeUserName = $_POST['user_name'];

            $employeeOrmEnt = EmployeeORM::model()->find(
                'user_name=:user_name',
                [':user_name' => $employeeUserName]
            );

            if ($employeeOrmEnt === null) {
                Yii::app()->session[Controller::ERR_MSG_KEY] = '查無員工';
                $this->redirect('new');
            }

            $now = Common::now();
            $attendanceRecord = new Attendancerecord();
            $attendanceRecord->employee_id = $employeeOrmEnt->id;
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

        $employeeOrmEnt = EmployeeORM::model()->findByPk($attendanceRecord->employee_id);

        $year = new DateTime($attendanceRecord->leave_time);

        $this->render('edit', [
            'attendanceRecord' => $attendanceRecord,
            'employee' => $employeeOrmEnt,
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