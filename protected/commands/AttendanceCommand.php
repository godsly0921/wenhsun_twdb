<?php

//寫入每天早上9:30計算 昨天出勤狀況
class AttendanceCommand extends CConsoleCommand
{
    public function run($today = null)
    {

        $res = new AttendanceService();

        if(empty($today)){
            $today = date("Y-m-d", strtotime('-1 day'));//跑昨天
            $send_mail = true;
        }else{
            $today = $today[0];//表示重跑
            $send_mail = false;
        }
        $res->getAttxendanceData($today,$send_mail);
        $res->getPartTimeData($today,$send_mail);
        $res->getScheduleData($today);//紀州庵本身不寄信(正職)
        $res->getScheduleData_PT($today);//紀州庵本身不寄信(兼職)

        if($send_mail){
            $today = date("Y-m-d");//跑今天
            $isAttxendanceDay = $res->checkAttxendanceDay($today);
            if($isAttxendanceDay){
                $res->getAttxendanceAbnormal($today);
                $res->getAttxendanceReport($today);
            }
        }

    }

}

?>