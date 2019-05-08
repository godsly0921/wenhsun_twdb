<?php

class CronService
{
    public function today_record()
    {
        try {
            // ST server 會以每月做資料夾命名
            $month_dir = date('Ym');

            // 找出路徑下所有檔案
            $alldir = scandir("C:/ST/Record/$month_dir");

            // 推算今天應該使用之檔名
            $today_st = date('Ymd') . ".st";
            $std = date("Y-m-d 00:00:00");
            $endd = date("Y-m-d 23:59:59");
            // 如果有檔案才做接下來的動作
            if (in_array($today_st, $alldir)) {

                // 取得所有當日,已寫入之資料
                $criteria = new CDbCriteria;
                $criteria->condition = 'flashDate >= :std AND flashDate <= :endd';
                $criteria->params = array(':std' => $std, ':endd' => $endd);
                $record_exist = Record::model()->findAll($criteria);

                // 暫存資料內容
                $tmp_string = file_get_contents("C:/ST/Record/$month_dir/$today_st");

                // 紀錄陣列,每185字串長度取一次
                $record = str_split($tmp_string, 185);

                $temp_point = 0;
                foreach ($record as $key => $value) {

                    $tmparr = array();

                    //echo substr($value, $temp_point, 10)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 10)));
                    $temp_point += 10;
                    //echo substr($value, $temp_point, 2)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 2)));
                    $temp_point += 2;
                    //echo substr($value, $temp_point, 3)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 3)));
                    $temp_point += 3;
                    //echo substr($value, $temp_point, 5)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 5)));
                    $temp_point += 5;
                    //echo substr($value, $temp_point, 5)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 5)));
                    $temp_point += 5;
                    //echo substr($value, $temp_point, 1)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 1)));
                    $temp_point += 1;
                    //echo substr($value, $temp_point, 1)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 1)));
                    $temp_point += 1;
                    //echo substr($value, $temp_point, 10)."|";

                    // 時間區段修改
                    $tmpdate = trim(substr($value, $temp_point, 10));
                    //array_push($tmparr, trim(substr($value, $temp_point, 10) ) );
                    $temp_point += 10;
                    //echo substr($value, $temp_point, 8)."|";
                    $tmptime = trim(substr($value, $temp_point, 8));
                    //array_push($tmparr, trim(substr($value, $temp_point, 8) ) );
                    $temp_point += 8;
                    array_push($tmparr, str_replace('/', '-', $tmpdate . " " . $tmptime));

                    //echo substr($value, $temp_point, 40)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 40)));
                    $temp_point += 40;
                    //echo substr($value, $temp_point, 25)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 25)));
                    $temp_point += 25;
                    //echo substr($value, $temp_point, 3)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 3)));
                    $temp_point += 3;
                    //echo substr($value, $temp_point, 1)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 1)));
                    $temp_point += 1;
                    //echo substr($value, $temp_point, 3)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 3)));
                    $temp_point += 3;
                    //echo substr($value, $temp_point, 3)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 3)));
                    $temp_point += 3;
                    //echo substr($value, $temp_point, 5)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 5)));
                    $temp_point += 5;
                    //echo substr($value, $temp_point, 10)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 10)));
                    $temp_point += 10;
                    //echo substr($value, $temp_point, 5)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 5)));
                    $temp_point += 5;
                    //echo substr($value, $temp_point, 15)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 15)));
                    $temp_point += 15;
                    //echo substr($value, $temp_point, 1)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 1)));
                    $temp_point += 1;
                    //echo substr($value, $temp_point, 1)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 1)));
                    $temp_point += 1;
                    //echo substr($value, $temp_point, 2)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 2)));
                    $temp_point += 2;
                    //echo substr($value, $temp_point, 1)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 1)));
                    $temp_point += 1;
                    //echo substr($value, $temp_point, 10)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 10)));
                    $temp_point += 10;
                    //echo substr($value, $temp_point, 5)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 5)));
                    $temp_point += 5;
                    //echo substr($value, $temp_point, 2)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 2)));
                    $temp_point += 2;
                    //echo substr($value, $temp_point, 8)."|";
                    array_push($tmparr, trim(substr($value, $temp_point, 8)));
                    $temp_point = 0;

                    // 檢測
                    $tosave = true;
                    foreach ($record_exist as $key => $rex) {
                        if ($rex->start_five == $tmparr[3] &&
                            $rex->end_five == $tmparr[4] &&
                            $rex->flashDate == $tmparr[7]
                        ) {
                            $tosave = false;
                        }
                    }

                    if ($tosave === true) {
                        $post = new Record;
                        $post->mem_num = $tmparr[0];
                        $post->info_num = $tmparr[1];
                        $post->reader_num = $tmparr[2];
                        $post->start_five = $tmparr[3];
                        $post->end_five = $tmparr[4];
                        $post->is_record = $tmparr[5];
                        $post->shiftID = $tmparr[6];
                        $post->flashDate = $tmparr[7];
                        $post->flashTime = '';
                        $post->memol = mb_convert_encoding($tmparr[8], "UTF-8", "BIG5");
                        $post->capfilename = $tmparr[9];
                        $post->attendance = $tmparr[10];
                        $post->ctrlmode = $tmparr[11];
                        $post->doorgroup = $tmparr[12];
                        $post->timezone = $tmparr[13];
                        $post->floorgroup = $tmparr[14];
                        $post->homeID = $tmparr[15];
                        $post->seriano = $tmparr[16];
                        $post->name = mb_convert_encoding($tmparr[17], "UTF-8", "BIG5");
                        $post->second = $tmparr[18];
                        $post->doorstatus = $tmparr[19];
                        $post->departmentNo = $tmparr[20];
                        $post->DayNightClass = $tmparr[21];
                        $post->PlateNo = $tmparr[22];
                        $post->Temperature = $tmparr[23];
                        $post->ClientNo = $tmparr[24];
                        $post->Preserve = $tmparr[25];
                        $post->save();
                        Yii::log(date("Y-m-d H:i:s").'====已寫入卡機刷卡紀錄====', CLogger::LEVEL_INFO);
                    }

                }

            } else {
                Yii::log(date("Y-m-d H:i:s").'====目前無資料====', CLogger::LEVEL_INFO);
            }

        } catch (Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
        }

    }

    public function today_mysql_save()
    {
        $today_file = 'E:\/MYSQL_BACKUP\/' . date("Y-m-d") . '\/' . date("Y-m-d") . '_ts_try.sql.gz'; //取得今天日期

        //判斷檔案是否存在
        if (file_exists($today_file)) {
            $filesize = filesize($today_file) / 1024 / 1024;//變成MB
            $model = new Dbbackup();
            $model->name = date("Y-m-d") . '_ts_try.sql.gz';
            $model->file_size = round($filesize, 5);
            $model->create_time = date("Y-m-d h:m:s");
            $model->save();
        } else {

            echo "備份檔案不存在";
        }


    }

    // dev
    public function dev_today_record_excel($today = null)
    {

        Yii::app()->db->charset = 'utf8';


        if ($today == null) {
            // 今日開始
            $std = date("Y-m-d 00:00:00");
            // 今日結束
            $endd = date("Y-m-d 23:59:59");

            $today = date("Y-m-d");
        } else {
            $today = $today[0];
            // 今日開始
            $std = date($today . " 00:00:00");
            // 今日結束
            $endd = date($today . " 23:59:59");
            //$today = $today[0];
        }


        $criteria = new CDbCriteria;
        $criteria->condition = 'use_date >= :std AND use_date <= :endd';
        $criteria->params = array(':std' => $std, ':endd' => $endd);
        $record_exist = Device_record::model()->findAll($criteria);

        $excel_path = '/Program Files (x86)/701Client/';

        $today_excel = 'MSG' . date("Ymd", strtotime($today)) . '.xls';

        if (file_exists($excel_path . $today_excel)) {

            include_once 'PHPExcel/IOFactory.php';
            $reader = PHPExcel_IOFactory::createReader('Excel5'); // 讀取舊版 excel 檔案
            $PHPExcel = $reader->load($excel_path . $today_excel);              // 檔案名稱
            $sheet = $PHPExcel->getSheet(0);          // 讀取第一個工作表(編號從 0 開始)
            $highestRow = $sheet->getHighestRow();                // 取得總行數
            $highestColumn = $sheet->getHighestColumn();   // 取得總欄數(結果為A,B,C...)
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // 取得總欄數(數字)

            // 一次讀取一列吐出來
            for ($row = 2; $row <= $highestRow; $row++) {

                $chknew = true;

                foreach ($record_exist as $xkey => $xval) {
                    if ($xval->detail == $sheet->getCellByColumnAndRow(9, $row)->getValue()
                        && $xval->use_date == $today . " " . $sheet->getCellByColumnAndRow(1, $row)->getValue()
                    ) {
                        $chknew = false;
                    }

                }

                if ($chknew) {

                    $post = new Device_record;
                    $post->day_num = $sheet->getCellByColumnAndRow(0, $row)->getValue();
                    $post->use_date = $today . ' ' . $sheet->getCellByColumnAndRow(1, $row)->getValue();
                    $post->station = $sheet->getCellByColumnAndRow(2, $row)->getValue();
                    $post->num = $sheet->getCellByColumnAndRow(3, $row)->getValue();
                    $post->name = $sheet->getCellByColumnAndRow(4, $row)->getValue();
                    $post->dep1 = $sheet->getCellByColumnAndRow(5, $row)->getValue();
                    $post->dep2 = $sheet->getCellByColumnAndRow(6, $row)->getValue();
                    $post->wnum = $sheet->getCellByColumnAndRow(7, $row)->getValue();
                    $post->des = $sheet->getCellByColumnAndRow(8, $row)->getValue();
                    $post->detail = $sheet->getCellByColumnAndRow(9, $row)->getValue();
                    $tempcard = explode(":", $sheet->getCellByColumnAndRow(9, $row)->getValue());
                    if (empty($tempcard[0])) {
                        $tempcard[0] = '';
                    }
                    if (empty($tempcard[1])) {
                        $tempcard[1] = '';
                    }
                    $post->card = $tempcard[0] . $tempcard[1];
                    $post->create_date = date($today . " H:i:s");
                    $post->save();


                }

            }
        }
    }

    // dev
    public function dev_today_record($today = null)
    {

        Yii::app()->db->charset = 'utf8';


        if ($today == null) {
            // 今日開始
            $std = date("Y-m-d 00:00:00");
            // 今日結束
            $endd = date("Y-m-d 23:59:59");

            $today = date("Y-m-d");
        } else {
            $today = $today[0];
            // 今日開始
            $std = date($today . " 00:00:00");
            // 今日結束
            $endd = date($today . " 23:59:59");
            //$today = $today[0];
        }


        $criteria = new CDbCriteria;
        $criteria->condition = 'use_date >= :std AND use_date <= :endd';
        $criteria->params = array(':std' => $std, ':endd' => $endd);
        $record_exist = Device_record::model()->findAll($criteria);

        $excel_path = '/soyal_record/';
        //$excel_path  = '/Applications/XAMPP/xamppfiles/htdocs/chingda/';

        $today_excel = date("Ymd", strtotime($today)) . '.txt';
        // 暫存資料內容
        //$tmp_string = file_get_contents($excel_path.$today_excel);

        $fh = fopen($excel_path . $today_excel, 'r');
        while ($line = fgets($fh)) {
            // <... Do your work with the line ...>
            $line = iconv('BIG5', 'UTF-8', $line);
            $unwrite_record = explode(",", $line);

            //echo($line);

            $chknew = true;

            foreach ($record_exist as $xkey => $xval) {
                $card_number = substr($unwrite_record[10], 0, 5) . ':' . substr($unwrite_record[10], 5, 5);
                $use_date = date('Y-m-d', strtotime($unwrite_record[2])) . " " . $unwrite_record[3];
                if ($xval->detail == $card_number && $xval->use_date == $use_date) {
                    $chknew = false;
                }
            }

            if ($chknew) {
                $post = new Device_record;
                $post->day_num = $unwrite_record[0];
                $post->use_date = date('Y-m-d', strtotime($unwrite_record[2])) . " " . $unwrite_record[3];
                $post->station = (string)$unwrite_record[4];
                $post->num = '0000';
                $post->name = (string)$unwrite_record[6];
                $post->dep1 = (string)$unwrite_record[7];
                $post->dep2 = (string)$unwrite_record[8];
                $post->wnum = '0000';
                $post->des = (string)$unwrite_record[9];
                $post->detail = (string)substr($unwrite_record[10], 0, 5) . ':' . substr($unwrite_record[10], 5, 5);
                $post->card = (string)substr($unwrite_record[10], 0, 5) . substr($unwrite_record[10], 5, 5);
                $post->create_date = date('Y-m-d H:i:s');
                $post->save();

            }


        }
        fclose($fh);
        echo 'ok';
        //exit();
    }

}

?>