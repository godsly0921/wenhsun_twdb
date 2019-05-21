<?php

declare(strict_types=1);

namespace Wenhsun\Salary\Service;

use CLogger;
use Common;
use SalaryReport;
use SalaryReportBatch as SalaryReportBatchModel;
use Throwable;
use Wenhsun\Salary\Entity\SalaryReportBatch;
use Wenhsun\Salary\Entity\SalaryReportEmployee;
use Wenhsun\Salary\Exception\SalaryReportServiceException;
use Yii;

class SalaryReportService
{
    public function addBatch(SalaryReportBatch $ent)
    {
        $now = Common::now();
        $tx = Yii::app()->db->beginTransaction();
        try {

            $salaryReportBatchModel = new SalaryReportBatchModel();
            $salaryReportBatchModel->batch_id = $ent->getBatchId();
            $salaryReportBatchModel->create_at = $now;
            $salaryReportBatchModel->update_at = $now;
            $salaryReportBatchModel->save();

            if ($salaryReportBatchModel->hasErrors()) {
                throw new SalaryReportServiceException(serialize($salaryReportBatchModel->getErrors()));
            }

            $cnt = 0;
            foreach ($ent->getEmployees() as $employeeEnt) {

                /** @var SalaryReportEmployee $employeeEnt */

                $salaryReportModel = new SalaryReport();
                $salaryReportModel->id = $employeeEnt->getId();
                $salaryReportModel->employee_id = $employeeEnt->getEmployeeId();
                $salaryReportModel->salary = $employeeEnt->getSalary();
                $salaryReportModel->draft_allowance = $employeeEnt->getDraftAllowance();
                $salaryReportModel->traffic_allowance = $employeeEnt->getTrafficAllowance();
                $salaryReportModel->overtime_wage = $employeeEnt->getOvertimeWage();
                $salaryReportModel->project_allowance = $employeeEnt->getProjectAllowance();
                $salaryReportModel->taxable_salary_total = $employeeEnt->calcTaxableSalaryTotal();
                $salaryReportModel->tax_free_overtime_wage = $employeeEnt->getTaxFreeOvertimeWage();
                $salaryReportModel->salary_total = $employeeEnt->calcSalaryTotal();
                $salaryReportModel->health_insurance = $employeeEnt->getHealthInsurance();
                $salaryReportModel->labor_insurance = $employeeEnt->getLaborInsurance();
                $salaryReportModel->pension = $employeeEnt->getPension();
                $salaryReportModel->deduction_total = $employeeEnt->calcDeductionTotal();
                $salaryReportModel->real_salary = $employeeEnt->calcRealSalary();
                $salaryReportModel->status = SalaryReportEmployee::NOT_SET_SALARY_YET;
                $salaryReportModel->create_at = $now;
                $salaryReportModel->update_at = $now;

                $salaryReportModel->save();

                if ($salaryReportModel->hasErrors()) {
                    throw new SalaryReportServiceException(serialize($salaryReportModel->getErrors()));
                }

                $cnt++;
                echo "inserted {$employeeEnt->getEmployeeId()} salary in report\n";
            }

            $tx->commit();

            echo "inserted {$cnt} empoyees\n";

        } catch (Throwable $ex) {
            $tx->rollback();
            echo $ex->getMessage();
            echo $ex->getTraceAsString();
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($ex->getTraceAsString(), CLogger::LEVEL_ERROR);
        }
    }
}