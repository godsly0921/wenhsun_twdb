<?php

class BookcategoryController extends Controller
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
		$model = $this->loadModel($id);
		if($model->status ==-1){
			echo "<script>alert('此 id = " . $id . " 已不存在');window.location.href = '".Yii::app()->createUrl(Yii::app()->controller->id.'/admin')."';</script>";
		}else{
			$this->render('view',array(
				'model'=>$model,
			));
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new BookCategory;
		$category = array();
		$categoryService = new BookcategoryService();
        $category = $categoryService->findAllRootCategory();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['BookCategory']))
		{
			$inputs = $_POST['BookCategory'];
			$inputs['create_at'] = date("Y-m-d H:i:s");
			$inputs['last_updated_user'] = Yii::app()->session['uid'];
			if($inputs['parents'] == '0'){
				$inputs['isroot'] = 1;
			}else{
				$inputs['isroot'] = 0;
			}
			$model->attributes=$inputs;
			if($model->save()){
				$mongo = new Mongo();
				$inputs['category_id'] = $model->category_id;
				$mongo->insert_record('wenhsun', 'book_category', $inputs);
				$this->redirect(array('view','id'=>$model->category_id));
			}
		}

		$this->render('create',array(
			'model' => $model,
			'category' => $category
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
		$category = array();
		$categoryService = new BookcategoryService();
        $category = $categoryService->findAllCategory();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['BookCategory']))
		{
			$inputs = $_POST['BookCategory'];
			$inputs['create_at'] = date("Y-m-d H:i:s");
			$inputs['last_updated_user'] = Yii::app()->session['uid'];
			if($inputs['parents'] == '0'){
				$inputs['isroot'] = 1;
			}else{
				$inputs['isroot'] = 0;
			}
			$model->attributes=$inputs;
			if($model->save()){
				$mongo = new Mongo();
				$update_find = array('category_id'=>$id);
				$update_input = array('$set' => $inputs);
				$mongo->update_record('wenhsun', 'book_category', $update_find, $update_input);
				$this->redirect(array('view','id'=>$model->category_id));
			}
		}
		$this->render('update',array(
			'model'=>$model,
			'category' => $category
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
				$update_find = array('category_id'=>$id);
				$update_input = array('$set' => $inputs);
				$mongo->update_record('wenhsun', 'book_category', $update_find, $update_input);
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
		$dataProvider=new CActiveDataProvider('BookCategory');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$categoryService = new BookcategoryService();
        $category_data = $categoryService->findAllDetailCategory();
        // var_dump($category_data);exit();
		$this->render('admin',array(
			'model'=>$category_data,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BookCategory the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=BookCategory::model()->with('_Account')->findByPk($id);
		if($model===null)
			echo "<script>alert('此 id = " . $id . " 已不存在');window.location.href = '".Yii::app()->createUrl(Yii::app()->controller->id.'/admin')."';</script>";
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BookCategory $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='book-category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
