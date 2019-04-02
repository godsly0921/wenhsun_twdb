<?php
class BlackstatisticsService{

    // 根據日期或關鍵字抓出所有符合之異常紀錄
    public function findBlackstatisticsAndConditionDayAll($inputs){
        $criteria = new CDbCriteria;

        //------------------------
        if($inputs["start"]!==" 00:00:00" && $inputs["end"]!==" 23:59:59"){

            $criteria->select = "*,COUNT( * ) AS total";

            $criteria->condition = "use_date >= :start AND use_date <= :end";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end']));

            $criteria ->order = "user_name ASC";

            $criteria ->group = "user_id";

        }

        if($inputs["start"]===" 00:00:00" && $inputs["end"]=== " 23:59:59"){

            $inputs['start'] = date("Y-m-d")." 00:00:00";
            $inputs['end'] = date("Y-m-d")." 23:59:59";

            $criteria->select = "*,COUNT( * ) AS total";
            $criteria->condition = "use_date >= :start AND use_date <= :end";

            $criteria->params=(array(':start' => $inputs['start'],':end' =>$inputs['end']));

            $criteria ->order = "user_name ASC";

            $criteria ->group = "user_id";

        }

        $tmp =  Blackrecord::model()->findAll($criteria);

        //var_dump($tmp);
        return $tmp;

    }

}
?>