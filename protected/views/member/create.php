<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">會員帳號新增</h3>
    </div>
</div>

<div id="error_msg">
    <?php if (isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== '') : ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (Yii::app()->session['error_msg'] as $error) : ?>
                    <li><?= $error[0] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<div id="success_msg">
    <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== '') : ?>
        <p class="alert alert-success">
            <?php echo Yii::app()->session['success_msg']; ?>
        </p>
    <?php endif; ?>
</div>

<form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('member/create'); ?>" method="post">
    <?php CsrfProtector::genHiddenField(); ?>
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="form-group">
                <label for="account" class="col-sm-2 control-label">*會員帳號:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="account" name="account" placeholder="會員帳號" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">*姓名:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="name" name="name" placeholder="姓名" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">註冊來源:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="account_type" name="account_type" value="後台建立" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="mem_password" class="col-sm-2 control-label">*密碼:</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="password" placeholder="請輸入密碼">
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirm" class="col-sm-2 control-label">*確認密碼:</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="password_confirm" placeholder="請再次輸入密碼">
                </div>
            </div>

            <div class="form-group">
                <label for="sex" class="col-sm-2 control-label">性別:</label>
                <div class="col-sm-6">
                    <label class="radio-inline">
                        <input type="radio" name="gender" value="F">女
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="gender" value="M">男
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="gender" value="">尚未設定
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">電話:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="請輸入電話" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">行動電話:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="mobile" placeholder="請輸入行動電話" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">*Email:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="email" name="email" placeholder="請輸入電子郵件" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">生日:</label>
                <div class="col-sm-6">
                    <select name="year" class="selectpicker" data-width="fit">
                        <option>西元年</option>
                        <?php
                        foreach ($years as $value) : ?>
                            <option value="<?= $value ?>">
                                <?= $value ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="month" class="selectpicker" data-width="fit">
                        <option>月</option>
                        <?php
                        foreach ($months as $value) : ?>
                            <option value="<?= $value ?>">
                                <?= $value ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="day" class="selectpicker" data-width="fit">
                        <option>日</option>
                        <?php
                        foreach ($days as $value) : ?>
                            <option value="<?= $value ?>">
                                <?= $value ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="nationality" class="col-sm-2 control-label">國別:</label>
                <div class="col-sm-6">
                    <select class="selectpicker countrypicker" id="nationality" name="nationality" data-default="TW" ></select>
                </div>
            </div>

            <div class="form-group">
                <label for="county" class="col-sm-2 control-label">縣市:</label>
                <div class="col-sm-6">
                    <div id="twzipcode"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="address" class="col-sm-2 control-label">地址:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="address" name="address" placeholder="請輸入詳細地址" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">權限:</label>
                <div class="col-sm-6">
                    <select name="member_type" class="selectpicker">
                        <option value="1">一般會員</option>
                        <option value="2">VIP</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">是否啟用:</label>
                <div class="col-sm-6">
                    <label class="radio-inline">
                        <input type="radio" name="active" value="Y" checked="checked">是
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="active" value="N">否
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit"class="btn btn-default">建立</button>
                </div>
            </div>

        </div>
    </div>
</form>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/twzipcode.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap-select-country/dist/js/bootstrap-select-country.min.js"></script>
<script>
    $(function() {
        if ($('#success_msg').html() != '') {
            $('#success_msg').show().fadeOut(5000);
        }
    });

    $(function() {
        if ($('#error_msg').html() != '') {
            $('#error_msg').show().fadeOut(5000);
        }
    });
</script>
<script>
    $('#twzipcode').twzipcode({
        zipcodeIntoDistrict: true,
        css: ["county form-control", "town form-control"],
        countyName: "county",
        districtName: "town"
    });
</script>

<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>