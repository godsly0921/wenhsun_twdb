<?php
class SycardService
{

//protected $filePath = "C:/ofile/";
//protected $downPath = "C:/dfile/";

    protected $filePath = "/data/ofile/";//讀取原始資料
    protected $downPath = "/data/dfile/";//下載檔案 把資料下載至卡機

    public $data = '{
        "0": { "val1":6660, "val2":8384, "val3":"全通卡1"},
        "1": { "val1":8555, "val2":13712, "val3":"全通卡2"},
        "2": { "val1":40459, "val2":15452, "val3":"莊婉勤"},
        "3": { "val1":24570, "val2":63029, "val3":"黃如君"},
        "4": { "val1":40378, "val2":31468, "val3":"劉湘雅"},
        "5": { "val1":31702, "val2":38092, "val3":"李培菁"},
        "6": { "val1":31922, "val2":21964, "val3":"張雯琳"},
        "7": { "val1":11228, "val2":19323, "val3":"曾文欽"},
        "8": { "val1":12625, "val2":12602, "val3":"宋明穎"},
        "9": { "val1":31945, "val2":4202, "val3":"郭文鳳"},
        "10": { "val1":3769, "val2":26322, "val3":"林孟賢"},
        "11": { "val1":4403, "val2":24676, "val3":"岑尚仁"},
        "12": { "val1":24740, "val2":33429, "val3":"張?文"},
        "13": { "val1":11828, "val2":45813, "val3":"周重羽"},
        "14": { "val1":27533, "val2":49632, "val3":"Gaurav"},
        "15": { "val1":11547, "val2":13269, "val3":"李薇妮"},
        "16": { "val1":48520, "val2":28251, "val3":"李俊逸"},
        "17": { "val1":40808, "val2":47708, "val3":"劉恩惠"},
        "18": { "val1":11645, "val2":32069, "val3":"楊立"},
        "19": { "val1":40376, "val2":2188, "val3":"林瑟蘭"},
        "20": { "val1":11547, "val2":40341, "val3":"吳若穎"},
        "21": { "val1":3769, "val2":33538, "val3":"楊岳強"},
        "22": { "val1":11828, "val2":5269, "val3":"鄭煒"},
        "23": { "val1":12396, "val2":57765, "val3":"劉宇晨"},
        "24": { "val1":29388, "val2":36124, "val3":"陳俊源"},
        "25": { "val1":10926, "val2":4853, "val3":"黃子榮"},
        "26": { "val1":11836, "val2":40741, "val3":"鄭博尹"},
        "27": { "val1":11836, "val2":16837, "val3":"楊梓鍵"},
        "28": { "val1":11828, "val2":42341, "val3":"顏君玲"},
        "29": { "val1":13491, "val2":24149, "val3":"黃鐘緯"},
        "30": { "val1":11111, "val2":11113, "val3":"全通卡1"},
        "31": { "val1":11111, "val2":11114, "val3":"全通卡1"},
        "32": { "val1":10534, "val2":34613, "val3":"Ragini Mishra"},
        "33": { "val1":10223, "val2":40277, "val3":"謝沛倫"},
        "34": { "val1":12388, "val2":12501, "val3":"Anup"},
        "35": { "val1":11069, "val2":45845, "val3":"許韶庭"},
        "36": { "val1":11360, "val2":501, "val3":"Jyoti Satija"},
        "37": { "val1":12252, "val2":57701, "val3":"Gayathri Pillai"},
        "38": { "val1":13493, "val2":19397, "val3":"張育誠"},
        "39": { "val1":11068, "val2":46053, "val3":"林育德"},
        "40": { "val1":6598, "val2":64960, "val3":"通行卡11431"},
        "41": { "val1":8530, "val2":54080, "val3":"通行卡11432"},
        "42": { "val1":17645, "val2":46096, "val3":"通行卡11433"},
        "43": { "val1":6794, "val2":53264, "val3":"通行卡11434"},
        "44": { "val1":6600, "val2":5680, "val3":"通行卡11435"},
        "45": { "val1":3902, "val2":27344, "val3":"通行卡11436"},
        "46": { "val1":6773, "val2":62720, "val3":"通行卡11437"},
        "47": { "val1":6775, "val2":34480, "val3":"通行卡11438"},
        "48": { "val1":6600, "val2":22640, "val3":"通行卡11439"},
        "49": { "val1":3907, "val2":9344, "val3":"通行卡11440"},
        "50": { "val1":12543, "val2":18149, "val3":"陳柏廷"},
        "51": { "val1":45305, "val2":4827, "val3":"孫敏勝"},
        "52": { "val1":24830, "val2":34949, "val3":"葉雲鵬"},
        "53": { "val1":12253, "val2":63381, "val3":"葉承彥"},
        "54": { "val1":13491, "val2":50213, "val3":"張銘翔"},
        "55": { "val1":13490, "val2":6981, "val3":"顏銘祥"},
        "56": { "val1":25524, "val2":10709, "val3":"方文琦"},
        "57": { "val1":13491, "val2":46389, "val3":"曾冠儒"},
        "58": { "val1":13489, "val2":22421, "val3":"陳婉婷"},
        "59": { "val1":12389, "val2":27861, "val3":"黃英欣"},
        "60": { "val1":11842, "val2":30213, "val3":"蕭辰宇"},
        "61": { "val1":11841, "val2":4869, "val3":"陳彥伯"},
        "62": { "val1":13489, "val2":44805, "val3":"陳芃君"},
        "63": { "val1":11833, "val2":6853, "val3":"鄭至清"},
        "64": { "val1":12266, "val2":25173, "val3":"吳東衡"},
        "65": { "val1":13489, "val2":34421, "val3":"楊恩誠"},
        "66": { "val1":11069, "val2":23061, "val3":"沈廷威"},
        "67": { "val1":63481, "val2":16484, "val3":"戴依馨"},
        "68": { "val1":11838, "val2":19173, "val3":"黃冠霖"},
        "69": { "val1":11828, "val2":3685, "val3":"Shalaka"},
        "70": { "val1":11841, "val2":48373, "val3":"陳彥伶"},
        "71": { "val1":11842, "val2":18581, "val3":"宋俊輝"},
        "72": { "val1":13490, "val2":549, "val3":"李家宏"},
        "73": { "val1":13491, "val2":58149, "val3":"葉勝凱"},
        "74": { "val1":12259, "val2":11221, "val3":"洪千雅"},
        "75": { "val1":25525, "val2":29269, "val3":"林明毅"},
        "76": { "val1":25519, "val2":57957, "val3":"梁家鈞"},
        "77": { "val1":12266, "val2":64709, "val3":"謝易佐"},
        "78": { "val1":13491, "val2":10485, "val3":"李宇雙"},
        "79": { "val1":13490, "val2":3877, "val3":"洪禾蓁"},
        "80": { "val1":13489, "val2":37269, "val3":"簡婕安"},
        "81": { "val1":13489, "val2":59477, "val3":"黃凡芸"},
        "82": { "val1":13489, "val2":24949, "val3":"鄭旭翔"},
        "83": { "val1":13489, "val2":38117, "val3":"任開琳"},
        "84": { "val1":11147, "val2":30853, "val3":"李忠信"},
        "85": { "val1":3769, "val2":30626, "val3":"林澤媺"},
        "86": { "val1":3768, "val2":45602, "val3":"楊先巧"},
        "87": { "val1":12248, "val2":46949, "val3":"魏天韻"},
        "88": { "val1":30872, "val2":9680, "val3":"李子念"},
        "89": { "val1":3769, "val2":35058, "val3":"曾士綸"},
        "90": { "val1":11642, "val2":52293, "val3":"李雅筑"},
        "91": { "val1":11069, "val2":11285, "val3":"高子雯"},
        "92": { "val1":12265, "val2":43861, "val3":"鄭彰緯"},
        "93": { "val1":12273, "val2":41141, "val3":"黃詩涵"},
        "94": { "val1":11175, "val2":3845, "val3":"邱怡龍"},
        "95": { "val1":43422, "val2":10781, "val3":"吳姵禛"},
        "96": { "val1":13490, "val2":25205, "val3":"徐蘇"},
        "97": { "val1":12292, "val2":44005, "val3":"李書蘋"},
        "98": { "val1":12280, "val2":24197, "val3":"蕭媛元"},
        "99": { "val1":12277, "val2":39397, "val3":"李宜臻"},
        "100": { "val1":12267, "val2":6053, "val3":"吳孟峰"},
        "101": { "val1":27533, "val2":27760, "val3":"蔡秉桓"},
        "102": { "val1":3768, "val2":15730, "val3":"劉仲達"},
        "103": { "val1":11838, "val2":23717, "val3":"李承翰"},
        "104": { "val1":13492, "val2":51717, "val3":"黃晟齊"},
        "105": { "val1":26097, "val2":45288, "val3":"巫翔裘"},
        "106": { "val1":13491, "val2":42661, "val3":"蕭芳松(換卡片)"},
        "107": { "val1":11710, "val2":48325, "val3":"吳尚儒"},
        "108": { "val1":13490, "val2":27413, "val3":"黃睿麟"},
        "109": { "val1":25516, "val2":18117, "val3":"林政宇"},
        "110": { "val1":25521, "val2":45381, "val3":"張智堯"},
        "111": { "val1":11111, "val2":11112, "val3":"全通卡1"},
        "112": { "val1":50560, "val2":34446, "val3":"陳育詩"},
        "113": { "val1":3768, "val2":54018, "val3":"方郁仁"},
        "114": { "val1":3769, "val2":37090, "val3":"林于茹"},
        "115": { "val1":11711, "val2":9205, "val3":"光電 張育誠"},
        "116": { "val1":11301, "val2":13011, "val3":"分隔重整"},
        "117": { "val1":13490, "val2":11349, "val3":"林致達"},
        "118": { "val1":0, "val2":0, "val3":"鄭斯璘"},
        "119": { "val1":3769, "val2":31682, "val3":"吳宗庭"},
        "120": { "val1":12268, "val2":28997, "val3":"Shashwat Bhattachary"},
        "121": { "val1":12255, "val2":22917, "val3":"劉志欣"},
        "122": { "val1":13490, "val2":30725, "val3":"蘇中英"},
        "123": { "val1":143, "val2":43889, "val3":"蕭芳松"},
        "124": { "val1":3769, "val2":36114, "val3":"彭奕"},
        "125": { "val1":12263, "val2":501, "val3":"陳慕修"},
        "126": { "val1":3768, "val2":64306, "val3":"李柏誼"},
        "127": { "val1":13490, "val2":44485, "val3":"吳宇恩"},
        "128": { "val1":14158, "val2":59861, "val3":"錢譽丹"},
        "129": { "val1":23884, "val2":57585, "val3":"黃齡蒂"},
        "130": { "val1":25524, "val2":32821, "val3":"林崇致"},
        "131": { "val1":4403, "val2":17444, "val3":"莊婉勤 (A085)"},
        "132": { "val1":11711, "val2":37221, "val3":"林柏宏"},
        "133": { "val1":13491, "val2":58213, "val3":"楊鈞量"},
        "134": { "val1":12276, "val2":20293, "val3":"李佳璋"},
        "135": { "val1":26125, "val2":50408, "val3":"陳柏全"},
        "136": { "val1":11841, "val2":26933, "val3":"陳辰地"},
        "137": { "val1":3769, "val2":57362, "val3":"葉子瑋"},
        "138": { "val1":62313, "val2":37196, "val3":"Abhishek Dubey"},
        "139": { "val1":25751, "val2":8936, "val3":"李名耀"}
    }';

    public function sy_test(){

        header ('Content-Type: text/html; charset=big5');

        if( file_exists($this->filePath.'UserCard.txt') ){
            $tmp_string = file_get_contents($this->filePath.'UserCard.txt');

            $records  = explode("\n",$tmp_string);//str_split($tmp_string, 923);
            $data = json_decode($this->data,true);

            // 將資料一筆一筆讀出來
            $update_record ='';
            $i = -1;
            $filename = "/data/dfile/UserCard.txt";

            $download_file = fopen("/data/dfile/UserCard.txt", "w") or die("Unable to open file!");
            //$record1 ='';
            foreach ($records as $key => $record) {
                
                if($i != -1 and isset($data[$i]['val1']) and  isset($data[$i]['val2']) and isset($data[$i]['val3'])){
                    $data[$i]['val1']= str_pad($data[$i]['val1'],5,"0",STR_PAD_LEFT);
                    $data[$i]['val2']= str_pad($data[$i]['val2'],5,"0",STR_PAD_LEFT);
                    $data[$i]['val3'] = mb_convert_encoding($data[$i]['val3'],"big5","auto");
                    $name_len = strlen( $data[$i]['val3']);
                }
                
                if(isset($data[$i]['val1']) and isset($data[$i]['val2']) and isset($data[$i]['val3'])){

                    $update_record = substr_replace($record,$data[$i]['val1'],7,5);
                    //  var_dump($update_record);
                    $update_record = substr_replace($update_record,$data[$i]['val2'],13,5);

                    $update_record = substr_replace($update_record,$data[$i]['val3'],19,$name_len);

                }else{
   
                    $update_record = $record;

                }
                $update_record = $update_record.PHP_EOL;
                fwrite($download_file,$update_record);
               

                $i++;
            }

        fclose($download_file);


    }     

}    

public function modifyCard( $newCardNum = '' ,$cardNum , $name , $mode ){


    if( $this->chkCardExist($cardNum) ){

        // 修改
        if( $newCardNum != $cardNum ){

            $res = $this->updateCard( $cardNum , $newCardNum , $mode );
            /*var_dump($res);*/
        }

    }else{

        // 新增
        $txtcard = $this->transformCard( $cardNum );
        $this->newcard( $txtcard , $name , $mode );

    }

    $this->copyToDownload();

}

public function newcard( $newCardNum , $name , $mode ){

    header ('Content-Type: text/html; charset=big5');

    if( file_exists('C:/ofile/UserCard.txt') ){

        $tmp_string = file_get_contents('C:/ofile/UserCard.txt');
        $records    = explode("\n",$tmp_string);
        

        $newSwtch   = True;
        $output = '';
        

        foreach ($records as $key => $record) {

            if( !empty($record)){

                $singalArr = explode(",",$record);

                //var_dump($singalArr);

                if( $singalArr[1] == '00000:00000' && empty(trim($singalArr[2])) &&  $newSwtch === True ){
                    $newSwtch = False;
                    //$singalArr[0].iconv('UTF-8','big5','新增').'<br>';
                    
                    // 寫入卡號
                    $singalArr[1] = $newCardNum;

                    $str = iconv("UTF-8","big5",$name);
                    $namelen = strlen($str);
                    
                    $singalArr[2] = $str;

                    // 計算出不同名子長度後,後方補上空白
                    for ($i=0; $i < (30-$namelen) ; $i++) { 

                        $singalArr[2] .= " ";

                    }
                    
                    $singalArr[15] = $mode;
                    
                    $output .= implode(',',$singalArr);
                    $output .= "\n";

                }else{

                    $singalArr = explode(",",$record);
                    //echo $singalArr[0].iconv('UTF-8','big5','照舊').'<br>';
                    $output .= implode(',',$singalArr);
                    $output .= "\n";
                }
            }


        }

        $myfile = fopen("/data/dfile/UserCard.txt", "w") or die("Unable to open file!");
        
        if(fwrite($myfile, $output)){

            $new_res = true;

        }else{

            $new_res = false;
        }
        echo var_dump($new_res);
    }


}

private function updateCard( $oldCard , $newCard , $mode ){

    echo $oldCard;
    echo "<br>";
    echo $newCard;
    echo "<br>";
    echo $mode;
    // 驗證新卡
    if( !$this->chkCardFormat( $newCard ) ){

        return array(False,iconv("UTF-8","big5",'請確認新卡號之格式'));
        exit;
    }
    
    if( $this->chkCardExist( $newCard) ){

        return array(False,iconv("UTF-8","big5",'想要改變之卡號已經存在'));
        exit;
    }


    // 轉換卡號
    echo $txtOldCard = $this->transformCard( $oldCard );
    echo "<br>";
    echo $txtNewCard = $this->transformCard( $newCard );

    if( file_exists($this->filePath.'UserCard.txt') ){
        //echo iconv("UTF-8","big5",'開始改');
        $tmp_string = file_get_contents($this->filePath.'UserCard.txt');
        $records    = explode("\n",$tmp_string);
        
        $newSwtch   = True;
        $output = '';
        foreach ($records as $key => $record) {

            if( !empty($record)){

                $singalArr = explode(",",$record);
                echo $record;
                echo "<br>";
                if($singalArr[1] == $txtOldCard){

                    //echo iconv("UTF-8","big5",'change').$singalArr[1];
                    $singalArr[1] = $txtNewCard;
                    $output .= implode(",",$singalArr);
                    $output .= "\n";

                }else{
                    $output .= $record;
                    $output .= "\n";
                }
                /*var_dump($singalArr);
                echo "<br><br>";*/
            }

        }
        
        $myfile = fopen("C:/ofile/UserCard.txt", "w") or die("Unable to open file!");
        
        if(fwrite($myfile, $output)){

            $upd_res = true;

        }else{

            $upd_res = false;
        }

    }


}

private function chkCardExist( $cardNum ){



    if( !$this->chkCardFormat( $cardNum ) ){

        return array(False,'E0');
        exit;
    }
    
    $txtCard = $this->transformCard( $cardNum );
    
    if( file_exists($this->filePath.'UserCard.txt') ){

        $tmp_string = file_get_contents($this->filePath.'UserCard.txt');
        $records    = explode("\n",$tmp_string);
        
        $numExist = False;
        foreach ($records as $key => $record) {

            if( !empty($record) ){

                $tmpData = explode(',', $record);
                if( $tmpData[1] == $txtCard ){

                    $numExist = True;
                }

            }
        }
        
        return $numExist;
    }else{

        return array(False,'E1');
    }
}

private function chkCardFormat( $cardNum ){

    if( strlen($cardNum) != 10 ){

        return False;
        exit;
    }
    if( !is_numeric($cardNum) ){
        return False;
        exit;
    }
    
    return True;
}

private function transformCard( $cardNum ){

    return $newstr = substr_replace($cardNum, ':', 5, 0);
}
  //protected $filePath = "/data/ofile/";//讀取原始資料
 //   protected $downPath = "/data/dfile/";//下載檔案 把資料下載至卡機
public function copyToDownload(){
  //  "/data/ofile/";//讀取原始資料
   // "/data/dfile/";//下載檔案 把資料下載至卡機
    /*echo $this->filePath.'UserCard.txt';
    if( file_exists($this->filePath.'UserCard.txt')){
        echo 'good';
    }else{
        echo 'fl';
    }*/
    copy( $this->filePath.'UserCard.txt',$this->downPath.'UserCard.txt');
}
}