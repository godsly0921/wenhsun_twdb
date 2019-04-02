<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 下午 05:07
 */
class RecruitmentService
{
    public function findRecruitment()
    {
        $result = Recruitment::model()->findAll();
        return $result;
    }

    /**
     * @param array $input
     * @return recruitment
     */
    public function create(array $inputs)
    {
        $recruitment = new Recruitment();
        $recruitment->rec_language = $inputs['rec_language'];
        $recruitment->rec_post = $inputs['rec_post'];
        $recruitment->rec_content = $inputs['rec_content'];
        $recruitment->rec_createtime = date("Y-m-d H:i:s");
        $recruitment->rec_type = $inputs['rec_type'];

        if (!$recruitment->validate()) {
            return $recruitment;
        }

        if (!$recruitment->hasErrors()) {
            $success = $recruitment->save();
        }

        if ($success === false) {
            $recruitment->addError('save_fail', '新增失敗');
            return $recruitment;
        }

        return $recruitment;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateRecruitment(array $inputs)
    {
        $recruitment = Recruitment::model()->findByPk($inputs["id"]);

        $recruitment->rec_language = $inputs['rec_language'];
        $recruitment->rec_post = $inputs['rec_post'];
        $recruitment->rec_content = $inputs['rec_content'];
        $recruitment->rec_createtime = date("Y-m-d H:i:s");
        $recruitment->rec_type = $inputs['rec_type'];

        if ($recruitment->validate()) {
            $recruitment->update();
        }

        return $recruitment;
    }


    public function findByLang($lang)
    {
        $result = Recruitment::model()->findAllByAttributes(
            ['rec_language' => $lang, 'rec_type' => 1],
            ['order' => 'rec_createtime DESC']
        );

        return $result;
    }

}