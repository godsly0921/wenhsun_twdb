<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 上午 10:46
 */
class ReportService
{
    public function countEachdayUpload(){// 統計近 20 天的上圖張數
        $sql = "select count(*) as each_day_count,create_time as create_day from single group by DATE_FORMAT(create_time,'%Y-%m-%d') limit 20";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $date = array();
        date_default_timezone_set("Asia/Taipei");
        foreach ($result as $key => $value) {
            $create_day = date_create($value['create_day']);
            $date[] = array(date_format($create_day,"D M j Y 00:00:00 TO"),(int)$value['each_day_count']);
        }
        return json_encode($date);
    }

    public function countSingleSize(){
        $sql = "select count(*) as total from single_size";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        return $result[0];
    }
    public function countSingle(){
        $sql = "select count(*) as total from single";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        return $result[0];
    }
    public function countSinglePublish(){
        $sql = "select count(*) as total from single where publish=1";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        return $result[0];
    }
    public function topProfile(){
        $sql = "select count(*) as each_day_count,DATE_FORMAT(create_time,'%Y-%m-%d') as create_day from single group by DATE_FORMAT(create_time,'%Y-%m-%d') order by each_day_count desc limit 5";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        return $result;
    }
    public function getSumOrder(){
        $sql= "SELECT SUM(cost_total) as order_total,(select sum(cost_total) from orders_item where order_category=1) as point_total,(select sum(cost_total) from orders_item where order_category=2) as sub_total,(select sum(cost_total) from orders_item where order_category=3) as single_total FROM `orders_item`"
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        return $result;
    }
    

    public function findById($id)
    {
        $model = Product::model()->findByPk($id);
        return $model;
    }

}