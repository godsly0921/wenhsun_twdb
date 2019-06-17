<?php
class CategoryController extends Controller{
    // layout
    public $layout = "//layouts/back_end";
    
    // 登入驗證
    protected function needLogin(): bool
    {
        return true;
    }

    public function Actionnew(){
        $categorys = array();
        $categoryService = new CategoryService();
        if( Yii::app()->request->isPostRequest ){
            $name = $_POST['name'];
            $parents = $_POST['parents'];
            $sort = $_POST['sort'];
            $status = $_POST['status'];
            $category_create = $categoryService -> create( $name, $parents, $sort, $status );
            if( $category_create[0] === true ){
                Yii::app()->session['success_msg'] = $category_create[1];
            }else{
                Yii::app()->session['error_msg'] = $category_create[1];
            }
            $categorys    = $categoryService -> findRootCategorys();
            $this->render('new',['categorys'=>$categorys]);
        }else{
            $categorys    = $categoryService -> findRootCategorys();
            $this->render('new',['categorys'=>$categorys]);
        }
    }

    public function Actionupdate($id){
        $categoryService = new CategoryService();
        $categorys    = $categoryService -> findRootCategorys();
        if( Yii::app()->request->isPostRequest ){
            $name = $_POST['name'];
            $parents = $_POST['parents'];
            $sort = $_POST['sort'];
            $status = $_POST['status'];
            $category_create = $categoryService -> update( $id, $name, $parents, $sort, $status );
            if( $category_create[0] === true ){
                Yii::app()->session['success_msg'] = $category_create[1];
            }else{
                Yii::app()->session['error_msg'] = $category_create[1];
            }
            $category_data = $categoryService -> findById($id);
            $this->render('update',array('categorys'=>$categorys, 'category_data'=>$category_data)); 
        }else{           
            $category_data = $categoryService -> findById($id);
            $this->render('update',array('categorys'=>$categorys, 'category_data'=>$category_data)); 
        }       
    }
    public function Actionlist(){
        $categoryService = new CategoryService();
        $category_data = $categoryService->findAllCategory();
        #echo json_encode($category_data);exit();
        $this->render('list',['category_data'=>$category_data]);
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