<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">修改公告</h3>
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
<div id="success_msg">
    <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
        <p class="alert alert-success">
            <?php echo Yii::app()->session['success_msg']; ?>
        </p>
    <?php endif; ?>
</div>

<form class="form-horizontal" action="<?php echo Yii::app()->createUrl('news/update');?>" method="post" enctype="multipart/form-data">

    <?php CsrfProtector::genHiddenField();?>

    <div class="panel panel-default">
        <div class="panel-body">
            <input type="hidden" name="id" value="<?= $news->id ?>">

            <div class="form-group">
                <label for="new_title" class="col-sm-2 control-label">標題:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="new_title" name="new_title" placeholder="請輸入公告標題" value="<?= $news->new_title ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="new_content" class="col-sm-2 control-label">內容:</label>
                <div class="col-sm-5">
                <textarea class="form-control" rows="3" id="new_content" name="new_content" placeholder="請輸入公吿內容" ><?= $news->new_content ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="sort" class="col-sm-2 control-label">排序:</label>
                <div class="col-sm-5">
                    <input type="number"  min="0" max="100" class="form-control" id="sort" name="sort" placeholder="排序0置頂" value="<?= $news->sort ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="new_image" class="col-sm-2 control-label">相關檔案:</label>
                <div class="col-sm-5">
                    <?php if($news->new_image!==""):;?>
                    <input class="form-control" name="new_image_old" type="text" placeholder="<?php echo ($news->new_image!=="")?$news->new_image:'' ?>" disabled="" value="<?php echo ($news->new_image!=="")?$news->new_image:'' ?>" accept="application/pdf">
                    <input type="file" id="new_image" name="new_image" value="<?php echo ($news->new_image!=="")?$news->new_image:'' ?>" accept="application/pdf">
                    <?php else:; ?>
                    <input type="file" id="new_image" name="new_image" value="" accept="application/pdf">
                    <?php endif; ?>
                </div>
            </div>
            <?php if($news->new_image!==""):;?>
                <div class="form-group">
                    <label for="new_image" class="col-sm-2 control-label"></label>
                    <div class="col-sm-5">
                        <!--<img src="<?php echo Yii::app()->request->baseUrl.$news->new_image; ?>"></img>-->
                    </div>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label class="col-sm-2 control-label">是否顯示</label>
                <div class="col-sm-5">
                <label class="radio-inline">
                    <input type="radio" name="new_type" value="0" <?php echo ($news->new_type==0)?'checked="checked"':'' ?>>否
                </label>
                <label class="radio-inline">
                    <input type="radio" name="new_type"  value="1" <?php echo ($news->new_type==1)?'checked="checked"':'' ?>>是
                </label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">修改</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        if ($('#success_msg').html() != '') {
            $('#success_msg').show().fadeOut(2000)
        }
    })
</script>