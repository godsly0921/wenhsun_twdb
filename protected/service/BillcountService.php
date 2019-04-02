<?php
use yii\base\ErrorException;

/*
 * 帳單相關服務
 * ----------------------------------------------------------------------------------
 *  
 *  1. 撈出帳單 - getBill()
 *
 *  2. 找出門的位置 - getDoorLocal()
 *
 *  3. 找出儀器位置 - getDevLocal()
 *
 *
 *
 */

class BillcountService{


    /*
     * 撈出帳單
     * ---------------------------------------------------------------------------
     * 根據會員id以及開始結束時間,撈出一份帳單
     *
     *
     */
// 相差時間參數
    public function timediff( $time1 , $time2 ){

        $firstTime = strtotime($time1);
        $lastTime  = strtotime($time2);
        $timeDiff  = abs($lastTime - $firstTime);

        // 回應單位為分鐘數
        return ceil($timeDiff/60)."<br>";

    }

    public function getProfessorMemberBillHistory($bill_record_id){
        // 儀器帳單
        $devBill_sql = 'SELECT b.*,(select position from device where CAST(r.station AS SIGNED) = station) as position,r.use_date as use_date , r.station as station,r.des FROM `bill` b LEFT JOIN device_record r on b.in_id=r.id WHERE b.bill_record_id = '.$bill_record_id.' and b.status = 1';
        $devBills = Yii::app()->db->createCommand($devBill_sql)->queryAll();
        // $devBills = Yii::app()->db->createCommand()
        // ->select('b.*,r.use_date as use_date , r.station as station,r.des')
        // ->from('bill b')
        // ->leftjoin('device_record r', 'b.in_id=r.id')
        // ->where('b.status = 1')
        // ->andwhere('b.bill_record_id = '.$bill_record_id)
        // ->queryall();
        // 門禁帳單
        $doorBill_sql = 'SELECT b.*,DATE_FORMAT(r.flashDate, "%Y-%m-%d") as flashDate,r.reader_num as reader_num,(select position from door where r.reader_num=station) as position from bill_door b LEFT JOIN record r on b.in_id=r.id where bill_record_id=' . $bill_record_id . ' and b.status = 1';
        $doorBills = Yii::app()->db->createCommand($doorBill_sql)->queryAll();
        // 可能情形為 : 1.純門禁 2.門禁+儀器 3.純儀器
        $disArr = array();
        // 預約未使用及未提前取消費
        $violation = array();
        // 以門禁為基礎計算是否需要折抵
        foreach ($doorBills as $doorBillk => $doorBill) {

            // 計算出此筆門禁24小時候是甚麼時間

            $tmpUseDate  =  strtotime($doorBill['flashDate']);
            $tmpUseDate += 86400;
            $allowDevdate = date("Y-m-d H:i:s",$tmpUseDate);
            
            //$doorPosition = $this->getDoorLocal( $doorBill['reader_num'] );
            $doorPosition = $doorBill['position'];
            foreach ($devBills as $devBillk => $devBill) {
                //$devPosition = $this->getDevLocal( $devBill['station'] );
                $devPosition = $devBill['position'];
                if($devBill['des'] == '預約未用'){
                    array_push($violation, $devBill);
                    unset($devBills["$devBillk"]);
                }else{
                    if( $doorPosition == $devPosition && $doorBill['member_id'] == $devBill['member_id']){

                        if( $this->chkAllowdate($doorBill['flashDate'],$allowDevdate,$devBill['use_date']) ){
                            $tmpArr[0] = $doorBill;
                            $tmpArr[1] = $devBill;
                            array_push( $disArr, $tmpArr );

                            // 刪除
                            unset($devBills["$devBillk"]);
                            unset($doorBills["$doorBillk"]);
                        }

                    }// if $doorPosition == $doorPosition 結束
                }
            }// foreach:$devBills 結束
        }
        foreach ($devBills as $devBillk => $devBill) {
            if($devBill['des'] == '預約未用'){
                array_push($violation, $devBill);
                unset($devBills["$devBillk"]);
            }
        }
        $data = [$disArr,$doorBills,$devBills,$violation];
        #echo json_encode($data);exit();
        return $data;  
    }

    public function getProfessorMemberBill( $id , $end ){    
        if($id == ''){
            $disArr = $doorBills = $devBills = $violation = array();
            $data = [$disArr,$doorBills,$devBills,$violation];
            return $data;  
        }  
        // 儀器帳單
        $devBill_sql = 'SELECT b.*,(select position from device where CAST(r.station AS SIGNED) = station) as position,r.use_date as use_date , r.station as station,r.des FROM `bill` b LEFT JOIN device_record r on b.in_id=r.id WHERE b.`member_id` in('.$id.') and r.use_date <= "'.$end.'" and b.status = 0';
        $devBills = Yii::app()->db->createCommand($devBill_sql)->queryAll();
        // $devBills = Yii::app()->db->createCommand()
        // ->select('b.*,r.use_date as use_date , r.station as station,r.des')
        // ->from('bill b')
        // ->leftjoin('device_record r', 'b.in_id=r.id')
        // ->where(array('in', 'b.member_id', $id))
        // ->andwhere('r.use_date <=:end',array(':end'=>$end))
        // ->andwhere('b.status = 0')
        // ->queryall();

        
        // 門禁帳單
        $doorBill_sql = 'SELECT b.*,DATE_FORMAT(r.flashDate, "%Y-%m-%d") as flashDate,r.reader_num as reader_num,(select position from door where r.reader_num=station) as position from bill_door b LEFT JOIN record r on b.in_id=r.id where b.member_id in('.$id.') and r.flashDate <="'.$end.'" and b.status = 0 and b.o_price !=0';
        //var_dump($doorBill_sql);exit();
        $doorBills = Yii::app()->db->createCommand($doorBill_sql)->queryAll();
        //因用yii的model語法無法正常搜尋,改用組合sql語法
        // $doorBills =  Yii::app()->db->createCommand()
        // ->select('b.*,DATE_FORMAT(r.flashDate, "%Y-%m-%d") as flashDate,r.reader_num as reader_num,count(*) as in_door_count')
        // ->from('bill_door b')
        // ->leftjoin('record r', 'b.in_id=r.id')
        // ->where(array('in', 'b.member_id', $id))
        // ->andwhere('r.flashDate >=:start',array(':start'=>$start))
        // ->andwhere('r.flashDate <=:end',array(':end'=>$end))
        // ->andwhere('b.status = 0')
        // ->group('DATE_FORMAT(r.flashDate, "%Y-%m-%d"),b.door_id')
        // ->queryall();
        // 可能情形為 : 1.純門禁 2.門禁+儀器 3.純儀器
        $disArr = array();
        // 預約未使用及未提前取消費
        $violation = array();
        // 以門禁為基礎計算是否需要折抵
        foreach ($doorBills as $doorBillk => $doorBill) {

            // 計算出此筆門禁24小時候是甚麼時間

            $tmpUseDate  =  strtotime($doorBill['flashDate']);
            $tmpUseDate += 86400;
            $allowDevdate = date("Y-m-d H:i:s",$tmpUseDate);
            
            //$doorPosition = $this->getDoorLocal( $doorBill['reader_num'] );
            $doorPosition = $doorBill['position'];
            foreach ($devBills as $devBillk => $devBill) {
                //$devPosition = $this->getDevLocal( $devBill['station'] );
                $devPosition = $devBill['position'];
                if($devBill['des'] == '預約未用'){
                    array_push($violation, $devBill);
                    unset($devBills["$devBillk"]);
                }else{
                    if( $doorPosition == $devPosition && $doorBill['member_id'] == $devBill['member_id']){

                        if( $this->chkAllowdate($doorBill['flashDate'],$allowDevdate,$devBill['use_date']) ){
                            $tmpArr[0] = $doorBill;
                            $tmpArr[1] = $devBill;
                            array_push( $disArr, $tmpArr );

                            // 刪除
                            unset($devBills["$devBillk"]);
                            unset($doorBills["$doorBillk"]);
                        }

                    }// if $doorPosition == $doorPosition 結束
                }
            }// foreach:$devBills 結束
        }
        foreach ($devBills as $devBillk => $devBill) {
            if($devBill['des'] == '預約未用'){
                array_push($violation, $devBill);
                unset($devBills["$devBillk"]);
            }
        }
        $data = [$disArr,$doorBills,$devBills,$violation];
        #echo json_encode($data);exit();
        return $data;  
    }

    public function getBill( $id , $start , $end ){

        
        //$start = '2018-07-01 00:00:00';
    
        //$end   = '2018-07-31 23:59:59';
        
        // 儀器帳單
        $devBills = Yii::app()->db->createCommand()
        ->select('b.*,r.use_date as use_date , r.station as station,r.des')
        ->from('bill b')
        ->leftjoin('device_record r', 'b.in_id=r.id')
        ->where('b.member_id=:id', array(':id'=>$id))
        ->andwhere('r.use_date >=:start',array(':start'=>$start))
        ->andwhere('r.use_date <=:end',array(':end'=>$end))
        ->andwhere('b.status = 0')
        ->queryall();

        
        // 門禁帳單
        $doorBills =  Yii::app()->db->createCommand()
        ->select('b.*,DATE_FORMAT(r.flashDate, "%Y-%m-%d") as flashDate,r.reader_num as reader_num,count(*) as in_door_count')
        ->from('bill_door b')
        ->leftjoin('record r', 'b.in_id=r.id')
        //->leftjoin('door d', 'd.id = b.door_id')
        ->where('b.member_id=:id', array(':id'=>$id))
        ->andwhere('r.flashDate >=:start',array(':start'=>$start))
        ->andwhere('r.flashDate <=:end',array(':end'=>$end))
        ->andwhere('b.status = 0')
        ->group('DATE_FORMAT(r.flashDate, "%Y-%m-%d"),b.door_id')
        ->queryall();
        #echo json_encode($doorBills);exit();

        // 可能情形為 : 1.純門禁 2.門禁+儀器 3.純儀器
        $disArr = array();

        // 預約未使用及未提前取消費
        $violation = array();
        // 以門禁為基礎計算是否需要折抵
        foreach ($doorBills as $doorBillk => $doorBill) {

            // 計算出此筆門禁24小時候是甚麼時間

            $tmpUseDate  =  strtotime($doorBill['flashDate']);
            $tmpUseDate += 86400;
            $allowDevdate = date("Y-m-d H:i:s",$tmpUseDate);
            
            $doorPosition = $this->getDoorLocal( $doorBill['reader_num'] );

            foreach ($devBills as $devBillk => $devBill) {
                $devPosition = $this->getDevLocal( $devBill['station'] );
                if($devBill['des'] == '預約未用'){
                    array_push($violation, $devBill);
                    unset($devBills["$devBillk"]);
                }else{
                    if( $doorPosition == $devPosition){

                        if( $this->chkAllowdate($doorBill['flashDate'],$allowDevdate,$devBill['use_date']) ){
                            $tmpArr[0] = $doorBill;
                            $tmpArr[1] = $devBill;
                            array_push( $disArr, $tmpArr );

                            // 刪除
                            unset($devBills["$devBillk"]);
                            unset($doorBills["$doorBillk"]);
                        }

                    }// if $doorPosition == $doorPosition 結束
                }
            }// foreach:$devBills 結束
        }
        foreach ($devBills as $devBillk => $devBill) {
            if($devBill['des'] == '預約未用'){
                array_push($violation, $devBill);
                unset($devBills["$devBillk"]);
            }
        }
        $data = [$disArr,$doorBills,$devBills,$violation];
        #echo json_encode($data);exit();
        return $data;  
    }

    public function billDataFormat($datas){
        #echo json_encode($datas);exit();
        $deviceServicesv =  new DeviceService();
        $devLists = $deviceServicesv ->findDeviceList();

        foreach ($devLists as $value) {
            $tmp[$value['id']] = $value['name'];
        }
        // 儀器名稱陣列
        $devLists = $tmp;

        unset($tmp);
        $doorSv    = new DoorService();
        $doorLists = $doorSv->get_all();
        $door_price = array();
        foreach ($doorLists as $doorListk => $doorList) {
            $tmp[$doorList['id']] = $doorList['name'];
            $door_price[$doorList['id']] = $doorList['price'];
        }
        // 門禁名稱陣列
        $doorLists = $tmp;
        // 總和金額
        $amount = 0;
        // 門禁儀器抵銷
        $disAmount = 0;
        // 門禁總金額
        $doorAmount = 0;
        // 儀器總金額
        $divAmount = 0;
        // 預約未使用及未提前取消費總金額
        $violationAmount = 0;

        $finaldatas = array();

        if(count($datas)>0){
            $finaldatas['datas'] = array();
            
            foreach ($datas as $datak => $data) {
                
                if( $datak == 0 ) {//儀器＋門禁
                    foreach ( $data as $key => $value ) {
                        $total = $door_price[$value[0]['door_id']] + $value[1]['d_price'];
                        //黃光室的機台(NX2000或是EVG620)，免收當天的進出管理費。 沒刷機台，就要每人每日收 500元。
                        if($value[0]['reader_num'] == '16' && ( $value[1]['dev_id'] == 38 || $value[1]['dev_id'] == 40 )) $value[0]['d_price'] = $value[0]['o_price'];//免收當天的進出管理費, 門禁儀器抵銷
                        else $value[0]['d_price'] = 0;
                        $total = ($value[0]['o_price'] + $value[1]['o_price'])-$value[0]['d_price'];
                        if($value[1]['d_price'] != 0){
                            $device_price = $value[1]['d_price'];
                        }else{
                            $device_price = $value[1]['o_price'];
                        }
                        $in_id = $value[1]['in_id'];
                        $out_id = $value[1]['out_id'];
                        $in_id_data = Device_record::model()->findByPk($in_id);
                        $out_id_data = Device_record::model()->findByPk($out_id);
                        $use_time = $this->timediff($in_id_data['use_date'],$out_id_data['use_date']);
                        $finaldatas['datas'][] = array(
                            'id' => $value[0]['id'],
                            'member_id' => $value[0]['member_id'],
                            'doorbillid' => $value[0]['door_id'],
                            'devbillid' => $value[1]['dev_id'],
                            //'in_door_count' => $value[0]['in_door_count'],
                            'doorname' => $doorLists[$value[0]['door_id']],
                            'devname' => $devLists[ $value[1]['dev_id']],
                            'doorprice' => $value[0]['o_price']-$value[0]['d_price'],
                            'dev_usetime' => $use_time,
                            'devprice' => $device_price,
                            'dev_o_price' => $value[1]['o_price'],
                            'dis' => $value[0]['d_price'], //門禁儀器抵銷
                            'violation_price' => '',
                            'totalprice' => $total,
                            'doortime' => $value[0]['flashDate'],
                            'devtime' => $value[1]['use_date'],
                        );
                        $doorAmount += $value[0]['o_price'];
                        $amount += $total;
                        $divAmount += $device_price;
                        $disAmount += $value[0]['d_price'];
                    }
                    
                }elseif($datak == 1) {//純門禁
                    foreach ( $data as $key => $value ) {
                        $finaldatas['datas'][] = array(
                            'id' => $value['id'],
                            'member_id' => $value['member_id'],
                            'doorbillid' => $value['door_id'],
                            'devbillid' => '',
                            //'in_door_count' => $value['in_door_count'],
                            'doorname' => $doorLists[$value['door_id']],
                            'devname' => '',
                            'doorprice' => $value['o_price'],
                            'dev_usetime' => '',
                            'devprice' => '',
                            'dev_o_price' => '',
                            'dis' => '',
                            'violation_price' => '',
                            'totalprice' => $door_price[$value['door_id']],
                            'doortime' => $value['flashDate'],
                            'devtime' => '',
                        );
                        $amount += $door_price[$value['door_id']];
                        $doorAmount += $door_price[$value['door_id']];
                    }
                }elseif($datak == 2){//純儀器
                    foreach ( $data as $key => $value ) {
                        if($value['d_price'] != 0){
                            $device_price = $value['d_price'];
                        }else{
                            $device_price = $value['o_price'];
                        }
                        $in_id = $value['in_id'];
                        $out_id = $value['out_id'];
                        $in_id_data = Device_record::model()->findByPk($in_id);
                        $out_id_data = Device_record::model()->findByPk($out_id);
                        $use_time = $this->timediff($in_id_data['use_date'],$out_id_data['use_date']);
                        $finaldatas['datas'][] = array(
                            'id' => $value['id'],
                            'member_id' => $value['member_id'],
                            'doorbillid' => '',
                            'devbillid' => $value['dev_id'],
                            //'in_door_count' => '',
                            'doorname' => '',
                            'devname' => $devLists[ $value['dev_id']],
                            'doorprice' => '',
                            'dev_usetime' => $use_time,
                            'devprice' => $device_price,
                            'dev_o_price' => $value['o_price'],
                            'dis' => $value['d_price'],//折扣後價格
                            'violation_price' => '',
                            'totalprice' => $device_price,
                            'doortime' => '',
                            'devtime' => $value['use_date'],
                        );
                        $amount += $device_price;
                        $divAmount += $device_price;

                    }
                }else{
                    foreach ( $data as $key => $value ) {
                        if($value['d_price'] != 0){
                            $device_price = $value['d_price'];
                        }else{
                            $device_price = $value['o_price'];
                        }
                        $finaldatas['datas'][] = array(
                            'id' => $value['id'],
                            'member_id' => $value['member_id'],
                            'doorbillid' => '',
                            'devbillid' => $value['dev_id'],
                            //'in_door_count' => '',
                            'doorname' => '',
                            'devname' => $devLists[ $value['dev_id']],
                            'doorprice' => '',
                            'dev_usetime' => '',
                            'devprice' => '',
                            'dis' => '',
                            'violation_price' => $device_price,
                            'totalprice' => $device_price,
                            'doortime' => '',
                            'devtime' => $value['use_date'],
                        );
                        $amount += $device_price;
                        $violationAmount += $device_price;

                    }
                }// if:else 結束
            }
            $finaldatas['count']['doorAmount'] = $doorAmount;
            $finaldatas['count']['divAmount'] = $divAmount;
            $finaldatas['count']['disAmount'] = $disAmount;
            $finaldatas['count']['violationAmount'] = $violationAmount;
            $finaldatas['count']['amount'] = $amount;
        }else{
            $finaldatas['count']['doorAmount'] = 0;
            $finaldatas['count']['divAmount'] = 0;
            $finaldatas['count']['disAmount'] = 0;
            $finaldatas['count']['violationAmount'] = 0;
            $finaldatas['count']['amount'] = 0;
        }
        #echo json_encode($finaldatas);exit();
        return $finaldatas;
    }
    // 找出指定門的位置
    public function getDoorLocal( $station ){
        
        $station = Yii::app()->db->createCommand()
        ->select('*')
        ->from('door d')
        ->where('d.station=:station', array(':station'=>$station))
        ->queryrow();        
         
        return $station['position'];

    }
    

    // 找出指定儀器位置
    public function getDevLocal( $station ){
        
        $station = (int)$station;

        $station = Yii::app()->db->createCommand()
        ->select('*')
        ->from('device d')
        ->where('d.station=:station', array(':station'=>$station))
        ->queryrow();        

        return $station['position'];

    }

    // 比較可折抵時間時間
    public function chkAllowdate( $basic , $allow , $chk ){

        $basic = strtotime($basic);
        $allow = strtotime($allow);
        $chk   = strtotime($chk);

        if( $chk >= $basic && $chk <= $allow){
            return true;
        }else{
            return false;
        }
    }

    public function billviewToTable( $userdebt , $datas , $mid , $start , $end ){
        $deviceServicesv =  new DeviceService();
        $devLists = $deviceServicesv ->findDeviceList();

        foreach ($devLists as $value) {
            $tmp[$value['id']] = $value['name'];
        }
        // 儀器名稱陣列
        $devLists = $tmp;

        unset($tmp);
        $doorSv    = new DoorService();
        $doorLists = $doorSv->get_all();
        $door_price = array();
        foreach ($doorLists as $doorListk => $doorList) {
            $tmp[$doorList['id']] = $doorList['name'];
            $door_price[$doorList['id']] = $doorList['price'];
        }
        // 門禁名稱陣列
        $doorLists = $tmp;

        // 學生名字
        $memberSv   = new MemberService();
        $memberName = $memberSv->findByMemId( $mid )->name;
        $tmp  ="<thead>
                <tr>
                <th>累計未繳款金額</th>
                <th>$userdebt</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <tr>
                ";
        $tmp .=  "
                <tr>
                <th>門禁名稱</th>
                <th>門禁使用時間</th>
                <th>門禁次數</th>
                <th>儀器名稱</th>
                <th>儀器使用時間</th>
                <th>門禁費用</th>
                <th>儀器費用</th>
                <th>門禁抵消費用</th>
                <th>應付金額</th>
                </tr>
                </thead>
                <tbody>";

        if( count($datas['datas'])==0){
            $tmp .="<tr>";
            $tmp .="<td>無</td>";
            $tmp .="<td></td>";
            $tmp .="<td></td>";
            $tmp .="<td></td>";
            $tmp .="<td></td>";
            $tmp .="<td></td>";
            $tmp .="<td></td>";
            $tmp .="<td></td>";
            $tmp .="<td></td>";
            $tmp .="<tr>";
        }else{
            foreach ($datas['datas'] as $datak => $data) {
                //var_dump($data);
                //echo "<br><br>";
                // 有資料才做
                if( count($data) > 0 ){

                    $tmp .="<tr>";
                    $tmp .="<td>$data[doorname]</td>";
                    $tmp .="<td>$data[doortime]</td>";
                    $tmp .="<td>$data[in_door_count]</td>";
                    $tmp .="<td>$data[devname]</td>";
                    $tmp .="<td>$data[devtime]</td>";
                    $tmp .="<td>$data[doorprice]</td>";
                    $tmp .="<td>$data[devprice]</td>";
                    $tmp .="<td>$data[dis]</td>";
                    $tmp .="<td>$data[totalprice]</td>";
                    $tmp .="</tr>";
                }
            }// foreach 迴圈結束
        }

        // 特殊狀況總金額
        $specialAmount = 0;



        $tmp .=  "
                <thead>
                <tr>
                <th>特殊情況申請理由</th>
                <th>是否通過</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>扣除金額</th>
                </tr>
                </thead>";
        $specialCases = $this->getSpecilCase($mid,$start,$end);

        if( count($specialCases) > 0){

            // 將所有特殊情形印出
            foreach ($specialCases as $specialCasek => $specialCase) {
                switch ($specialCase['status']) {
                    case '0':
                        $specialstatus = '尚未審核';
                        break;
                    case '1':
                        $specialstatus = '審核通過';
                        break;
                    case '2':
                        $specialstatus = '審核失敗';
                        break;
                    case '3':
                        $specialstatus = '已付款';
                        break;

                }
                $tmp .= "<tr>
                         <td>{$specialCase['des']}</td>
                         <td>$specialstatus</td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td>{$specialCase['gap']}</td>
                         </tr>";

                $specialAmount +=$specialCase['gap'];
            }

        }else{
            $tmp .= "<tr>
                    <td>無</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>";
        }

        // 最後總結
        $tmp .=  "
                <thead>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>特殊情況異動</th>
                <th>門禁費用總和</th>
                <th>儀器費用總和</th>
                <th>門禁抵消費用總和</th>
                <th>應付金額總和</th>
                </tr>
                </thead>";
        $final = $datas['count']['amount']-$specialAmount;
        $tmp .= "<tr>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>-$specialAmount</td>
                 <td>".$datas['count']['doorAmount']."</td>
                 <td>".$datas['count']['divAmount']."</td>
                 <td>-".$datas['count']['disAmount']."</td>
                 <td>".$datas['count']['amount']."</td>
                 </tr>";
        for ($j=0; $j < 1; $j++) {
            $tmp .="<tr>";
            for ($i=0; $i < 8; $i++) {
                $tmp .="<td style='background-color:#d4d4d4'></td>";
            }
            $tmp .="</tr>";

        }



        $tmp .="</tbody>";




        return [$tmp,$final];
    }
    public function billToTable( $datas , $mid , $start , $end ){
        //var_dump($datas);
        // 學生名字
        $memberSv   = new MemberService();
        $memberName = $memberSv->findByMemId( $mid )->name;
        $tmp  ="<thead>
                <tr>
                <th>帳單使用人:</th>
                <th>$memberName</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <tr>
                ";
        $tmp .=  "
                <tr>
                <th>門禁名稱</th>
                <th>門禁使用時間</th>
                <th>門禁次數</th>
                <th>儀器名稱</th>
                <th>儀器使用時間</th>
                <th>門禁費用</th>
                <th>儀器費用</th>
                <th>門禁抵消費用</th>
                <th>應付金額</th>
                </tr>
                </thead>
                <tbody>";        

        if( count($datas['datas'])==0){
                $tmp .="<tr>";
                $tmp .="<td>無</td>";
                $tmp .="<td></td>";
                $tmp .="<td></td>";
                $tmp .="<td></td>";
                $tmp .="<td></td>";
                $tmp .="<td></td>";
                $tmp .="<td></td>";
                $tmp .="<td></td>";
                $tmp .="<tr>";
        }else{
            foreach ($datas['datas'] as $datak => $data) {
                $tmp .="<tr>";
                $tmp .="<td>$data[doorname]</td>";
                $tmp .="<td>$data[doortime]</td>";
                $tmp .="<td>$data[in_door_count]</td>";
                $tmp .="<td>$data[devname]</td>";
                $tmp .="<td>$data[devtime]</td>";
                $tmp .="<td>$data[doorprice]</td>";
                $tmp .="<td>$data[devprice]</td>";
                $tmp .="<td>$data[dis]</td>";
                $tmp .="<td>$data[totalprice]</td>";
                $tmp .="</tr>";
            }// foreach 迴圈結束
        }
        
        // 特殊狀況總金額
        $specialAmount = 0;
        
        $tmp .=  "
                <thead>
                <tr>
                <th>特殊情況申請理由</th>
                <th>是否通過</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>扣除金額</th>
                </tr>
                </thead>";
        $specialCases = $this->getSpecilCase($mid,$start,$end);
        
        if( count($specialCases) > 0){
            
            // 將所有特殊情形印出
            foreach ($specialCases as $specialCasek => $specialCase) {
                switch ($specialCase['status']) {
                    case '0':
                        $specialstatus = '尚未審核';
                        break;
                    case '1':
                        $specialstatus = '審核通過';
                        break;
                    case '2':
                        $specialstatus = '審核失敗';
                        break;
                    case '3':
                        $specialstatus = '已付款';
                        break;                                                                    

                }
                $tmp .= "<tr>
                         <td>{$specialCase['des']}</td>
                         <td>$specialstatus</td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td>{$specialCase['gap']}</td>
                         </tr>";

                $specialAmount +=$specialCase['gap'];
            }

        }else{
            $tmp .= "<tr>
                    <td>無</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>";
        }

        // 最後總結
        $tmp .=  "
                <thead>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>特殊情況異動</th>
                <th>門禁費用總和</th>
                <th>儀器費用總和</th>
                <th>門禁抵消費用總和</th>
                <th>應付金額總和</th>
                </tr>
                </thead>";
        $final = $datas['count']['amount']-$specialAmount;
        $tmp .= "<tr>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>-$specialAmount</td>
                 <td>".$datas['count']['doorAmount']."</td>
                 <td>".$datas['count']['divAmount']."</td>
                 <td>-".$datas['count']['disAmount']."</td>
                 <td>".$datas['count']['amount']."</td>
                 </tr>";                
        for ($j=0; $j < 1; $j++) { 
        $tmp .="<tr>";
        for ($i=0; $i < 9; $i++) {
            $tmp .="<td style='background-color:#d4d4d4'></td>";
        }
        $tmp .="</tr>";

        }

        
        
        $tmp .="</tbody>";
        



        return [$tmp,$final];

    }
    
    /*
     * 抓出時間範圍中的特殊申請
     * --------------------------------------------------------------------
     * 
     *
     */

    public function getSpecilCase( $id , $start , $end ){

        $datas = Yii::app()->db->createCommand()
        ->select('*')
        ->from('change_bill_apply')
        ->where('mem_id=:mem_id', array(':mem_id'=>$id))
        ->andwhere('status = 1')
        ->andwhere('bill_mon <= :bill_mon',array(':bill_mon'=> $end))
        ->queryAll();
        
        return $datas;
    }

    /*
     * 產出最後加總的tr
     * --------------------------------------------------------------------
     *
     */

    public function allStudentAmount( $amount ){

        $tmp = "<thead>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>教授帳單總和</th>
                </tr>
                </thead>";

        $tmp .= "<tr>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td>$amount</td>
                 </tr>";                   
        
        return $tmp;
    }
}