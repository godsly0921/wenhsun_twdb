<?php

declare(strict_types=1);

namespace Wenhsun\Author;

use Yii;

class AuthorService
{
    public function queryAll()
    {
        return Yii::app()->db->createCommand('SELECT * FROM author')->queryAll();
    }

    public function queryByBirthYear(string $year, string $toYear): array
    {
        if (empty($toYear)) {
            $toYear = $year;
        }

        return Yii::app()->db->createCommand(
            '
              SELECT * FROM author 
              WHERE birth_year >= :year
              AND birth_year <= :toYear
            '
        )->bindValues([
            ':year' => $year,
            ':toYear' => $toYear,
        ])->queryAll();
    }

    public function queryByService(string $service): array
    {
        return Yii::app()->db->createCommand(
            '
              SELECT * FROM author 
              WHERE service LIKE :service
            '
        )->bindValues([
            ':service' => $service,
        ])->queryAll();
    }

    public function queryByJobTitle(string $jobTitle): array
    {
        return Yii::app()->db->createCommand(
            '
              SELECT * FROM author 
              WHERE job_title LIKE :job_title
            '
        )->bindValues([
            ':job_title' => $jobTitle,
        ])->queryAll();
    }

    public function queryByAddress(string $address): array
    {
        return Yii::app()->db->createCommand(
            "
              SELECT * FROM author 
              WHERE JSON_SEARCH(home_address, 'all', :home_address) IS NOT NULL
            "
        )->bindValues([
            ':home_address' => $address,
        ])->queryAll();
    }

    public function queryByIdentityType(string $identityType): array
    {
        return Yii::app()->db->createCommand(
            "
              SELECT * FROM author 
              WHERE JSON_SEARCH(identity_type, 'all', :identity_type) IS NOT NULL
            "
        )->bindValues([
            ':identity_type' => $identityType,
        ])->queryAll();
    }

    public function queryByPenName($penName): array
    {
        return Yii::app()->db->createCommand(
            "
              SELECT * FROM author 
              WHERE JSON_SEARCH(pen_name, 'all', :pen_name) IS NOT NULL
            "
        )->bindValues([
            ':pen_name' => $penName,
        ])->queryAll();
    }

    public function queryByAuthorName($authorName): array
    {
        return Yii::app()->db->createCommand(
            '
              SELECT * FROM author 
              WHERE author_name LIKE :authorName
            '
        )->bindValues([
            ':authorName' => $authorName,
        ])->queryAll();
    }

    public function queryByMemo($memo): array
    {
        return Yii::app()->db->createCommand(
            '
              SELECT * FROM author 
              WHERE memo LIKE :memo
            '
        )->bindValues([
            ':memo' => $memo,
        ])->queryAll();
    }


}