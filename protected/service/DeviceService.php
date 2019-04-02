<?php
class DeviceService{   
  
  // 抓出所有分類
  public function getall(){

    $res = Yii::app()->db->createCommand()
    ->select('d.*, l.name as pname')
    ->from('device d')
    ->join('local  l', 'd.position = l.id')
    /*->join('account  a', 'p.builder = a.id')*/
    ->queryAll();
    return ($res);

  }

  //取得所有儀器資料
  public function findDevices()
  {
    $result = Device::model()->findAll();
    return $result;

  }

  //取得該用戶儀器權限
  public function findDevicesPermission($user_permission_devices)
  {

    $in_string = implode(',',json_decode($user_permission_devices,true));
    $result = Device::model()->findAll("station IN ({$in_string})");
    return $result;
  }

  //取得所有儀器資料並採用ID為鍵值
  public static function findDeviceList()
  {
    $datas = Device::model()->findAll(array(
        'select' => '*',
        'order' => 'station DESC',
    ));
    $devices = array();
    if (count($datas) > 0) {

      foreach ($datas as $key => $value) {
        $devices[$value->id] = $value;
      }
      return $devices;

    } else {
      return $devices;
    }

  }

    //取得所有儀器資料並採用ID為鍵值
    public static function findDeviceListWithStation()
    {
        $datas = Device::model()->findAll(array(
            'select' => '*',
            'order' => 'station DESC',
        ));
        $devices = '';
        if (count($datas) > 0) {

            foreach ($datas as $key => $value) {
                $devices[$value->station] = $value;
            }
            return $devices;

        } else {
            return $devices;
        }

    }



  public function getallposition(){

    return ( Local::model()->findAll() );

  }
  // 新增放置位置
  public function create( $name ){
    
    $post = new Local;
    $post->name     = $name;
    $post->status   = 1;
    $post->create_date    = date("Y-m-d H:i:s");
    $post->edit_date      = date("Y-m-d H:i:s");

    if( $post->save() ){

      return array(true,'放置地點新增成功');

    }else{

      return array(false,$post->getErrors());
    }

  }

  // 新增裝置
  public function createdev($inputs){

    $model = new Device;
    $model->purchase_date = $inputs['purchase_date'];
    $model->available_year = $inputs['available_year'];
    $model->codenum = $inputs['codenum'];
    $model->name = $inputs['name'];
    $model->en_name = $inputs['en_name'];
    $model->position = $inputs['position'];
    $model->status = $inputs['status'];
    $model->attention_item = $inputs['attention_item'];
    $model->ip = $inputs['ip'];
    $model->station = $inputs['station'];
    $model->create_date    = date("Y-m-d H:i:s");
    $model->edit_date      = date("Y-m-d H:i:s");

    if( $model->save() ){
      return array(true,'設備新增成功');

    }else{

      return array(false,$model->getErrors());
    }

  }  
  
  // 抓出單一分類資料
  public function get_one_device( $id ){

    return( Device::model()->findbyPk($id) );

  }
  
  // 更新
  public function update($inputs){
    
    $model = Device::model()->findByPk($inputs['id']);

    $model->purchase_date = $inputs['purchase_date'];
    $model->available_year = $inputs['available_year'];
    $model->codenum = $inputs['codenum'];
    $model->name = $inputs['name'];
    $model->en_name = $inputs['en_name'];
    $model->position = $inputs['position'];
    $model->status = $inputs['status'];
    $model->attention_item = $inputs['attention_item'];
    $model->ip = $inputs['ip'];
    $model->station = $inputs['station'];
    $model->edit_date = date("Y-m-d H:i:s");

    if( $model->save() ){

      return array(true,'儀器更新成功');

    }else{
    
      return array(false,$post->getErrors());
    }

  }

  // 站號狀態更新
  public function update_by_station($id,$type,$use_name,$use_id){

    $model = Device::model()->findByPk($id);

    $model->type = (int)$type;
    $model->use_name = $use_name;
    $model->use_id = $use_id;
    $model->last_time = date("Y-m-d H:i:s");

    if($model->save()){
      return true;
    }else{
      return false;
    }

  }

  // 刪除
  public function del( $id ){

    $post = Device::model()->findByPk( $id ); 
    $post->delete();
    
  }

  // 刪除
  public function findByPk( $id ){

    $post = Device::model()->findByPk( $id );
    return $post;

  }
  
  // 依照站號抓取
  public function get_by_station( $station ){
    
    $criteria = new CDbCriteria();
    $criteria ->addCondition("station = :sation");
    $criteria->params = array(':sation' => $station );
    $data = Device::model()->findAll($criteria);
    
    if( $data ){

      return [ true  , $data[0]->id ];

    }else{
      
      return [ false , "" ];
      
    }

  }

  public function findDevAndLocal($id){
      $data = Yii::app()->db->createCommand()
          ->select('d.*,l.name as lname')
          ->from('device d')
          ->leftjoin('local l', 'd.position=l.id')
          ->where('d.id=:id',  array(':id'=>$id))
          ->order('station asc')
          ->queryall();

      return $data;
  }
  
  public function get_all_for_monitor(){
        $data = Yii::app()->db->createCommand()
        ->select('d.*,l.name as lname')
        ->from('device d')
        ->leftjoin('local l', 'd.position=l.id')
        ->order('station asc')
        ->queryall();

        return $data;
  }

  public function get_by_station_for_devrec( $station , $position ){

        $data = Yii::app()->db->createCommand()
        ->select('d.*,l.name as lname')
        ->from('device d')
        ->leftjoin('local l', 'd.position=l.id')
        ->where('d.station=:station', array(':station'=>$station))
        ->andwhere(array('like', 'l.name', "%$position%"))
        ->queryrow();

        return $data;    

  }

  public function get_by_station_type($station){

    $data = Yii::app()->db->createCommand()
        ->select('*')
        ->from('device')
        ->where('station=:station', array(':station'=>$station))
        ->queryrow();

    return $data;

  }
}
?>