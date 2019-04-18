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

    public function ActionFileUpload(){
        $photographService = new PhotographService();
        $single_size_price = $single_data = $single_size = array();
        parse_str($_POST['single_size_price'], $single_size_price);
        parse_str($_POST['single_data'], $single_data);
        // 檔案存放路徑
        $ds          = DIRECTORY_SEPARATOR;
        $storeFolder = PHOTOGRAPH_STORAGE_DIR;
        $targetPath = $storeFolder . 'source' . $ds;
        // 如果檔案不為空，則上傳
        if (!empty($_FILES)) { 
            $tempFile   = $_FILES['fileBlob']['tmp_name'];
            $fileName = $_POST['fileName'];          // you receive the file name as a separate post data
            $fileSize = $_POST['fileSize'];          // you receive the file size as a separate post data
            $single_data['category_id'] = implode(',', $single_data['category_id']);
            $single_data['photo_name'] = $fileName;
            $ext = explode('.', $fileName)[1];
            $single_data['ext'] = $ext;
            $single = $photographService->createSingleBase($single_data);
            $index =  $_POST['chunkIndex'];          // the current file chunk index
            $totalChunks = $_POST['chunkCount'];     // the total number of chunks for this file
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