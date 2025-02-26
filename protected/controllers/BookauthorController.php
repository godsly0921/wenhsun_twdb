<?php

class BookauthorController extends Controller
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
				'model'=>$this->loadModel($id),
			));
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new BookAuthor;
		$model_author_event = new BookAuthorEvent;
		$book_author_gallery = new BookAuthorGallery;
		// var_dump($model_author_event);exit();
		$bookService = new BookService();
		$single = $bookService->getFK_Singles_data();
		$book_category = $bookService->getFK_Category_data();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['BookAuthor']))
		{	
			$transaction = Yii::app()->db->beginTransaction();
    		try {
				$inputs = $_POST['BookAuthor'];
				$inputs['create_at'] = date("Y-m-d H:i:s");
				$inputs['last_updated_user'] = Yii::app()->session['uid'];
				$model->attributes=$inputs;
				$book_author_event_inputs = isset($_POST['BookAuthorEvent'])?$_POST['BookAuthorEvent']:array();

				if($model->save()){
					$total = count($_FILES["book_author_gallery"]["name"]);
					for( $i=0 ; $i < $total ; $i++ ) {
		                if($_FILES["book_author_gallery"]["tmp_name"][$i] != "") {
		                    if(!is_dir(BOOKAUTHORGALLERY . $model->author_id)) {
		                       mkdir(BOOKAUTHORGALLERY . $model->author_id, 0777,true);
		                    }
		                    $uuid_name = date("YmdHis").uniqid();
		                    $tmp = explode('.',$_FILES["book_author_gallery"]["name"][$i]);
            				$ext = end($tmp);
		                    move_uploaded_file($_FILES["book_author_gallery"]["tmp_name"][$i],
		                    BOOKAUTHORGALLERY . $model->author_id . "/" . $uuid_name . '.' . $ext);
		                    $ag = new BookAuthorGallery();
		                    $ag->author_id = $model->author_id;
		                    $ag->img = BOOKAUTHORGALLERY_SHOW . $model->author_id . "/" . $uuid_name . '.' . $ext;
		                    $ag->captions = $_POST['captions'][$i];
		                    $ag->sort = $_POST['sort'][$i];
		                    $ag->save();
		                }
		            }
		            if(!empty($book_author_event_inputs)){
		            	foreach ($book_author_event_inputs as $key => $value) {
							if((!empty($value["title"]) || !empty($value["description"])) && !empty($value["year"])){
								$value["create_at"] = date("Y-m-d H:i:s");
								$value["update_at"] = date("Y-m-d H:i:s");
								$value["author_id"] = $model->author_id;
								$model_author_event=new BookAuthorEvent;
								$model_author_event->attributes=$value;		

								if($model_author_event->save()){
									$mongo = new Mongo();
									$mongo->insert_record('wenhsun', 'book_author_event', $value);
								}else{
									var_dump($model_author_event);exit();
								}
							}
						}
		            }
					$mongo = new Mongo();
					$inputs['author_id'] = $model->author_id;
					$inputs['literary_genre'] = explode(",",$inputs['literary_genre']);
					$mongo->insert_record('wenhsun', 'book_author', $inputs);
					$transaction->commit();
					$this->redirect(array('view','id'=>$model->author_id));
				}
	        }catch (Exception $e) {
	            $transaction->rollback();
	            Yii::log(date('Y-m-d H:i:s') . "  book_author create fail. Message =>" . $e->getMessage(), CLogger::LEVEL_INFO);
	            $this->render('create',array(
					'model'=>$model,
					'model_author_event'=>$model_author_event,
					'single'=>$single,
					'book_category'=>$book_category,
					'book_author_gallery' => $book_author_gallery,
					'total_model_author_event' => count($book_author_event_inputs)
				));
	        } 
		}
			
		$this->render('create',array(
			'model'=>$model,
			'model_author_event'=>$model_author_event,
			'single'=>$single,
			'book_category'=>$book_category,
			'book_author_gallery' => $book_author_gallery,
			'total_model_author_event' => 0
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
		$book_author_gallery = $bookService->getBookAuthorGallery($id);
		$model_author_event = $bookService->getBookAuthorEvent($id);
		$single = $bookService->getFK_Singles_data();
		$book_category = $bookService->getFK_Category_data($model->literary_genre);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['BookAuthor']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try {
				$book_author_event_inputs = isset($_POST['BookAuthorEvent'])?$_POST['BookAuthorEvent']:array();
				$inputs = $_POST['BookAuthor'];
				$inputs['update_at'] = date("Y-m-d H:i:s");
				$inputs['last_updated_user'] = Yii::app()->session['uid'];
				$model->attributes=$inputs;
				if($model->save()){
					$total = count($_FILES["book_author_gallery"]["name"]);
					for( $i=0 ; $i < $total ; $i++ ) {
		                if($_FILES["book_author_gallery"]["tmp_name"][$i] != "") {
		                    if(!is_dir(BOOKAUTHORGALLERY . $id)) {
		                       mkdir(BOOKAUTHORGALLERY . $id, 0777,true);
		                    }
		                    $uuid_name = date("YmdHis").uniqid();
		                    $tmp = explode('.',$_FILES["book_author_gallery"]["name"][$i]);
            				$ext = end($tmp);
		                    move_uploaded_file($_FILES["book_author_gallery"]["tmp_name"][$i],
		                    BOOKAUTHORGALLERY . $id . "/" . $uuid_name . '.' . $ext);
		                    $ag = new BookAuthorGallery();
		                    $ag->author_id = $id;
		                    $ag->img = BOOKAUTHORGALLERY_SHOW . $id . "/" . $uuid_name . '.' . $ext;
		                    $ag->captions = $_POST['captions'][$i];
		                    $ag->sort = $_POST['sort'][$i];
		                    $ag->save();
		                }
		            }

		            if(isset($_POST["old_captions"])){
		                foreach ($_POST["old_captions"] as $key => $value) {
		                    if(!empty($value)){
		                        $model = BookAuthorGallery::model()->findByPK($key);
		                        if(!empty($model)){
		                           $model->captions = $value;
		                           $model->sort = $_POST['old_sort'][$key];
		                           $model->save();
		                        }
		                    }
		                }
		            }
					BookAuthorEvent::model()->deleteAll(array(
		                'condition' => "author_id=:author_id",   
		                'params' => array(':author_id' => $model->author_id ),    
		            ));  
		            $mongo = new Mongo();
		            $delete_find = array('author_id'=>$model->author_id);
		            $mongo->delete_record( 'wenhsun', 'book_author_event', $delete_find );
		            if(!empty($book_author_event_inputs)){
		            	foreach ($book_author_event_inputs as $key => $value) {
							if((!empty($value["title"]) || !empty($value["description"])) && !empty($value["year"])){
								$value["create_at"] = date("Y-m-d H:i:s");
								$value["update_at"] = date("Y-m-d H:i:s");
								$value["author_id"] = $model->author_id;
								$model_author_event=new BookAuthorEvent;	
								$model_author_event->attributes=$value;		
								if($model_author_event->save()){
									$mongo = new Mongo();
									$mongo->insert_record('wenhsun', 'book_author_event', $value);
								}else{
									var_dump($model_author_event);exit();
								}
							}
						}
		            }
					
					$mongo = new Mongo();
					$update_find = array('author_id'=>$id);
					$inputs['literary_genre'] = explode(",",$inputs['literary_genre']);
					$update_input = array('$set' => $inputs);
					$mongo->update_record('wenhsun', 'book_author', $update_find, $update_input);
					$transaction->commit();
					$this->redirect(array('update','id'=>$model->author_id));
				}
	        }catch (Exception $e) {
	            $transaction->rollback();
	            Yii::log(date('Y-m-d H:i:s') . "  book_author update fail. Message =>" . $e->getMessage(), CLogger::LEVEL_INFO);
	            $this->render('update',array(
					'model'=>$model,
					'model_author_event'=>$model_author_event,
					'single'=>$single,
					'book_category'=>$book_category,
					'book_author_gallery' => $book_author_gallery,
					'total_model_author_event' => count($book_author_event_inputs)
				));
	        } 
		}
		// var_dump($model_author_event);exit();
		// var_dump($book_author_gallery);exit();
		$this->render('update',array(
			'model'=>$model,
			'model_author_event'=>$model_author_event,
			'single'=>$single,
			'book_category'=>$book_category,
			'book_author_gallery' => $book_author_gallery,
			'total_model_author_event' => count($model_author_event)
		));
	}

	public function actionDeleteGallery()
    {
        if($_POST) {
            try {
                $id = $_POST['id'];
                $authorGallery = BookAuthorGallery::model()->findByPK($id);
                if($authorGallery != null) {
                    $authorGallery->is_delete = 1;
                    $authorGallery->save();
                    echo "success";
                    return;
                }
            } catch (exception $e) {
                echo $e;
                return;
            }
            echo "刪除圖片失敗";
            return;
        }
        echo "fail";
        return;
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
				BookAuthorGallery::model()->updateAll(array("is_delete"=>1),"author_id=".$id);  
				$mongo = new Mongo();
				$update_find = array('author_id'=>$id);
				$update_input = array('$set' => $inputs);
            	$mongo->update_record('wenhsun', 'book_author', $update_find, $update_input);
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
		$dataProvider=new CActiveDataProvider('BookAuthor');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new BookAuthor('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BookAuthor']))
			$model->attributes=$_GET['BookAuthor'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BookAuthor the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=BookAuthor::model()->findByPk($id);
		if($model===null)
			echo "<script>alert('此 id = " . $id . " 已不存在');window.location.href = '".Yii::app()->createUrl(Yii::app()->controller->id.'/admin')."';</script>";
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BookAuthor $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='book-author-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
