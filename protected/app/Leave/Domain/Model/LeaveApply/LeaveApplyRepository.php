<?php

declare(strict_types=1);

namespace Wenhsun\Leave\Domain\Model\LeaveApply;

interface LeaveApplyRepository
{
    public function save(Application $application): void;
}