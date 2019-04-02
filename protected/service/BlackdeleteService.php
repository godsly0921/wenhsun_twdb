<?php
class BlackdeleteService{

    // 根據日期或關鍵字抓出所有符合之異常紀錄
    public function findBlackdeleteAndConditionDayAll($inputs){
        $criteria = new CDbCriteria;

        //------------------------
        if($inputs["start"]!==" 00:00:00" && $inputs["end"]!==" 23:59:59"){

            $criteria->select = "*";

            $criteria->condition = "delete_datetime  >= :start AND delete_datetime  <= :end AND delete_datetime !=:delete_datetime";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end'],':delete_datetime' =>'0000-00-00 00:00:00'));

            $criteria ->order = "user_name ASC";

        }

        if($inputs["start"]===" 00:00:00" && $inputs["end"]=== " 23:59:59"){

            $inputs['start'] = date("Y-m-d")." 00:00:00";
            $inputs['end'] = date("Y-m-d")." 23:59:59";

            $criteria->select = "*";
            $criteria->condition = "delete_datetime  >= :start AND delete_datetime  <= :end AND delete_datetime !=:delete_datetime";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end'],':delete_datetime' =>'0000-00-00 00:00:00'));

            $criteria ->order = "user_name ASC";

        }

        $tmp =  Blackrecord::model()->findAll($criteria);

        //var_dump($tmp);
        return $tmp;

    }

}
?>