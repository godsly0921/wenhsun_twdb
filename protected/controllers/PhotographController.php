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
                    $ext = explode('.', $fileName)[1];
                    $ext = strtolower($ext);
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
        $photograph_data = $photographService->findAllPhotograph();
        $this->render('list',['photograph_data'=>$photograph_data]);
    }

    public function ActionUpdate($id){
        $photographService = new PhotographService();
        $category_service = new CategoryService();
        $photograph_data = $photographService->findSingleAndSinglesize($id);    
        $category_data = $category_service->findCategoryMate();
        $this->render('update',array( 'photograph_data' => $photograph_data, 'category_data' => $category_data ));
    }

    public function ActionFileDelete(){
        $category_id = $_POST['id'];
        $post = Category::model()->findByPk( $category_id );
        if($post->isroot==1){
            Category::model()->deleteAll(array(
                'condition' => "parents=:parents",   
                'params' => array(':parents' => $category_id ),    
            ));    
        }
        $post->delete();
        $this->redirect(Yii::app()->createUrl('category/list'));
    }
}
?>