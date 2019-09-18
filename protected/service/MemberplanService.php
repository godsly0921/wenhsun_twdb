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
}
