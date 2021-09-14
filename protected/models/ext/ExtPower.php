<?php
class ExtPower extends Power
{
	/**
	 * 
	 * 新增權限功能
	 * 
	 */
	public function create($power_number, $power_name ,$power_controller , $power_master_number, $power_range){
		$power = new Power;
		$power->power_number = $power_number;
		$power->power_name = $power_name;
		$power->power_controller = $power_controller;
		$power->power_master_number = $power_master_number;
		$power->power_range = $power_range;
		$power->insert();
		return $power;
	}
	
	public function power_list(){
		return Power::model() -> findAll(array(
		'select' => 'power_number,power_name,power_controller,power_master_number,power_range,power_display',
		'order' => 'power_number ASC ,power_master_number ASC , power_range ASC',
		));
	}
	/*
	 * 
	 * 判斷power_number是否有值存在
	 * 
	 */
	public function power_number_exists($power_number){
		return Power::model() -> findAll(array(
		'select' => 'power_number',
		'condition'=>'power_number=:power_number',
			'params'=>array(
				':power_number'=>$power_number,
			)
		));
	}
	
	public static function findPowerNameArray($account_group_list){
	 	$group_lists = array_unique(explode(",",$account_group_list));
		$criteria = new CDbCriteria();
		$criteria -> select= 'power_number ,power_name ,power_controller ,power_master_number ,power_range,power_display';
		$criteria -> addInCondition('power_number', $group_lists);
		$criteria -> order = 'power_range ASC,power_number ASC';
		$result = Power::model() -> findAll($criteria);
//		 foreach ($group_lists as $lists) {
//            $powers_name = Power::model()->find([
//                'select'=> 'power_number ,power_name ,power_controller ,power_master_number ,power_range,power_display ',
//                'condition'=>'power_number=:power_number',
//                'order' => 'power_range ASC,power_number ASC' ,
//                'params'=> [
//                ':power_number'=> $lists,
//                ]
//            ]);
//
//            if ($powers_name !== null) {
//                $power_name_arrays[] = $powers_name ;
//            }
//
//		 }
        return $result;
	}

	public static function findByPowerMasterNumber($account_group_list){
        $sql = "SELECT s.system_number,s.system_name ,s.system_controller ,s.system_type, s.system_range 
                FROM `power` p INNER JOIN `system` s on 
                p.power_master_number = s.system_number and p.power_display = 1 and p.power_number in($account_group_list)
                group by p.power_master_number";

        return Yii::app()->db->createCommand($sql)->queryAll($sql);
    }

	public static function findByPowerMasterNumberAndPowerNumber($power_master_number,$power_number){
		return ExPower::model()->find(array(
			'condition'=>'power_master_number=:power_master_number and power_number=:power_number',
			'params'=>array(
				':power_master_number'=>$power_master_number,
				':power_number'=>$power_number,
			)
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	
}
