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

    public function updateSingleSize ( $single_id, $input, $size_type ){
        $result = Singlesize::model()->find(array(
            'condition'=>'single_id=:single_id and size_type=:size_type',
            'params'=>array(
                ':single_id' => $single_id,
                ':size_type' => $size_type,
            )
        ));
        $single = Singlesize::model()->findByPk($result->single_size_id);
        foreach ($input as $key => $value) {
            $single->$key = $value;
        }
        if($single->save()){
            return array('status'=>true,'data'=>$single);
        }else{
            return array('status'=>false,'data'=>$single);
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

    public function updateAllSingle ( $single_id, $input ){
        Single::model()->updateAll($input, 'single_id in('.$single_id.')');
    }

    public function updateAllSingleSize ( $single_id, $size_type, $input ){
        Singlesize::model()->updateAll($input, 'single_id in('.$single_id.') and size_type="' . $size_type . '"');
    }
    public function storeUpdataSingle( $single_id, $photograph_data ){
        $single = Single::model()->findByPk($single_id);
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
    public function createImageQueue( $single_id, $width, $height, $ext ){
        $max_size_type = Imagemagick::getPhotographMaxSize( $width, $height );
        $status = true;
        if($ext != 'jpg'){
            $image_queue = new imagequeue();      
            $image_queue->single_id = $single_id;
            $image_queue->size_type = 'source';
            $image_queue->create_time = date('Y-m-d h:i:s');
            $image_queue->save();
            $single_size['single_id'] = $single_id;
            $single_size['size_type'] = 'source';
            $single_size['size_description'] = Imagemagick::$size_desc_map['source'];
            $single_size_create = $this->createSingleSize($single_size);
        }
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

    public function updateImageQueue($single_id, $size_type){
        $result = imagequeue::model()->find(array(
            'condition'=>'single_id=:single_id and size_type=:size_type',
            'params'=>array(
                ':single_id' => $single_id,
                ':size_type' => $size_type,
            )
        ));
        $image_queue = Imagequeue::model()->findByPk($result->image_queue_id);
        $image_queue->queue_status =1;
        $image_queue->done_time = date('Y-m-d h:i:s');
        $image_queue->save();
    }

    public function doImageQueue(){
        $sql = "SELECT iq.*,s.ext,s.dpi,s.color,s.direction FROM `image_queue` iq LEFT JOIN single s on iq.single_id = s.single_id where iq.queue_status = 0";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $single_id = '';
        $ds          = DIRECTORY_SEPARATOR;
        $storeFolder = PHOTOGRAPH_STORAGE_DIR;
        $size_bound_settings = Imagemagick::$size_bound_settings;
        foreach ($result as $key => $value) {
            $update_single_size = array();
               
            if($value['dpi'] == '' || $value['color'] == '' || $value['direction'] == '' || $value['size_type'] == 'source'){$targetPath =$storeFolder . 'source' . $ds;
                $targetFile = $targetPath . $value['single_id'] . "." . $value['ext'];
                if( $value['ext'] != 'jpg' ){
                    $targetPath = $storeFolder . 'source_to_jpg' . $ds;
                    $targetFile = $targetPath . $value['single_id'] . ".jpg";
                }                     
                $single_size = $this->getPhotographData($targetFile);
                $single = array();
                $single['dpi'] = $single_size['resolution'];
                $single['color'] = $single_size['colorspace'];
                $single['direction'] = $single_size['direction'];
                $this->updateSingle( $value['single_id'], $single );
                $file_size = filesize($targetFile);
                $update_single_size['dpi'] = $single_size['resolution'];
                $update_single_size['mp'] = $single_size['mp'];
                $update_single_size['w_h'] = $single_size['w_h'];
                $update_single_size['print_w_h'] = $single_size['print_w_h'];
                $update_single_size['file_size'] = $file_size;
                $update_single_size['ext'] = $value['ext'];
                $this->updateSingleSize( $value['single_id'], $update_single_size, 'source' );
                $this->updateImageQueue($value['single_id'],'source');
            }else{
                $targetPath = $storeFolder . $value['size_type'] . $ds;
                $targetFile = $targetPath . $value['single_id'] . ".jpg";
                if($value['size_type'] != 'source'){
                    Imagemagick::PhotographScaleConvert( $targetPath, $value['single_id'], $value['size_type'] );
                }
                list($width, $height) = getimagesize($targetFile);
                $getPhotographScale = $width . 'x' . $height;
                $getPhotographMP = Imagemagick::getPhotographMP( $width, $height );
                $dpi = $size_bound_settings[$value['size_type']]['dpi'];
                $print_w_h = Imagemagick::get_print_datas( $width/$dpi . 'x' . $height/$dpi, $value['dpi'] );
                
                $file_size = filesize($targetFile);
                $update_single_size['dpi'] = $dpi;
                $update_single_size['mp'] = $getPhotographMP;
                $update_single_size['w_h'] = $getPhotographScale;
                $update_single_size['print_w_h'] = $print_w_h;
                $update_single_size['file_size'] = $file_size;
                $update_single_size['ext'] = 'jpg';
                $this->updateSingleSize( $value['single_id'], $update_single_size, $value['size_type'] );
                $this->updateImageQueue($value['single_id'],$value['size_type']);
            }
                    
        }
        
    }

    public function getPhotographData($targetFile){
    	$photograph_data = Imagemagick::get_graph_data( $targetFile );
        $explode_w_h = explode('x', $photograph_data['w_h']);
        $getPhotographMP = Imagemagick::getPhotographMP( $explode_w_h[0], $explode_w_h[1] );
    	$photograph_data['mp'] = $getPhotographMP;
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