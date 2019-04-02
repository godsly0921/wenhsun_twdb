<?php

/**
 * Author: Eric G. Huang
 * Date: 7/10/15 1:14 PM
 */
class AdviserValidator
{
    /**
     * @param $name
     * @return bool
     */
    public function validateChineseName($name)
    {
        $result = (preg_match('/^[\x{4e00}-\x{9fa5}]*$/u', $name)) ? true : false;

        return $result;
    }

    /**
     * @param $name
     * @return bool
     */
    public function validateEnglishName($name)
    {
        $result = (mb_strlen($name) === strlen($name)) ? true : false;

        return $result;
    }


    public function validateBirth($birth)
    {
        $result = true;
        $birthDate = new DateTime($birth);

        if ($birthDate->format('Y') < 1800) {
            $result = false;
        }

        return $result;
    }

    public function validatePhone($phone)
    {
        $phone = str_replace('-', '', $phone);
        $result = (preg_match('/^[0-9]*$/i', $phone)) ? true : false;

        return $result;
    }
    
    public function validateEmail($email)
    {
        $result = (filter_var($email, FILTER_VALIDATE_EMAIL)) ? true : false;

        return $result;
    }
}