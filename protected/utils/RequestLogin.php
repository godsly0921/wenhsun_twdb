<?php
/**
 * @author Neil Kuo
 * @copyright Neil Kuo
 * @since 2015-07-07
 * @return 請求登入,檢查SESSION是否存在
 */
class RequestLogin
{
	public static function checkLogin($action)
    {

		if ($action->id != 'login' && $action->id != 'auth') {

			if (!isset(Yii::app()->session["pid"])) {
				return false;
			}else{
                return true;
            }

            if (!isset(Yii::app()->session["mem_id"])) {
                return false;
            }else{
                return true;
            }
            if (!isset(Yii::app()->session["adv_id"])) {
                return false;
            }else{
                return true;
            }

            $isAllow = false;
            $powers = CJSON::decode((Yii::app()->session['power_session_jsons']));
            $controllerAction = Yii::app()->controller->id . '/' . $action->id;
            foreach ($powers as $power) {
                if ($controllerAction === $power['power_controller']) {
                    $isAllow = true;
                }
            }
            return $isAllow;

		} else {
			return true;
		}
	}

}