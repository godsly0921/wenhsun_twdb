<?php

/**
 * This is the model class for table "book_sub_category".
 *
 * The followings are the available columns in table 'book_sub_category':
 * @property integer $sub_category_id
 * @property string $name
 * @property integer $isroot
 * @property integer $parents
 * @property integer $status
 * @property string $create_at
 * @property string $update_at
 * @property string $delete_at
 * @property integer $last_updated_user
 */
class BookSubCategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'book_sub_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, isroot, parents, status, delete_at, last_updated_user', 'required'),
			array('isroot, parents, status, last_updated_user', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('create_at, update_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sub_category_id, name, isroot, parents, status, create_at, update_at, delete_at, last_updated_user', 'safe', 'on'=>'search'),
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
			'sub_category_id' => '索引編號',
			'name' => '次文類名稱',
			'isroot' => '是否為根文類',
			'parents' => '根文類編號',
			'status' => '文類狀態',
			'create_at' => '建立時間',
			'update_at' => '更新時間',
			'delete_at' => '刪除時間',
			'last_updated_user' => '最後異動的人',
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

		$criteria->compare('sub_category_id',$this->sub_category_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('isroot',$this->isroot);
		$criteria->compare('parents',$this->parents);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('delete_at',$this->delete_at,true);
		$criteria->compare('last_updated_user',$this->last_updated_user);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookSubCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
