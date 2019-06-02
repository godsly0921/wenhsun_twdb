<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">寄送公告</h3>
    </div>
</div>

<?php if (isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (Yii::app()->session['error_msg'] as $error): ?>
                <li><?= $error[0] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<div id="success_msg">
    <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
        <p class="alert alert-success">
            <?php echo Yii::app()->session['success_msg']; ?>
        </p>
    <?php endif; ?>
</div>

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('news/sendmail'); ?>" method="post"
      enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <input type="hidden" name="id" value="<?= $news->id ?>">

            <div class="form-group">
                <label for="new_title" class="col-sm-2 control-label">標題:</label>
                <div class="col-sm-5">
                    <input type="text" disabled="disabled" class="form-control" id="new_title" name="new_title"
                           placeholder="請輸入公告標題" value="<?= $news->new_title ?>">
                    <input type="hidden" class="form-control" id="new_title" name="new_title" placeholder="請輸入公告標題"
                           value="<?= $news->new_title ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="new_content" class="col-sm-2 control-label">內容:</label>
                <div class="col-sm-5">
                    <textarea class="form-control" disabled="disabled" rows="3" id="new_content" name="new_content"
                              placeholder="請輸入公吿內容"><?= $news->new_content ?></textarea>
                    <input type="hidden" class="form-control" id="new_content" name="new_content" placeholder="請輸入公吿內容"
                           value="<?= $news->new_content ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="new_image" class="col-sm-2 control-label">相關檔案:</label>
                <div class="col-sm-5">
                    <?php if ($news->new_image !== ""):; ?>
                        <input class="form-control" name="new_image_old" type="text"
                               placeholder="<?php echo ($news->new_image !== "") ? $news->new_image : '' ?>" disabled=""
                               value="<?php echo ($news->new_image !== "") ? $news->new_image : '' ?>"
                               accept="application/pdf">
                        <!--<input type="file" id="new_image" name="new_image" value="<?php /*echo ($news->new_image!=="")?$news->new_image:'' */ ?>" accept="application/pdf">-->
                    <?php else:; ?>
                        <!--<input type="file" id="new_image" name="new_image" value="" accept="application/pdf">-->
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="new_image" class="col-sm-2 control-label">寄送角色:</label>
                <div class="col-sm-5">
                    <select class="form-control" multiple="multiple" name="select_roles[]">
                        <?php if (!empty($roles)): ?>
                            <?php foreach ($roles as $role): ?>
                                <?php if ($role['id'] === $data->role): ?>
                                    <option value="<?= $role['group_number'] ?>"
                                            selected><?= $role['group_name'] ?></option>
                                <?php else: ?>
                                    <option
                                        value="<?= $role['group_number'] ?>"><?= $role['group_name'] ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">無可用角色</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">寄送</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(function () {
        if ($('#success_msg').html() != '') {
            $('#success_msg').show().fadeOut(2000)
        }
    })
</script>