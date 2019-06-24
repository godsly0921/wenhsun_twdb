<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

class LeaveRecord
{
    private $employeeId;
    private $annualYear;
    private $leaves;

    public function __construct(
        EmployeeId $employeeId,
        string $annualYear,
        array $leaves
    ) {
        $this->employeeId = $employeeId;
        $this->annualYear = $annualYear;
        $this->leaves = $leaves;
    }

    public function getEmployeeId(): EmployeeId
    {
        return $this->employeeId;
    }

    public function getAnnualYear(): string
    {
        return $this->annualYear;
    }

    public function getLeaves(): array
    {
        return $this->leaves;
    }
}