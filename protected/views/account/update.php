<div class="row">
    <div class="title-wrap col-lg-12">
        <h3>帳號權限修改</h3>
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

<div id="acc_success">
<?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
    <p class="alert alert-success">
        <?php echo Yii::app()->session['success_msg']; ?>
    </p>
<?php endif; ?>
</div>

<div class="panel panel-default">
    <div class="panel-heading">基本資料修改</div>
    <div class="panel-body">
        <form class="form-horizontal" method="post" action="<?php echo Yii::app()->createUrl('account/update'); ?>">
            <?php CsrfProtector::genHiddenField(); ?>
            <input type="hidden" name="account_id" value="<?= $accounts->id ?>">
            <input type="hidden" name="user_account" value="<?= $accounts->user_account ?>">
            <input type="hidden" name="update_type" value="INFO">

            <div class="form-group">
                <label for="user_account" class="col-sm-2 control-label">帳號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="user_account" value="<?= $accounts->user_account ?>" disabled>
                </div>
            </div>

            <div class="form-group">
                <label for="account_name" class="col-sm-2 control-label">姓名:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="account_name" name="account_name" placeholder="請輸入姓名" value="<?= $accounts->account_name ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">群組:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="account_group">
                        <?php foreach($groups as $group): ?>
                            <?php if($accounts->account_group == $group->group_number):?>
                                <option selected="selected" value="<?= $group->group_number ?>">
                                    <?= $group->group_name ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $group->group_number ?>">
                                    <?= $group->group_name ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">狀態:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="account_type">
                        <?php if ((int)$accounts->account_type === 1): ?>
                            <option value="1" selected >停權</option>
                            <option value="0">啟用</option>

                        <?php else: ?>
                            <option value="0" selected >啟用</option>
                            <option value="1">停權</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">修改</button>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">密碼修改</div>
    <div class="panel-body">
        <form class="form-horizontal" method="post" action="<?php echo Yii::app()->createUrl('account/update'); ?>">
            <?php CsrfProtector::genHiddenField(); ?>
            <input type="hidden" name="account_id" value="<?= $accounts->id ?>">
            <input type="hidden" name="update_type" value="PW">
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
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">密碼修改</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function() {
        if ($('#acc_success').html() != '') {
            $('#acc_success').show().fadeOut(2000)
        }
    })
</script>



