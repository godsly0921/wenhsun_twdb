<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

use PHPUnit\Framework\TestCase;

class AnnualLeaveTest extends TestCase
{
    public function testAddMinutes(): void
    {
        $annualLeave = new AnnualLeave(30);
        $newAnnualLeave = $annualLeave->addMinutes(30);

        $this->assertEquals(60, $newAnnualLeave->getMinutes());
    }
}
