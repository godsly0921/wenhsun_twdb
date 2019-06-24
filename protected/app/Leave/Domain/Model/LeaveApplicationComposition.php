<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

class LeaveApplicationComposition
{
    private $annualYear;
    private $minutes;

    public function __construct(string $annualYear, int $minutes)
    {
        $this->annualYear = $annualYear;
        $this->minutes = $minutes;
    }
}