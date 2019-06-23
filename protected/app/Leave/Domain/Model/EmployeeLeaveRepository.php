<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

interface EmployeeLeaveRepository
{
    public function save(Leave $leave): void;

    public function isSetAnnualLeave(EmployeeId $employeeId, string $annualYear): bool;
}