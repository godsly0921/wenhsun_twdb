<?php
class PhotographController extends Controller{
    // layout
    public $layout = "//layouts/back_end";
    
    // 登入驗證
    protected function needLogin(): bool
    {
        return true;
    }

    public function Actionnew(){
        $category_service = new CategoryService();
        $category_data = $category_service->findCategoryMate();
        $this->render('new',array('category_data'=>$category_data));
    }
    public function ActionBatUploadFile(){
        // 如果檔案不為空，則上傳
        $time_start = microtime(true);
        if (!empty($_FILES['file'])) { 
            $photographService = new PhotographService();
            $return_data = $single_data = array();
            $ds          = DIRECTORY_SEPARATOR; // '/'
            $storeFolder = PHOTOGRAPH_STORAGE_DIR; //檔案儲存的路徑
            $targetPath = $storeFolder . 'source' . $ds; // 原始檔存放路徑
            $tempFile   = $_FILES['file']['tmp_name']; //上傳檔案的暫存
            $fileName = $_FILES['file']['name']; //上傳檔案的檔名
            $fileSize = $_FILES['file']['size']; //上傳檔案的檔案大小
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $exist_filename = $photographService->existPhotoNameExist($fileName); // 查詢此張圖片是否有上傳過，用原始檔名判斷
                if(!$exist_filename){
                    $single_data['photo_name'] = $fileName;
                    $ext = explode('.', $fileName);
                    $ext = strtolower(end($ext));
                    $single_data['ext'] = $ext;
                    $single = $photographService->createSingleBase($single_data); // 先存圖片檔名、檔案格式進資料庫
                    if($single['status']){
                        $single =  $single['data'];
                        $targetFile =  $targetPath . $single->single_id . "." . $ext; // 暫時用 single 資料表的流水號做圖檔命名
                        if ( move_uploaded_file($tempFile,$targetFile) ) {
                            if($ext !='jpg'){
                                Imagemagick::SourcePhotographToJpgConvert( $single->single_id, $ext );//若不是 jpg 的圖檔，需先轉成 jpg
                                Imagemagick::build_o_p( $storeFolder . 'source_to_jpg' . $ds, $single->single_id ); //背景執行切縮圖
                                $targetFile =  $storeFolder . 'source_to_jpg' . $ds . $single->single_id . ".jpg"; // 暫時用 single 
                            }else{
                                Imagemagick::build_o_p( $storeFolder . 'source' . $ds, $single->single_id );//背景執行切縮圖
                            }
                            list($width, $height) = getimagesize($targetFile);
                            $create_image_queue = $photographService->createImageQueue( $single->single_id, $width, $height, $ext ); // 切圖佇列資料寫入
                            $return_data[] = array(
                                'single_id' => $single->single_id,
                                'fileName' => $fileName,
                                'fileSize' => $fileSize,
                                'status' => true,
                                'errorMsg' => ''
                            );
                            $time = microtime(true) - $time_start;
                            $return_data['runtime'] = $time;
                            #echo json_encode($return_data);exit();
                        }else{
                            $return_data[] = array(
                                'fileName' => $fileName,
                                'fileSize' => $fileSize,
                                'status' => false,
                                'errorMsg' => 'upload image failed'
                            );
                            $time = microtime(true) - $time_start;
                            $return_data['runtime'] = $time;
                            echo json_encode($return_data);exit();
                        }
                    }
                }else{
                    $return_data[] = array(
                        'fileName' => $fileName,
                        'fileSize' => $fileSize,
                        'status' => false,
                        'errorMsg' => $fileName . ' is already exists'
                    );
                    $return_data['append'] = false;
                    $time = microtime(true) - $time_start;
                    $return_data['runtime'] = $time;
                    echo json_encode($return_data);exit();
                }                
                $transaction->commit();
                echo json_encode($return_data);exit();
            }catch(Exception $e){
                $transaction->rollback();
                $return_data[] = array(
                    'fileName' => '',
                    'fileSize' => '',
                    'status' => false,
                    'errorMsg' => 'unknown failed'
                );
                $time = microtime(true) - $time_start;
                $return_data['runtime'] = $time;
                echo $e;
                echo json_encode($return_data);exit();
                exit();
            }
        }else{
            $return_data[] = array(
                'fileName' => '',
                'fileSize' => '',
                'status' => false,
            );
            $time = microtime(true) - $time_start;
            $return_data['runtime'] = $time;
            echo json_encode($return_data);exit();
        }
    }

    public function ActionPhotographData(){
        if( Yii::app()->request->isPostRequest ){
            $single = array();
            $photographService = new PhotographService();
            $single_size_price = $single_data = $single_size = array();
            parse_str($_POST['single_size_price'], $single_size_price);
            parse_str($_POST['single_data'], $single_data);
            $single_data['category_id'] = implode(',', $single_data['category_id']);
            $single_data['keyword'] = $_POST['keywords_data'];
            $single_data['filming_date'] = $single_data['filming_date']==''?NULL:$single_data['filming_date'];
            $photographService->updateAllSingle($_POST['update_single_ids'], $single_data);
            foreach ($single_size_price['twd'] as $key => $value) {
                $single_size = array();
                $single_size['sale_twd'] = $value;
                $single_size['sale_point'] = $single_size_price['point'][$key];
                $photographService->updateAllSingleSize($_POST['update_single_ids'], $key, $single_size);
            }
            echo true;exit();
        }else{
            echo false;exit();
        }
    }

    public function Actionlist(){
        $photographService = new PhotographService();
        $photograph_data = array();
        // $photograph_data = $photographService->findAllPhotograph();
        $data = [];
        // if($photograph_data){
        //     foreach ($photograph_data as $key => $value) {
        //         $data[] = [
        //             "img_base_info" => $value['single_id'],
        //             "filming_name" => $value['filming_name'],
        //             "copyright" => $value['copyright'] == 0 ? '不通過' : '通過',
        //             "publish" => $value['publish'] == 0 ?'否':'是',
        //             "percent" => round($value['percent'],2) . "%",
        //             "create_time" => $value['create_time'],
        //             "edit" => '<a class="oprate-right" href="'. Yii::app()->createUrl('photograph/update/') . '/' . $value['single_id'] . '"><i class="fa fa-pencil-square-o fa-lg"></i></a>',
        //             "delete" => '<a class="oprate-right oprate-del" data-mem-id="' . $value['single_id'] . '" data-mem-name="' . $value['single_id'] .'"><i class="fa fa-times fa-lg"></i></a>',
        //         ];
        //     }
        // }
        $this->render('list',['data' => $data]);
    }
    public function ActionAjaxPhotographList(){
        // 獲取 DataTable 發送的參數
        $draw = $_POST['draw'];
        $start = $_POST['start']; // 當前頁的起始記錄
        $length = $_POST['length']; // 每頁顯示多少條數據
        $search = $_POST['search'] ? $_POST['search'] : ""; // 搜索框中的搜尋條件
        $photographService = new PhotographService();
        $photograph_data = array();
        $photograph_data = $photographService->findPhotographWithPageLimit($_POST['search'], $length, $start);
        $data = [];
        if($photograph_data){
            foreach ($photograph_data as $key => $value) {
                $data[] = [
                    "img_base_info" => '<img src="'. Yii::app()->createUrl('/') . '/image_storage/P/' . $value['single_id']. '.jpg"><br/><center>圖片編號：' . $value['single_id'] .'</center>',
                    "filming_name" => $value['filming_name'],
                    "copyright" => $value['copyright'] == 0 ? '不通過' : '通過',
                    "publish" => $value['publish'] == 0 ?'否':'是',
                    "percent" => round($value['percent'],2) . "%",
                    "create_time" => $value['create_time'],
                    "edit" => '
                        <a class="oprate-right" href="'. Yii::app()->createUrl('photograph/update/') . '/' . $value['single_id'] . '"><i class="fa fa-pencil-square-o fa-lg"></i></a>
                        <a class="oprate-right oprate-del" data-mem-id="' . $value['single_id'] . '" data-mem-name="' . $value['single_id'] .'"><i class="fa fa-times fa-lg"></i></a>
                    '
                ];
            }
        }
        $recordsTotal = $photographService->countPhotograph($search);
        // 返回 DataTable 所需的數據格式
        $response = [
            "draw" => $draw,
            "recordsTotal" => (int)$recordsTotal['total'],
            "recordsFiltered" => (int)$recordsTotal['total'],  // 可以根據過濾條件更改這裡
            "data" => $data
        ];

        echo json_encode($response);
    }
    public function ActionUpdateSingle(){
        $photographService = new PhotographService();      
        $single_data = array();
        parse_str($_POST['single_data'], $single_data);
        $single_data['copyright'] = $_POST["copyright"];
        $single_data['publish'] = $_POST["publish"];
        $single_data['category_id'] = implode(',', $single_data['category_id']);
        $single_id = $_POST['single_id'];
        $single_data['filming_date'] = $single_data['filming_date']==''?NULL:$single_data['filming_date'];
        //var_dump($single_data);exit();
        $result = $photographService->updateSingle( $single_id, $single_data );
        echo json_encode($result);exit();
    }

    public function ActionUpdateSingleSize(){
        $photographService = new PhotographService();
        $single_size_price = array();
        parse_str($_POST['single_size_price'], $single_size_price);
        foreach ($single_size_price['sale_twd'] as $key => $value) {
            $single_size = array();
            $single_size['sale_twd'] = $value==''?0:$value;
            $single_size['sale_point'] = $single_size_price['sale_point'][$key]==''?0:$single_size_price['sale_point'][$key];
            $photographService->updateAllSingleSize($_POST['single_id'], $key, $single_size);
        }
        echo json_encode(array('status'=>true));exit(); 
    }

    public function ActionUpdate($id){
        $photographService = new PhotographService();
        $category_service = new CategoryService();
        $photograph_data = $photographService->findSingleAndSinglesize($id);    
        $category_data = $category_service->findCategoryMate();
        $this->render('update',array( 'photograph_data' => $photograph_data, 'category_data' => $category_data ));
    }

    public function ActionDelete(){
        $id = $_POST['id'];
        $photographService = new PhotographService();
        if($id != '')
            $photograph_delete = $photographService->deletePhotograph($id);
        $this->redirect(Yii::app()->createUrl('photograph/list'));
    }

    public function ActionUpdateMongoAll(){
        $sql = "SELECT * FROM `single`";
        $row = array();
        $datas = Yii::app()->db->createCommand($sql)->queryAll();
        $mongo = new Mongo();
        $i = 0;
        foreach ($datas as $key => $value) {
            $row = array(
                "single_id"=>$value["single_id"],
                "photo_name"=>$value["photo_name"],
                "ext"=>$value["ext"],
                "dpi"=>$value["dpi"],
                "color"=>$value["color"],
                "direction"=>$value["direction"],
                "author"=>$value["author"],
                "photo_source"=>$value["photo_source"],
                "category_id"=>explode(',', $value["category_id"]),
                "filming_date"=>$value["filming_date"],
                "filming_date_text"=>$value["filming_date_text"],
                "filming_location"=>$value["filming_location"],
                "filming_name"=>$value["filming_name"],
                "store_status"=>$value["store_status"],
                "people_info"=>$value["people_info"],
                "object_name"=>$value["object_name"],
                "event_name"=>$value["event_name"],
                "keyword"=>explode(',', $value["keyword"]),
                "index_limit"=>$value["index_limit"],
                "original_limit"=>$value["original_limit"],
                "photo_limit"=>$value["photo_limit"],
                "description"=>$value["description"],
                "publish"=>$value["publish"],
                "copyright"=>$value["copyright"],
                "authorization_status"=>$value["authorization_status"],
                "memo1"=>$value["memo1"],
                "memo2"=>$value["memo2"],
                "create_time"=>$value["create_time"],
                "create_account_id"=>$value["create_account_id"]
            );
            $update_find = array('single_id'=>$value["single_id"]);
            $update_input = array('$set' => $row);
            $mongo->update_record('wenhsun', 'single', $update_find, $update_input);
            $i++;
        }
        echo "已完成更新，共更新 " .$i . "筆";
    }
}
?>
