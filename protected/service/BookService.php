<?php
class BookService
{
	public function getAllFK_data(){
		$book_author = $this->getFK_Author_data();
		$book_category = $this->getFK_Category_data();
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
	public function getFK_Category_data(){
		$data = array();
		$categoryService = new BookcategoryService();
		$data = $categoryService->findCategoryTreeString();
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
}
?>