<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Application;

use DateTime;
use Wenhsun\Leave\Domain\Model\Minute;

class EmployeeLeaveService
{
    public function queryEmployeeLeaveSum(DateTime $startDate, DateTime $endDate, string $type): Minute
    {
        return new Minute(30);
    }
}