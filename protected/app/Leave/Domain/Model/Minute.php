<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

class Minute
{
    private $minutes;

    public function __construct(int $minutes)
    {
        $this->minutes = $minutes;
    }

    public function minutesValue(): int
    {
        return $this->minutes;
    }
}