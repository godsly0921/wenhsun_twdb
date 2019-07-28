<?php

declare(strict_types=1);

namespace Wenhsun\Employee\Service;

class DepartmentQueryService
{
    public function getDepartments(): array
    {
        return [
            "文訊",
            "基金會",
            "紀州庵",
        ];
    }
}