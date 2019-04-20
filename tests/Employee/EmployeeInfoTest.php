<?php

declare(strict_types=1);

namespace Wenhsun\Employee;

use PHPUnit\Framework\TestCase;

class EmployeeInfoTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    private function makeSUT()
    {
        return new EmployeeInfo(new EmployeeId());
    }

    public function testSetPassword_MustHashIt()
    {
        $sut = $this->makeSUT();
        $sut->setPassword("1234");

        $this->assertEquals(md5("1234"), $sut->getPassword());
    }
}
