<?php
/*----------------------------------------------------------------
 | soyal 紀錄相關服務
 |----------------------------------------------------------------
 |
 |
 */
class Device_recordService{   
    
    // 根據不同卡號抓出所有紀錄
    public function get_by_card( $card ){
        
        // $criteria = new CDbCriteria; 

        // $criteria->condition = "tobill = :tobill AND card= :card";

        // $criteria->params=(array(':tobill' => '0',':card' => $card ));
        // /*$criteria ->addCondition("'convert'=0");  
        // $criteria ->addCondition("card= $card");*/
        // $criteria ->order = "use_date ASC";
        $sql = "SELECT *,DATE_FORMAT(create_date, '%Y-%m-%d') as create_date_format FROM `device_record` where card = '" . $card . "' and tobill=0 group by create_date asc";
        $tmp = Yii::app()->db->createCommand($sql)->queryAll();
        //$tmp =  Device_record::model()->findAll($criteria);
       
        return $tmp;
        //
    }

    public function get_by_card_arr( $card ){
        //var_dump($card);
        $data = Yii::app()->db->createCommand()
        ->select('*')
        ->from('device_record')
        ->where(array('in', 'card',  $card ))
        ->queryAll();

        return $data;
    }

    public function chk_use_reservation($id , $start , $end,$staion ){
        /*
        echo $id;
        echo "<br/>";
        echo $start;
        echo "<br/>";
        echo $end;
        echo "<br/>";
        echo $staion;
        echo "<br/>";echo "<br/>";echo "<br/>";echo "<br/>";
        */
        $user = Yii::app()->db->createCommand()
            ->select('*')
            ->from('device_record')
            ->where('card =:card', array(':card'=>$id))
            ->andWhere('use_date>:start_time',array(':start_time'=>$start))
            ->andWhere('use_date<:end_time',array(':end_time'=>$end))
            ->andWhere(array('like', 'station', "%$staion%"))
            ->queryAll();
        return $user;
    }

    public function get_card_and_station($card,$station){
        $record = Yii::app()->db->createCommand()
        ->select('*')
        ->from('device_record')
        ->where('card =:card', array(':card'=>$card))
        ->andWhere('station=:station',array(':station'=>$station))
        ->andWhere('wnum=:wnum',array(':wnum'=>'開始儀器'))
        ->order('use_date DESC')
        ->limit('1')
        ->queryRow();
        return $record;
    }

    public function chk_count($start , $end)
    {
        $result = Yii::app()->db->createCommand()
            ->select('count(id) as total')
            ->from('device_record')
            ->where('true')
            ->andWhere('use_date>=:start_time',array(':start_time'=>$start))
            ->andWhere('use_date<=:end_time',array(':end_time'=>$end))
            ->queryRow();
        return $result;
    }

     public function get_record_for_env( $start , $end , $position ){
        
        $datas = Yii::app()->db->createCommand()
        //,l.name as lname,d.name as dname
        ->select('dr.*')
        ->from('device_record dr')
        ->where('use_date >:start', array(':start'=>$start))
        ->andwhere('use_date <:end', array(':end'=>$end))
        ->queryAll();            
       // var_dump($datas);
        return $datas;
     }

    public function create_record(array $inputs)
    {

        $post = new Device_record;
        $post->day_num = $inputs["day_num"];
        $post->use_date =  $inputs["use_date"];
        $post->station  = $inputs["station"];
        $post->num   = $inputs["num"];
        $post->name = $inputs["name"];
        $post->dep1 = $inputs["dep1"];
        $post->dep2  = $inputs["dep2"];
        $post->wnum  = $inputs["des"];
        $post->des = $inputs["des"];
        $post->detail  = $inputs["detail"];
        $post->card  =  $inputs["card"];
        $post->create_date  = date('Y-m-d'." H:i:s");

        if ($post->save()) {
            return true;
        } else {
            return false;
        }

    }
    public function create_record_return_id(array $inputs)
    {

        $post = new Device_record;
        $post->day_num = $inputs["day_num"];
        $post->use_date =  $inputs["use_date"];
        $post->station  = $inputs["station"];
        $post->num   = $inputs["num"];
        $post->name = $inputs["name"];
        $post->dep1 = $inputs["dep1"];
        $post->dep2  = $inputs["dep2"];
        $post->wnum  = $inputs["des"];
        $post->des = $inputs["des"];
        $post->detail  = $inputs["detail"];
        $post->card  =  $inputs["card"];
        $post->tobill  =  $inputs["tobill"];
        $post->create_date  = date('Y-m-d'." H:i:s");

        if ($post->save()) {
            return $post->getPrimaryKey();
        } else {
            return false;
        }

    }
}
?>