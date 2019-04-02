<?php

class LevelonediscountService
{
    public function findLevelonediscount()
    {
        $result = Levelonediscount::model()->findAll();
        return $result;
    }

    /**
     * @param array $input
     * @return contact
     */
    public function create(array $inputs)
    {
        if (count($inputs['level']) >= 1) {
            foreach ($inputs['device'] as $key => $value) {
                /*foreach ($inputs['level_one_all'] as $k=>$v) {*/
                if (count($inputs["level"]) > 1 || count($inputs["weeks"]) > 1 || $inputs['start_hors'] != "" || $inputs['start_minute'] != "" || $inputs['end_hors'] != "" || $inputs['end_minute'] != "" || $inputs['discount'] != "") {
                    $model = new Levelonediscount();
                    $model->device_id = $value;

                    if ($inputs['level'] !== null) {
                        $model->level = json_encode($inputs['level']);
                    } else {
                        $model->level = 'null';
                    }

                    //  var_dump($model->level);

                    if ($inputs['weeks'] !== null) {
                        $model->weeks = json_encode($inputs['weeks']);
                    } else {
                        $model->weeks = 'null';
                    }
                    // var_dump($model->weeks);

                    if ($inputs['professor'] !== null) {
                        $model->professor = json_encode($inputs['professor']);
                    } else {
                        $model->professor = 'null';
                    }

                    //  var_dump($model->professor);

                    //  exit();
                    $model->start_hors = $inputs['start_hors'];
                    $model->start_minute = $inputs['start_minute'];
                    $model->end_hors = $inputs['end_hors'];
                    $model->end_minute = $inputs['end_minute'];
                    $model->discount = $inputs['discount'];
                    $model->builder = Yii::app()->session['uid'];
                    $model->create_time = date("Y-m-d H:i:s");
                    $model->edit_time = date("Y-m-d H:i:s");
                    $model->discount_start_time = $inputs['discount_start_time'];
                    $model->discount_end_time = $inputs['discount_end_time'];

                    if (!$model->validate()) {
                        return $model;
                    }

                    if (!$model->hasErrors()) {
                        $model->save();

                    } else {
                        $model = 'error';
                    }
                }

                /*  }*/
            }

        }

        return $model;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateLevelonediscount(array $inputs)
    {
        $model = Levelonediscount::model()->findByPk($inputs["id"]);

        if (count($inputs["level"]) > 1 || count($inputs["weeks"]) > 1 || $inputs['start_hors'] != "" || $inputs['start_minute'] != "" || $inputs['end_hors'] != "" || $inputs['end_minute'] != "" || $inputs['discount'] != "") {
            $model->device_id = $inputs['device_id'];

            if ($inputs['level'] !== null) {
                $model->level = json_encode($inputs['level']);
            } else {
                $model->level = 'null';
            }

            //  var_dump($model->level);

            if ($inputs['weeks'] !== null) {
                $model->weeks = json_encode($inputs['weeks']);
            } else {
                $model->weeks = 'null';
            }
            // var_dump($model->weeks);

            if ($inputs['professor'] !== null) {
                $model->professor = json_encode($inputs['professor']);
            } else {
                $model->professor = 'null';
            }

            //  var_dump($model->professor);

            //  exit();
            $model->start_hors = $inputs['start_hors'];
            $model->start_minute = $inputs['start_minute'];
            $model->end_hors = $inputs['end_hors'];
            $model->end_minute = $inputs['end_minute'];
            $model->discount = $inputs['discount'];
            $model->builder = Yii::app()->session['uid'];
            $model->edit_time = date("Y-m-d H:i:s");
            $model->discount_start_time = $inputs['discount_start_time'];
            $model->discount_end_time = $inputs['discount_end_time'];

            if (!$model->validate()) {
                return $model;
            }

            if (!$model->hasErrors()) {
                $model->save();

            } else {
                $model->addError('error', '錯誤');
                Yii::app()->session['error_msg'] = $model->getErrors();
            }
        } else {
            $model->addError('error', '欄位未正確填寫');
            Yii::app()->session['error_msg'] = $model->getErrors();
        }

        return $model;
    }

    // 抓取該機台所有優惠
    public function get_by_devid( $devid ){

        $criteria = new CDbCriteria();
        $criteria ->addCondition("device_id = :devid ");
        $criteria->params = array(':devid' => $devid);

        $data = Levelonediscount::model()->findAll($criteria);
        
        return $data;
    }
    // 抓取指定機台與教授的優惠
    public function get_by_devid_and_professor( $devid, $professor ){
        $professor = '%' . $professor . '%';
        $criteria = new CDbCriteria();
        $criteria ->addCondition("device_id = :devid and professor like :professor");
        $criteria->params = array(':devid' => $devid,':professor'=>$professor);

        $data = Levelonediscount::model()->findAll($criteria);

        return $data;
    }
}