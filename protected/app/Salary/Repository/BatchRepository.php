<?php

declare(strict_types=1);

namespace Wenhsun\Salary\Repository;

use SalaryReportBatch;

class BatchRepository
{
    public function fetchBatch()
    {
        return SalaryReportBatch::model()->byUpdateAt()->findAll();
    }
}