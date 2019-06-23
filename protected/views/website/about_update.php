<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">關於我們內容更新</h3>
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

<?php if(isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
<div class="alert alert-success">
    <?=Yii::app()->session['success_msg'];?>
</div>
<?php endif; ?>

<?php
unset( Yii::app()->session['error_msg'] );
unset( Yii::app()->session['success_msg'] );
?>


<div class="panel panel-default">
    <div class="panel-heading">
        <a href="<?php echo Yii::app()->createUrl('website/about_list'); ?>" class="btn btn-success btn-right">回關於我們列表</a>
    </div>
    <div class="panel-body">
        <form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('website/about_update');?>/<?=$about->id?>" method="post" enctype="multipart/form-data">
            <?php CsrfProtector::genHiddenField(); ?>
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">項目:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="title" name="title"value="<?=$about->title?>" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">項目說明:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="description" name="description" placeholder="請輸入項目說明" value="<?=$about->description?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">圖片:</label>
                <div class="col-sm-5">
                    <?php if ($about->image !== ''):;?>
                        <input class="form-control" id="pic" name="image_old" type="text" value="<?php echo ($about->image !== NULL) ? $about->image : '' ?>" readonly>
                        <br>
                        <input type="file" class="form-control-file" id="image" name="image" value="<?php echo ($about->image !== NULL)?$about->image:'' ?>" onchange="checkImage(this)">
                        <br>
                        <img id="image_pic" src="<?=Yii::app()->createUrl('/') . $about->image?>" width="100%"></td>
                    <?php else:; ?>
                        <input type="file" class="form-control-file" id="image" name="image" value=""onchange="checkImage(this)">
                    <?php endif; ?>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-primary" onclick="removePic($('#image'));">刪除圖片(恢復預設)</button>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">內文:</label>
                <div class="col-sm-5">
                    <textarea id="paragraph" name="paragraph" class="form-control" rows="20"><?php echo $about->paragraph; ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">更新</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    function checkImage(image){
        var file = image.files[0];
        var _URL = window.URL || window.webkitURL;
        var maxwidth = 1170;
        var maxheight = 400;
        img = new Image();
        img.src = _URL.createObjectURL(file);
        img.onload = function() {
           imgwidth = this.width;
           imgheight = this.height;
           if(imgwidth != maxwidth && imgheight != maxheight){
            alert('圖片長寬不符合規定\n圖片尺寸必需是 => ' + maxwidth + ' X ' + maxheight);
            $('#image').val('');
           }
        }
    }

    function removePic(e) {
        $("#pic").val("");
        e.wrap('<form>').closest('form').get(0).reset();
        e.unwrap();
        $("#image_pic").hide();
    }
</script>
