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
}
