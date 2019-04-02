<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/12
 * Time: 下午 08:29
 */
class Common
{
    public static function years()
    {
        //取得現在年份
        $date = date("Y");
        $years = [];
        for($i=0;$i<=99;$i++){

            array_push($years,$date-$i);

        }
        return $years;
    }

    public static function months()
    {
        //取得所有月份
        $months = [0=>'01',1=>'02',2=>'03',3=>'04',4=>'05',5=>'06',6=>'07',7=>'08',8=>'09',9=>'10',10=>'11',11=>'12'];

        return $months;
    }

    public static function weeks()
    {
        //取得所有週
        $weeks = [0=>'星期日',1=>'星期一',2=>'星期二',3=>'星期三',4=>'星期四',5=>'星期五',6=>'星期六',];

        return $weeks;
    }

    public static function st_weeks()
    {
        //取得所有週
        $weeks = ["1"=>'星期一',"2"=>'星期二',"3"=>'星期三',"4"=>'星期四',"5"=>'星期五',"6"=>'星期六',"0"=>'星期日'];

        return $weeks;
    }


    public static function week_list()
    {
        //取得所有週
        $weeks = '0,1,2,3,4,5,6';

        return $weeks;
    }

    public static function hours()
    {
        //取得所有24制
        $hours = ['00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12',
            '13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'22','23'=>'23'];

        return (object)$hours;
    }

    public static function minutes()
    {
        //取得所有分鐘
        $minutes = ['00'=>'00','05'=>'05','10'=>'10','15'=>'15','20'=>'20','25'=>'25','30'=>'30','35'=>'35','40'=>'40','45'=>'45','50'=>'50','55'=>'55','59'=>'59'];

        return $minutes;
    }

    public static function days()
    {
        //取得1-31日
        $days = [0=>'01',1=>'02',2=>'03',3=>'04',4=>'05',5=>'06',6=>'07',7=>'08',8=>'09',9=>'10',10=>'11',11=>'12'
        ,12=>'13',13=>'14',14=>'15',15=>'16',16=>'17',17=>'18',18=>'19',19=>'20',20=>'21',21=>'22',22=>'23',23=>'24'
        ,24=>'25',25=>'26',26=>'27',27=>'28',28=>'29',29=>'30',30=>'31'
        ];

        return $days;
    }

    public static function numbers($total)
    {
        $numbers = [];
        for($i=1;$i<=$total;$i++){
            $numbers[$i] = $i;
        }

        $numbers = (object)$numbers;
        return $numbers;
    }


    /**
     * 抓取要縮圖的比例
     * $source_w : 來源圖片寬度
     * $source_h : 來源圖片高度
     * $inside_w : 縮圖預定寬度
     * $inside_h : 縮圖預定高度
     * Test:
     *   $v = (getResizePercent(1024, 768, 400, 300));
     *   echo 1024 * $v . "\n";
     *   echo  768 * $v . "\n";
     */
    public static function getResizePercent($source_w, $source_h, $inside_w, $inside_h) {
        if ($source_w < $inside_w && $source_h < $inside_h) {
            return 1;
            // Percent = 1, 如果都比預計縮圖的小就不用縮
        }
        $w_percent = $inside_w / $source_w;
        $h_percent = $inside_h / $source_h;
        return ($w_percent > $h_percent) ? $h_percent : $w_percent;
    }

}