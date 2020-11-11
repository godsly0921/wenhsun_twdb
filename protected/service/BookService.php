<?php
class BookService
{
	public function getBookPK_data($id){
		$sql = "SELECT 
				a.book_id,
				a.single_id,
				a.book_num,
				a.book_name,
				a.publish_year,
				a.publish_month,
				a.publish_day,
				a.book_version,
				a.book_pages,
				a.summary,
				a.memo,
				a.category,
				a.status,
				a.create_at,
				a.update_at,
				a.sub_author_id,
				author.name as author_id,
				publish_place.name as publish_place,
				publish_unit.name as publish_organization,
				size.name as book_size,
				series.name as series,
				account.account_name as last_updated_user
			FROM book a
			LEFT JOIN book_author author on author.author_id=a.author_id
			LEFT JOIN book_publish_place publish_place on publish_place.publish_place_id=a.publish_place
			LEFT JOIN book_publish_unit publish_unit on publish_unit.publish_unit_id=a.publish_organization
			LEFT JOIN book_size size on size.book_size_id=a.book_size
			LEFT JOIN book_series series on series.book_series_id=a.series
			LEFT JOIN account on account.id=a.last_updated_user
			WHERE a.book_id='" . $id . "'
		";

		$data = Yii::app()->db->createCommand($sql)->queryRow();
		// var_dump($sql);exit();
		return $data;
	}
	public function getAll_data(){
		$sql = "SELECT 
				a.book_id,
				a.single_id,
				a.book_num,
				a.book_name,
				a.publish_year,
				a.publish_month,
				a.publish_day,
				a.book_version,
				a.book_pages,
				a.summary,
				a.memo,
				a.category,
				a.status,
				a.sub_author_id,
				author.name as author_name,
				publish_place.name as publish_place_name,
				publish_unit.name as publish_unit_name,
				size.name as size_name,
				series.name as series_name,
				account.account_name as account_name
			FROM book a
			LEFT JOIN book_author author on author.author_id=a.author_id
			LEFT JOIN book_publish_place publish_place on publish_place.publish_place_id=a.publish_place
			LEFT JOIN book_publish_unit publish_unit on publish_unit.publish_unit_id=a.publish_organization
			LEFT JOIN book_size size on size.book_size_id=a.book_size
			LEFT JOIN book_series series on series.book_series_id=a.series
			LEFT JOIN account on account.id=a.last_updated_user
			WHERE a.status<>-1
		";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}
	public function getAllFK_data($category=''){
		$book_author = $this->getFK_Author_data();
		$book_category = $this->getFK_Category_data($category);
		$book_publish_place = $this->getFK_PublishPlace_data();
		$book_publish_unit = $this->getFK_PublishPlaceUnit_data();
		$book_series = $this->getFK_Series_data();
		$book_size = $this->getFK_Size_data();
		$singles = $this->getFK_Singles_data();
		return array(
			"book_author" => $book_author,
			"book_category" => $book_category,
			"book_publish_place" => $book_publish_place,
			"book_publish_unit" => $book_publish_unit,
			"book_series" => $book_series,
			"book_size" => $book_size,
			"singles" => $singles
		);
	}
	public function getAdvanceFilter_data($category=''){
		$book_author = $this->getFK_Author_data();
		$book_category = $this->getFK_Category_data($category);
		$book_publish_place = $this->getFK_PublishPlace_data();
		$book_publish_unit = $this->getFK_PublishPlaceUnit_data();
		$book_series = $this->getFK_Series_data();
		$book_size = $this->getFK_Size_data();
		$publish_year = $this->getFK_PublishYear_data();
		$video_extension = $this->getFK_VideoExtension_data();
		return array(
			"book_author" => $book_author,
			"book_category" => $book_category,
			"book_publish_place" => $book_publish_place,
			"book_publish_unit" => $book_publish_unit,
			"book_series" => $book_series,
			"book_size" => $book_size,
			"publish_year" => $publish_year,
			"video_extension" => $video_extension
		);
	}

	public function getFK_VideoExtension_data(){
		$data = array();
		$sql = "SELECT extension FROM `video` WHERE status=1 GROUP BY extension";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}

	public function getFK_PublishYear_data(){
		$data = array();
		$sql = "SELECT publish_year FROM book WHERE status=1 GROUP BY publish_year ORDER BY publish_year ASC";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}
	public function getFK_Singles_data(){
		$data = array();
		$sql = "SELECT * FROM single WHERE publish=1 AND copyright=1";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}
	public function getFK_Author_data(){
		$data = array();
		$sql = "SELECT author_id,name FROM book_author WHERE status='1'";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}
	public function getFK_Category_data($category=''){
		$data = array();
		$categoryService = new BookcategoryService();
		$data = $categoryService->findCategoryTreeString("1", $category);
		return $data;
	}
	public function getFK_PublishPlace_data(){
		$data = array();
		$sql = "SELECT publish_place_id,name FROM book_publish_place WHERE status='1'";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}
	public function getFK_PublishPlaceUnit_data(){
		$data = array();
		$sql = "SELECT publish_unit_id,name FROM book_publish_unit WHERE status='1'";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}
	public function getFK_Series_data(){
		$data = array();
		$sql = "SELECT book_series_id,name FROM book_series WHERE status='1'";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}
	public function getFK_Size_data(){
		$data = array();
		$sql = "SELECT book_size_id,name FROM book_size WHERE status='1'";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}

	public function getBookAuthorEvent($author_id){
		$data = array();
		$data = BookAuthorEvent::model()->findAll(array(
            'condition'=>'author_id=:author_id',
            'params'=>array(
                ':author_id' => $author_id,
            )
        ));
		return $data;
	}
}
?>