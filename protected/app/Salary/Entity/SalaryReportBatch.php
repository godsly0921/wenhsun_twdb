<?php

declare(strict_types=1);

namespace Wenhsun\Salary\Entity;

class SalaryReportBatch
{
    private $batchId;

    private $employees;

    public function __construct(string $batchId)
    {
        $this->batchId = $batchId;
        $this->employees = [];
    }

    /**
     * @return string
     */
    public function getBatchId(): string
    {
        return $this->batchId;
    }

    public function addEmployee(SalaryReportEmployee $employee)
    {
        $this->employees[] = $employee;
    }

    public function getEmployees()
    {
        return $this->employees;
    }
}