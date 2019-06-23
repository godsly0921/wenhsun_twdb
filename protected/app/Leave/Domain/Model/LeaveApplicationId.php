<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

use Wenhsun\Tool\Uuid;

class LeaveApplicationId
{
    private $leaveApplicationId;

    public function __construct(?string $leaveApplicationId = null)
    {
        $this->leaveApplicationId = $leaveApplicationId ?? Uuid::gen();
    }

    public function value(): string
    {
        return $this->leaveApplicationId;
    }
}