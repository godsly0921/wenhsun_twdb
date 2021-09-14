<?php
/**
 * @author Neil Kuo
 * @copyright Neil Kuo
 * @since 2015-07-08 10:42:30
 */
class PowerService
{
    public function findPower()
    {
        $result = Power::model() -> findAll(array(
		'select' => 'power_number,power_name,power_controller,power_master_number,power_range,power_display',
		'order' => 'power_number ASC ,power_master_number ASC , power_range ASC',
		));
        return $result;
    }

    public function genPowerId()
    {
        $result = Power::model() -> findAll(array(
            'select' => 'power_number,power_name,power_controller,power_master_number,power_range,power_display',
            'order' => 'power_number ASC ,power_master_number ASC , power_range ASC',
        ));

        $p_id = 0;
        foreach($result as $key=>$value){
            if($value->power_number > $p_id){
                $p_id = $value->power_number;
            }
        }
        $p_id = $p_id+1;
        return $p_id;
    }

    /**
     * @param array $input
     * @return Power
     */
    public function create(array $inputs)
    {
        $power = new Power();
        // $power->power_number = $inputs['power_number'];
        $power->power_name = $inputs['power_name'];
        $power->power_controller = $inputs['power_controller'];
		$power->power_master_number = $inputs['power_master_number'];
		$power->power_range = $inputs['power_range'];
		$power->power_display = $inputs['power_display'];



        if (!$power->validate()) {
            return $power;
        }

        // if ($this->powerNumberExist($power->power_number)) {
        //     $power->addError('power_exist', '新增功能失敗，功能編號已經存在');
        //     return $power;
        // }

        if (!$power->hasErrors()) {
            $success = $power->save();
        }

        if ($success === false) {
            $power->addError('save_fail', '新增功能失敗');
            return $power;
        }

        return $power;
    }

    /**
     * @param $power_number
     * @return bool
     */
    public function powerNumberExist($power_number)
    {
        $result = Power::model()->find([
            'select' => 'power_number',
            'condition'=>'power_number=:power_number',
            'params'=> [':power_number' => $power_number,]
        ]);

        return ($result == false) ? false : true;
    }

    public function update(array $inputs)
    {
        $power = Power::model()->findByPk($inputs["id"]);

        // $power->power_number = $inputs["power_number"];
        $power->power_name = $inputs["power_name"];
        $power->power_controller = $inputs["power_controller"];
		$power->power_master_number = $inputs["power_master_number"];
		$power->power_range = $inputs["power_range"];
		$power->power_display = $inputs["power_display"];

        if ($power->validate()) {
            $power->update();
        }

        return $power;
    }
}