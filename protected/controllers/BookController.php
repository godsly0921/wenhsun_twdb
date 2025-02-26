<?php

class BookController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/back_end';

	protected function needLogin(): bool
    {
        return true;
    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$bookService = new BookService();
		$categoryService = new BookcategoryService();
		$data = $bookService->getBookPK_data($id);
		// var_dump($data);exit();
		if(empty($data) || $data['status'] ==-1){
			echo "<script>alert('此 id = " . $id . " 已不存在');window.location.href = '".Yii::app()->createUrl(Yii::app()->controller->id.'/admin')."';</script>";
		}
		$category_data = $categoryService->get_Allcategory_data("1");
		// $data = $this->loadModel($id);
		$data = (object)$data;
		$category = explode(',',$data->category);
		$category_name = array();
		foreach ($category as $key => $value) {
			if(isset($category_data[$value])){
				array_push($category_name,$category_data[$value]);
			}
		}
		$data->category = implode('，',$category_name);
		// var_dump($data);exit();
		$this->render('view',array(
			'model'=>$data,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Book;
		$bookService = new BookService();
		$FK_data = $bookService->getAllFK_data();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		//$model->sub_author_id = explode(",",$model->sub_author_id);
		if(isset($_POST['Book']))
		{
			$inputs = $_POST['Book'];
			$inputs['book_num'] = "B" . $inputs['book_num'];
			$inputs['sub_author_id'] = isset($inputs['sub_author_id'])?implode(",",$inputs['sub_author_id']):"";
			$inputs['create_at'] = date("Y-m-d H:i:s");
			$inputs['last_updated_user'] = Yii::app()->session['uid'];
			$model->attributes = $inputs;
			// $model->attributes=$_POST['Book'];
			if($model->save()){
				$mongo = new Mongo();
				$inputs['book_id'] = $model->book_id;
				$sub_author_id = isset($inputs['sub_author_id'])?$inputs['sub_author_id']:"";
				$inputs['sub_author_id'] = explode(",",$inputs['sub_author_id']);
				$inputs['category'] = isset($inputs['category'])?explode(",",$inputs['category']):array();
				$sql = "SELECT name FROM book_author WHERE author_id=" . $inputs['author_id'];
				$book_data = Yii::app()->db->createCommand($sql)->queryRow();
				$inputs['author_name'] = (!empty($book_data)) ? $book_data['name']:""; //作家
				if(!empty($sub_author_id)){
					$sql = "SELECT GROUP_CONCAT(name) as sub_name FROM book_author WHERE author_id IN (" . $sub_author_id .")";
					$sub_book_data = Yii::app()->db->createCommand($sql)->queryRow();
					$inputs['sub_author_name'] = (!empty($sub_book_data)) ? explode(",",$sub_book_data['sub_name']):""; //次作家
				}
				$sql = "SELECT GROUP_CONCAT(name) as series_name FROM book_series WHERE book_series_id =" . $inputs['series'];
				$series_data = Yii::app()->db->createCommand($sql)->queryRow();
				$inputs['series_name'] = (!empty($series_data)) ? $series_data['series_name']:""; //叢書名
				$mongo->insert_record('wenhsun', 'book', $inputs);
				$this->redirect(array('view','id'=>$model->book_id));
			}
		}
		$model->sub_author_id = !empty($model->sub_author_id)?explode(',',$model->sub_author_id):array();
		$this->render('create',array(
			'model'=>$model,
			'FK_data'=>$FK_data,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$bookService = new BookService();
		$FK_data = $bookService->getAllFK_data($model->category);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		// var_dump($model);exit();
		if(isset($_POST['Book']))
		{
			$inputs = $_POST['Book'];
			$inputs['sub_author_id'] = isset($inputs['sub_author_id'])?implode(",",$inputs['sub_author_id']):"";
			$inputs['update_at'] = date("Y-m-d H:i:s");
			$inputs['last_updated_user'] = Yii::app()->session['uid'];
			$model->attributes = $inputs;
			if($model->save()){
				$mongo = new Mongo();
				$update_find = array('book_id'=>$id);
				$sub_author_id = isset($inputs['sub_author_id'])?$inputs['sub_author_id']:"";
				$inputs['sub_author_id'] = explode(",",$inputs['sub_author_id']);
				$inputs['category'] = isset($inputs['category'])?explode(",",$inputs['category']):array();
				$sql = "SELECT name FROM book_author WHERE author_id=" . $inputs['author_id'];
				$book_data = Yii::app()->db->createCommand($sql)->queryRow();
				$inputs['author_name'] = (!empty($book_data)) ? $book_data['name']:""; //作家
				if(!empty($sub_author_id)){
					$sql = "SELECT GROUP_CONCAT(name) as sub_name FROM book_author WHERE author_id IN (" . $sub_author_id .")";
					$sub_book_data = Yii::app()->db->createCommand($sql)->queryRow();
				}
				$inputs['sub_author_name'] = (!empty($sub_book_data)) ? explode(",",$sub_book_data['sub_name']):""; //次作家
				$sql = "SELECT GROUP_CONCAT(name) as series_name FROM book_series WHERE book_series_id =" . $inputs['series'];
				$series_data = Yii::app()->db->createCommand($sql)->queryRow();
				$inputs['series_name'] = (!empty($series_data)) ? $series_data['series_name']:""; //叢書名
				$update_input = array('$set' => $inputs);
            	$mongo->update_record('wenhsun', 'book', $update_find, $update_input);
				$this->redirect(array('view','id'=>$model->book_id));
			}
		}
		$model->sub_author_id = !empty($model->sub_author_id)?explode(',',$model->sub_author_id):array();
		$this->render('update',array(
			'model'=>$model,
			'FK_data'=>$FK_data,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		if($model){
			$inputs = array();
			$inputs['status'] = -1;
			$inputs['update_at'] = date("Y-m-d H:i:s");
			$inputs['delete_at'] = date("Y-m-d H:i:s");
			$inputs['last_updated_user'] = Yii::app()->session['uid'];
			$model->attributes = $inputs;
			if($model->save()){
				$mongo = new Mongo();
				$update_find = array('book_id'=>$id);
				$update_input = array('$set' => $inputs);
            	$mongo->update_record('wenhsun', 'book', $update_find, $update_input);
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Book');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$bookService = new BookService();
		$categoryService = new BookcategoryService();
		$data = $bookService->getAll_data();
		$category_data = $categoryService->get_Allcategory_data("1");
		$this->render('admin',array(
			'model'=>$data,
			'category_data'=>$category_data
		));
	}

	public function findSubAuthorName($sub_author_id){
		$data = array();
		if(!empty($sub_author_id)){
			$sql = "SELECT GROUP_CONCAT(name) as sub_author_name FROM book_author WHERE author_id IN(" . $sub_author_id . ")";
			$data = Yii::app()->db->createCommand($sql)->queryAll();
		}
		
        if(!empty($data)){
        	return $data[0]['sub_author_name'];
        }else{
        	return '';
        }
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Book the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Book::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Book $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='book-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
