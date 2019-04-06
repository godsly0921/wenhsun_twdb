<?php

/**
 * Created by PhpStorm.
 * User: EricG
 * Date: 7/4/15
 * Time: 10:54 AM
 */
class CsrfProtector
{
    public static function putToken($return = false)
    {   
        $token = sha1(uniqid(rand()));
        Yii::app()->session['_token'] = $token;

        if ($return === true){
            return $token;
        }

    }

    public static function getToken()
    {
        $token = isset(Yii::app()->session['_token']) ? Yii::app()->session['_token'] : null;

        return $token;
    }

    public static function genHiddenField()
    {
        $token = self::getToken();

        if ($token === null) {
            $token = static::putToken(true);
        }

        $hiddenField = '<input type="hidden" id="_token" name="_token" value="' . $token . '" />';

        echo $hiddenField;
    }

    public static function genUserToken()
    {
        $token = self::getToken();

        echo $token;
    }

    /**
     * @param $inputToken
     * @return bool
     */
    public static function compare($inputToken)
    {
        $result = false;

        $token = self::getToken();

        if ($token !== null) {
            if ($token === $inputToken) {
                $result = true;
            }
        }

        return $result;
    }

    public static function comparePost()
    {
        $inputToken = filter_input(INPUT_POST, '_token');

        $result = self::compare($inputToken);

        return $result;
    }


}