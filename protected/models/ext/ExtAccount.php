<?php
class ExtAccount extends Account
{
	public function account_list(){
		return Account::model() -> findAll(array(
		'select' => 'id,user_account,account_name,account_group,make_time,account_type',
		'order' => 'account_group ASC' ,
		));
	}

    /**
     * @param array $inputs
     * @return Account
     * @throws CDbException
     */
    public function create(array $inputs)
    {
        $account = new Account;

        $account->user_account = $inputs["user_account"];
        $account->password = $inputs["password"];
        $account->account_name = $inputs["account_name"];
        $account->account_group = $inputs["account_group"];
        $account->make_time = date("Y-m-d H:i:s");
        $account->account_type = $inputs["account_type"];

        if ($account->validate()) {
            $account->password = md5($account->password);
            $account->insert();
        }

        return $account;
    }
	
	/*
	 * 
	 * 判斷account_name是否有值存在
	 * 
	 */
	public function account_name_exists($user_account)
    {
		$result = Account::model()->findAll(array(
		'select' => 'user_account',
		'condition'=>'user_account=:user_account',
			'params'=>array(
				':user_account'=>$user_account,
			)
		));

        return ($result == false) ? false : true;
	}
	
	/**
	 * 登入
	 */
	public static function findByUsernameAndPassword($user_account, $password){
		return Account::model()->find(array(
			'condition'=>'user_account=:user_account and password=:password',
			'params'=>array(
				':user_account'=>$user_account,
				':password'=>md5($password),
			)
		));
	}

    public static function findByUserAccount($userAccount)
    {
        return Account::model()->find([
            'condition'=>'user_account=:user_account',
            'params' => [
                ':user_account' => $userAccount
            ]
        ]);
    }

	/**
	 * 更新帳戶資料
	 */
	public function accountUpdate(array $inputs)
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

    public function updateAccountPassword(array $inputs)
    {
        $accounts = ExtAccount::model()->findByPk($inputs["id"]);
        $accounts->password = $inputs["password"];

        if ($accounts->validate()) {
            $accounts->password = md5($accounts->password);
            $accounts->update();
        }

        return $accounts;
    }

	public static function getAccountData($account_id){
		$accounts = Account::model() -> findByPk($account_id);
		return $accounts;
	}

	/**
	 *	
	 * 找帳號id
	 */
	public function findByAccountId($user_account){
		return Account::model() -> find(array(
		'select' => 'id',
		'condition'=>'user_account=:user_account',
			'params'=>array(
				':user_account'=>$user_account,
			)
		));
	}

	public function powers($power_master_number){
		$group = ExtGroup::findByGroupNumber($this->account_group);
		$results = array();
		if(isset($group)){
			$results = $group->powers($power_master_number);
		}
		return $results;
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
