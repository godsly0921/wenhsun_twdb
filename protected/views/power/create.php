<div class="row">
    <div class="title-wrap col-lg-12">
        <h3>新增功能</h3>
    </div>
</div>

<?php if(isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (Yii::app()->session['error_msg'] as $error): ?>
                <li><?= $error[0] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('power/create');?>" method="post">

    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">
        	
            <div class="form-group">
                <label for="power_number" class="col-sm-2 control-label">功能編號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="power_number" name="power_number" placeholder="請輸入功能編號" value="<?= $p_id ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="power_name" class="col-sm-2 control-label">功能名稱:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="power_name" name="power_name" placeholder="請輸入功能名稱">
                </div>
            </div>

            <div class="form-group">
                <label for="power_controller" class="col-sm-2 control-label">程式位置:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="power_controller" name="power_controller" placeholder="請輸入程式位置">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">系統編號:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="power_master_number">
                        <?php foreach($systems as $system): ?>
                            <option value="<?= $system->system_number; ?>"><?= $system->system_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="power_range" class="col-sm-2 control-label">排序:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="power_range" name="power_range" placeholder="請輸入排序">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">選單顯示:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="power_display">
                        <?php foreach($power_display as $display_key=>$display): ?>
                            <option value="<?= $display_key; ?>"><?= $display; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
             <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">功能新增</button>
                </div>
            </div>
            
            
        </div>
    </div>
</form>
