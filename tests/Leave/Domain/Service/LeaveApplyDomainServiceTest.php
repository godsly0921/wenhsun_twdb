<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Service;

use PHPUnit\Framework\TestCase;

class LeaveApplyDomainServiceTest extends TestCase
{
    /**
     * @dataProvider calcDateProvider
     * @param $startDate
     * @param $endDate
     * @param $expectedDate
     */
    public function testCalcMinutes($startDate, $endDate, $expectedDate): void
    {
        $sut = new LeaveApplyDomainService();

        $r = $sut->calcMinutes($startDate, $endDate);

        $this->assertEquals($expectedDate, $r);
    }

    public function calcDateProvider(): array
    {
        return [
            '2019/01/01 09:00:00 to 2019/01/01 18:00:00' => ['2019/01/01 09:00:00', '2019/01/01 18:00:00', 480],
            '2019/01/01 09:00:00 to 2019/01/02 12:00:00' => ['2019/01/01 09:00:00', '2019/01/02 12:00:00', 660],
            '2019/01/01 09:00:00 to 2019/01/02 13:30:00' => ['2019/01/01 09:00:00', '2019/01/01 13:30:00', 210],
            '2019/01/01 09:00:00 to 2019/01/02 09:30:00' => ['2019/01/01 09:00:00', '2019/01/01 09:30:00', 30],
        ];
    }
}
