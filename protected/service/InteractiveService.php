<?php

class InteractiveService
{
    public static function findInteractive($episode_number)
    {
        $result = Interactive::model()->findAll(array(
            'select' => '*',
            'condition'=>'episode_number=:episode_number',
            'params'=>array(
                ':episode_number' => $episode_number,
            )
        ));

        return ($result === false) ? false : $result;
    }

    public static function findActorInteractive($actor_ids,$episode_ids)
    {

        $outputs_episode = explode(",",$episode_ids);
        $condition_in_episode = ['episode_number'=>$outputs_episode];

        $condition  = 'name LIKE :name';
        $params = array(':name' => "%$actor_ids%");
        $criteria = new CDbCriteria();
        $criteria->select = '*';
        $criteria->condition = $condition;
        $criteria->params = $params;
        if(count($condition_in_episode)>=1){
            foreach($condition_in_episode as $key=>$value){
                $criteria->addInCondition($key, $value);
            }
        }

        $criteria->order = 'event_number ASC';
        $result = Interactive::model()->findAll($criteria);

        if(count($result>=1)){
            $event_number = '';
            foreach($result as $key=>$value){
                $event_number= $event_number.','.$value->event_number;

            }
            $outputs = explode(",",$event_number);
            $condition_in = ['event_number'=>$outputs];

            $criteria2 = new CDbCriteria();
            $criteria2->select = '*';
            if(count($condition_in)>=1){
                foreach($condition_in as $key=>$value){
                    $criteria2->addInCondition($key, $value);
                }
            }
            $criteria2->order = 'event_number ASC';
            $result2 = Interactive::model()->findAll($criteria2);

            if(count($result2)>=1){
                return $result2;


            }else{
                return false;
            }

        }else{
            return false;
        }



    }


    public static function findScenesInteractive($scenes_ids,$episode_ids)
    {

        $outputs_episode = explode(",",$episode_ids);
        $condition_in_episode = ['episode_number'=>$outputs_episode];


        $condition  = 'name LIKE :name';
        $params = array(':name' => "%$scenes_ids%");
        $criteria = new CDbCriteria();
        $criteria->select = '*';
        $criteria->condition = $condition;
        $criteria->params = $params;
        if(count($condition_in_episode)>=1){
            foreach($condition_in_episode as $key=>$value){
                $criteria->addInCondition($key, $value);
            }
        }
        $criteria->order = 'event_number ASC';
        $result = Interactive::model()->findAll($criteria);


        if(count($result>=1)){

            $event_number = '';

            foreach($result as $key=>$value){
                $event_number= $event_number.','.$value->event_number;

            }

            $outputs = explode(",",$event_number);
            $condition_in = ['event_number'=>$outputs];

            $criteria2 = new CDbCriteria();
            $criteria2->select = '*';
            if(count($condition_in)>=1){
                foreach($condition_in as $key=>$value){
                    $criteria2->addInCondition($key, $value);
                }
            }
            $criteria2->order = 'event_number ASC';
            $result2 = Interactive::model()->findAll($criteria2);

            if(count($result2)>=1){

                return $result2;

            }else{
                return false;
            }

        }else{
            return false;
        }



    }

    public static function findEpisodeInteractive($episode_ids)
    {
        $episode =['000'=>'EP00',
            '001'=>'EP01',
            '002'=>'EP02',
            '003'=>'EP03',
            '004'=>'EP04',
            '005'=>'EP05',
            '006'=>'EP06',
            '007'=>'EP07',
            '008'=>'EP08',
            '009'=>'EP09',
            '010'=>'EP10',
            '011'=>'EP11',
            '012'=>'EP12',
            ''=>'',
        ];

        $condition  = 'episode_number LIKE :episode_number';
        $params = array(':episode_number' => "%$episode[$episode_ids]%");
        $criteria = new CDbCriteria();
        $criteria->select = '*';
        $criteria->condition = $condition;
        $criteria->params = $params;
        /*if(count($condition_in)>=1){
            foreach($condition_in as $key=>$value){
                $criteria->addInCondition($key, $value);
            }
        }*/
        $criteria->order = 'event_number ASC';
        $result = Interactive::model()->findAll($criteria);




        if(count($result>=1)){

            $event_number = '';

            foreach($result as $key=>$value){
                $event_number= $event_number.','.$value->event_number;

            }

            $outputs = explode(",",$event_number);
            $condition_in = ['event_number'=>$outputs];

            $criteria2 = new CDbCriteria();
            $criteria2->select = '*';
            if(count($condition_in)>=1){
                foreach($condition_in as $key=>$value){
                    $criteria2->addInCondition($key, $value);
                }
            }
            $criteria2->order = 'event_number ASC';
            $result2 = Interactive::model()->findAll($criteria2);

            if(count($result2)>=1){
                return $result2;
            }else{
                return false;
            }

        }else{
            return false;
        }



    }

    public function find_interactive()
    {
        $result = Interactive::model()->findAll();
        return $result;
    }

    public function update(array $inputs)
    {
        $model = Interactive::model()->findByPk($inputs["id"]);

        $model->event_number = $inputs["event_number"];
        $model->episode_number  = $inputs["episode_number"];
        $model->event = $inputs["event"];
        $model->enter_time = $inputs["enter_time"];
        $model->out_time = $inputs["out_time"];
        $model->color = $inputs["color"];
        $model->mode = $inputs["mode"];
        $model->name = $inputs["name"];
        $model->weights = $inputs["weights"];
        $model->quantity = $inputs["quantity"];

        if ($model->validate()) {
            $model->update();
        }

        return $model;
    }

    /**
     * @param array $input
     * @return Power
     */
    public function create(array $inputs)
    {
        $model = new Interactive();
        $model->event_number = $inputs["event_number"];
        $model->episode_number  = $inputs["episode_number"];
        $model->event = $inputs["event"];
        $model->enter_time = $inputs["enter_time"];
        $model->out_time = $inputs["out_time"];
        $model->color = $inputs["color"];
        $model->mode = $inputs["mode"];
        $model->name = $inputs["name"];
        $model->weights = $inputs["weights"];
        $model->quantity = $inputs["quantity"];

        if (!$model->validate()) {
            return $model;
        }


        if (!$model->hasErrors()) {
            $success = $model->save();
        }

        if ($success === false) {
            $model->addError('save_fail', '新增參數失敗');
            return $model;
        }

        return $model;
    }


}
