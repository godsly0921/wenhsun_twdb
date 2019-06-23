<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

class Employee
{
    private $employeeId;
    private $onBoardDate;

    public function __construct(
        EmployeeId $employeeId,
        string $onBoardDate
    ) {
        $this->employeeId = $employeeId;
        $this->onBoardDate = $onBoardDate;
    }

    public function getOnBoardDate(): string
    {
        return $this->onBoardDate;
    }

    public function getEmployeeId(): EmployeeId
    {
        return $this->employeeId;
    }
}