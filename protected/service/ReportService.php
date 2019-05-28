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
        $sql= "SELECT SUM(cost_total) as order_total,(select sum(cost_total) from orders_item where order_category=1 and order_detail_status=1) as point_total,(select sum(cost_total) from orders_item where order_category=2 and order_detail_status=1) as sub_total,(select sum(cost_total) from orders_item where order_category=3 and order_detail_status=1) as single_total FROM `orders_item` where order_detail_status=1";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        return $result[0];
    }
    
    public function countEachdayOrder(){
        $sql= "SELECT SUM(oi.cost_total) as order_total, DATE_FORMAT(o.order_datetime, '%Y-%m-%d') as orderdate FROM `orders_item`oi JOIN orders o on oi.order_id = o.order_id where oi.order_detail_status=1 group by DATE_FORMAT(o.order_datetime, '%Y-%m-%d') limit 20";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $date = array();
        date_default_timezone_set("Asia/Taipei");
        foreach ($result as $key => $value) {
            $orderdate = date_create($value['orderdate']);
            $date[] = array(date_format($orderdate,"D M j Y 00:00:00 TO"),(int)$value['order_total']);
        }
        return json_encode($date);
    }

    public function top3_Order(){
        $sql = "SELECT o.order_id,case oi.order_category when 1 then '購買點數方案' when 2 then '購買自由載' else '購買單圖授權' end as order_category,o.order_datetime,m.name as member_name FROM `orders` o JOIN orders_item oi on o.order_id=oi.order_id LEFT JOIN member_address_book m on o.address_book_id=m.address_book_id order by o.order_datetime desc limit 3";
        $top3_order = Yii::app()->db->createCommand($sql)->queryAll();
        return $top3_order;
    }

    public function findById($id)
    {
        $model = Product::model()->findByPk($id);
        return $model;
    }

}