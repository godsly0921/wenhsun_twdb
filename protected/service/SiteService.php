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
                array('filming_name'=>array( '$regex' => $keyword )),
                array('object_name'=>array( '$regex' => $keyword )),
                array('people_info'=>array( '$regex' => $keyword )),
                array('description'=>array( '$regex' => $keyword )),
                array('photo_source'=>array( '$regex' => $keyword )),
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
        // 圖片狀態是已上架且已通過著作權審核 且為內外部都可以使用
        if(IpService::ipCheck()){//內部使用的圖片
            //var_dump('內部使用圖片');
            $filter['$and'] = array(array('copyright' => '1'),array('publish' => '1'),array('photo_limit'=>array( '$in' => array('1','2','3'))));
           
        }else{//僅限外部使用
            //var_dump('外部使用圖片');
            $filter['$and'] = array(array('copyright' => '1'),array('publish' => '1'),array('photo_limit'=>array( '$in' => array('1','3'))));
        }
        //$filter['$and'] = array(array('copyright' => '1'),array('publish' => '1'),array('photo_limit' => '1'));
        // 組合所有搜尋條件
        foreach ($store_filter as $key => $value) {
            array_push($filter['$and'], array($key=>$value));
        }
        return $filter;
    }
    /*
    $book_filter = array(
        "keyword" => $keyword,
        "author_id" => $author_id, // 作家
        "publish_unit_id" => $publish_unit_id, // 出版單位
        "book_category_id" => $book_category_id, // 作品分類
        "publish_year_s" => $publish_year_s, // 出版區間 - 年(開始)
        "publish_month_s" => $publish_month_s, // 出版區間 - 月(開始)
        "publish_day_s" => $publish_day_s, // 出版區間 - 日(開始)
        "publish_year_e" => $publish_year_e, // 出版區間 - 年(結束)
        "publish_month_e" => $publish_month_e, // 出版區間 - 月(結束)
        "publish_day_e" => $publish_day_e, // 出版區間 - 日(結束)
        "book_size" => $book_size, // 開本規格
        "series" => $series // 叢書名
    );
    */
    public function storeFindBookFilter($book_filter){
        // 關鍵字搜尋
        if( $book_filter["keyword"] != '' ){
            $explode_keyword = explode(',', $book_filter["keyword"]);
            //$store_filter['keyword'] = array( '$all' => $explode_keyword ); 
            $store_filter['$or'] = array(
                array('keyword'=>array( '$in' => $explode_keyword )),
                // array('book_num'=>array( '$regex' => $explode_keyword )),
                // array('summary'=>array( '$regex' => $explode_keyword )),
                // array('memo'=>array( '$regex' => $explode_keyword )),
                array('sub_author_name'=>array( '$in' => $explode_keyword ))
                // array('author_name'=>array( '$regex' => $explode_keyword ))
            );
            foreach ($explode_keyword as $key => $value) {
                $store_filter['$or'][]=array('book_num'=>array('$regex' => $value));
                $store_filter['$or'][]=array('summary'=>array('$regex' => $value));
                $store_filter['$or'][]=array('memo'=>array('$regex' => $value));
                $store_filter['$or'][]=array('author_name'=>array('$regex' => $value));
            }
        }
        // book_category_id 作品分類
        if( $book_filter["book_category_id"] != '' ){
            $explode_book_category_id = explode(',', $book_filter["book_category_id"]);
            $store_filter['category'] = array( '$all' => $explode_book_category_id );
        }     
        // author_id 作家
        if( $book_filter["author_id"] != '' ){
            $store_filter['author_id'] = $book_filter["author_id"];
        } 
        // publish_unit_id 出版單位
        if( $book_filter["publish_unit_id"] != '' ){
            $store_filter['publish_organization'] = $book_filter["publish_unit_id"];
        } 
        // book_size 開本規格
        if( $book_filter["book_size"] != '' ){
            $store_filter['book_size'] = $book_filter["book_size"];
        } 
        // series 叢書名
        if( $book_filter["series"] != '' ){
            $store_filter['series'] = $book_filter["series"];
        } 
        // 出版區間 - 年(開始)
        if( $book_filter["publish_year_s"] != '' ){
            $store_filter['$and']["publish_year"]["$gte"] = $book_filter["publish_year_s"];
        }
        // 出版區間 - 月(開始)
        if( $book_filter["publish_month_s"] != '' ){
            $store_filter['$and']["publish_month"]["$gte"] = $book_filter["publish_month_s"];
        }
        // 出版區間 - 日(開始)
        if( $book_filter["publish_day_s"] != '' ){
            $store_filter['$and']["publish_day"]["$gte"] = $book_filter["publish_day_s"];
        }

        // 出版區間 - 年(結束)
        if( $book_filter["publish_year_e"] != '' ){
            $store_filter['$and']["publish_year"]["$lte"] = $book_filter["publish_year_e"];
        }
        // 出版區間 - 月(結束)
        if( $book_filter["publish_month_e"] != '' ){
            $store_filter['$and']["publish_month"]["$lte"] = $book_filter["publish_month_e"];
        }
        // 出版區間 - 日(結束)
        if( $book_filter["publish_day_e"] != '' ){
            $store_filter['$and']["publish_day"]["$lte"] = $book_filter["publish_day_e"];
        }
        // 狀態是已上架
        $filter['$and'] = array(array('status' => '1'));
        // 組合所有搜尋條件
        foreach ($store_filter as $key => $value) {
            array_push($filter['$and'], array($key=>$value));
        }
        return $filter;
    }

    /*
    $book_filter = array(
        "keyword" => $keyword,
        "video_extension" => $video_extension,
        "video_category_id" => $video_category_id // 作品分類
    );
    */
    public function storeFindVideoFilter($video_filter){
        // 關鍵字搜尋
        if( $video_filter["keyword"] != '' ){
            $explode_keyword = explode(',', $video_filter["keyword"]);
            $store_filter['$or'] = array(
                array('name'=>array( '$in' => $explode_keyword ))
            );
            foreach ($explode_keyword as $key => $value) {
                $store_filter['$or'][]=array('description'=>array('$regex' => $value));
            }
        }
        // book_size 開本規格
        if( $video_filter["video_category_id"] != '' ){
            $explode_video_category_id = explode(',', $video_filter["video_category_id"]);
            $store_filter['category'] = array( '$all' => $explode_video_category_id );
        }     
        // video_extension 原始格式
        if( $video_filter["video_extension"] != '' ){
            $store_filter['extension'] = $video_filter["video_extension"];
        } 

        // 狀態是已上架
        $filter['$and'] = array(array('status' => '1'));
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

    public function findBookCount($book_filter){
        $filter = $option = $result = array();
        $total_result = 0;
        $mongo = new Mongo();
        $cmd = [
            'count' => "book",
            'query' => $this->storeFindBookFilter($book_filter)
        ];

        //$option['projection'] = array('single_id'=>1,'people_info'=>1,'object_name'=>1,'filming_date'=>1,'filming_location'=>1,'keyword'=>1);
        $result = $mongo->command('wenhsun', $cmd)->toArray();
        if (!empty($result)) {
            $total_result = $result[0]->n;
        }
        return $total_result;
    }

    public function findVideoCount($video_filter){
        $filter = $option = $result = array();
        $total_result = 0;
        $mongo = new Mongo();
        $cmd = [
            'count' => "video",
            'query' => $this->storeFindVideoFilter($video_filter)
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

    public function findBook($book_filter, $page, $limit){
        $filter = $option = $result = array();
        $mongo = new Mongo();
        $filter = $this->storeFindBookFilter($book_filter);
        $option['projection'] = array('book_id'=>1,'single_id'=>1,'book_name'=>1);
        $option['skip'] = ($page-1)*$limit;
        $option['limit'] = $limit;
        $result = $mongo->search_record('wenhsun', 'book', $filter, $option);
        return iterator_to_array($result);
    }
    public function findVideo($video_filter, $page, $limit){
        $filter = $option = $result = array();
        $mongo = new Mongo();
        $filter = $this->storeFindVideoFilter($video_filter);
        $option['projection'] = array('video_id'=>1,'m3u8_url'=>1,'name'=>1,'uuid_name'=>1);
        $option['skip'] = ($page-1)*$limit;
        $option['limit'] = $limit;
        $result = $mongo->search_record('wenhsun', 'video', $filter, $option);
        return iterator_to_array($result);
    }

    //尋找相同類型的圖片
    public function findSameCategory($category_id,$single_id){
        $store_filter = array();
        $mongo = new Mongo();
        $store_filter['category_id'] = array( '$all' => $category_id );
        $store_filter['single_id'] = array('$ne' => $single_id);
        // 圖片狀態是已上架且已通過著作權審核
        if(IpService::ipCheck()){//內部使用的圖片
            //var_dump('內部使用圖片');
            $filter['$and'] = array(array('copyright' => '1'),array('publish' => '1'));
        }else{//僅限外部使用
            //var_dump('外部使用圖片');
            $filter['$and'] = array(array('copyright' => '1'),array('publish' => '1'),array('photo_limit' => '1')); 
        }
        //$filter['$and'] = array(array('copyright' => '1'),array('publish' => '1'),array('photo_limit' => '1'));
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
      
        //0 不開放(都不提供) 1開放 2文訊使用 3API
        if(IpService::ipCheck()){//內部用戶
            
            $filming_date_range = Yii::app()->db->createCommand()
            ->select('DATE_FORMAT(MAX(filming_date),"%Y") as max_filming_date,DATE_FORMAT(MIN(filming_date),"%Y") as min_filming_date')
            ->from('single')
            ->where('copyright=:copyright and publish=:publish and photo_limit in(:photo_limit)', array(':copyright'=>1,':publish'=>1,':photo_limit'=>'1,2,3'))
            ->queryAll(); 
        }else{//外部使用 
            $filming_date_range = Yii::app()->db->createCommand()
            ->select('DATE_FORMAT(MAX(filming_date),"%Y") as max_filming_date,DATE_FORMAT(MIN(filming_date),"%Y") as min_filming_date')
            ->from('single')
            ->where('copyright=:copyright and publish=:publish and photo_limit in(:photo_limit)', array(':copyright'=>1,':publish'=>1,':photo_limit'=>'1,3'))
            ->queryAll();
        }
             
        
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
