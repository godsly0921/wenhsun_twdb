<div class="row">
    <div class="title-wrap col-lg-12">
        <h3>新增帳號</h3>
    </div>
</div>

<?php if(isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (Yii::app()->session['acc_error'] as $error): ?>
                <li><?= $error[0] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('account/create');?>" method="post">

    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label for="user_account" class="col-sm-2 control-label">帳號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="user_account" name="user_account" placeholder="請輸入帳號" value="<?php echo Yii::app()->session['user_account']?>">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">密碼:</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="password" name="password" placeholder="請輸入密碼">
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirm" class="col-sm-2 control-label">確認密碼:</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="請再次輸入密碼">
                </div>
            </div>

            <div class="form-group">
                <label for="account_name" class="col-sm-2 control-label">姓名:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="account_name" name="account_name" placeholder="請輸入姓名" value="<?php echo Yii::app()->session['account_name']?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">群組:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="account_group">
                        <?php foreach($groups as $group): ?>
                            <option value="<?= $group->group_number?>"><?= $group->group_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">狀態:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="account_type">
                        <?php foreach($account_type as $type_key => $type): ?>
                            <option value="<?= $type_key; ?>"><?= $type; ?></option>
                        <?php endforeach; ?>
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
