<?php
class PhotographController extends Controller{
    // layout
    public $layout = "//layouts/back_end";
    
    // 登入驗證
    protected function needLogin(): bool
    {
        return true;
    }

    public function ActionImageQueue(){
        $photographService = new PhotographService();
        $photographService->doImageQueue();
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
                $exist_filename = $photographService->existPhotoNameExist($fileName);
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
                                Imagemagick::SourcePhotographToJpgConvert( $single->single_id, $ext );
                                $targetFile =  $storeFolder . 'source_to_jpg' . $ds . $single->single_id . ".jpg"; // 暫時用 single 
                            }
                            list($width, $height) = getimagesize($targetFile);
                            $create_image_queue = $photographService->createImageQueue( $single->single_id, $width, $height, $ext );
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

    public function ActionFileUpload(){
        $photographService = new PhotographService();
        $single_size_price = $single_data = $single_size = array();
        parse_str($_POST['single_size_price'], $single_size_price);
        parse_str($_POST['single_data'], $single_data);
        // 檔案存放路徑
        $ds          = DIRECTORY_SEPARATOR;
        $storeFolder = PHOTOGRAPH_STORAGE_DIR;
        $targetPath = $storeFolder . 'source' . $ds;
        var_dump($_FILES['file']);exit();
        // 如果檔案不為空，則上傳
        if (!empty($_FILES)) { 
            $tempFile   = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];          // you receive the file name as a separate post data
            $fileSize = $_FILES['file']['size'];          // you receive the file size as a separate post data
            $single_data['category_id'] = implode(',', $single_data['category_id']);
            $single_data['photo_name'] = $fileName;
            $ext = explode('.', $fileName)[1];
            $single_data['ext'] = $ext;
            $single = $photographService->createSingleBase($single_data);
            $targetFile =  $targetPath . $single->single_id . "." . $ext;
            if ( move_uploaded_file($tempFile,$targetFile) ) {
                $single_size = $photographService->getPhotographData($targetFile);
                 
                $single_data = array();
                $single_data['dpi'] = $single_size['dpi'];
                $single_data['color'] = $single_size['colorspace'];
                $single_data['direction'] = $photograph_data['direction'];
                $photographService->updateSingle( $single->single_id, $single_data );
                $single_size['single_id'] = $single->single_id;
                $single_size['size_type'] = 'source';
                $single_size['size_description'] = Imagemagick::$size_desc_map['source'];
                $single_size['file_size'] = $fileSize;
                $single_size['ext'] = $ext;
                $single_size['sale_twd'] = $ext;
                $single_size['sale_twd'] = (int)$single_size_price['twd']['source'];
                $single_size['sale_point'] = (int)$single_size_price['point']['source'];
                $single_create = $photographService->createSingleSize($single_size);
                $get_max_size = $photographService->getPhotographMaxSize( $single->single_id, $single_size_price, $photograph_data );
                $zoomUrl = 'http://localhost:8080/wenhsun_hr/image_storage/source/' . $fileName;
                $data =[
                    'chunkIndex' => $index,         // the chunk index processed
                    'initialPreview' => '', // the thumbnail preview data (e.g. image)
                    'initialPreviewConfig' => [
                        [
                            'type' => 'image',      // check previewTypes (set it to 'other' if you want no content preview)
                            'caption' => $fileName, // caption
                            'key' => $fileId,       // keys for deleting/reorganizing preview
                            'fileId' => $fileId,    // file identifier
                            'size' => $fileSize,    // file size
                            'zoomData' => $zoomUrl, // separate larger zoom data
                        ]
                    ],
                    'append' => true
                ];
                echo json_encode($data);exit();
            } else {
                $data = [
                    'error' => 'Error uploading chunk ' . $_POST['chunkIndex']
                ];
                echo json_encode($data);exit();
            }
        } else {
            $data = [
                'error' => 'Error uploading chunk ' . $_POST['chunkIndex']
            ];
            echo json_encode($data);exit();
        }
    }

    public function Actionlist(){
        $photographService = new PhotographService();
        $photograph_data = array();
        $photograph_data = $photographService->findAllPhotograph();
        $this->render('list',['photograph_data'=>$photograph_data]);
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