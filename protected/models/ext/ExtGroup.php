<?php

class ExtGroup extends Group
{
	public function group_list(){
		return Group::model() -> findAll(array(
		'select' => 'id,group_number,group_name,group_list',
		'order' => 'group_number ASC' ,
		));
	}
	
	public function create($group_number, $group_name ,$group_list){
		$group = new Group;
		$group->group_number = $group_number;
		$group->group_name = $group_name;
		$group->group_list = $group_list;
		$group->insert();
		return $group;
	}
	
	public function group_number_exists($group_number){
		return Group::model() -> findAll(array(
		'select' => 'group_number',
		'condition'=>'group_number=:group_number',
			'params'=>array(
				':group_number'=>$group_number,
			)
		));
	}
	
	/**
	 * 更新群組資料
	 */
	public static function groupUpdate($group_id,$group_name,$group_number,$group_string){
		$groups = ExtGroup::model() -> findByPk($group_id);
		$groups -> group_name = $group_name;
		$groups -> group_number = $group_number;
		$groups -> group_list = $group_string;
		$groups -> update();
		return $groups;
	}
	
	//找帳戶權限資料跟Controller路徑
	public static function findAccountGroup($find_group){
		return Group::model()->find(array(
			'select' => 'group_list,group_number',
			'condition'=>'group_number=:group_number ',
			'params'=>array(
				':group_number'=>$find_group,
			)
		));
	}
	
	public static function findByGroupNumber($group_number){
		return ExtGroup::model()->find(array(
			'condition'=>'group_number=:group_number ',
			'params'=>array(
				':group_number'=>$group_number,
			)
		));
	}
	
	public function powers($power_master_number){
		$groups = explode(',', $this->group_list);
		$results = array();
		foreach($groups as $group){
			if($group!=''){
				$power = ExtPower::findByPowerMasterNumberAndPowerNumber($power_master_number,$group);
				if(isset($power)){
					$results[] = $power;
				}
			}
		}
		return $results;
	}

    public function getGroupJoinAccountOnGroupNum()
    {
        $sql = "
            SELECT * FROM `group` g
            INNER JOIN account a on g.`group_number` = a.`account_group` and a.account_type = 1
            ORDER BY g.`group_number` ASC
        ";

        $result = Yii::app()->db->createCommand($sql)->queryAll($sql);

        return $result;
    }

	public function getGroupJoinAdviserOnGroupNum()
	{
		$sql = "
            SELECT * FROM `group` g
            INNER JOIN adviser a on g.`group_number` = a.`adv_group` and a.adv_type = 1 and a.adv_deletedate = '0000-00-00 00:00:00'
            ORDER BY g.`group_number` ASC
        ";

		$result = Yii::app()->db->createCommand($sql)->queryAll($sql);

		return $result;
	}

	public function getGroupJoinMemberOnGroupNum()
	{
		$sql = "
            SELECT * FROM `group` g
            INNER JOIN member m on g.`group_number` = m.`mem_group` and m.mem_type = 1 and m.mem_deletedate = '0000-00-00 00:00:00'
            ORDER BY g.`group_number` ASC
        ";

		$result = Yii::app()->db->createCommand($sql)->queryAll($sql);

		return $result;
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
}
