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

    public function findSingleAndSinglesize($single_id){
        $sql = "SELECT * FROM `single` s LEFT JOIN single_size ss on s.single_id = ss.single_id where s.single_id =" . $single_id . " order by ss.single_size_id asc";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $data = array();
        foreach ($result as $key => $value) {
            if($key == 0){
                $data['source'] = array(
                    'size_type' => $value['size_type'],
                    'size_description' => $value['size_description'],
                    'dpi' => $value['dpi'],
                    'mp' => $value['mp'],
                    'w_h' => $value['w_h'],
                    'print_w_h' => $value['print_w_h'],
                    'file_size' => round($value['file_size']/1024/1024,2) . " MB",
                    'ext' => $value['ext'],
                    'color' => $value['color'],
                );
                $category_sql = 'SELECT a.name as child_name,b.name as parent_name FROM `category` a join category b on a.parents=b.category_id where a.category_id in('.$value['category_id'].')';
                $category_result = Yii::app()->db->createCommand($category_sql)->queryAll();
                $category = array();
                foreach ($category_result as $category_key => $category_value) {
                    $txt = $category_value['parent_name'] . ' => ' . $category_value['child_name'];
                    array_push($category, $txt);
                }
                $data['image'] = DOMAIN . 'image_storage/S/' . $value['single_id'] . '.jpg';
                $data['photograph_info'] = array(
                    'object_name' => $value['object_name'],
                    'photo_name' => $value['photo_name'],
                    'single_id' => $value['single_id'],
                    'description' => $value['description'],
                    'people_info' => $value['people_info'],
                    'filming_date' => $value['filming_date'],
                    'filming_location' => $value['filming_location'],
                    'filming_name' => $value['filming_name'],
                    'category_id' => explode(',', $value['category_id']),
                    'category_name' => implode('<br/>', $category),
                    'keyword' => $value['keyword'],
                    'memo1' => $value['memo1'],
                    'memo2' => $value['memo2'],
                    'store_status' => $value['store_status'],
                    'index_limit' => $value['index_limit'],
                    'original_limit' => $value['original_limit'],
                    'photo_limit' => $value['photo_limit'],
                    'publish' => $value['publish'],
                    'copyright' => $value['copyright'],
                );
            }else{
                $data['size'][] = array(
                    'size_type' => $value['size_type'],
                    // 'size_description' => $value['size_description'],
                    // 'dpi' => $value['dpi'],
                    // 'mp' => $value['mp'],
                    'w_h' => $value['w_h'],
                    // 'print_w_h' => $value['print_w_h'],
                    'file_size' => round($value['file_size']/1024/1024,2) . " MB",
                    'sale_twd' => $value['sale_twd'],
                    'sale_point' => $value['sale_point'],
                );
            }        
        }
        #echo json_encode($data);exit();
        return $data;
    }

    //搜尋圖片原始檔名
    public function existPhotoNameExist($photo_name){
        $result = Single::model()->find(array(
            'condition'=>'photo_name=:photo_name',
            'params'=>array(
                ':photo_name' => $photo_name
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

    //更新佇列狀態
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