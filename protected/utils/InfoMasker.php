<?php
/**
 * Author: Eric G. Huang
 * Date: 7/10/15 1:41 AM
 */


class InfoMasker
{
    private $maskSign = '*';

    /**
     * @param $maskSign
     */
    public function setMaskSign($maskSign)
    {
        $this->maskSign = $maskSign;
    }

    /**
     * @param $input
     * @param $start
     * @param $length
     * @return string
     */
    public function doMask($input, $start, $length)
    {
        $output = $input;

        if (mb_strlen($input) > 0) {
            $mask = str_repeat($this->maskSign, $length);
            $begin = mb_substr($input, 0, $start);
            $end = mb_substr($input, $start + $length);

            $output = $begin . $mask . $end;
        }

        return $output;
    }

    /**
     * @param $name
     * @return string
     */
    public function maskName($name)
    {
        $length = strlen($name);
        $unicodeLength = mb_strlen($name);

        if ($length === $unicodeLength) {
            //English only
            $maskLength = mb_strlen($name) - 6;

            if ($maskLength < 0 ) {
                $maskLength = $length - 1;
                $result = $this->doMask($name, 1, $maskLength);
            } else {
                $result = $this->doMask($name, 3, $maskLength);
            }

        } else if ($length % $unicodeLength === 0 && $length % 3 === 0) {
            //Chinese only
            if ($unicodeLength > 3) {
                $result = $this->doMask($name, 2, mb_strlen($name) - 3);
            } else {
                $result = $this->doMask($name, 1, 1);
            }

        } else {
            //English and Chinese
            $result = $this->doMask($name, 1, mb_strlen($name) - 3);
        }

        return $result;
    }

    /**
     * @param $id
     * @return string
     */
    public function maskPersonId($id)
    {
        $start = 4;
        $result = $this->doMask($id, $start, mb_strlen($id) - $start);

        return $result;
    }

    /**
     * @param $birth
     * @return string
     */
    public function maskBirth($birth)
    {
        $year = str_repeat($this->maskSign, 4);
        $month = str_repeat($this->maskSign, 2);
        $day = str_repeat($this->maskSign, 2);

        $birthMask = "{$year}/{$month}/{$day}";

        return $birthMask;
    }

    public function maskPhone($phone)
    {
        $phoneNumber = str_replace('-', '', $phone);
        $start = 5;
        $maskResult = $this->doMask($phoneNumber, $start, mb_strlen($phoneNumber) - $start - 2);

        if (strpos($phone, '-') !== false) {

            $phoneSplit = explode('-', $phone);
            $result = '';
            $startPosition = 0;
            foreach ($phoneSplit as $number) {
                $length = mb_strlen($number);
                $result .= mb_substr($maskResult, $startPosition, $length) . '-';
                $startPosition = $startPosition + $length;
            }

            $result = rtrim($result, '-');
        } else {
            $result = $maskResult;
        }

        return $result;
    }

    public function maskMobile($mobile)
    {
        $start = 4;
        $result = $this->doMask($mobile, $start, mb_strlen($mobile) - $start - 3);

        return $result;
    }

    public function maskEmail($email)
    {
        list($emailMember, $emailCompany) = array_pad(explode('@', $email, 2), 2, null);

        $start = floor(mb_strlen($emailMember) / 2);

        $emailMemberMask = $this->doMask($emailMember, $start, mb_strlen($emailMember) - $start);

        $result = $emailMemberMask . '@' . $emailCompany;

        return $result;
    }

    public function maskAddress($address)
    {
        $start = floor(mb_strlen($address) / 3);
        $result = $this->doMask($address, $start, mb_strlen($address) - $start);

        return $result;
    }

}