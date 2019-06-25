<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

abstract class LeaveType
{
    abstract public function type(): string;
}