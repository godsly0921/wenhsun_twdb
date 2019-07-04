<?php

declare(strict_types=1);

use Employee as EmployeeORM;

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

        $this->render('hist', [
            'employeeId' => $employeeId,
            'employeeName' => $employeeOrmEnt->name,
            'year' => $year,
            'list' => $list
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

            $date = $_POST['date'];

            $now = Common::now();
            $attendanceRecord = new Attendancerecord();
            $attendanceRecord->employee_id = $_POST['employee_id'];
            $attendanceRecord->create_at = $now;
            $attendanceRecord->update_at = $now;
            $attendanceRecord->day = $date;
            $attendanceRecord->first_time = '1970-01-01 00:00:00';
            $attendanceRecord->last_time = '1970-01-01 00:00:00';
            $attendanceRecord->abnormal_type = '0';
            $attendanceRecord->abnormal = '請假';
            $attendanceRecord->take = $_POST['leave_type'];
            $attendanceRecord->leave_time = $date;
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

        $this->render('edit', [
            'attendanceRecord' => $attendanceRecord,
            'leaveMap' => $this->leaveMap,
        ]);
    }

    public function actionUpdate(): void
    {
        $this->checkCSRF('index');

        try {

            $id = $_POST['id'];

            $attendanceRecord = Attendancerecord::model()->findByPk($id);

            $attendanceRecord->day = $_POST['date'];
            $attendanceRecord->leave_time = $_POST['date'];
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