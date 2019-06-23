<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

abstract class Leave
{
    protected $employeeId;
    protected $annualYear;
    protected $leaveType;
    protected $status;
    protected $minutes;

    public function __construct(
        EmployeeId $employeeId,
        string $annualYear,
        string $status,
        int $minutes
    ) {
        $this->leaveType = $this->determineLeaveType();
        $this->employeeId = $employeeId;
        $this->annualYear = $annualYear;
        $this->status = $status;
        $this->minutes = $minutes;
    }

    abstract protected function determineLeaveType(): string;

    public function getEmployeeId(): EmployeeId
    {
        return $this->employeeId;
    }

    public function getAnnualYear(): string
    {
        return $this->annualYear;
    }

    public function getLeaveType(): string
    {
        return $this->leaveType;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getMinutes(): int
    {
        return $this->minutes;
    }
}