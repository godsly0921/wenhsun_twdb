<?php

class ManagerlogService
{
    public function findManagerloglist(){
        $datas = Managerlog::model()->findAll(array(
            'select' => 'id,admin,operation,ip,create_date,edit_date',
            'order' => 'id ASC',
        ));

        if($datas==null){
            $datas == false;
        }
        return $datas;
    }

}
