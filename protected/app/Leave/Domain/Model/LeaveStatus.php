<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

abstract class LeaveStatus
{
    public const ALIVE = 'ALIVE';
    public const EXTEND = 'EXTEND';
    public const EXPIRE = 'EXPIRE';
}