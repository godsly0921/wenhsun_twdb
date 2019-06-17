<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">新增公告</h3>
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

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('news/create');?>" method="post" enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField();?>

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="form-group">
                <label for="new_title" class="col-sm-2 control-label">標題:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="new_title" name="new_title" placeholder="請輸入公告標題" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="new_content" class="col-sm-2 control-label">內容:</label>
                <div class="col-sm-5">
                <textarea class="form-control" rows="3" id="new_content" name="new_content" placeholder="請輸入公告內容" ></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="sort" class="col-sm-2 control-label">排序:</label>
                <div class="col-sm-5">
                    <input type="number"  min="0" max="100" class="form-control" id="sort" name="sort" placeholder="排序0置頂" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="new_image" class="col-sm-2 control-label">檔案(限2M以下):</label>
                <div class="col-sm-5">
                    <input type="file" id="new_image" name="new_image" placeholder="請上傳(pdf、png、xls、xlsx、doc、docx、jpeg)" value="" accept="image/png,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/jpeg,application/vnd.ms-excel">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">是否顯示</label>
                <div class="col-sm-5">
                <label class="radio-inline">
                    <input type="radio" name="new_type" value="0" checked="checked">否
                </label>
                <label class="radio-inline">
                    <input type="radio" name="new_type"  value="1">是
                </label>
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