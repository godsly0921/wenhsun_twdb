<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

class AnnualLeave extends Leave
{
    public function determineType(): string
    {
        return 'ANNUAL';
    }
}