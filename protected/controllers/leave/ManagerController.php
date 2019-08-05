<?php

declare(strict_types=1);

use Employee as EmployeeORM;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Service\EmployeeLeaveCalculator;

class ManagerController extends Controller
{
    // private $leaveMap = [
    //     Attendance::SICK_LEAVE => '普通傷病假',
    //     Attendance::PERSONAL_LEAVE => '事假',
    //     Attendance::PUBLIC_AFFAIRS_LEAVE => '公假',
    //     Attendance::OCCUPATIONAL_SICKNESS_LEAVE => '公傷病假',
    //     Attendance::ANNUAL_LEAVE => '特休假',
    //     Attendance::MATERNITY_LEAVE => '分娩假含例假日',
    //     Attendance::MARITAL_LEAVE => '婚假',
    //     Attendance::FUNERAL_LEAVE => '喪假',
    //     Attendance::COMPENSATORY_LEAVE => '補休假',
    //     Attendance::MENSTRUAL_LEAVE => '生理假',
    //     Attendance::PATERNITY_LEAVE => '陪產假',
    //     Attendance::MISCARRIAGE_LEAVE => '流產假',
    //     Attendance::PRENATAL_LEAVE => '產檢假',
    // ];

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
        $employees = EmployeeORM::model()->findAll();

        $userNameSearchWord = $this->buildUsernameSearchWord($employees);

        $nameSearchWord = $this->buildNameSearchWord($employees);

        $this->render('index', ['userNameSearchWord' => $userNameSearchWord, 'nameSearchWord' => $nameSearchWord]);
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

    private function buildNameSearchWord($employees): string
    {
        $loginIds = [];
        foreach ($employees as $employee) {
            $loginIds[] = $employee->name;
        }

        $userNameSearchWord = implode('","', $loginIds);

        return '"' . $userNameSearchWord . '"';
    }

    public function actionHist(): void
    {
        $employeeUserName = $_GET['user_name'];
        $name = isset($_GET['name']) ? $_GET['name'] : '';
        $year = $_GET['year'];
        $type = isset($_GET['type']) ? $_GET['type'] : 1;

        $serv = new AttendancerecordService();

        $id = '';
        $empName = '';
        $userName = '';

        if ($type == 1) {
            $employeeOrmEnt = EmployeeORM::model()->find(
                'user_name=:user_name',
                [':user_name' => $employeeUserName]
            );

            if ($employeeOrmEnt === null) {
                Yii::app()->session[Controller::ERR_MSG_KEY] = '查無員工';
                $this->redirect('index');
            }

            $holidayList = $serv->getEmployeeLeaveListHoliday($employeeOrmEnt->id, $year);
            $overtimeList = $serv->getEmployeeLeaveListOvertime($employeeOrmEnt->id, $year);

            $employee = new Employee(new EmployeeId($employeeOrmEnt->id), $employeeOrmEnt->onboard_date);

            $id = $employeeOrmEnt->id;
            $empName = $employeeOrmEnt->name;
            $userName = $employeeOrmEnt->user_name;
        } elseif ($type == 2) {
            $emp = EmployeeORM::model()->find(
                'name=:name',
                [':name' => $name]
            );

            if ($emp === null) {
                Yii::app()->session[Controller::ERR_MSG_KEY] = '查無員工';
                $this->redirect('index');
            }

            $holidayList = $serv->getEmployeeLeaveListHoliday($emp->id, $year);
            $overtimeList = $serv->getEmployeeLeaveListOvertime($emp->id, $year);

            $employee = new Employee(new EmployeeId($emp->id), $emp->onboard_date);

            $id = $emp->id;
            $empName = $emp->name;
            $userName = $emp->user_name;
        }

        if (!empty($holidayList)) {
            foreach ($holidayList as $idx => $row) {
                if (isset($this->leaveMap[$row['take']])) {
                    $holidayList[$idx]['take'] = $this->leaveMap[$row['take']];
                }
            }
        }

        if (!empty($overtimeList)) {
            foreach ($overtimeList as $idx => $row) {
                if (isset($this->leaveMap[$row['take']])) {
                    $overtimeList[$idx]['take'] = $this->leaveMap[$row['take']];
                }
            }
        }

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
            'employeeId' => $id,
            'employeeUserName' => $userName,
            'employeeName' => $empName,
            'year' => $year,
            'holidayList' => $holidayList,
            'overtimeList' => $overtimeList,
            'sum' => $summary,
        ]);
    }

    public function actionNew(): void
    {
        $employees = EmployeeORM::model()->findAll();
        $employee = EmployeeORM::model()->findByPk(Yii::app()->session['uid']);
        $userNameSearchWord = $this->buildUsernameSearchWord($employees);
        $nameSearchWord = $this->buildNameSearchWord($employees);

        $this->render('new', [
            'userNameSearchWord' => $userNameSearchWord,
            'leaveMap' => $this->leaveMap,
            'emp' => $employee,
            'nameSearchWord' => $nameSearchWord
        ]);
    }

    public function actionOvertime(): void
    {
        $employees = EmployeeORM::model()->findAll();
        $employee = EmployeeORM::model()->findByPk(Yii::app()->session['uid']);
        $userNameSearchWord = $this->buildUsernameSearchWord($employees);
        $nameSearchWord = $this->buildNameSearchWord($employees);

        $this->render('overtime', [
            'userNameSearchWord' => $userNameSearchWord,
            'leaveMap' => $this->leaveMap,
            'emp' => $employee,
            'nameSearchWord' => $nameSearchWord
        ]);
    }

    public function actionCreate(): void
    {
        $this->checkCSRF('index');

        try {
            $employeeUserName = $_POST['user_name'];

            $employeeOrmEnt = EmployeeORM::model()->find(
                'user_name=:user_name',
                [':user_name' => $employeeUserName]
            );

            if (isset($_POST['agent'])) {
                $agent = EmployeeORM::model()->find(
                    'name = :name',
                    [':name' => $_POST['agent']]
                );
            }

            $manager = EmployeeORM::model()->find(
                'name = :name',
                [':name' => $_POST['manager']]
            );

            if ($employeeOrmEnt === null || (isset($_POST['agent']) && $agent === null) || $manager === null) {
                Yii::app()->session[Controller::ERR_MSG_KEY] = '查無員工';
                if ($_POST['leave_type'] == 11) {
                    $this->redirect('overtime');
                } else {
                    $this->redirect('new');
                }
            }

            $now = Common::now();
            $days = filter_input(INPUT_POST, 'days');
            if ($days == 1) {
                $attendanceRecord = new Attendancerecord();
                $attendanceRecord->employee_id = $employeeOrmEnt->id;
                $attendanceRecord->create_at = $now;
                $attendanceRecord->update_at = $now;
                $attendanceRecord->day = filter_input(INPUT_POST, 'start_date');
                $attendanceRecord->first_time = '0000-00-00 00:00:00';
                $attendanceRecord->last_time = '0000-00-01 00:00:00';
                $attendanceRecord->abnormal_type = '0';
                $attendanceRecord->abnormal = $_POST['leave_type'] == 11 ? '加班' : '請假';
                $attendanceRecord->take = $_POST['leave_type'];
                $attendanceRecord->leave_time = filter_input(INPUT_POST, 'start_date');
                $attendanceRecord->leave_minutes = (float) filter_input(INPUT_POST, 'leave_minutes') * 60;
                $attendanceRecord->reply_description = '';
                $attendanceRecord->reply_update_at = '0000-00-00 00:00:00';
                $attendanceRecord->reason = filter_input(INPUT_POST, 'reason');
                $attendanceRecord->remark = filter_input(INPUT_POST, 'remark');
                $attendanceRecord->start_time = filter_input(INPUT_POST, 'start_date') . ' ' . filter_input(INPUT_POST, 'start_time') . ':00';
                $attendanceRecord->end_time = filter_input(INPUT_POST, 'end_date') . ' ' . filter_input(INPUT_POST, 'end_time') . ':00';
                $attendanceRecord->manager = $manager->id;
                $attendanceRecord->agent = isset($_POST['agent']) ? $agent->id : '';
                $attendanceRecord->status = 0;
                $attendanceRecord->save();
            } else {
                $date = date(filter_input(INPUT_POST, 'start_date'));
                for ($i = 1; $i < $days; $i++) {
                    $attendanceRecord = new Attendancerecord();
                    $attendanceRecord->employee_id = $employeeOrmEnt->id;
                    $attendanceRecord->create_at = $now;
                    $attendanceRecord->update_at = $now;
                    $attendanceRecord->day = filter_input(INPUT_POST, 'start_date');
                    $attendanceRecord->first_time = '0000-00-00 00:00:00';
                    $attendanceRecord->last_time = '0000-00-01 00:00:00';
                    $attendanceRecord->abnormal_type = '0';
                    $attendanceRecord->abnormal = $_POST['leave_type'] == 11 ? '加班' : '請假';
                    $attendanceRecord->take = $_POST['leave_type'];
                    $attendanceRecord->leave_time = $date;
                    $attendanceRecord->leave_minutes = 480;
                    $attendanceRecord->reply_description = '';
                    $attendanceRecord->reply_update_at = '0000-00-00 00:00:00';
                    $attendanceRecord->reason = filter_input(INPUT_POST, 'reason');
                    $attendanceRecord->remark = filter_input(INPUT_POST, 'remark');
                    if ($i === 1) {
                        $attendanceRecord->start_time = $date . ' ' . filter_input(INPUT_POST, 'start_time') . ':00';
                    } else {
                        $attendanceRecord->start_time = $date . ' ' . '09:00';
                    }
                    $attendanceRecord->end_time = $date . ' ' . '18:00';
                    $attendanceRecord->manager = $manager->id;
                    $attendanceRecord->agent = isset($_POST['agent']) ? $agent->id : '';
                    $attendanceRecord->status = 0;
                    $attendanceRecord->save();
                    $date = date(date("Y-m-d", strtotime("+1 day",strtotime($date))));
                }

                $date = date($date, strtotime('+1 day'));
                $attendanceRecord = new Attendancerecord();
                $attendanceRecord->employee_id = $employeeOrmEnt->id;
                $attendanceRecord->create_at = $now;
                $attendanceRecord->update_at = $now;
                $attendanceRecord->day = filter_input(INPUT_POST, 'start_date');
                $attendanceRecord->first_time = '0000-00-00 00:00:00';
                $attendanceRecord->last_time = '0000-00-01 00:00:00';
                $attendanceRecord->abnormal_type = '0';
                $attendanceRecord->abnormal = $_POST['leave_type'] == 11 ? '加班' : '請假';
                $attendanceRecord->take = $_POST['leave_type'];
                $attendanceRecord->leave_time = $date;
                $attendanceRecord->leave_minutes = (float) filter_input(INPUT_POST, 'last_hours') * 60;
                $attendanceRecord->reply_description = '';
                $attendanceRecord->reply_update_at = '0000-00-00 00:00:00';
                $attendanceRecord->reason = filter_input(INPUT_POST, 'reason');
                $attendanceRecord->remark = filter_input(INPUT_POST, 'remark');
                $attendanceRecord->start_time = filter_input(INPUT_POST, 'start_date') . ' ' . filter_input(INPUT_POST, 'start_time') . ':00';
                $attendanceRecord->end_time = filter_input(INPUT_POST, 'end_date') . ' ' . filter_input(INPUT_POST, 'end_time') . ':00';
                $attendanceRecord->manager = $manager->id;
                $attendanceRecord->agent = isset($_POST['agent']) ? $agent->id : '';
                $attendanceRecord->status = 0;
                $attendanceRecord->save();
            }

            if ($attendanceRecord->hasErrors()) {
                Yii::log(serialize($attendanceRecord->getErrors()), CLogger::LEVEL_ERROR);
                Yii::app()->session[Controller::ERR_MSG_KEY] = '新增失敗';
                if ($_POST['leave_type'] == 11) {
                    $this->redirect('overtime');
                } else {
                    $this->redirect('new');
                }
            }

            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '新增成功';
            if ($_POST['leave_type'] == 11) {
                $this->redirect('overtime');
            } else {
                $this->redirect('new');
            }

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = $ex->getMessage();
            if ($_POST['leave_type'] == 11) {
                $this->redirect('overtime');
            } else {
                $this->redirect('new');
            }
        }
    }

    public function actionEdit(): void
    {
        $id = $_GET['id'];

        $attendanceRecord = Attendancerecord::model()->findByPk($id);

        if (!$attendanceRecord) {
            $this->redirect('index');
        }

        if ($attendanceRecord->agent != '') {
            $agent = EmployeeORM::model()->findByPk($attendanceRecord->agent);
            $attendanceRecord->agent = $agent->name;
        }

        if ($attendanceRecord->manager != '') {
            $manager = EmployeeORM::model()->findByPk($attendanceRecord->manager);
            $attendanceRecord->manager = $manager->name;
        }

        $employeeOrmEnt = EmployeeORM::model()->findByPk($attendanceRecord->employee_id);

        $year = new DateTime($attendanceRecord->leave_time);

        $nameSearchWord = $this->buildNameSearchWord(EmployeeORM::model()->findAll());

        $this->render('edit', [
            'attendanceRecord' => $attendanceRecord,
            'employee' => $employeeOrmEnt,
            'leaveMap' => $this->leaveMap,
            'year' => $year->format('Y'),
            'nameSearchWord' => $nameSearchWord
        ]);
    }

    public function actionUpdate(): void
    {
        $this->checkCSRF('index');
        
        if (isset($_POST['agent'])) {
            $agent = EmployeeORM::model()->find(
                'name = :name',
                [':name' => $_POST['agent']]
            );
        }

        $manager = EmployeeORM::model()->find(
            'name = :name',
            [':name' => $_POST['manager']]
        );

        if (($_POST['agent'] != '' && $agent === null) || $manager === null) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '查無員工';
            $this->redirect('new');
        }

        try {

            $id = $_POST['id'];

            $attendanceRecord = Attendancerecord::model()->findByPk($id);
            $now = Common::now();
            $attendanceRecord->update_at = $now;
            $attendanceRecord->day = $_POST['leave_date'];
            $attendanceRecord->leave_time = $_POST['leave_date'];
            $attendanceRecord->take = $_POST['leave_type'];
            $attendanceRecord->leave_minutes = (FLOAT) filter_input(INPUT_POST, 'leave_minutes') * 60;
            $attendanceRecord->reason = filter_input(INPUT_POST, 'reason');
            $attendanceRecord->remark = filter_input(INPUT_POST, 'remark');
            $attendanceRecord->start_time = filter_input(INPUT_POST, 'leave_date') . ' ' . filter_input(INPUT_POST, 'start_time') . ':00';
            $attendanceRecord->end_time = filter_input(INPUT_POST, 'leave_date') . ' ' . filter_input(INPUT_POST, 'end_time') . ':00';
            $attendanceRecord->manager = $manager->id;
            $attendanceRecord->agent = $_POST['agent'] == '' ? '' : $agent->id;
            $attendanceRecord->status = 1;
            $attendanceRecord->update();

            $service = new AttendancerecordService();
            $result = $service->sendApproveMail($id);

            if ($result) {
                Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '修改成功';
            } else {
                Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '修改成功但寄發通知信失敗';
            }
            $this->redirect('edit?id=' . $_POST['id']);

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = $ex->getMessage();
            echo $ex->getMessage();
            $this->redirect('edit?id=' . $_POST['id']);
        }
    }

    public function actionDelete() {
        $id = filter_input(INPUT_POST, 'id');

        $model = Attendancerecord::model()->findByPk($id);

        if ($model !== null) {
            $result = $model->delete();
        }

        echo json_encode($result);
        exit;
    }
}