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
        $this->render('new');
    }

    public function Actionupload(){
        // 檔案存放路徑
        $ds          = DIRECTORY_SEPARATOR;
        $storeFolder = '../../../image_storage';
        $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
         
        // 如果檔案不為空，則上傳
        if (!empty($_FILES)) {   
         $tempFile   = $_FILES['file']['tmp_name'];
         $targetFile =  $targetPath. $_FILES['file']['name'];
         
          if ( move_uploaded_file($tempFile,$targetFile) ) {
             echo "File is valid, and was successfully uploaded.\n";
         } else {
             echo "Possible file upload attack!\n";
         }
         
         echo "Here is some more debugging info:\n";
            echo '<pre>';
            print_r($_FILES);
            echo '</pre>';
        }
    }

    public function Actionlist(){
        $photographService = new PhotographService();
        $photograph_data = array();
        $photograph_data = $photographService->findAllPhotograph();
        $this->render('list',['photograph_data'=>$photograph_data]);
    }

    public function Actiondelete(){
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