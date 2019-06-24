<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model;

abstract class Leave
{
    private $minutes;
    private $type;

    abstract public function determineType(): string;

    public function __construct(int $minutes)
    {
        $this->minutes = $minutes;
        $this->type = $this->determineType();
    }

    public function getMinutes(): int
    {
        return $this->minutes;
    }

    public function getType(): string
    {
        return $this->type;
    }
}