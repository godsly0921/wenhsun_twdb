<?php
class ExtSystem extends System
{
	/**
	 * 
	 * 新增權限功能
	 * 
	 */
	public function create($power_number, $power_name ,$power_controller , $power_master_number, $power_range){
		$power = new System;
		$power->power_number = $power_number;
		$power->power_name = $power_name;
		$power->power_controller = $power_controller;
		$power->power_master_number = $power_master_number;
		$power->power_range = $power_range;
		$power->insert();
		return $power;
	}
	
	public static function system_list(){
		return System::model() -> findAll(array(
		'select' => 'id,system_number,system_name,system_controller ,system_type,system_range',
		'order' => 'system_number ASC, system_range ASC ,system_type ASC',
		));
	}

	
	/**
	 * @name systemUpdate
	 * @param string $system_id, $system_number, $system_name, $system_controller, $system_type, $system_range
	 * @return object
	 * @copyright ImageDJ Corporation
	 * @author neil_kuo
	 * @access static
	 */
	public static function systemUpdate($system_id, $system_number, $system_name, $system_controller, $system_type, $system_range){
		$systems = ExSystem::model() -> findByPk($system_id);
		$systems -> system_number = $system_number;
		$systems -> system_name = $system_name;
		$systems -> system_controller = $system_controller;
		$systems -> system_type = $system_type;
		$systems -> system_range = $system_range;
		$systems -> update();
		return $systems;
	}
	
	public static function findSystemNameArray($account_group_list)
    {
	 	$group_lists = explode(",", $account_group_list);
		$system_name_arrays = [];
		$i = '';
        foreach ($group_lists as $lists) {

            $system_data = System::model()->find([
                'select'=> 'system_number,system_name ,system_controller ,system_type, system_range',
                'order' => 'system_range ASC',
                'condition'=>'system_number=:system_number',
                'params'=> [
                    ':system_number'=> $lists,
                ]
            ]);

            if ($system_data !== null && $i != $system_data['system_number']) {
                $system_name_arrays[] = $system_data ;
            }

			$i = $system_data['system_number'];
        }

		return $system_name_arrays;
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	
}
