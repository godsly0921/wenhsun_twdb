<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Infra;

use DateTime;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Model\EmployeeRepository;
use Yii;

class MySQLEmployeeRepository implements EmployeeRepository
{

    /**
     * @param DateTime $nowDate
     * @return Employee[]
     */
    public function getEmployees(DateTime $nowDate): array
    {
        $sql = "
            SELECT * FROM employee
            WHERE NOT EXISTS (
            SELECT 1 FROM `leave_record`
              WHERE `employee`.id = `leave_record`.employee_id 
              AND `leave_record`.annual_year = :annual_year
            )
        ";

        $binds = [':annual_year' => $nowDate->format('Y')];

        $employeesFromDB = Yii::app()->db->createCommand($sql)->bindValues($binds)->queryAll();

        $employees = [];

        foreach ($employeesFromDB as $employee) {
            $employeeId = new EmployeeId($employee['id']);
            $em = new Employee($employeeId, $employee['create_at']);
            $employees[] = $em;
        }

        return $employees;
    }
}