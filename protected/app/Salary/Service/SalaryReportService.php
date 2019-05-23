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
use Wenhsun\Salary\Repository\BatchRepository;
use Yii;

class SalaryReportService
{
    public function getBatchList()
    {
        $repo = new BatchRepository();
        return $repo->fetchBatch();
    }

    public function getListByBatch($batchId)
    {
        $repo = new BatchRepository();

        return $repo->fetchEmployeeByBatch($batchId);
    }

    public function setEmployeeSalary(SalaryReportEmployee $ent)
    {
        try {

            $now = Common::now();
            $repo = new BatchRepository();
            $salaryReportModel = $repo->fetchByBatchAndEmployeeId($ent->getBatchId(), $ent->getEmployeeId());

            $salaryReportModel->salary = $ent->getSalary();
            $salaryReportModel->draft_allowance = $ent->getDraftAllowance();
            $salaryReportModel->traffic_allowance = $ent->getTrafficAllowance();
            $salaryReportModel->overtime_wage = $ent->getOvertimeWage();
            $salaryReportModel->project_allowance = $ent->getProjectAllowance();
            $salaryReportModel->taxable_salary_total = $ent->calcTaxableSalaryTotal();
            $salaryReportModel->tax_free_overtime_wage = $ent->getTaxFreeOvertimeWage();
            $salaryReportModel->salary_total = $ent->calcSalaryTotal();
            $salaryReportModel->health_insurance = $ent->getHealthInsurance();
            $salaryReportModel->labor_insurance = $ent->getLaborInsurance();
            $salaryReportModel->pension = $ent->getPension();
            $salaryReportModel->deduction_total = $ent->calcDeductionTotal();
            $salaryReportModel->real_salary = $ent->calcRealSalary();
            $salaryReportModel->status = SalaryReportEmployee::SET_SALARY;
            $salaryReportModel->update_at = $now;

            $salaryReportModel->update();

            if ($salaryReportModel->hasErrors()) {
                throw new SalaryReportServiceException(serialize($salaryReportModel->getErrors()));
            }

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            throw new SalaryReportServiceException($ex->getMessage());
        }

    }

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
                $salaryReportModel->batch_id = $employeeEnt->getBatchId();
                $salaryReportModel->employee_id = $employeeEnt->getEmployeeId();
                $salaryReportModel->employee_login_id = $employeeEnt->getEmployeeLoginId();
                $salaryReportModel->employee_name = $employeeEnt->getEmployeeName();
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

            throw new SalaryReportServiceException($ex->getMessage());
        }
    }

    public function findByBatchAndEmployeeId($batchId, $employeeId)
    {
        $repo = new BatchRepository();

        return $repo->fetchByBatchAndEmployeeId($batchId, $employeeId);
    }
}