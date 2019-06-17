<?php

declare(strict_types=1);

namespace Wenhsun\Author;

use Yii;

class AuthorService
{
    private const RESULT_LIMIT = 8000;

    public function queryAll()
    {
        $countTotal = Yii::app()->db->createCommand('SELECT count(*) as search_count FROM author')->queryAll();

        if ($countTotal[0]['search_count'] > self::RESULT_LIMIT) {
            return [];
        }

        return Yii::app()->db->createCommand('SELECT * FROM author')->queryAll();
    }

    public function queryByBirthYear(string $year, string $toYear): array
    {
        if (empty($toYear)) {
            $toYear = $year;
        }

        $countTotal = Yii::app()->db->createCommand(
            '
              SELECT count(*) as search_count FROM author 
              WHERE birth_year >= :year
              AND birth_year <= :toYear
            '
        )->bindValues([
            ':year' => $year,
            ':toYear' => $toYear,
        ])->queryAll();

        if ($countTotal[0]['search_count'] > self::RESULT_LIMIT) {
            return [];
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
        $countTotal = Yii::app()->db->createCommand(
            '
              SELECT count(*) as search_count FROM author 
              WHERE service LIKE :service
            '
        )->bindValues([
            ':service' => $service,
        ])->queryAll();

        if ($countTotal[0]['search_count'] > self::RESULT_LIMIT) {
            return [];
        }

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
        $countTotal = Yii::app()->db->createCommand(
            '
              SELECT count(*) as search_count FROM author 
              WHERE job_title LIKE :job_title
            '
        )->bindValues([
            ':job_title' => $jobTitle,
        ])->queryAll();

        if ($countTotal[0]['search_count'] > self::RESULT_LIMIT) {
            return [];
        }

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
        $countTotal = Yii::app()->db->createCommand(
            "
                SELECT count(*) as search_count FROM author 
                WHERE JSON_SEARCH(home_address, 'all', :home_address) IS NOT NULL
            "
        )->bindValues([
            ':home_address' => $address,
        ])->queryAll();

        if ($countTotal[0]['search_count'] > self::RESULT_LIMIT) {
            return [];
        }

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
        $countTotal = Yii::app()->db->createCommand(
            "
              SELECT count(*) as search_count FROM author 
              WHERE JSON_SEARCH(identity_type, 'all', :identity_type) IS NOT NULL
            "
        )->bindValues([
            ':identity_type' => $identityType,
        ])->queryAll();

        if ($countTotal[0]['search_count'] > self::RESULT_LIMIT) {
            return [];
        }

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
        $countTotal = Yii::app()->db->createCommand(
            "
              SELECT count(*) as search_count FROM author 
              WHERE JSON_SEARCH(pen_name, 'all', :pen_name) IS NOT NULL
            "
        )->bindValues([
            ':pen_name' => $penName,
        ])->queryAll();

        if ($countTotal[0]['search_count'] > self::RESULT_LIMIT) {
            return [];
        }

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
        $countTotal = Yii::app()->db->createCommand(
            "
              SELECT count(*) as search_count FROM author 
              WHERE author_name LIKE :authorName
            "
        )->bindValues([
            ':authorName' => $authorName,
        ])->queryAll();

        if ($countTotal[0]['search_count'] > self::RESULT_LIMIT) {
            return [];
        }

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
        $count_total = Yii::app()->db->createCommand(
            '
              SELECT count(*) as search_count FROM author 
              WHERE memo LIKE :memo
            '
        )->bindValues([
            ':memo' => $memo,
        ])->queryAll();

        if ($count_total[0]['search_count'] > self::RESULT_LIMIT) {
            return [];
        }

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