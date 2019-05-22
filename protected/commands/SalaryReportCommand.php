<?php

declare(strict_types=1);

use Wenhsun\Salary\Entity\SalaryReportBatch;
use Wenhsun\Salary\Entity\SalaryReportEmployee;
use Wenhsun\Salary\Repository\EmployeeRepository;
use Wenhsun\Salary\Service\SalaryReportService;
use Wenhsun\Salary\Service\SalaryService;
use Wenhsun\Tool\Uuid;

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
        $batchId = $year . $month;
        $batchReportBatch = new SalaryReportBatch($batchId);

        foreach ($employees as $employee) {

            $salaryReportEmployee = new SalaryReportEmployee(
                Uuid::gen(),
                $employee['employee_id'],
                (float)$employee['salary'],
                0,
                0,
                0,
                0,
                0,
                (float)$employee['health_insurance'],
                (float)$employee['labor_insurance'],
                (float)$employee['pension']
            );

            $batchReportBatch->addEmployee($salaryReportEmployee);
        }

        $salaryReportServ = new SalaryReportService();
        $salaryReportServ->addBatch($batchReportBatch);

        echo "Finished\n";
    }
}