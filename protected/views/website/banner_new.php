<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">首頁輪播圖管理</h3>
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
<strong>新增成功!</strong><?=Yii::app()->session['success_msg'];?>
</div>
<?php endif; ?>

<?php
unset( Yii::app()->session['error_msg'] );
unset( Yii::app()->session['success_msg'] );
?>


<div class="panel panel-default">
    <div class="panel-heading">首頁輪播圖管理</div>
    <div class="panel-body">
        <form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('website/banner_new');?>" method="post" enctype="multipart/form-data">
            <?php CsrfProtector::genHiddenField(); ?>
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">超連結:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="link" name="link" placeholder="請輸入超連結" value="">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">圖片標題:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="title" name="title" placeholder="請輸入圖片標題" value="">
                </div>
            </div> 
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">圖片替代文字:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="alt" name="alt" placeholder="請輸入圖片替代文字" value="">
                </div>
            </div>    
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">排序:</label>
                <div class="col-sm-5">
                    <input type="number" class="form-control" name="sort" placeholder="請輸入排序" value="0">
                </div>
            </div>
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">輪播圖片</label>
                <div class="col-sm-5">
                    <input type="file" class="form-control-file" id="image" name="image" placeholder="請上傳輪播圖片" value="" required onchange="checkImage(this)">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">新增</button>
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
</script>