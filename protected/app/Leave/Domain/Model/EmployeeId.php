<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

class EmployeeId
{
    private $employeeId;

    public function __construct(string $employeeId)
    {
        $this->employeeId = $employeeId;
    }

    public function value(): string
    {
        return $this->employeeId;
    }
}