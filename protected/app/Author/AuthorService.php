<?php

declare(strict_types=1);

namespace Wenhsun\Author;

use Yii;

class AuthorService
{
    private static $result_limit = 8000;
    public function queryAll()
    {
        $count_total = Yii::app()->db->createCommand('SELECT count(*) as search_count FROM author')->queryAll();
        if($count_total[0]['search_count']<= self::$result_limit){
            return Yii::app()->db->createCommand('SELECT * FROM author')->queryAll();
        }else{
            return array();
        }
    }

    public function queryByBirthYear(string $year, string $toYear): array
    {
        if (empty($toYear)) {
            $toYear = $year;
        }
        $count_total = Yii::app()->db->createCommand(
            '
              SELECT count(*) as search_count FROM author 
              WHERE birth_year >= :year
              AND birth_year <= :toYear
            '
        )->bindValues([
            ':year' => $year,
            ':toYear' => $toYear,
        ])->queryAll();

        if($count_total[0]['search_count']<= 8000){
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
        }else{
            return array();
        }
    }

    public function queryByService(string $service): array
    {
        $count_total = Yii::app()->db->createCommand(
            '
              SELECT count(*) as search_count FROM author 
              WHERE service LIKE :service
            '
        )->bindValues([
            ':service' => $service,
        ])->queryAll();

        if($count_total[0]['search_count']<= 8000){
            return Yii::app()->db->createCommand(
                '
                  SELECT * FROM author 
                  WHERE service LIKE :service
                '
            )->bindValues([
                ':service' => $service,
            ])->queryAll();
        }else{
            return array();
        }
    }

    public function queryByJobTitle(string $jobTitle): array
    {
        $count_total = Yii::app()->db->createCommand(
            '
              SELECT count(*) as search_count FROM author 
              WHERE job_title LIKE :job_title
            '
        )->bindValues([
            ':job_title' => $jobTitle,
        ])->queryAll();

        if($count_total[0]['search_count']<= self::$result_limit){
            return Yii::app()->db->createCommand(
                '
                  SELECT * FROM author 
                  WHERE job_title LIKE :job_title
                '
            )->bindValues([
                ':job_title' => $jobTitle,
            ])->queryAll();
        }else{
            return array();
        }
    }

    public function queryByAddress(string $address): array
    {
        $count_total = Yii::app()->db->createCommand(
                "
                  SELECT count(*) as search_count FROM author 
                  WHERE JSON_SEARCH(home_address, 'all', :home_address) IS NOT NULL
                "
            )->bindValues([
                ':home_address' => $address,
            ])->queryAll();

        if($count_total[0]['search_count']<= self::$result_limit){
            return Yii::app()->db->createCommand(
                "
                  SELECT * FROM author 
                  WHERE JSON_SEARCH(home_address, 'all', :home_address) IS NOT NULL
                "
            )->bindValues([
                ':home_address' => $address,
            ])->queryAll();
        }else{
            return array();
        }
    }

    public function queryByIdentityType(string $identityType): array
    {
        $count_total = Yii::app()->db->createCommand(
            "
              SELECT count(*) as search_count FROM author 
              WHERE JSON_SEARCH(identity_type, 'all', :identity_type) IS NOT NULL
            "
        )->bindValues([
            ':identity_type' => $identityType,
        ])->queryAll();
        if($count_total[0]['search_count']<= self::$result_limit){
            return Yii::app()->db->createCommand(
                "
                  SELECT * FROM author 
                  WHERE JSON_SEARCH(identity_type, 'all', :identity_type) IS NOT NULL
                "
            )->bindValues([
                ':identity_type' => $identityType,
            ])->queryAll();
        }else{
            return array();
        }
        
    }

    public function queryByPenName($penName): array
    {
        $count_total = Yii::app()->db->createCommand(
            "
              SELECT count(*) as search_count FROM author 
              WHERE JSON_SEARCH(pen_name, 'all', :pen_name) IS NOT NULL
            "
        )->bindValues([
            ':pen_name' => $penName,
        ])->queryAll();
        if($count_total[0]['search_count']<= self::$result_limit){
            return Yii::app()->db->createCommand(
                "
                  SELECT * FROM author 
                  WHERE JSON_SEARCH(pen_name, 'all', :pen_name) IS NOT NULL
                "
            )->bindValues([
                ':pen_name' => $penName,
            ])->queryAll();
        }else{
            return array();
        }
    }

    public function queryByAuthorName($authorName): array
    {
        $count_total = Yii::app()->db->createCommand(
            "
              SELECT count(*) as search_count FROM author 
              WHERE author_name LIKE :authorName
            "
        )->bindValues([
            ':authorName' => "%" . $authorName . "%",
        ])->queryAll();
        if($count_total[0]['search_count']<= self::$result_limit){
            return Yii::app()->db->createCommand(
                '
                  SELECT * FROM author 
                  WHERE author_name LIKE :authorName
                '
            )->bindValues([
                ':authorName' =>  "%" . $authorName . "%",
            ])->queryAll();
        }else{
            return array();
        }
    }

    public function queryByMemo($memo): array
    {
        $count_total = Yii::app()->db->createCommand(
            '
              SELECT count(*) as search_count FROM author 
              WHERE memo LIKE :memo
            '
        )->bindValues([
            ':memo' => "%" . $memo . "%",
        ])->queryAll();
        if($count_total[0]['search_count']<= self::$result_limit){
            return Yii::app()->db->createCommand(
                '
                  SELECT * FROM author 
                  WHERE memo LIKE :memo
                '
            )->bindValues([
                ':memo' => "%" . $memo . "%",
            ])->queryAll();
        }else{
            return array();
        }
    }


}