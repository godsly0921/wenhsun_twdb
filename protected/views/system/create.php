<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">系統新增</h3>
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

<form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('system/create');?>" method="post">
    <?php CsrfProtector::genHiddenField(); ?>
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="form-group">
                <label for="group_number"  class="col-sm-2 control-label">系統編號:</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="system_number" name="system_number" placeholder="請輸入系統編號(數字)">
                </div>
            </div>

            <div class="form-group">
                <label for="system_name"  class="col-sm-2 control-label">系統名稱:</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="system_name" name="system_name" placeholder="請輸入系統名稱">
                </div>
            </div>

            <div class="form-group">
                <label for="system_controller"  class="col-sm-2 control-label">Controller:</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="system_controller" name="system_controller" placeholder="請輸入Controller">
                </div>
            </div>

            <div class="form-group">
                <label for="system_range"  class="col-sm-2 control-label">排序:</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="system_range" name="system_range" placeholder="請輸入排序(數字)">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">系統狀態:</label>
                <div class="col-sm-4">
                    <select class="form-control" name="system_type">
                        <option value="0">停用</option>
                        <option value="1">啟用</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">新增</button>
                </div>
            </div>

        </div>
    </div>
</form>


