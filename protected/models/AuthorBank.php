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
        return [
            ['bank_name', 'length', 'min' => 1, 'max' => 50, 'tooLong' => "銀行名稱最多50個字元"],
            ['bank_code', 'length', 'min' => 1, 'max' => 3, 'tooLong' => "銀行代碼最多3個字元"],
            ['branch_name', 'length', 'min' => 1, 'max' => 50, 'tooLong' => "分行名稱最多50個字元"],
            ['branch_code', 'length', 'min' => 1, 'max' => 8, 'tooLong' => "分行代碼最多8個字元"],
            ['bank_account', 'length', 'min' => 1, 'max' => 20, 'tooLong' => "帳號最多20個字元"],
            ['account_name', 'length', 'min' => 1, 'max' => 50, 'tooLong' => "戶名最多50個字元"],
        ];

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