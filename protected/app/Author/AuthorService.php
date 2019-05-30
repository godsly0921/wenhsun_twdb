<?php

declare(strict_types=1);

namespace Wenhsun\Author;

use Yii;

class AuthorService
{
    public function queryByBirthYear(string $year, string $toYear): array
    {
        return [];
    }

    public function queryByService(string $service): array
    {
        return Yii::app()->db->createCommand(
            '
              SELECT * FROM author 
              WHERE service LIKE :service
            '
        )->bindValues([
            ':service' => "{$service}%",
        ])
            ->queryAll();
    }

    public function queryByJobTitle(string $jobTitle): array
    {
        return Yii::app()->db->createCommand(
            '
              SELECT * FROM author 
              WHERE job_title LIKE :job_title
            '
        )->bindValues([
            ':job_title' => "{$jobTitle}%",
        ])
            ->queryAll();
    }

    public function queryByAddress(string $address): array
    {
        return Yii::app()->db->createCommand(
            "
              SELECT * FROM author 
              WHERE JSON_SEARCH(home_address, 'all', :home_address) IS NOT NULL
            "
        )->bindValues([
            ':home_address' => "{$address}%",
        ])
            ->queryAll();
    }

    public function queryByIdentityType(string $identityType): array
    {
        return [];
    }
}