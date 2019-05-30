<?php

declare(strict_types=1);

namespace Wenhsun\Salary\Repository;

use Common;
use RuntimeException;
use SalaryEmployee;
use Wenhsun\Salary\Entity\EmployeeSalary;
use Yii;

class EmployeeRepository
{
    public function queryEmployees()
    {
        return Yii::app()->db->createCommand(
            '
              SELECT 
                e.id employee_id, e.name, e.user_name, e.department, e.position,
                s.salary, s.health_insurance, s.labor_insurance, s.pension
              FROM employee e
              LEFT JOIN salary_employee s ON e.id = s.employee_id
            '
        )->queryAll();
    }

    public function findEmployee($employeeId)
    {
        return Yii::app()->db->createCommand(
            '
              SELECT 
                e.id employee_id, e.name, e.user_name, e.department, e.position,
                s.salary, s.health_insurance, s.salary, s.labor_insurance, s.pension
              FROM employee e
              LEFT JOIN salary_employee s ON e.id = s.employee_id
              WHERE e.id = :employee_id
            '
        )->bindValues([
            ':employee_id' => $employeeId,
        ])->queryRow();
    }

    public function saveSalary(EmployeeSalary $employeeSalary)
    {
        $employeeSalaryModel = SalaryEmployee::model()->find([
            "condition" => "employee_id=:employee_id",
            "params" => [
                ":employee_id" => $employeeSalary->getEmployeeId(),
            ]
        ]);

        $now = Common::now();

        if (!$employeeSalaryModel) {
            $employeeSalaryModel = new SalaryEmployee();
        }

        $employeeSalaryModel->employee_id = $employeeSalary->getEmployeeId();
        $employeeSalaryModel->salary = $employeeSalary->getSalary();
        $employeeSalaryModel->health_insurance = $employeeSalary->getHealthInsurance();
        $employeeSalaryModel->labor_insurance = $employeeSalary->getLaborInsurance();
        $employeeSalaryModel->pension = $employeeSalary->getPension();
        $employeeSalaryModel->update_at = $now;
        $employeeSalaryModel->create_at = $now;

        $employeeSalaryModel->save();

        if ($employeeSalaryModel->hasErrors()) {
            throw new RuntimeException($employeeSalaryModel->getErrors());
        }
    }
}