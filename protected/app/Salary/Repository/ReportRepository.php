<?php

declare(strict_types=1);

namespace Wenhsun\Salary\Repository;

use CLogger;
use Common;
use SalaryReport;
use SalaryReportBatch;
use Throwable;
use Wenhsun\Salary\Entity\SalaryReportBatch as SalaryReportBatchEnt;
use Wenhsun\Salary\Entity\SalaryReportEmployee;
use Wenhsun\Salary\Exception\SalaryReportRepositoryException;
use Yii;

class ReportRepository
{
    public function fetchBatch()
    {
        return SalaryReportBatch::model()->byUpdateAt()->findAll();
    }

    public function fetchEmployeeByBatch($batchId)
    {
        return SalaryReport::model()->findAll([
            'condition' => 'batch_id=:batch_id',
            'params' => [
                ':batch_id' => $batchId,
            ]
        ]);
    }

    public function fetchByBatchAndEmployeeId($batchId, $employeeId)
    {
        return SalaryReport::model()->find([
            'condition' => 'batch_id=:batch_id AND employee_id=:employee_id',
            'params' => [
                ':batch_id' => $batchId,
                ':employee_id' => $employeeId,
            ]
        ]);
    }

    public function findById($id): ?SalaryReportEmployee
    {

        $model = SalaryReport::model()->findByPk($id);

        if (!$model) {
            return null;
        }

        return new SalaryReportEmployee(
            $model->id,
            $model->batch_id,
            $model->employee_id,
            $model->employee_login_id,
            $model->employee_name,
            $model->employee_department,
            $model->employee_position,
            $model->salary,
            $model->draft_allowance,
            $model->traffic_allowance,
            $model->overtime_wage,
            $model->project_allowance,
            $model->tax_free_overtime_wage,
            $model->health_insurance,
            $model->labor_insurance,
            $model->pension,
            $model->memo,
            $model->other_plus,
            $model->other_minus
        );
    }

    public function updateEmployeeSalary(SalaryReportEmployee $ent): void
    {
        try {
            $now = Common::now();

            $model = SalaryReport::model()->findByPk($ent->getId());

            $model->salary = $ent->getSalary();
            $model->draft_allowance = $ent->getDraftAllowance();
            $model->traffic_allowance = $ent->getTrafficAllowance();
            $model->overtime_wage = $ent->getOvertimeWage();
            $model->project_allowance = $ent->getProjectAllowance();
            $model->taxable_salary_total = $ent->calcTaxableSalaryTotal();
            $model->tax_free_overtime_wage = $ent->getTaxFreeOvertimeWage();
            $model->memo = $ent->getMemo();
            $model->other_plus = $ent->getOtherPlus();
            $model->other_minus = $ent->getOtherMinus();
            $model->salary_total = $ent->calcSalaryTotal();
            $model->health_insurance = $ent->getHealthInsurance();
            $model->labor_insurance = $ent->getLaborInsurance();
            $model->pension = $ent->getPension();
            $model->deduction_total = $ent->calcDeductionTotal();
            $model->real_salary = $ent->calcRealSalary();
            $model->status = SalaryReportEmployee::SET_SALARY;
            $model->update_at = $now;

            $model->update();

            if ($model->hasErrors()) {
                throw new SalaryReportRepositoryException(serialize($model->getErrors()));
            }

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            throw new SalaryReportRepositoryException($ex->getMessage());
        }
    }

    public function forAllEmployeeByBatch(string $batchId): ?SalaryReportBatchEnt
    {
        $result = Yii::app()->db->createCommand(
            '
              SELECT * FROM salary_report_batch b
              INNER JOIN salary_report e ON b.batch_id = e.batch_id
              WHERE b.batch_id = :batch_id
            '
        )->bindValues([
            ':batch_id' => $batchId,
        ])
        ->queryAll();

        if (!$result) {
            return null;
        }

        return $this->makeSalaryReportBatchEnt($batchId, $result);
    }

    public function forRangeEmployeeByBatch($batchId, $id): ?SalaryReportBatchEnt
    {
        $bindValues = [':batch_id' => $batchId];
        $in = [];

        foreach ($id as $inx => $val) {
            $bindValues[":id_{$inx}"] = $val;
            $in[] = ":id_{$inx}";
        }

        $inCond = implode(',', $in);

        $sql = "
        SELECT * FROM salary_report_batch b
        INNER JOIN salary_report e ON b.batch_id = e.batch_id
        WHERE b.batch_id = :batch_id
        AND e.id IN ({$inCond})
        ";

        $result = Yii::app()->db->createCommand($sql)->bindValues($bindValues)->queryAll();

        if (!$result) {
            return null;
        }

        return $this->makeSalaryReportBatchEnt($batchId, $result);
    }

    private function makeSalaryReportBatchEnt($batchId, array $data): SalaryReportBatchEnt
    {
        $salaryReportBatch = new SalaryReportBatchEnt($batchId);

        foreach ($data as $row) {
            $salaryReportEmployee = new SalaryReportEmployee(
                $row['id'],
                $batchId,
                $row['employee_id'],
                $row['employee_login_id'],
                $row['employee_name'],
                $row['employee_department'],
                $row['employee_position'],
                (float)$row['salary'],
                $row['draft_allowance'],
                $row['traffic_allowance'],
                $row['overtime_wage'],
                $row['project_allowance'],
                $row['tax_free_overtime_wage'],
                (float)$row['health_insurance'],
                (float)$row['labor_insurance'],
                (float)$row['pension'],
                $row['memo'],
                (float)$row['other_plus'],
                (float)$row['other_minus']
            );

            $salaryReportBatch->addEmployee($salaryReportEmployee);
        }

        return $salaryReportBatch;
    }

    public function deleteBatch(string $batchId): void
    {
        $bindValues = [':batch_id' => $batchId];

        $sql = "
        DELETE FROM salary_report
        WHERE batch_id = :batch_id
        ";

        Yii::app()->db->createCommand($sql)->bindValues($bindValues)->execute();

        $sql = "
        DELETE FROM salary_report_batch
        WHERE batch_id = :batch_id
        ";

        Yii::app()->db->createCommand($sql)->bindValues($bindValues)->execute();
    }
}