<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">新增最新消息</h3>
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
        <a href="<?php echo Yii::app()->createUrl('website/activity_news_list'); ?>" class="btn btn-success btn-right">回最新消息列表</a>
    </div>
    <div class="panel-body">
        <form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('website/activity_news_update');?>" method="post" enctype="multipart/form-data">
            <?php CsrfProtector::genHiddenField(); ?>
            <input type="hidden" name="id" value="<?=$news->id?>">
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">標題:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="title" name="title" value="<?=$news->title?>" placeholder="請輸入標題">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">副標:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="second_title" name="second_title" placeholder="請輸入副標" value="<?=$news->second_title?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">內文1:</label>
                <div class="col-sm-8">
                    <textarea id="content" name="content" class="form-control" rows="10"><?php echo $news->content; ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">主要內文:</label>
                <div class="col-sm-8">
                    <textarea id="main_content" name="main_content" class="form-control" rows="20"><?php echo $news->main_content; ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">圖片:</label>
                <div class="col-sm-8">
                    <?php if ($news->image !== ''):;?>
                        <input class="form-control" id="pic" name="image_old" type="text" value="<?php echo ($news->image !== NULL) ? $news->image : '' ?>" readonly>
                        <br>
                        <input type="file" class="form-control-file" id="image" name="image" value="<?php echo ($news->image !== NULL) ? $news->image:'' ?>" onchange="checkImage(this)">
                        <br>
                        <img id="image_pic" src="<?=Yii::app()->createUrl('/') . $news->image?>" width="100%"></td>
                    <?php else:; ?>
                        <input type="file" class="form-control-file" id="image" name="image" value=""onchange="checkImage(this)">
                    <?php endif; ?>
                </div>
                <div class="col-sm-4"><span style="color:red;">975*500</span></div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">上下架:</label>
                <div class="col-sm-8">
                    <select name="active" class="form-control">
                        <option value="T" <?php if ($news->active == 'T') :?> selected <?php endif; ?>>上架</option>
                        <option value="F" <?php if ($news->active == 'F') :?> selected <?php endif; ?>>下架</option>
                    </select>
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
<script src="<?php echo Yii::app()->request->baseUrl.'/assets/ckeditor/all/ckeditor.js'; ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        CKEDITOR.replace("main_content",{
            filebrowserBrowseUrl : "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/ckfinder.html';?>",
            filebrowserImageBrowseUrl : "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/ckfinder.html?type=Images';?>",
            filebrowserFlashBrowseUrl:"<?= Yii::app()->request->baseUrl.'/assets/ckfinder/ckfinder.html?Type=Flash';?>",
            filebrowserUploadUrl: "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';?>",
            filebrowserImageUploadUrl: "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';?>",
            filebrowserFlashUploadUrl: "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';?>",
            height: 450
        });

        CKEDITOR.replace("content",{
            filebrowserBrowseUrl : "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/ckfinder.html';?>",
            filebrowserImageBrowseUrl : "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/ckfinder.html?type=Images';?>",
            filebrowserFlashBrowseUrl:"<?= Yii::app()->request->baseUrl.'/assets/ckfinder/ckfinder.html?Type=Flash';?>",
            filebrowserUploadUrl: "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';?>",
            filebrowserImageUploadUrl: "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';?>",
            filebrowserFlashUploadUrl: "<?= Yii::app()->request->baseUrl.'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';?>",
            height: 200
        });
    })
    function checkImage(image){
        var file = image.files[0];
        var _URL = window.URL || window.webkitURL;
        var maxwidth = 975;
        var maxheight = 500;
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
</script>
