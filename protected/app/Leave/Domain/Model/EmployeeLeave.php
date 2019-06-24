<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

class EmployeeLeave
{
    private $employeeId;

    private $leaves;

    public function __construct(
        EmployeeId $employeeId,
        array $leaves
    ) {
        $this->employeeId = $employeeId;
        $this->leaves = $leaves;
    }

    public function getEmployeeId(): EmployeeId
    {
        return $this->employeeId;
    }

    public function fetchLeaves(): array
    {
        return $this->leaves;
    }
}