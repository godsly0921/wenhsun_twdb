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
            $store_filter['keyword'] = array( '$all' => $explode_keyword ); 
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
        $filter = $this->storeFindPhotoFilter($single_id, $keyword, $category_id, $filming_date, $object_name);
        $option['projection'] = array('single_id'=>1,'people_info'=>1,'object_name'=>1,'filming_date'=>1,'filming_location'=>1,'keyword'=>1);
        $option['skip'] = ($page-1)*$limit;
        $option['limit'] = $limit;
        $result = $mongo->search_record('wenhsun', 'single', $filter, $option);
        return iterator_to_array($result);
    }
}
?>