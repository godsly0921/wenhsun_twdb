<?php
class DevcloseService{   
  
  public function getallnew(){
    $res = Yii::app()->db->createCommand()
    ->select('*')
    ->from('device')
    ->queryAll();
    return ($res);
  }
  // 抓出所有分類
  public function getall(){

    $res = Yii::app()->db->createCommand()
        ->select('c.*, d.name as dname,d.id as did, r.name as rname')
        ->from('devclose c')
        ->join('device d', 'c.devid = d.id')
        ->join('clsreason r', 'c.reason = r.id')
        ->queryAll();
    return ($res);

  }

  public function get_clsreason(){
    $res = Yii::app()->db->createCommand()
        ->select('*')
        ->from('clsreason')
        ->queryAll();
    return ($res);
  }

  public function findDevicCloseAll($device_id)
  {
    $res = Yii::app()->db->createCommand()
        ->select('c.*, d.name as dname,d.id as did, r.name as rname')
        ->from('devclose c')
        ->where('devid =:dev', array(':dev'=>$device_id))
        ->join('device d', 'c.devid = d.id')
        ->join('clsreason r', 'c.reason = r.id')
        ->queryAll();
    return ($res);
  }

  public function findDevicCloseAllForStation($station_id,$datetime)
{
  $res = Yii::app()->db->createCommand()
      ->select('dc.*, d.*')
      ->from('device d')
      ->where('station =:station and dc.startc <= :datetime and dc.endc >= :datetime', array(':station'=>$station_id,':datetime'=>$datetime))
      ->join('devclose dc', 'dc.devid = d.id')
      ->queryAll();
  //2018-11-09 08:00:00 >= 2018-12-17 22:22:00  and 2018-11-10 08:00:00 <= 2018-12-17 22:22:00
  return ($res);
}

  public function findDevicCloseAllForStationV2($station_id,$start_time)
  {
    $res = Yii::app()->db->createCommand()
        ->select('dc.*, d.*')
        ->from('device d')
        ->where('station =:station and dc.startc <= :start_time and dc.endc >= :start_time', array(':station'=>$station_id,':start_time'=>$start_time))
        ->join('devclose dc', 'dc.devid = d.id')
        ->queryAll();
    //2019-01-09 23:00:00 <= 2019-01-09 23:10:00 and 2019-01-09 23:59:59 >= 2019-01-09 23:10:00
    return ($res);
  }

  public function getallr(){

    $res = Yii::app()->db->createCommand()
    ->select('d.*')
    ->from('clsreason d')
    /*->join('local  l', 'd.position = l.id')*/
    /*->join('account  a', 'p.builder = a.id')*/
    ->queryAll();
    return ($res);

  }

  public function checkexist( $dev , $ctime ){

    $res = Yii::app()->db->createCommand()
    ->select('*')
    ->from('devclose')
    ->where('devid =:dev AND startc <:ctime AND  endc >:ctime', array(':dev'=>$dev, ':ctime'=>$ctime))
    ->queryAll();
    if(count($res)>0){

      return true;

    }else{

      return false;

    }
  }
  public function getallposition(){

    return ( Local::model()->findAll() );

  }
  // 新增放置位置
  public function create( $name ){
    $post = new Clsreason;
    $post->name        = $name;
    $post->builder     = Yii::app()->session['uid'];
    $post->status      = 1;
    $post->create_date    = date("Y-m-d H:i:s");
    $post->edit_date      = date("Y-m-d H:i:s");

    if( $post->save() ){

      return array(true,'關閉理由新增成功');

    }else{

      return array(false,$post->getErrors());
    }

  }

  // 新增裝置
  public function createdev( $dev , $reason , $start ,$end ){
    
    $post = new Devclose;
    $post->devid   = $dev;
    $post->startc  = $start;
    $post->endc    = $end;
    $post->reason  = $reason;
    $post->status  = 1;
    $post->builder = Yii::app()->session['uid'];
    $post->create_date    = date("Y-m-d H:i:s");
    $post->edit_date      = date("Y-m-d H:i:s");

    if( $post->save() ){

      return array(true,'關閉時間新增成功');

    }else{

      return array(false,$post->getErrors());
    }

  }  
  
  // 抓出單一分類資料
  public function get_one_device( $id ){

    return( Devclose::model()->findbyPk($id) );

  }
  
  // 更新
  public function update( $dev , $reason , $start ,$end , $cid ){
    
    $post            = Devclose::model()->findByPk( $cid );
    $post->devid   = $dev;
    $post->startc  = $start;
    $post->endc    = $end;
    $post->reason  = $reason;
    $post->edit_date = date("Y-m-d H:i:s");
    
    if( $post->save() ){

      return array(true,'關閉時間更新成功');

    }else{
    
      return array(false,$post->getErrors());
    }

  }

  // 刪除
  public function del( $id ){

    $post = Devclose::model()->findByPk( $id ); 
    $post->delete();
    
  }
}
?>