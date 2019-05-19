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
                <label for="account" class="col-sm-2 control-label">*會員帳號(Email):</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="account" name="account" placeholder="e.g. member@email.com" value="<?= $data['account'] ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">*姓名:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="name" name="name" placeholder="姓名" value="<?= $data['name'] ?>">
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
                        <input type="radio" name="gender" value="F" <?php echo ($data['gender'] == 'F') ? 'checked="checked"' : '' ?>>女
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="gender" value="M" <?php echo ($data['gender'] == 'M') ? 'checked="checked"' : '' ?>>男
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="gender" value="" <?php echo (!in_array($data['gender'], array('F', 'M'))) ? 'checked="checked"' : '' ?>>尚未設定
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">電話:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="請輸入電話" value="<?= $data['phone'] ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">行動電話:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="mobile" placeholder="請輸入行動電話" value="<?= $data['mobile'] ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">生日:</label>
                <div class="col-sm-6">
                    <select name="year" class="selectpicker" data-width="fit">
                        <option>西元年</option>
                        <?php
                        foreach ($years as $value) : ?>
                            <?php if ($year == $value) : ?>
                                <option selected="selected" value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php else : ?>
                                <option value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>

                    <select name="month" class="selectpicker" data-width="fit">>
                        <option>月</option>
                        <?php
                        foreach ($months as $value) : ?>
                            <?php if ($month == $value) : ?>
                                <option selected="selected" value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php else : ?>
                                <option value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>

                    <select name="day" class="selectpicker" data-width="fit">>
                        <option>日</option>
                        <?php
                        foreach ($days as $value) : ?>
                            <?php if ($day == $value) : ?>
                                <option selected="selected" value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php else : ?>
                                <option value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="nationality" class="col-sm-2 control-label">國別:</label>
                <div class="col-sm-6">
                    <select class="selectpicker countrypicker" id="nationality" name="nationality" data-default="TW" value="<?= $data['nationality'] ?>"></select>
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
                    <input type="text" class="form-control" id="address" name="address" placeholder="請輸入詳細地址" value="<?= $data['address'] ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">權限:</label>
                <div class="col-sm-6">
                    <select name="member_type" class="selectpicker">
                        <option value="1" <?php echo ($data['member_type'] == "1") ? 'selected="selected"' : '' ?>>一般會員</option>
                        <option value="2" <?php echo ($data['member_type'] == "2") ? 'selected="selected"' : '' ?>>VIP</option>
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
    $("#twzipcode").twzipcode("set", {
        "county": "<?php echo $data['county']; ?>",
        "district": "<?php echo $data['town']; ?>"
    });
</script>

<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>