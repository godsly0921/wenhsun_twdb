<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 上午 10:46
 */
class SiteService
{
	public function findPhoto($single_id, $keyword, $category_id, $filming_date, $object_name, $page, $limit){
		$filter = $store_filter = $option = $result = array();
        $mongo = new Mongo();
        if( $single_id != '' ){
            $store_filter['single_id'] = $single_id;          
        }

        if( $keyword != '' ){
        	$explode_keyword = explode(',', $keyword);
        	if(count($explode_keyword)>1){
        		$keyword_filter = array();
        		foreach ($explode_keyword as $key => $value) {
	        		$keyword_filter['$or'][] = array('$regex' => new MongoDB\BSON\Regex ($value));
	        	}
	        	$store_filter['keyword'] = $keyword_filter;
        	}else{
        		$store_filter['keyword'] = array( '$regex' => new MongoDB\BSON\Regex ($keyword));
        	}     
        }

        if( $category_id != '' ){
            $store_filter['category_id'] = array( '$in' => $category_id );
        }
        
        if( $filming_date != '' ){
        	$explode_filming_date = explode('-', $filming_date);
        	$min_date = $explode_filming_date[0]!=''?$explode_filming_date[0]:0;
        	$max_date = $explode_filming_date[1];
        	$store_filter['$and'] = array(
				array('filming_date'=>array('$gte'=>$min_date)),
				array('filming_date'=>array('$lt'=>$max_date))
			);
            //$store_filter['filming_date'] = array( '$regex' => '/' . $filming_date . '/' );
        }

        if( $object_name != '' ){
            $store_filter['object_name'] = array( '$regex' =>  new MongoDB\BSON\Regex ($object_name));
        }

        if(isset($store_filter['$and'])){
        	$filter['$and'] = array_merge($store_filter['$and'], array(array('copyright' => '1'),array('publish' => '1')));
        	unset($store_filter['$and']);
        }else{
        	$filter['$and'] = array(array('copyright' => '1'),array('publish' => '1'));
        } 

        if(count($store_filter) > 1){
            $filter['$or'] = array(); 
            foreach ($store_filter as $key => $value) {
                array_push($filter['$or'], array($key=>$value));
            }
        }else{
            $filter = $store_filter; 
        }

        $option['projection'] = array('single_id'=>1,'people_info'=>1,'object_name'=>1,'filming_date'=>1,'filming_location'=>1,'keyword'=>1);
        $result = $mongo->search_record('wenhsun', 'single', $filter, $option);
        echo json_encode(iterator_to_array($result));exit();
        return $result;
	}
}
?>