<?php
/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 上午 10:46
 */
class SiteService
{
    public function storeFindPhotoFilter($single_id, $keyword, $category_id, $filming_date, $object_name){
        $filter = $store_filter = array();
        // 圖號搜尋
        if( $single_id != '' ){
            $store_filter['single_id'] = $single_id;          
        }
        // 關鍵字搜尋
        if( $keyword != '' ){
            $explode_keyword = explode(',', $keyword);
            //$store_filter['keyword'] = array( '$all' => $explode_keyword ); 
            $store_filter['$or'] = array(
                array('keyword'=>array( '$in' => $explode_keyword )),
                // array('author'=>array( '$in' => $explode_keyword )),
                array('people_info'=>array( '$regex' => $keyword )),
                array('event_name'=>array( '$regex' => $keyword )),
                array('filming_location'=>array( '$regex' => $keyword )),
                array('filming_date'=>array( '$regex' => $keyword )),
                array('filming_date_text'=>array( '$regex' => $keyword ))
            );
        }
        // 分類搜尋
        if( $category_id != '' ){
            $store_filter['category_id'] = array( '$all' => $category_id );
        }       
        // 照片時代搜尋
        if( $filming_date != '' ){
            $explode_filming_date = explode('-', $filming_date);
            $min_date = $explode_filming_date[0]!=''?$explode_filming_date[0]:0;
            $max_date = $explode_filming_date[1];
            $store_filter['$and'] = array(
                array('filming_date'=>array('$gte'=>$min_date)),
                array('filming_date'=>array('$lt'=>$max_date))
            );
        }
        // 作品名稱搜尋
        if( $object_name != '' ){
            $store_filter['object_name'] = $object_name;
        }
        // 圖片狀態是已上架且已通過著作權審核
        $filter['$and'] = array(array('copyright' => '1'),array('publish' => '1'));
        // 組合所有搜尋條件
        foreach ($store_filter as $key => $value) {
            array_push($filter['$and'], array($key=>$value));
        }
        return $filter;
    }

	public function findPhotoCount($single_id, $keyword, $category_id, $filming_date, $object_name){
		$filter = $option = $result = array();
        $total_result = 0;
        $mongo = new Mongo();
        $cmd = [
            'count' => "single",
            'query' => $this->storeFindPhotoFilter($single_id, $keyword, $category_id, $filming_date, $object_name)
        ];

        //$option['projection'] = array('single_id'=>1,'people_info'=>1,'object_name'=>1,'filming_date'=>1,'filming_location'=>1,'keyword'=>1);
        $result = $mongo->command('wenhsun', $cmd)->toArray();
        if (!empty($result)) {
            $total_result = $result[0]->n;
        }
        return $total_result;
	}
    public function findPhoto($single_id, $keyword, $category_id, $filming_date, $object_name, $page, $limit){
        $filter = $option = $result = array();
        $mongo = new Mongo();
        $filter = $this->storeFindPhotoFilter($single_id, $keyword, $category_id, $filming_date, $object_name, $single_id);
        $option['projection'] = array('single_id'=>1,'people_info'=>1,'object_name'=>1,'filming_date'=>1,'filming_location'=>1,'keyword'=>1);
        $option['skip'] = ($page-1)*$limit;
        $option['limit'] = $limit;
        $result = $mongo->search_record('wenhsun', 'single', $filter, $option);
        return iterator_to_array($result);
    }

    //尋找相同類型的圖片
    public function findSameCategory($category_id,$single_id){
        $store_filter = array();
        $mongo = new Mongo();
        $store_filter['category_id'] = array( '$all' => $category_id );
        $store_filter['single_id'] = array('$ne' => $single_id);
        // 圖片狀態是已上架且已通過著作權審核
        $filter['$and'] = array(array('copyright' => '1'),array('publish' => '1'));
        // 組合所有搜尋條件
        foreach ($store_filter as $key => $value) {
            array_push($filter['$and'], array($key=>$value));
        }
        $option['projection'] = array('single_id'=>1,'people_info'=>1,'object_name'=>1,'filming_date'=>1,'filming_location'=>1,'keyword'=>1);
        $option['limit'] = 10;
        $result = $mongo->search_record('wenhsun', 'single', $filter, $option);
        return iterator_to_array($result);
    }
    public function findPhotoFilmingRange(){
        $filming_date_range = array();
        $min_year = $max_year = 0;
        $filming_date_range = Yii::app()->db->createCommand()
        ->select('DATE_FORMAT(MAX(filming_date),"%Y") as max_filming_date,DATE_FORMAT(MIN(filming_date),"%Y") as min_filming_date')
        ->from('single')
        ->where('copyright=:copyright and publish=:publish', array(':copyright'=>1,':publish'=>1))
        ->queryAll();
        if($filming_date_range && $filming_date_range[0]['min_filming_date'] != NULL && $filming_date_range[0]['max_filming_date'] != NULL){
            if(($filming_date_range[0]['min_filming_date']%10)!=0){
                $min_year = $filming_date_range[0]['min_filming_date']-($filming_date_range[0]['min_filming_date']%10);
            }else{
                $min_year = $filming_date_range[0]['min_filming_date'];
            }

            if(($filming_date_range[0]['max_filming_date']%10)!=0){
                $max_year = $filming_date_range[0]['max_filming_date'] + (10 - ($filming_date_range[0]['max_filming_date']%10));
            }else{
                $max_year = $filming_date_range[0]['max_filming_date'];
            }
        }else{
            $min_year = 1911;
            $max_year = date('Y');
        }
        $filming_date_range = array();
        if($min_year != $max_year){
            for ($i=$min_year; $i<=$max_year ; $i+=10) {
                array_push($filming_date_range, $i);
            }
        }
        $ticks_positions = array();
        for ($i=0; $i<count($filming_date_range); $i++) {
            if($i == count($filming_date_range)-1) array_push($ticks_positions, 100);
            else array_push($ticks_positions, $i*ceil(100/count($filming_date_range)));
        }
        return array('filming_date_range'=>$filming_date_range,'ticks_positions'=>$ticks_positions);
    }

    public function findPhotoObjectname(){
        $distinct_object_name  = Yii::app()->db->createCommand()
        ->select('DISTINCT(object_name) as distinct_object_name')
        ->from('single')
        ->where('copyright=:copyright and publish=:publish and object_name != ""', array(':copyright'=>1,':publish'=>1))
        ->queryAll(); 
        return $distinct_object_name;
    }
}
?>