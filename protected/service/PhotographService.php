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
    public function getPhotographData($targetFile){
    	$single_size = array();
    	$photograph_data = Imagemagick::get_graph_data( $targetFile );
    	$photograph_data['mp'] = Imagemagick::get_pixel_sizes( $photograph_data['w_h'], $photograph_data['colorspace'] );
    	$single_size['dpi'] = $photograph_data['resolution'];
    	$single_size['mp'] = $photograph_data['mp'];
    	$single_size['w_h'] = $photograph_data['w_h'];
    	$single_size['print_w_h'] = $photograph_data['print_w_h'];
    	return array( 'single_size'=>$single_size, 'photograph_data' => $photograph_data);
    }
    public function getPhotographMaxSize( $single_id, $single_size_price, $photograph_data ){
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