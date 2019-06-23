<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Service;

use DateTime;
use Mockery;
use PHPUnit\Framework\TestCase;
use Wenhsun\Leave\Domain\Model\AnnualLeave;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Model\EmployeeLeaveRepository;
use Wenhsun\Leave\Domain\Model\EmployeeRepository;

class EmployeeAnnualLeaveCalculatorTest extends TestCase
{
    /**
     * @dataProvider onBoardDataProvider
     * @param $onBoardDate
     * @param $expectLeaveDay
     */
    public function testCalculate($onBoardDate, $expectLeaveDay): void
    {
        $employee = new Employee(
            new EmployeeId('ID'),
            $onBoardDate
        );

        $fakeEmployeeRepository = Mockery::mock(EmployeeRepository::class);
        $fakeEmployeeRepository->allows(['getEmployees' => [$employee]]);

        $fakeEmployeeLeaveRepository = Mockery::spy(EmployeeLeaveRepository::class);

        $sut = new EmployeeAnnualLeaveCalculator($fakeEmployeeRepository, $fakeEmployeeLeaveRepository);

        $sut->calculate(new DateTime('2019/01/01'));

        $fakeEmployeeLeaveRepository->shouldHaveReceived('save')->once()
            ->withArgs(function($leave) use ($expectLeaveDay) {

                /** @var $leave AnnualLeave */

                $this->assertEquals($expectLeaveDay * 8 * 60, $leave->getMinutes());

                return true;
            });
    }

    public function onBoardDataProvider(): array
    {
        return [
            '滿半年3天' => ['2018/06/01', 3],
            '滿一年7天' => ['2018/01/01', 7],
        ];
    }
}
