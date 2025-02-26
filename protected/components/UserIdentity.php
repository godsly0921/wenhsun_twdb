<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
    //Original function
    public function authenticate(){
    	$memberService = new MemberService();
    	$member = $memberService->findByAccount($this->username);
    	$member = $member[0];
        if(!$member){
        	Yii::log("account login::member is null");
            Yii::app()->session['message'] = '找不到該帳號。';
           	$this->errorCode=self::ERROR_USERNAME_INVALID;
           	return false;
        }else{
        	if($member->active == "Y"){
        		if(md5($this->password) == $member->password){
					$this->errorCode=self::ERROR_NONE;
					Yii::log('login success');
					Yii::app()->session['member_id'] = $member->id;//會員帳號ID
	                Yii::app()->session['member_account'] = $member->account;//會員帳號
	                Yii::app()->session['member_name'] = $member->name;//會員名稱
					return true;
				}else{
					Yii::log('Set login::password is error');
	                Yii::app()->session['message'] = '密碼錯誤';
					$this->errorCode=self::ERROR_USERNAME_INVALID;
					return false;
				}
        	}else{
        		Yii::log('Set login::password is error');
                Yii::app()->session['message'] = '帳號尚未驗證';
				$this->errorCode=self::ERROR_USERNAME_INVALID;
				return false;
        	}
    		
        }
	}
}
