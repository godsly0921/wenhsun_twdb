<?php
class PhotographService{
	

	public function findAllPhotograph(){
        $model = Single::model()->findAll();
        if(count($model)!=0){
            return $model;
        }else{
            return array();
        }
    }

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

    public function createImageQueue( $single_id, $width, $height ){
        $max_size_type = Imagemagick::getPhotographMaxSize( $width, $height );
        if( $max_size_type == '') {
            $max_size_type = 'source';
        }
        $image_queue = new imagequeue();
        $image_queue->single_id = $single_id;
        $image_queue->size_type = $max_size_type;
        $image_queue->create_time = date('Y-m-d h:i:s');
        if($image_queue->save()){
            return array('status'=>true,'data'=>$image_queue);
        }else{
            return array('status'=>false,'data'=>$image_queue);
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
        $single_size['single_id'] =  $single_id;
        $single_size['single_id'] =  $single_id;
        $single_size['single_id'] =  $single_id;
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