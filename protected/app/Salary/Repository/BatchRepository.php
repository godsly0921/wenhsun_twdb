<?php

declare(strict_types=1);

namespace Wenhsun\Salary\Repository;

use SalaryReport;
use SalaryReportBatch;

class BatchRepository
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
}