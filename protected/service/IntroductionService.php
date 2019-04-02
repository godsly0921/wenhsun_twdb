<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 上午 10:46
 */
class IntroductionService
{
    public function findIntroductions()
    {
        $result = Introduction::model()->findAll();
        return $result;
    }

    public function create(array $inputs)
    {
        $introduction = new Introduction();

        $introduction->intro_language = $inputs["intro_language"];
        $introduction->intro_content = $inputs["intro_content"];
        $introduction->intro_createdate = date("Y-m-d H:i:s");


        if (!$introduction->validate()) {
            return $introduction;
        }

        if (!$introduction->hasErrors()) {
            $introduction->save();
        }

        return $introduction;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateIntroduction(array $inputs)
    {
        $introduction = Introduction::model()->findByPk($inputs["id"]);

        $introduction->id = $inputs["id"];
        $introduction->intro_content = $inputs["intro_content"];
        $introduction->intro_language = $inputs["intro_language"];

        if ($introduction->validate()) {
            $introduction->update();
        }

        return $introduction;
    }

    public function findByLang($lang)
    {
        $result = Introduction::model()->findAllByAttributes(
            ['intro_language' => $lang],
            ['order' => 'intro_createdate DESC']
        );

        return $result;
    }
}