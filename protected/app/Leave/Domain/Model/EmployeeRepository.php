<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

use DateTime;

interface EmployeeRepository
{
    /**
     * @param DateTime $nowDate
     * @return Employee[]
     */
    public function getEmployees(DateTime $nowDate): array;
}