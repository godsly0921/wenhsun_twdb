<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function actionlist()
    {
        $this->render('list',[
            'data' => [],
            'categories' => $this->categories()
        ]);
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
                    ',
                    "id" => $value['single_id']
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

    private function categories()
    {
        $result = [];

        $criteria = new CDbCriteria();
        $criteria->addCondition('isroot=1');
        $criteria->addCondition('status=1');
        $rootCategories = Category::model()->findAll($criteria);

        foreach ($rootCategories as $rootCategory) {
            foreach ($rootCategory->categories as $category) {
                if ($category->status == 1) {
                    $result[$category->category_id] = "{$rootCategory->name}_{$category->name}";
                }
            }
        }
        return $result;
    }

    public function actionPreview()
    {
        $input = $_POST;
        $page = isset($input['page']) ? intval($input['page']) : 1;
        $perPage = isset($input['per_page']) ? intval($input['per_page']) : 10;
        $photos = $this->query($input, $page, $perPage);

        header('Content-Type: application/json');
        echo json_encode($photos, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }

    public function actionExport()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        $fields = array_merge($_GET['fields'], ['create_time']);
        $rows = [[
            'url' => '圖片', 'id' => '圖庫編號', 'original_name' => '原始檔名', 'current_name' => '圖片名稱',
            'category' => '圖片分類', 'persons' => '人物資訊', 'objects' => '物件名稱', 'event' => '事件名稱',
            'location' => '拍攝地點',  'description' => '內容描述', 'date_taken' => '拍攝時間', 'status' => '保存狀況',
            'source' => '入藏來源', 'index_limit' => '索引使用限制', 'original_limit' => '原件使用限制', 'photo_limit' => '影像使用限制',
            'keyword' => '關鍵字', 'remark1' => '備註一', 'remark2' => '備註二', 'create_time' => '上傳時間'
        ]];
        $rows = array_merge($rows, $this->query($_GET));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $scale = 0.2; // 縮放為原圖的 10%
        foreach ($rows as $row => $values) {
            $columnIndex = 65; // ASCII for 'A'
            foreach ($values as $key => $value) {
                if (!in_array($key, $fields)) continue;
                $colLetter = chr($columnIndex++);
                $coordinate = $colLetter . ($row + 1);
                if (preg_match('/\.jpg$/i', $value) && file_exists(__DIR__ . '/../../' . $value)) {
                    $imagePath = __DIR__ . '/../../' . $value;
                    $imageSize = getimagesize($imagePath); // [width, height]

                    if ($imageSize) {
                        list($imgWidth, $imgHeight) = $imageSize;

                        // 縮放尺寸
                        $scaledWidth = $imgWidth * $scale;
                        $scaledHeight = $imgHeight * $scale;

                        // Excel 尺寸估算：欄寬 = px / 7.5、列高 = px * 0.75
                        $columnWidth = $scaledWidth / 7.5;
                        $rowHeight = $scaledHeight * 0.75;

                        // 設定欄寬與列高（加一點 padding）
                        $sheet->getColumnDimension($colLetter)->setWidth($columnWidth + 2);
                        $sheet->getRowDimension($row + 1)->setRowHeight($rowHeight + 5);
                    }

                    $drawing = new Drawing();
                    $drawing->setPath($imagePath);
                    $drawing->setWidth($scaledWidth); // 縮放後的寬度
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setCoordinates($coordinate);
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue($coordinate, $value);
                }
            }
        }

        // 清除先前輸出
        ob_end_clean(); // 有些情況需要避免亂碼或標頭錯誤

        // 設定下載標頭
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="圖片匯出.xlsx"');
        header('Cache-Control: max-age=0');

        // 輸出到瀏覽器（不儲存）
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function query(array $input,  $page = null,  $perPage = 10)
    {
        $criteria = new CDbCriteria();
        if (isset($input['category']) && !empty($input['category'])) {
            $criteria->addCondition('category_id=' .$input['category']);
        }
        if ($page) {
            $total = Single::model()->count($criteria);
            $criteria->offset = ($page - 1) * $perPage;
            $criteria->limit = $perPage;
            $rows = Single::model()->findAll($criteria);
            $rows = $this->transform($rows);
            return [
                'current_page' => $page,
                'last_page' => ceil($total / 10),
                'total_rows' =>  $total,
                'per_page' => $perPage,
                'data' => $rows
            ];
        }

        return $this->transform(Single::model()->findAll($criteria));
    }

    private function transform(array $rows)
    {
        $result = [];

        foreach ($rows as $row) {
            $result[] = [
                'url' => Yii::app()->createUrl("image_storage/S/{$row->single_id}.jpg"),
                'id' => $row->single_id,
                'original_name' => $row->photo_name,
                'current_name' => $row->filming_name,
                'category' => $row->present()->category,
                'persons' => $row->people_info,
                'objects'  => $row->object_name,
                'event' => $row->event_name,
                'location' => $row->filming_location,
                'description' => $row->description,
                'date_taken' => $row->present()->date_taken,
                'status' => $row->present()->status,
                'source' => $row->photo_source,
                'index_limit' => $row->present()->index_limit,
                'original_limit' => $row->present()->original_limit,
                'photo_limit' => $row->present()->photo_limit,
                'keyword' => $row->keyword,
                'remark1' => $row->memo1,
                'remark2' => $row->memo2,
                'create_time' => $row->create_time
            ];
        }

        return $result;
    }

    public function actionBatchUpdate()
    {
        $input = $this->validateInputForBatchUpdate();

        $photographService = new PhotographService();
        $updated = 0;
        foreach ($input['ids'] as $id) {
            $updateResult = $photographService->updateSingle($id, $input);
            if ($updateResult['status'] === true) $updated++;
        }

        echo CJSON::encode(['success' => true, 'message' => '批次更新成功', 'updated' => $updated]);
        Yii::app()->end();
    }

    private function validateInputForBatchUpdate()
    {
        $input = [
            'ids' => Yii::app()->request->getPost('ids', []),
            'category_id' => Yii::app()->request->getPost('category_id'),
            'publish' => Yii::app()->request->getPost('publish'),
            'keywords' => Yii::app()->request->getPost('keywords'),
            'description' => Yii::app()->request->getPost('description')
        ];
        if (count($input['ids']) === 0) {
            echo CJSON::encode(['success' => false, 'message' => 'No IDs.']);
            Yii::app()->end();
        } elseif (count($input['category_id']) === 0) {
            echo CJSON::encode(['success' => false, 'message' => 'No category.']);
            Yii::app()->end();
        } elseif (!preg_match('/^(0|1)$/', $input['publish'])) {
            echo CJSON::encode(['success' => false, 'message' => 'Invalid publish.']);
            Yii::app()->end();
        }

        $input['category_id'] = implode(',', $input['category_id']);

        return $input;
    }
}
?>
