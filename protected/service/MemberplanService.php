<?php
class MemberplanService
{
    private $memberValidate;

    public function __construct($memberValidate = null)
    {
        $this->memberValidate = $memberValidate ?: new MemberValidator();
    }

    public static function findByMemberPlanEnable($member_id){
        $datetime_now = date('Y-m-d H:i:s');
        $data = array();
        if((int)$member_id){
            $sql = "SELECT * from `member_plan` where member_id = " . (int)$member_id . " and '" . $datetime_now . "' BETWEEN date_start and date_end and status=1 and remain_amount>0 order by date_end asc limit 1";
            $data = Yii::app()->db->createCommand($sql)->queryAll();
        }
        return $data;
    }
    public static function findByMemberAllPlanEnable($member_id){
        $datetime_now = date('Y-m-d H:i:s');
        $data = array();
        if((int)$member_id){
            $sql = "SELECT * from `member_plan` mp inner join orders_item o on mp.order_item_id=o.orders_item_id inner join product p on o.product_id = p.product_id where mp.member_id = " . (int)$member_id . " and '" . $datetime_now . "' BETWEEN mp.date_start and mp.date_end and mp.status=1 and mp.remain_amount>0 order by mp.date_end asc";
            $data = Yii::app()->db->createCommand($sql)->queryAll();
        }
        return $data;
    }
}
