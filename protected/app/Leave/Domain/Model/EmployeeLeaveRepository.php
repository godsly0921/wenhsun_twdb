<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

interface EmployeeLeaveRepository
{
    public function resetLeave(EmployeeLeave $employeeLeave): void;
    public function saveRecord(LeaveRecord $leave): void;
}