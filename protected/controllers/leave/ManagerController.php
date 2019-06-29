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
        $this->render('index');
    }

    public function actionHist(): void
    {
        $employeeId = $_POST['employee_id'];
        $year = $_POST['year'];

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

    private function getLeaveText()
    {

    }
}