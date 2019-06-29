<?php

declare(strict_types=1);

use Employee as EmployeeORM;

class ManagerController extends Controller
{
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


    }
}