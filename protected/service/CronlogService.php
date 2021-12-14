<?php

class CronlogService
{
    public function WriteLog($cron_name, $start_time, $end_time, $log)
    {
        $model = new CronLog();
        $model->cronname = $cron_name;
        $model->start_time = $start_time;
        $model->end_time = $end_time;
        $model->log = $log;
        if(!$model->save()){
            $log_string =  $cron_name . " start at => " . $start_time . "；end at => " . $end_time . "；log => " . $log;
            Yii::log(date("Y-m-d H:i:s") . ' ====寫入Cron LOG 失敗====' . $log_string, CLogger::LEVEL_INFO);
        }
    }
}
