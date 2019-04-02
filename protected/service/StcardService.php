<?php

/* 
 * ST卡機相關操作
 * ------------------------------------------------------------------------
 * 此檔案用來放置關於ST卡片資料的新增及修改
 *
 * ------------------------------------------------------------------------
 * 相關功能清單:
 * 
 * 1. 查詢卡號是否存已經存在於 ST SERVER - if_cardnum_exist()
 *
 * 2. 新增一筆卡號資料於 ST SERVER - create_cardnum()
 *
 * 3. 測試目前CardInfo.st中資料  - st_test()
 *
 * 4. 修改卡號(包括新增與修改判斷) - card_motify()
 *
 * 5. 卡片啟用與停用 - card_switch()
 *
 * 6. 卡片ST檔下載至卡機 - st_card_download()
 *
 * 7. 改變會員卡片號碼 - st_change_cardnum()
 *
 *
 */

class StcardService
{
    /*
     * 查詢卡號是否存已經存在於 ST SERVER 
     * ------------------------------------------------------------
     * true  - 卡號已經存在
     *
     * false - 卡號不存在 
     *
     */
    public function if_cardnum_exist( $card ){

        if( file_exists('C:/ST/CardInfo.st') ){

            $tmp_string = file_get_contents('C:/ST/CardInfo.st');

            $records  = str_split($tmp_string, 923);
            
            $exist_sw = false;

            foreach ($records as $key => $record) {
                
                if( $card == substr( $record ,16,10)){
                    $exist_sw = true;
                }

            }        
            
            return $exist_sw;
        }

    }

    /*
     * 新增一筆卡號資料於 ST SERVER
     * -------------------------------------------------------  
     * 1.card - 要新增的卡號
     * 
     * 2.name - 使用者姓名
     *
     */

    public function create_cardnum( $card , $name){

        header ('Content-Type: text/html; charset=big5');

        if( file_exists('C:/ST/CardInfo.st') ){

            $tmp_string = file_get_contents('C:/ST/CardInfo.st');

            $records  = str_split($tmp_string, 923);

            $output = '';
            $write_sw = 1;

            foreach ($records as $key => $record) {
                 
                if ( substr("$record", 0, 1) == '*'){

                    $output .= $record;

                }else{

                    $tmp_sort = trim($record);
                    
                    // 如果還沒寫過才寫
                    if( $write_sw == 1 ){
                        
                        // 判斷有沒有資料 - 1
                        $output .= '*';

                        
                        // 員工編號 - 11
                        for($i=0;$i<10;$i++){

                            $output .= " ";

                        }
                        
                        // 用戶位置 - 16
                        $output .= $tmp_sort;

                        // 卡號 - 26
                        $output .= $card;


                        // 身分證字號 - 41
                        for($i=0;$i<15;$i++){

                            $output .= " ";

                        }

                        // 填寫名子 - 61
                        $str=iconv("UTF-8","big5","$name");
                        $namelen = strlen($str);
                        
                        $output .= $str;

                        // 計算出不同名子長度後,後方補上空白
                        for ($i=0; $i < (20-$namelen) ; $i++) { 
                            
                            $output .= " ";

                        }       

                        // 英文名子 - 81
                        for ($i=0; $i < 20 ; $i++) { 
                            
                            $output .= " ";

                        }
                        
                        // 生日 - 91
                        $output .= "____/__/__";
                                    

                        // 部門 -94
                        for ($i=0; $i < 3 ; $i++) { 
                            
                            $output .= " ";

                        }   

                        // 職稱 - 97 
                        for ($i=0; $i < 3 ; $i++) { 
                            
                            $output .= " ";

                        }      

                        // 進出管制 - 98 
                        $output .= '0'; 

                        // 空白5 - 103
                        $output .= "  \0\0\0";

                        // 日期限制開始 - 105
                        $output .= "0 ";

                        // 日期限制結束時間 - 107
                        $output .= "0 ";

                        // 空白 *32 - 139
                        $output .= "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";

                        // 婚姻狀況 - 140 
                        $output .= " ";

                        // 人員類別 - 143 
                        $output .= "   ";

                        // 性別 - 144 
                        $output .= " ";
                        
                        // 血型 - 146 
                        $output .= "  ";
                                               
                        // 學歷 - 149
                        $output .= "   ";

                        // 車號 - 159
                        $output .= "          ";

                        // 空白 *10 - 169
                        // ^改\0
                        $output .= "\0\0\0\0\0\0\0\0\0\0";

                        // 學校名稱 - 199
                        $output .= "                              ";

                        //手機號碼 - 214
                        $output .= "               ";

                        // 到職日期 - 224
                        $output .= "____/__/__";

                        // 離職日期 - 234
                        $output .= "____/__/__";

                        // 郵遞區號 - 240
                        $output .= "      ";

                        // 地址1 - 320 
                        for ($i=0; $i < 80 ; $i++) { 

                           $output .= " ";

                        }
                        // 郵遞區號2 - 326
                        $output .= "      ";

                        // 地址2 - 406
                        for ($i=0; $i < 80 ; $i++) { 

                           $output .= " ";

                        }

                        // 電話1 - 426
                        for ($i=0; $i < 20 ; $i++) { 

                           $output .= " ";

                        }

                        // 電話2 - 446
                        for ($i=0; $i < 20 ; $i++) { 

                           $output .= " ";

                        }

                        // 備註 - 676
                        for ($i=0; $i < 230 ; $i++) { 

                           $output .= " ";

                        }           

                        // 空白 *20 - 696
                        $output .= "____/_/__         \0\0";

                        
                        // EMAIL - 746
                        for ($i=0; $i < 50 ; $i++) { 

                           $output .= " ";

                        }                        
                        
                        // 管制模式 - 747
                        $output .= "1";
                        
                        // 管制門組 - 750
                        $output .= "0  ";

                        // 管制時段 - 753
                        $output .= "0  ";

                        // 別名 - 773
                        $output .= $str;
                        for ($i=0; $i < (20-$namelen) ; $i++) { 

                           $output .= " ";

                        } 

                        // 語音 - 793
                        $output .= $str;
                        for ($i=0; $i < (20-$namelen) ; $i++) { 

                           $output .= " ";

                        }
                        // 考勤時段 - 796 
                        $output .= "   ";

                        // 指定期限 - 799
                        $output .= "0  ";

                        // 個人密碼 - 803
                        $output .= "    ";

                        // 指定期間開始 -813
                        $output .= "____/__/__";

                        // 指定期間結束 - 823
                        $output .= "____/__/__";

                        // 15個字元沒用到 - 838
                        for ($i=0; $i < 15 ; $i++) { 

                           $output .= " ";

                        }        

                        // 住戶編號 - 848
                        for ($i=0; $i < 10 ; $i++) { 

                           $output .= " ";

                        }                                           

                        // 樓控群組 - 853
                        for ($i=0; $i < 5 ; $i++) { 

                           $output .= " ";

                        }                            

                        // 空白 *70 
                        for ($i=0; $i < 10 ; $i++) { 

                           $output .= "\0";

                        }      
                        for ($i=0; $i < 58 ; $i++) { 

                           $output .= " ";

                        }
                        $output .= "\r";
                        $output .= "\n";
                        /*for ($i=0; $i < 648 ; $i++) { 
                            
                            $output .= " ";

                        }  

                        $output .= '1'; 

                        $output .= ' ';

                        for ($i=0; $i < 56 ; $i++) { 
                            
                            $output .= " ";

                        }
                        $output .='____/__/______/__/__';

                        for ($i=0; $i < 88 ; $i++) { 
                            
                            $output .= " ";

                        }*/

                        $write_sw = 0;

                    }else{

                        $output .= $record;

                    }
                    
                }
                 
            }// foreach 結束

            // 寫入
            $myfile = fopen("C:/ST/CardInfo.st", "w") or die("Unable to open file!");
            

            if(fwrite($myfile, $output)){
                
                $this->st_card_download();
                $new_res = true;

            }else{
                
                $new_res = false;
            }

            fclose($myfile);

            return $new_res;

        }
    }

    /*
     * 測試目前資料
     * ----------------------------------------------------------
     * 直接印出st檔中各筆資料的內容,並將空白以^代替,方便查看 
     * 
     *
     */
    public function st_test(){

        header ('Content-Type: text/html; charset=big5');

        if( file_exists('C:ST/CardInfo.st') ){
            $tmp_string = file_get_contents('C:/ST/CardInfo.st');

            $records  = str_split($tmp_string, 923);

            // 將資料一筆一筆讀出來
            foreach ($records as $key => $record) {
            echo "<hr>";
            echo "length(no mb):". strlen($record);
            echo "<br>";
            echo "length(mb):".mb_strlen($record,'big5');
            echo "<br>";
                $record_card = substr("$record", 16, 10);

                $record= str_replace("\0","!",$record);
                $record= str_replace("\t","@",$record);
                $record= str_replace("\n","#",$record);
                $record= str_replace("\x0B","$",$record);
                $record= str_replace("\r","%",$record);
                $record= str_replace(" ","^",$record);

                echo $record;

                echo "<br><hr><br/>";
            }
        }        
    }

    /* 
     * 修改卡號(包括新增與修改判斷) 
     * ----------------------------------------------------------
     * 根據資料庫卡號跟使用者所填寫之卡號,判斷要新增還是要修改
     *
     * 1. id
     *
     * 2. ocard
     *
     * 3. ncard
     * 0666008384
     */
    public function card_motify( $id, $name , $ocard , $ncard ){
        
        echo 'id:<br>';
        echo $id;
        echo '<br>name<br>';
        echo $name;
        echo '<br>ocard<br>';
        echo $ocard;
        echo '<br>ncard<br>';
        echo $ncard;
        // 如果不同,要開始進入判斷修改
           
        if( $ocard != $ncard){
            
            // 如果原來沒有卡號,表示為新增卡號
            if( empty($ocard) ){
                
                
                $card_exist = $this->if_cardnum_exist($ncard);
                
                if( !$card_exist ){

                    $motify_res = $this->create_cardnum($ncard,$name);
                    $this->st_card_download();
                    return $motify_res;
                }

            // 如果本來有卡號,表示為更新卡號
            }else{

                $res = $this->st_change_cardnum( $ocard , $ncard );
                $this->st_card_download();
                return $res;
            }

        }else{
            return true;
            // 相同不改變

        }
    }


    /*
     * 卡片啟用與停用
     * ---------------------------------------------------------- 
     * 根據不同卡號以及卡片狀態寫入ST檔案中,並且修改
     * 
     * 1. card_num - 卡片號碼
     *
     * 2. $status - 狀態
     *
     */

    public function card_switch( $card_num , $status ){


        
        $stop_err = false;
        
        if( !empty($card_num) ){
            
            // 如果檔案存在才做
            if( file_exists('C:/ST/CardInfo.st') ){
            
            $tmp_string = file_get_contents('C:/ST/CardInfo.st');

            $records  = str_split($tmp_string, 923);
            
            // 要更新的資料
            $update_record = '';

            // 將資料一筆一筆讀出來
            foreach ($records as $key => $record) {
                //echo $key;
                $record_card = substr("$record", 16, 10);
                
                // 如果資料卡號等於接收到的卡號,表示為要修改的資料
                if( $card_num == $record_card){
                   
                    //echo $inputs['card_number'];
                    //echo "<br>";
                    $update_record .= substr($record, 0, 746);
                    
                    if( $status == 1){
                        //echo  0;

                        $update_record .= 0;

                    }else{
                        //echo 1;
                        $update_record .= 1;
                    }
                    
                    
                    $update_record .= substr($record,747, 176);

                    
                }else{
                    
                    // 如果不是要修改的,直接回存即可
                    $update_record .= $record;

                }

               
            }


            // 寫入檔案
            $myfile = fopen("C:/ST/CardInfo.st", "w") or die("Unable to open file!");
            if( !fwrite($myfile, $update_record) ){
         
                $stop_err = true;
            }
            fclose($myfile);            

            }
            
            return $stop_err;
            
        }

    }

    /*
     * 卡片ST檔下載至卡機 
     * ---------------------------------------------------------------
     * 卡片資料修改完後,必須需得要將資料下載到各卡機才能生效,
     * 此方法將檔案複製到該目錄後完成下載動作
     * Download.st 廠商提供之卡機軟體呼叫方法，放一個Download 卡機會自動下載更新的ST檔
     */

    public function st_card_download(){

        if(file_exists("Download.st")){
            if(copy("Download.st","C:/ST/Download.st") ){
               // $res = exec('START C:\xampp\htdocs\chingda\stserver_open.bat');
               // echo  $res;
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }


    }

    /*
     * 改變會員門組設定
     * -----------------------------------------------------------------
     * 改變st中,帶入新的門組設定是根據卡號進行修改
     *
     */

    public function st_change_door($card ,$door){

        header ('Content-Type: text/html; charset=big5');

      //  $official = "/Applications/XAMPP/xamppfiles/htdocs/chingda/CardInfo.st";
      //  $official2 = "/Applications/XAMPP/xamppfiles/htdocs/chingda/CardInfo2.st";

        $official ="C:/ST/CardInfo.st";

        if( file_exists($official) ){

            $tmp_string = file_get_contents($official);

            $records  = str_split($tmp_string, 923);

            $output = '';

            foreach ($records as $key => $record) {

                $record_card = substr("$record", 16, 10);//原始ＳＴ檔每一筆卡號

                if( $record_card == $card ){//假如原始ＳＴ檔卡號等於要修改卡號$card


                    if(strlen($door)>3 || strlen($door)<3){
                        // 管制門組 - 750
                        //$output .= "0  ";
                        $res = 'error:input (door)string exception';
                        return $res;

                    }

                    $output .= substr_replace($record , $door , 747 , 3);  //0表示一所以（748-1）  CtrlDoor	3	管制門組	748	750

                }else{
                    $output .= $record;

                }
            }// foreach 結束

            // 寫入

            $myfile = fopen($official, "w") or die("Unable to open file!");


            if(fwrite($myfile, $output)){

                $new_res = true;

            }else{

                $new_res = false;
            }

            fclose($myfile);

            return $new_res;


        }
    }

    /*
     * 改變會員時段設定
     * -----------------------------------------------------------------
     * 改變st中,帶入時段設定是根據卡號進行修改
     *
     */

    public function st_change_time($card ,$time){

        header ('Content-Type: text/html; charset=big5');

        $official ="C:/ST/CardInfo.st";
        //$official2 = "/Applications/XAMPP/xamppfiles/htdocs/chingda/CardInfo2.st";
       // $official3 = "/Applications/XAMPP/xamppfiles/htdocs/chingda/CardInfo3.st";

        //$official ="C:/ST/CardInfo.st";

        if( file_exists($official) ){

            $tmp_string = file_get_contents($official);

            $records  = str_split($tmp_string, 923);

            $output = '';

            foreach ($records as $key => $record) {

                $record_card = substr($record, 16, 10);//原始ＳＴ檔每一筆卡號

                $time = str_pad($time,3," ",STR_PAD_RIGHT);


                if( $record_card == $card ){//假如原始ＳＴ檔卡號等於要修改卡號$card

                    if(strlen($time)>3 || strlen($time)<3){
                        // 管制門組 - 750
                        //$output .= "0  ";
                        $res = 'error:input ($time)string exception';
                        return $res;

                    }
                    //echo $time;
                    //echo $record;
                   // echo '<br>';
                    $output .= substr_replace($record , $time , 750 , 3);  //
                   // echo $output;

                   // echo $output;


                }else{
                    $output .= $record;

                }
            }// foreach 結束

            // 寫入

            $myfile = fopen($official, "w") or die("Unable to open file!");


           if(fwrite($myfile, $output)){

                $new_res = true;

           }else{

                $new_res = false;
            }

            fclose($myfile);

            return $new_res;


        }

    }

    /*
     * 改變會員卡片號碼 
     * -----------------------------------------------------------------
     * 改變st中,原始的卡片號碼成為新的號碼
     *
     */

    public function st_change_cardnum( $ocard , $ncard ){

        header ('Content-Type: text/html; charset=big5');

        if( file_exists('C:/ST/CardInfo.st') ){

            $tmp_string = file_get_contents('C:/ST/CardInfo.st');

            $records  = str_split($tmp_string, 923);

            $output = '';
            

            foreach ($records as $key => $record) {

                $record_card = substr("$record", 16, 10); 
                
                if( $record_card == $ocard ){

                    //echo str_replace(" ","^",substr("$record", 0, 16));
                    $output .= substr("$record", 0, 16);
                    //echo str_replace(" ","^",substr("$record", 16, 10));
                    $output .= $ncard;
                    //echo str_replace(" ","^",substr("$record", 26, 897));
                    $output .= substr("$record", 26, 897);

                    //echo "<br><br>-----------------------------------------------------<br/>";
                   
                }else{

                    $output .= $record;
                    
                }
                   
                 
            }// foreach 結束

            // 寫入
            
            $myfile = fopen("C:/ST/CardInfo.st", "w") or die("Unable to open file!");
            

            if(fwrite($myfile, $output)){
                
                $new_res = true;

            }else{
                
                $new_res = false;
            }

            fclose($myfile);

            return $new_res;
            

        }        
    }
    public function sy_test(){

        header ('Content-Type: text/html; charset=big5');

        if( file_exists('C:/UserCard.txt') ){
            $tmp_string = file_get_contents('C:/UserCard.txt');

            $records  = explode("\n",$tmp_string);//str_split($tmp_string, 923);

            // 將資料一筆一筆讀出來
            foreach ($records as $key => $record) {
            echo "<hr>";
            echo "length(no mb):". strlen($record);
            echo "<br>";
            echo "length(mb):".mb_strlen($record,'big5');
            echo "<br>";
                $record_card = substr("$record", 16, 10);

                $record= str_replace("\0","!",$record);
                $record= str_replace("\t","@",$record);
                $record= str_replace("\n","#",$record);
                $record= str_replace("\x0B","$",$record);
                $record= str_replace("\r","%",$record);
                $record= str_replace(" ","^",$record);

                echo $record;

                echo "<br><hr><br/>";
            }
        }        
    }    
    
    // 下載測試
    public function std_test(){

        $this->st_card_download();
    }
}