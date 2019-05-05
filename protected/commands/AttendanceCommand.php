<?php

//寫入每天早上9:30計算 昨天出勤狀況
class AttendanceCommand extends CConsoleCommand
{
    public function run($day = null)
    {

        $res = new AttendanceService();

        if(false){//false 表示測試
            $today = date("Y-m-d", strtotime('-1 day'));
        }else{
            $today = '2019-05-03';//測試日期
        }

        $res->getAttxendanceData($today);
    }

}

?>