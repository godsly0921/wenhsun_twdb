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

            if (!empty($employee['salary'])) {
                $configService = new ConfigService();
                $AnnualLeaveType = $configService->findByConfigName("AnnualLeaveType");
                if(!empty($AnnualLeaveType)){
                    $AnnualLeaveType = $AnnualLeaveType[0]['config_value'];
                }else{
                    $AnnualLeaveType = 1;
                }
                $check_role = [2,5,26];
                $AnnualLeaveFiscalYear = 0;
                if($AnnualLeaveType == 1 && in_array($employee['role'], $check_role)){
                    $leaveService = new LeaveService();
                    $annualLeaveMinutes = $leaveService->calcAnnualLeaveSummaryYear_FiscalYear($employee['employee_id'], $year);
                    if(!empty($annualLeaveMinutes)){
                        if($annualLeaveMinutes[0]["is_close"] == '1'){
                            $appliedAnnualLeave = $leaveService->getEmployeeLeaves_FiscalYear(
                                $annualLeaveMinutes["id"],
                                $employee['employee_id']
                            );
                            $annualLeaveMinutes = $annualLeaveMinutes["special_leave"];
                            $AnnualLeaveFiscalYear = round(
                                ($employee['salary']/30/8) * (($annualLeaveMinutes-$appliedAnnualLeave)/60)
                            );
                        }
                    }
                }
                $salaryReportEmployee = new SalaryReportEmployee(
                    Uuid::gen(),
                    $batchId,
                    $employee['employee_id'],
                    $employee['user_name'],
                    $employee['name'],
                    $employee['department'],
                    $employee['position'],
                    (float)$employee['salary'],
                    0,
                    0,
                    0,
                    0,
                    0,
                    (float)$employee['health_insurance'],
                    (float)$employee['labor_insurance'],
                    (float)$employee['pension']
                    $AnnualLeaveFiscalYear,
                );

                $batchReportBatch->addEmployee($salaryReportEmployee);
            }
        }

        $salaryReportServ = new SalaryReportService();
        $salaryReportServ->addBatch($batchReportBatch);

        echo "Finished\n";
    }
}