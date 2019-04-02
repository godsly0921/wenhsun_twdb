<?php

class CalculationfeeService
{
    public function findCalculationfee()
    {
        $result = Calculationfee::model()->findAll();
        return $result;
    }

    /**
     * @param array $input
     * @return contact
     */
    public function create(array $inputs)
    {


        if (count($inputs['level_one'][0]) > 1) {
            foreach ($inputs['device'][0] as $key=>$value) {
                foreach ($inputs['level_one'][0] as $k=>$v) {

                    if($v['base_minute']!="" || $v['base_charge']!="" || $v['start_base_charge']!="" || $v['max_use_base']!="" || $v['unused_base']!=""){
                        $model = new Calculationfee();
                        $model->device_id = $value;
                        $model->level_one_id = $k;
                        $model->base_minute = $v['base_minute'];
                        $model->base_charge = $v['base_charge'];
                        $model->start_base_charge = $v['start_base_charge'];
                        $model->max_use_base = $v['max_use_base'];
                        $model->unused_base = $v['unused_base'];
                        $model->builder = Yii::app()->session['uid'];
                        $model->create_time = date("Y-m-d H:i:s");
                        $model->edit_time =date("Y-m-d H:i:s");

                        if (!$model->validate()) {
                            return $model;
                        }

                        if (!$model->hasErrors()) {
                            $model->save();

                        }else{
                            $model = 'error';
                        }
                    }

                }
            }

        }

        return $model;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateCalculationfee(array $inputs)
    {
        $model = Calculationfee::model()->findByPk($inputs["id"]);

        $model->id = $model->id;
        $model->device_id = $inputs['device_id'];
        $model->level_one_id = $inputs['level_one_id'];
        $model->base_minute = $inputs['base_minute'];
        $model->base_charge = $inputs['base_charge'];
        $model->start_base_charge = $inputs['start_base_charge'];
        $model->max_use_base = $inputs['max_use_base'];
        $model->unused_base = $inputs['unused_base'];

        if ($model->validate()) {
            $model->update();
        }

        return $model;
    }

    // 依照id 跟 會員分類抓取
    public function get_by_IdLv( $id , $lv ){
        
        $criteria = new CDbCriteria();
        $criteria ->addCondition("device_id = :id AND level_one_id = :lv");
        $criteria->params = array(':id' => $id , ':lv' => $lv );
        $data = Calculationfee::model()->findAll($criteria);

        if( $data ){

            return [ true  , $data[0]];

        }else{ 
      
            return [ false , "" ];
    
        }
    
    }
}