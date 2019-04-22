<?php
class PhotographService{
    //找出所有的圖片 - 圖片列表
	public function findAllPhotograph(){
        $sql = "SELECT *,((select count(*) from image_queue where s.single_id=single_id and queue_status=1)/(select count(*) from image_queue where s.single_id=single_id))*100 as percent FROM `single` s";
        $model = Yii::app()->db->createCommand($sql)->queryAll();
        if(count($model)!=0){
            return $model;
        }else{
            return array();
        }
    }

    //搜尋圖片原始檔名
    public function existPhotoNameExist( $photo_name ){
         $result = Single::model()->find(array(
            'condition'=>'photo_name=:photo_name',
            'params'=>array(
                ':photo_name' => $photo_name,
            )
        ));

        return ($result == false) ? false : true;
    }

    // 圖片上架時儲存的圖片資訊 - 是最初始的值
    public function createSingleBase( $input ){
    	$single = new Single();
    	foreach ($input as $key => $value) {
    		$single->$key = $value;
    	}
    	$single->create_time = date('Y-m-d h:i:s');
    	$single->create_account_id = Yii::app()->session['uid'];
    	if($single->save()){
    		return array('status'=>true,'data'=>$single);
    	}else{
    		return array('status'=>false,'data'=>$single);
    	}
    }
	// 圖片上架時儲存的圖片資訊 - 是最初始的值
    public function createSingleSize( $input ){
    	$single_size = new Singlesize();
    	foreach ($input as $key => $value) {
    		$single_size->$key = $value;
    	}
    	if($single_size->save()){
    		return array('status'=>true,'data'=>$single_size);
    	}else{
    		return array('status'=>false,'data'=>$single_size);
    	}
    }
    public function updateSingle ( $single_id, $input ){
    	$single = Single::model()->findByPk($single_id);;
    	foreach ($input as $key => $value) {
    		$single->$key = $value;
    	}
    	if($single->save()){
    		return array('status'=>true,'data'=>$single);
    	}else{
    		return array('status'=>false,'data'=>$single);
    	}
    }

    public function storeUpdataSingle( $single_id, $photograph_data ){
        $single = Single::model()->findByPk($single_id);;
        $single_data = array();
        $single_data['dpi'] = $photograph_data['resolution'];
        $single_data['color'] = $photograph_data['colorspace'];
        $single_data['direction'] = $photograph_data['direction'];
        $update_single = $this->updateSingle( $single->single_id, $single_data );
        //var_dump($update_single);exit();
        if( $update_single['status'] ){
            return array( 'status' => true, 'data' => $update_single['data'] );
        }else{
            return array( 'status' => false, 'data' => $update_single['data'] );
        }
    }

    //建立切圖佇列資料
    public function createImageQueue( $single_id, $width, $height ){
        $max_size_type = Imagemagick::getPhotographMaxSize( $width, $height );
        $status = true;
        
        foreach ($max_size_type as $key => $value) {    
            $image_queue = new imagequeue();      
            $image_queue->single_id = $single_id;
            $image_queue->size_type = $value;
            $image_queue->create_time = date('Y-m-d h:i:s');
            $image_queue->save();
            $single_size['single_id'] = $single_id;
            $single_size['size_type'] = $value;
            $single_size['size_description'] = Imagemagick::$size_desc_map[$value];
            $single_size_create = $this->createSingleSize($single_size);
        }       
    }

    public function doImageQueue(){
       $sql = "SELECT iq.*,s.ext,s.dpi,s.color,s.direction FROM `image_queue` iq LEFT JOIN single s on iq.single_id = s.single_id where iq.queue_status = 0 order by iq.single_id";
       $result = Yii::app()->db->createCommand($sql)->queryAll();
        $single_id = '';
        $ds          = DIRECTORY_SEPARATOR;
        $storeFolder = PHOTOGRAPH_STORAGE_DIR;
        $size_bound_settings = Imagemagick::$size_bound_settings;
        foreach ($result as $key => $value) {
            $targetPath = $storeFolder . 'source_to_jpg' . $ds;
            $targetFile = $targetPath . $value['single_id'] . "." . $value['ext'];
            if($value['dpi'] == '' || $value['color'] == '' || $value['direction'] == ''){
                Imagemagick::SourcePhotographToJpgConvert( $value['single_id'], $value['ext'] );                
                $single_size = $photographService->getPhotographData($targetFile);
                $single = array();
                $value['dpi'] = $single_size['dpi'];
                $single['dpi'] = $single_size['dpi'];
                $single['color'] = $single_size['colorspace'];
                $single['direction'] = $photograph_data['direction'];
                $photographService->updateSingle( $single->single_id, $single );
            }
            list($width, $height) = getimagesize($targetFile);
            $getPhotographScale = Imagemagick::getPhotographscale( $width, $height, $value['ext'] );
            $getPhotographMP = Imagemagick::getPhotographMP( $width, $height );
            $dpi = $size_bound_settings[$value['size_type']]['dpi'];
            $print_w_h = Imagemagick::get_print_datas( $width . 'x' . $height, $value['dpi'] );
            Imagemagick::buildFiveKindsSizes($file_name, $file_rename, $graph_type, $dpi);
        }
        
    }

    public function getPhotographData($targetFile){
    	$photograph_data = Imagemagick::get_graph_data( $targetFile );
    	$photograph_data['mp'] = Imagemagick::getPhotographMP( $photograph_data['w_h'] );
    	return $photograph_data;
    }
    public function getPhotographMaxSize( $single_id, $width, $height ){
        $single_size = array();
        $single_size['single_id'] =  $single_id;
        $single_size['size_type'] =  $single_id;
        $single_size['size_description'] =  $single_id;
    	$max_size_data = Imagemagick::get_size_datas($w_h, $photograph_data['dpi'], $photograph_data['colorspace']);
    	foreach ($max_size_data as $key => $value) {
 			$single_size = array();
 			$single_size['single_id'] = $single_id;   	
 			$single_size['size_type'] = $key;
 			$single_size['size_description'] = Imagemagick::$size_desc_map[$key];
 			$single_size['dpi'] = $value['dpi'];
 			$single_size['mp'] = $value['mp'];
 			$single_size['w_h'] = $value['w_h'];
 			$single_size['print_w_h'] = $photograph_data['print_w_h'];
 			$single_size['file_size'] = 0;
 			$single_size['ext'] = 'jpg';
 			$single_size['sale_twd'] = $single_size_price['twd'][$key];
 			$single_size['sale_point'] = $single_size_price['point'][$key];
    	}
    	return $single_size;
    }
}
?>