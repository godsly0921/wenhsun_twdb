<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Service;

use DateTime;
use PHPUnit\Framework\TestCase;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeId;

class EmployeeLeaveCalculatorTest extends TestCase
{
    /**
     * @dataProvider employeeProvider
     * @param DateTime $now
     * @param Employee $employee
     * @param $expectedMinutes
     */
    public function testCalcAnnualLeaveInRecentYear(DateTime $now, Employee $employee, int $expectedMinutes): void
    {
        $sut = new EmployeeLeaveCalculator();

        $leaveMinutes = $sut->calcAnnualLeaveInRecentYear($now, $employee);

        $this->assertEquals($expectedMinutes, $leaveMinutes->minutesValue());
    }

    public function employeeProvider(): array
    {
        return [
            '不滿半年0天' => [
                new DateTime('2019/06/30'),
                new Employee(new EmployeeId('EP001'), '2019/01/01'),
                0,
            ],
            '滿半年3天' => [
                new DateTime('2019/07/01'),
                new Employee(new EmployeeId('EP001'), '2019/01/01'),
                3 * 8 * 60,
            ],
            '滿一年7天' => [
                new DateTime('2020/01/01'),
                new Employee(new EmployeeId('EP001'), '2019/01/01'),
                7 * 8 * 60,
            ],
            '超過25年一律30天' => [
                new DateTime('2045/01/01'),
                new Employee(new EmployeeId('EP001'), '2019/01/01'),
                30 * 8 * 60,
            ]
        ];
    }

    /**
     * @dataProvider getEmployeeNearlyAnnualLeaveDataProvider
     * @param string $onBoardDate
     * @param DateTime $now
     * @param string $expectedDate
     */
    public function testGetEmployeeNearlyAnnualLeaveStartDate(string $onBoardDate, DateTime $now, string $expectedDate): void
    {
        $employee = new Employee(new EmployeeId('EP001'), $onBoardDate);

        $sut = new EmployeeLeaveCalculator();

        $r = $sut->getEmployeeNearlyAnnualLeaveStartDate($employee, $now);

        $this->assertEquals($expectedDate, $r);
    }

    public function getEmployeeNearlyAnnualLeaveDataProvider(): array
    {
        return [
            '未滿1年' => [
                '2019/01/01',
                new DateTime('2019/12/31'),
                '2019-01-01'
            ],
            '大於1年' => [
                '2019/01/01',
                new DateTime('2020/01/02'),
                '2020-01-01'
            ],
            '大於2年' => [
                '2019/01/01',
                new DateTime('2021/01/02'),
                '2021-01-01'
            ],
        ];
    }

    /**
     * @dataProvider getTestCalcAnnualLeaveSummaryOnBoardDateCases
     * @param string $onBoardDate
     * @param DateTime $now
     * @param string $expectedDate
     */
    public function testCalcAnnualLeaveSummaryOnBoardDate(string $onBoardDate, DateTime $now, int $expectedLeaveMinutes): void
    {
        $employee = new Employee(new EmployeeId('EP001'), $onBoardDate);

        $sut = new EmployeeLeaveCalculator();

        $r = $sut->calcAnnualLeaveSummaryOnBoardDate($now, $employee);

        $this->assertEquals($expectedLeaveMinutes, $r->minutesValue());
    }


    public function getTestCalcAnnualLeaveSummaryOnBoardDateCases(): array
    {
        return [
            '未滿半年' => [
                '2019/01/01',
                new DateTime('2019/05/30'),
                0,
            ],
            '滿半年' => [
                '2019/01/01',
                new DateTime('2019/07/01'),
                1440
            ],
            '滿1年' => [
                '2019/01/01',
                new DateTime('2020/01/01'),
                4800,
            ],
            '滿25年' => [
                '2019/01/01',
                new DateTime('2044/01/01'),
                239040
            ],
            '滿30年' => [
                '2019/01/01',
                new DateTime('2049/01/01'),
                311040
            ],
        ];
    }
}
