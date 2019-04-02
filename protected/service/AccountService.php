<?php
class AccountService
{
    public function findAccounts()
    {
        $accounts = Account::model()->findAll(array(
            'select' => 'id,user_account,account_name,account_group,make_time,account_type',
            'order' => 'account_group ASC' ,
        ));

        return $accounts;
    }

    /**
     * @param array $inputs
     * @return Account
     * @throws CDbException
     */
    public function create(array $inputs)
    {
        $account = new Account();

        $account->user_account = $inputs["user_account"];
        $account->password = $inputs["password"];
        $account->account_name = $inputs["account_name"];
        $account->account_group = $inputs["account_group"];
        $account->make_time = date("Y-m-d H:i:s");
        $account->account_type = $inputs["account_type"];

        if (!$account->validate()) {
            return $account;
        }

        if ($inputs["password_confirm"] === "" || $inputs["password"] !== $inputs["password_confirm"]) {
            $account->addError('password_confirm', '確認密碼錯誤, 請重新輸入');
            return $account;
        }

        if ($this->userAccountExist($account->user_account)) {
            $account->addError('account_exist', '帳號已存在');
            return $account;
        }

        if (!$account->hasErrors()) {
            $account->password = md5($account->password);
            $account->save();
        }

        return $account;
    }

    /**
     * @param $user_account
     * @return bool
     */
    public function userAccountExist($user_account)
    {
        $result = Account::model()->findAll(array(
            'select' => 'user_account',
            'condition'=>'user_account=:user_account',
            'params'=>array(
                ':user_account' => $user_account,
            )
        ));

        return ($result == false) ? false : true;
    }

    /**
     * @param $user_account
     * @param $password
     * @return CActiveRecord
     */
    public function findByUserAccountAndPassword($user_account, $password)
    {
        $account = Account::model()->find(array(
            'condition'=>'user_account=:user_account and password=:password',
            'params'=>array(
                ':user_account'=>$user_account,
                ':password'=>md5($password),
            )
        ));

        return $account;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateAccount(array $inputs)
    {
        $accounts = ExtAccount::model()->findByPk($inputs["id"]);

        $accounts->user_account = $inputs["user_account"];
        $accounts->account_name = $inputs["account_name"];
        $accounts->account_group = $inputs["account_group"];
        $accounts->account_type = $inputs["account_type"];

        if ($accounts->validate()) {
            $accounts->update();
        }

        return $accounts;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateAccountPassword(array $inputs)
    {
        $account = Account::model()->findByPk($inputs["id"]);
        $account->password = $inputs["password"];

        if (!$account->validate()) {
            return $account;
        }

        if ($inputs["password_confirm"] === "" || $inputs["password"] !== $inputs["password_confirm"]) {
            $account->addError('password_confirm', '確認密碼錯誤, 請重新輸入');
            return $account;
        }

        if (!$account->hasErrors()) {
            $account->password = md5($account->password);
            $account->update();
        }

        return $account;
    }

    /**
     * @param $account_id
     * @return CActiveRecord
     */
    public function findAccountData($account_id)
    {
        $accounts = Account::model()->findByPk($account_id);
        return $accounts;
    }

    /**
     * @param $user_account
     * @param null $select
     * @return CActiveRecord
     */
    public function findByUserAccount($user_account, $select = null)
    {
        $sqlSetting = [];

        if ($select !== null)
            $findArray['select'] = $select;

        $sqlSetting['condition'] = 'user_account=:user_account';
        $sqlSetting['params'] = [':user_account' => $user_account];

        $account = ExtAccount::model()->find($sqlSetting);

        return $account;
    }

}