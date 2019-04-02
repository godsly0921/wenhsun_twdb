<?php
class BlackrecordService{

    // 根據日期或關鍵字抓出所有符合之異常紀錄
    public function findBlackrecordAndConditionDayAll($inputs){
        $criteria = new CDbCriteria;
        $criteria->select = '*';

      /*  var_dump($inputs);
       exit();*/

        //------------------------
        if($inputs["start"]!==" 00:00:00" && $inputs["end"]!==" 23:59:59" && $inputs["keyword"]==="" && $inputs["status"] ==="0"){

            $criteria->condition = "use_date >= :start AND use_date <= :end";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end']));

            $criteria ->order = "user_name ASC";


        }

        //預設查詢
        if($inputs["start"]!==" 00:00:00" && $inputs["end"]!==" 23:59:59" && $inputs["keyword"]==="" && $inputs["status"] ==="1"){

            $criteria->condition = "use_date >= :start AND use_date <= :end";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end']));

            $criteria ->order = "user_name ASC";

        }

        //預設查詢
        if($inputs["start"]!==" 00:00:00" && $inputs["end"]!==" 23:59:59" && $inputs["keyword"]==="" && $inputs["status"] ==="2"){

            $criteria->condition = "use_date >= :start AND use_date <= :end";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end']));

            $criteria ->order = "user_name ASC";

        }
        //------------------------

        //------------------------
        //關鍵字：使用者姓名＆日期
        if($inputs["start"]!==" 00:00:00" && $inputs["end"]!==" 23:59:59" && $inputs["keyword"]!=="" && $inputs["status"]  ==="0"){

            $criteria->condition = "use_date >= :start AND use_date <= :end AND user_name like :keyword";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end'],':keyword' =>'%'.$inputs['keyword'].'%'));

            $criteria ->order = "user_name ASC";


        }

        //關鍵字：卡號 ＆日期
        if($inputs["start"]!==" 00:00:00" && $inputs["end"]!==" 23:59:59" && $inputs["keyword"]!=="" && $inputs["status"] ==="1"){

            $criteria->condition = "date >= :start AND date <= :end AND card_number like :keyword";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end'],':keyword' =>'%'.$inputs['keyword'].'%'));

            $criteria ->order = "user_name ASC";


        }

        //僅關鍵字 : 儀器名稱
        if($inputs["start"]!==" 00:00:00" && $inputs["end"]!==" 23:59:59" && $inputs["keyword"]!=="" && $inputs["status"] ==="2"){

            $criteria->condition = "date >= :start AND date <= :end AND device_name like :keyword";

            $criteria->params=(array(':keyword' =>'%'.$inputs['keyword'].'%'));

            $criteria ->order = "user_name ASC";


        }
        //------------------------

        //------------------------
        //僅關鍵字 : 使用者姓名
        if($inputs["start"]===" 00:00:00" && $inputs["end"]=== " 23:59:59" && $inputs["keyword"]!=="" && $inputs["status"] ==="0"){

            $criteria->condition = "user_name like :keyword";

            $criteria->params=(array(':keyword' =>'%'.$inputs['keyword'].'%'));

            $criteria ->order = "user_name ASC";

        }

        //僅關鍵字 : 卡號
        if($inputs["start"]===" 00:00:00" && $inputs["end"]=== " 23:59:59" && $inputs["keyword"]!=="" && $inputs["status"] ==="1"){

            $criteria->condition = "card_number like :keyword";

            $criteria->params=(array(':keyword' =>'%'.$inputs['keyword'].'%'));

            $criteria ->order = "user_name ASC";

        }

        //僅關鍵字 : 儀器名稱
        if($inputs["start"]===" 00:00:00" && $inputs["end"]=== " 23:59:59" && $inputs["keyword"]!=="" && $inputs["status"] ==="2"){

            $criteria->condition = "device_name like :keyword";

            $criteria->params=(array(':keyword' =>'%'.$inputs['keyword'].'%'));

            $criteria ->order = "user_name ASC";

        }


        $tmp =  Blackrecord::model()->findAll($criteria);

        //var_dump($tmp);
        return $tmp;





    }

    public function black_list($user_id){
        return $result = Blackrecord::model() -> findAll(array(
            'select' => '*',
            'order' => 'id ASC',
            'condition'=>"delete_datetime = :delete_datetime and user_id = :user_id and category = :category",
            'params'=>[
                ':user_id'=>$user_id,
                ':delete_datetime'=>'0000-00-00 00:00:00',
                ':category'=>'0',
            ]
        ));
    }

    /**
     * @param array $inputs
     * @return Account
     * @throws CDbException
     */
    public function create(array $inputs)
    {
        $model = new Blackrecord();

        $model->use_date  = $inputs["use_date"];
        $model->use_period = $inputs["use_period"];
        $model->user_name = $inputs["user_name"];
        $model->user_id = $inputs["user_id"];
        $model->card_number = $inputs["card_number"];
        $model->device_id = $inputs["device_id"];
        $model->occupied_periods = $inputs["occupied_periods"];
        $model->be_occupied = $inputs["be_occupied"];
        $model->be_occupied_id = $inputs["be_occupied_id"];
        $model->category = $inputs["category"];
        $model->delete_datetime = "0000-00-00 00:00:00";
        $model->delete_reason = "";
		
        if ($model->save()) {
			
			
            return true;

        }else{
            return false;
        }

    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function update(array $inputs)
    {
        $model = Blackrecord::model()->findByPk($inputs["id"]);
        $model->delete_datetime  = date("Y-m-d H:i:s");
        $model->delete_reason    = "管理員刪除";

        if( $model->update()){
            return $model;
        }else{
            return false;
        }




    }

}
?>