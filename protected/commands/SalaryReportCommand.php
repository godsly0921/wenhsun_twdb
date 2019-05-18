<?php

declare(strict_types=1);

use Wenhsun\Salary\Repository\EmployeeRepository;
use Wenhsun\Salary\Service\SalaryService;

class SalaryReportCommand extends CConsoleCommand
{
    public function run($day = null)
    {
        $date = new DateTime();
        $year = $date->format('Y');
        $month = $date->format('m');

        $salaryRepository = new EmployeeRepository();
        $salaryServ = new SalaryService($salaryRepository);

        $employees = $salaryServ->getEmployees();

        if (empty($employees)) {
            echo "not found any employee\n";
            exit;
        }

        //insert batch and get id


        foreach ($employees as $index => $employee) {

        }
    }
}