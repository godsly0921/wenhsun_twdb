<?php

declare(strict_types=1);

use Wenhsun\Salary\Entity\EmployeeSalary;
use Wenhsun\Salary\Repository\EmployeeRepository;
use Wenhsun\Salary\Service\SalaryService;

class EmployeesController extends Controller
{
    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex()
    {
        $salaryServ = new SalaryService(new EmployeeRepository());
        $employees = $salaryServ->getEmployees();

        $this->render('list', ['list' => $employees]);
    }

    public function actionEdit($id)
    {
        $salaryServ = new SalaryService(new EmployeeRepository());
        $data = $salaryServ->getEmployee($id);

        $this->render('edit', ['data' => $data]);
    }

    public function actionUpdate()
    {
        try {
            $this->checkCSRF('index');

            $employeeSalary = new EmployeeSalary(
                $_POST['id'],
                $_POST['salary'],
                $_POST['health_insurance'],
                $_POST['labor_insurance'],
                $_POST['pension']
            );

            $salaryServ = new SalaryService(new EmployeeRepository());
            $salaryServ->saveEmployeeSalary($employeeSalary);

            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '更新成功';
            $this->redirect("edit?id={$employeeSalary->getEmployeeId()}");

        } catch (Throwable $ex) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = $ex->getMessage();
            $this->redirect("edit?id={$_POST['id']}");
        }
    }
}