<?php

class RequestLogin
{
	public static function checkLogin($action)
    {
        if ($action->id == 'login' || $action->id == 'auth') {
            return true;
        }


        if (!isset(Yii::app()->session["pid"])) {
            return false;
        }

        /*$isAllow = false;
        $powers = CJSON::decode((Yii::app()->session['power_session_jsons']));
        $controllerAction = Yii::app()->controller->id . '/' . $action->id;

        foreach ($powers as $power) {
            if (strtolower($controllerAction) === strtolower($power['power_controller'])) {
                $isAllow = true;
            }
        }

        return $isAllow;*/
	 return true;
	}

}
