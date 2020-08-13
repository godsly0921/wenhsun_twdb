<?php

class VideoController extends Controller
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
		$categoryService = new BookcategoryService();
		if($model->status ==-1){
			echo "<script>alert('此 id = " . $id . " 已不存在');window.location.href = '".Yii::app()->createUrl(Yii::app()->controller->id.'/admin')."';</script>";
		}else{
			$category_data = $categoryService->get_Allcategory_data("2");
			$category = explode(',',$model->category);
			$category_name = array();
			foreach ($category as $key => $value) {
				if(isset($category_data[$value])){
					array_push($category_name,$category_data[$value]);
				}
			}
			$model->category = implode('，',$category_name);
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
		$model=new Video;
		$category_data = array();
		$categoryService = new BookcategoryService();
		$category_data = $categoryService->findCategoryTreeString("2");
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Video']))
		{
			$inputs = $_POST['Video'];
			$inputs['create_at'] = date("Y-m-d H:i:s");
			$inputs['last_updated_user'] = Yii::app()->session['uid'];
			$video = $_FILES["m3u8_url"];
	        if($video['name']!==""){
	            $uuid_name = date("YmdHis").uniqid();
	            $tmp = explode('.',$video['name']);
	            $ext = end($tmp);
	            $inputs['file_size'] = round($video['size'] / 1024);
	        	$inputs['extension'] = $ext;
	            move_uploaded_file($video['tmp_name'],PHOTOGRAPH_STORAGE_DIR."video/source/".$uuid_name.'.'.$ext);
	            $video_show_path = PHOTOGRAPH_STORAGE_DIR."video/source/".$uuid_name.'.'.$ext;
	            $cmd_string = 'ffprobe -i "' . $video_show_path . '" -show_entries format=duration -v quiet -of csv="p=0"';
	            exec($cmd_string,$output,$return);
	            if ($return == 0) {
	            	$inputs['length'] = round($output[0]);
	            	$inputs['m3u8_url'] = $this->m3u8_url_create($video_show_path,$uuid_name);
	            }
	        }
			$model->attributes = $inputs;
			if($model->save()){
				$mongo = new Mongo();
				$inputs['video_id'] = $model->video_id;
				$inputs['category'] = explode(",",$inputs['category']);
				$mongo->insert_record('wenhsun', 'video', $inputs);
				$this->redirect(array('view','id'=>$model->video_id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'category_data'=>$category_data,
		));
	}

	public function m3u8_url_create($path,$uuid_name){
		$m3u8_path = PHOTOGRAPH_STORAGE_DIR."video/m3u8/".$uuid_name;
		if(!is_dir($m3u8_path)) {
			mkdir($m3u8_path, 0777, true);
		}
		$cmd_string = "ffmpeg -i '" . $path . "' -c:v libx264 -c:a aac -strict -2 -f hls -hls_list_size 0 -hls_time 2 '" . $m3u8_path . "/" . $uuid_name . ".m3u8'";
		exec($cmd_string,$output,$return);
		return $uuid_name . "/" . $uuid_name . ".m3u8";
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$category_data = array();
		$categoryService = new BookcategoryService();
		$category_data = $categoryService->findCategoryTreeString("2",$model->category);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Video']))
		{
			$inputs = $_POST['Video'];
			$video = $_FILES["m3u8_url_new"];
	        if($video['name']!==""){
	            $uuid_name = date("YmdHis").uniqid();
	            $tmp = explode('.',$video['name']);
	            $ext = end($tmp);
	            $inputs['file_size'] = round($video['size'] / 1024);
	        	$inputs['extension'] = $ext;
	            move_uploaded_file($video['tmp_name'],PHOTOGRAPH_STORAGE_DIR."video/source/".$uuid_name.'.'.$ext);
	            $video_show_path = PHOTOGRAPH_STORAGE_DIR."video/source/".$uuid_name.'.'.$ext;
	            $cmd_string = 'ffprobe -i "' . $video_show_path . '" -show_entries format=duration -v quiet -of csv="p=0"';
	            exec($cmd_string,$output,$return);
	            if ($return == 0) {
	            	$inputs['length'] = round($output[0]);
	            	$inputs['m3u8_url'] = $this->m3u8_url_create($video_show_path,$uuid_name);
	            }
	        }
			$inputs['update_at'] = date("Y-m-d H:i:s");
			$inputs['last_updated_user'] = Yii::app()->session['uid'];
			$model->attributes = $inputs;
			if($model->save()){
				$mongo = new Mongo();
				$update_find = array('video_id'=>$id);
				$inputs['category'] = explode(",",$inputs['category']);
				$update_input = array('$set' => $inputs);
            	$mongo->update_record('wenhsun', 'video', $update_find, $update_input);
				$this->redirect(array('view','id'=>$model->video_id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'category_data'=>$category_data,
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
				$update_find = array('video_id'=>$id);
				$update_input = array('$set' => $inputs);
            	$mongo->update_record('wenhsun', 'video', $update_find, $update_input);
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
		$dataProvider=new CActiveDataProvider('Video');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Video('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Video']))
			$model->attributes=$_GET['Video'];
		$categoryService = new BookcategoryService();
		$category_data = $categoryService->get_Allcategory_data("2");
		$this->render('admin',array(
			'model'=>$model,
			'category_data'=>$category_data
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Video the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Video::model()->with('_Account')->findByPk($id);
		if($model===null)
			echo "<script>alert('此 id = " . $id . " 已不存在');window.location.href = '".Yii::app()->createUrl(Yii::app()->controller->id.'/admin')."';</script>";
		// if($model===null)
		// 	throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Video $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='video-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
