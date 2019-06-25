<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Application;

class LeaveApplyCommand
{
    public $employeeId;
    public $startDate;
    public $endDate;
    public $type;
    public $memo;
    public $fileLocation;
}