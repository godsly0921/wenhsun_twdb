<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

class AnnualLeaveType extends LeaveType
{
    public function type(): string
    {
        return 'ANNUAL';
    }
}