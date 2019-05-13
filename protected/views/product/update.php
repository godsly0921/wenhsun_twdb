<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">產品管理</h3>
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
    <div class="panel-heading">產品管理</div>
    <div class="panel-body">
        <form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('product/update');?>/<?= $product_data->product_id ?>" method="post">
            <?php CsrfProtector::genHiddenField(); ?>
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">產品名稱:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="請輸入產品名稱" value="<?=$product_data->product_name?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">選擇優惠折扣:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="coupon_id" required>
                        <option value="0" <?=$product_data->coupon_id == 0 ? 'selected':''?>>無</option>
                        <?php foreach ($coupon as $key => $value) { ?>
                            <option value="<?=$value['coupon_id']?>" <?=$product_data->coupon_id == $value['coupon_id'] ? 'selected':''?>><?=$value['coupon_name']?></option>
                        <?php }?>
                    </select>
                </div>
            </div> 
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">點數 ( 自由載請填寫0 ) :</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="pic_point" name="pic_point" placeholder="請輸入點數" value="<?=$product_data->pic_point?>" <?=$product_data->product_type != 1 ? 'readonly':''?> required>
                </div>
            </div>    
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">產品分類:</label>
                <div class="col-sm-5">
                    <div class="radio">
                        <label>
                          <input type="radio" class="flat product_type" <?=$product_data->product_type == 1 ? 'checked':''?> name="product_type" value="1"> 點數
                        </label>
                        <label>
                          <input type="radio" class="flat product_type" <?=$product_data->product_type == 2 ? 'checked':''?> name="product_type" value="2"> 自由載 30 天
                        </label>
                        <label>
                          <input type="radio" class="flat product_type" <?=$product_data->product_type == 3 ? 'checked':''?> name="product_type" value="3"> 自由載 90 天
                        </label>
                        <label>
                          <input type="radio" class="flat product_type" <?=$product_data->product_type == 4 ? 'checked':''?> name="product_type" value="4"> 自由載 360 天
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">張數 ( 點數請填寫0 ) :</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="pic_number" name="pic_number" placeholder="請輸入張數" value="<?=$product_data->pic_number?>" <?=$product_data->product_type == 1 ? 'readonly':''?> required>
                </div>
            </div>
            <div class="form-group">
                <label for="adv_id" class="col-sm-2 control-label">產品售價:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="price" name="price" placeholder="請輸入產品售價" value="<?=$product_data->price?>" required>
                </div>
            </div>         
            <div class="form-group">
                <label class="col-sm-2 control-label">啟用:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="status" required>                      
                        <option value="1" <?=$product_data->status == 1 ? 'selected':''?>>是</option>
                        <option value="0" <?=$product_data->status == 0 ? 'selected':''?>>否</option>
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
<script type="text/javascript">
    $(document).ready(function () {
        $( ".product_type" ).change(function() {
            var product_type = $('.product_type:checked').val();
            if( product_type == 1 ){
                $('#pic_point').attr('readonly', false);
                $('#pic_number').attr('readonly', true);
                $('#pic_number').val(0);
            }else{
                $('#pic_point').attr('readonly', true);
                $('#pic_number').attr('readonly', false);
                $('#pic_point').val(0);
            }
        });
    })
</script>