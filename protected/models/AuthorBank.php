<?php

declare(strict_types=1);

class AuthorBank extends CActiveRecord
{
    public $id;
    public $author_id;
    public $bank_name;
    public $bank_code;
    public $branch_name;
    public $branch_code;
    public $bank_account;
    public $account_name;
    public $create_at;
    public $update_at;

    public function tableName()
    {
        return 'author_bank';
    }

    public function rules()
    {
        return [];
    }

    public function relations()
    {
        return [];
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}