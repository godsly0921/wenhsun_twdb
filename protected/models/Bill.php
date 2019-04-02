<?php

/**
 * This is the model class for table "bill".
 *
 * The followings are the available columns in table 'bill':
 * @property integer $id
 * @property integer $member_id
 * @property integer $in_id
 * @property integer $out_id
 * @property string $applicable
 * @property integer $discount
 * @property integer $o_price
 * @property integer $d_price
 * @property string $create_date
 * @property string $edit_date
 */
class Bill extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bill';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, in_id, out_id, applicable, discount, o_price, d_price, create_date, edit_date', 'required'),
			array('member_id, in_id, out_id, discount, o_price, d_price', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, member_id, in_id, out_id, applicable, discount, o_price, d_price, create_date, edit_date', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'member_id' => 'Member',
			'in_id' => 'In',
			'out_id' => 'Out',
			'applicable' => 'Applicable',
			'discount' => 'Discount',
			'o_price' => 'O Price',
			'd_price' => 'D Price',
			'create_date' => 'Create Date',
			'edit_date' => 'Edit Date',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('in_id',$this->in_id);
		$criteria->compare('out_id',$this->out_id);
		$criteria->compare('applicable',$this->applicable,true);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('o_price',$this->o_price);
		$criteria->compare('d_price',$this->d_price);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('edit_date',$this->edit_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Bill the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
