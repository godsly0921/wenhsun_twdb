<?php
class OperationlogService{
	public static function create_operationlog( $motion, $log, $status=1 ){
		$model = new Operationlog();
		$model->account_id = isset(Yii::app()->session['uid'])?Yii::app()->session['uid']:'0';
		$model->motion = $motion;
		$model->log = $log;
        $model->status = $status;
		$model->time = date('Y-m-d H:i:s');
		if (!$model->validate()) {
            return $model;
        }

        if (!$model->hasErrors()) {
            if( $model->save() ){
                return array(true,'新增成功',$model);         
            }else{       
                return array(false,$model->getErrors());
            }
        }
        return $model;
	}
}
?>