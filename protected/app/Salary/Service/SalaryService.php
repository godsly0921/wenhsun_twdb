<?php

declare(strict_types=1);

namespace Wenhsun\Salary\Service;

use Wenhsun\Salary\Entity\EmployeeSalary;
use Wenhsun\Salary\Repository\EmployeeRepository;

class SalaryService
{
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function getEmployees()
    {
        return $this->employeeRepository->queryEmployees();
    }

    public function getEmployee($employeeId)
    {
        return $this->employeeRepository->findEmployee($employeeId);
    }

    public function saveEmployeeSalary(EmployeeSalary $employeeSalary)
    {
        $this->employeeRepository->saveSalary($employeeSalary);
    }
}