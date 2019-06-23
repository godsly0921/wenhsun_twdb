<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

class AnnualLeave extends Leave
{
    protected function determineLeaveType(): string
    {
        return 'ANNUAL';
    }
}