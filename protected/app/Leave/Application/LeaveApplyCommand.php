<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Application;

class LeaveApplyCommand
{
    public $employeeId;
    public $type;
    public $startDate;
    public $endDate;
}