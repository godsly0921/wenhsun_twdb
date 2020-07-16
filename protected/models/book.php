<?php

/**
 * This is the model class for table "book".
 *
 * The followings are the available columns in table 'book':
 * @property integer $book_id
 * @property integer $single_id
 * @property string $book_num
 * @property integer $main_category
 * @property integer $sub_category
 * @property string $book_name
 * @property integer $author_id
 * @property integer $sub_author_id
 * @property integer $publish_place
 * @property integer $publish_organization
 * @property string $publish_date
 * @property integer $book_version_id
 * @property integer $book_pages
 * @property string $book_size
 * @property integer $series
 * @property string $summary
 * @property string $memo
 * @property string $create_datetime
 * @property string $update_datetime
 * @property string $delete_datetime
 * @property integer $last_operator
 */
class book extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'book';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('single_id, book_num, main_category, sub_category, book_name, author_id, sub_author_id, publish_place, publish_organization, publish_date, book_version_id, book_pages, book_size, series, summary, memo, create_datetime, update_datetime, delete_datetime, last_operator', 'required'),
			array('single_id, main_category, sub_category, author_id, sub_author_id, publish_place, publish_organization, book_version_id, book_pages, series, last_operator', 'numerical', 'integerOnly'=>true),
			array('book_num', 'length', 'max'=>128),
			array('book_name, book_size', 'length', 'max'=>32),
			array('publish_date', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('book_id, single_id, book_num, main_category, sub_category, book_name, author_id, sub_author_id, publish_place, publish_organization, publish_date, book_version_id, book_pages, book_size, series, summary, memo, create_datetime, update_datetime, delete_datetime, last_operator', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'book_id' => 'Book',
			'single_id' => 'Single',
			'book_num' => 'Book Num',
			'main_category' => 'Main Category',
			'sub_category' => 'Sub Category',
			'book_name' => 'Book Name',
			'author_id' => 'Author',
			'sub_author_id' => 'Sub Author',
			'publish_place' => 'Publish Place',
			'publish_organization' => 'Publish Organization',
			'publish_date' => 'Publish Date',
			'book_version_id' => 'Book Version',
			'book_pages' => 'Book Pages',
			'book_size' => 'Book Size',
			'series' => 'Series',
			'summary' => 'Summary',
			'memo' => 'Memo',
			'create_datetime' => 'Create Datetime',
			'update_datetime' => 'Update Datetime',
			'delete_datetime' => 'Delete Datetime',
			'last_operator' => 'Last Operator',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('book_id',$this->book_id);
		$criteria->compare('single_id',$this->single_id);
		$criteria->compare('book_num',$this->book_num,true);
		$criteria->compare('main_category',$this->main_category);
		$criteria->compare('sub_category',$this->sub_category);
		$criteria->compare('book_name',$this->book_name,true);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('sub_author_id',$this->sub_author_id);
		$criteria->compare('publish_place',$this->publish_place);
		$criteria->compare('publish_organization',$this->publish_organization);
		$criteria->compare('publish_date',$this->publish_date,true);
		$criteria->compare('book_version_id',$this->book_version_id);
		$criteria->compare('book_pages',$this->book_pages);
		$criteria->compare('book_size',$this->book_size,true);
		$criteria->compare('series',$this->series);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('create_datetime',$this->create_datetime,true);
		$criteria->compare('update_datetime',$this->update_datetime,true);
		$criteria->compare('delete_datetime',$this->delete_datetime,true);
		$criteria->compare('last_operator',$this->last_operator);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return book the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
