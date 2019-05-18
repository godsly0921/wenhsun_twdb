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


<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_content">
                <section class="content invoice">
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12 invoice-header">
                            <h1><i class="fa fa-globe"></i> 訂購資訊<small class="pull-right">訂購日期: <?= $order_info['order_data']['order_datetime'] ?></small></h1>
                        </div>
                    <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            From
                            <address>
                                <strong><?= $company_info['name'] ?></strong>
                                <br><?= $company_info['address'] ?>
                                <br>電話: <?= $company_info['phone'] ?>
                                <br>Email: <?= $company_info['email'] ?>
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            To
                            <address>
                                <strong><?= $order_info['member_address_book']['name'] ?></strong>
                                <br><?= $order_info['member_address_book']['address'] ?>
                                <br>電話: <?= $order_info['member_address_book']['mobile'] ?>
                                <br>Email: <?= $order_info['member_address_book']['email'] ?>
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <b>公司統編 <?= $order_info['member_address_book']['invoice_number'] ?></b>
                            <br>
                            <br>
                            <b>訂單編號:</b> <?= $order_info['order_data']['order_id'] ?>
                            <br>
                            <b>訂購日期:</b><?= $order_info['order_data']['order_datetime'] ?>
                        </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table">
                            <p class="lead">訂購項目:</p>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>產品編號</th>
                                        <th>產品名稱</th>
                                        <th>產品訂價</th>
                                        <th>產品折扣</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($order_info['order_data']['order_category'] != 3){ ?>
                                    <tr>
                                        <td><?=$order_info['order_item']['product_id']?></td>
                                        <td><?=$order_info['order_item']['product_name']?></td>
                                        <td><?=$order_info['order_item']['cost_total']?></td>
                                        <td><?=$order_info['order_item']['discount']?></td>
                                    </tr>
                                    <?php }else{?>
                                        <tr>
                                            <td><?=$order_info['order_item']['single_id']?></td>
                                            <td><?=$order_info['order_item']['size_type']?></td>
                                            <td><?=$order_info['order_item']['cost_total']?></td>
                                            <td><?=$order_info['order_item']['discount']?></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table">
                            <p class="lead">使用記錄:</p>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>圖片編號</th>
                                        <th>尺寸類型</th>
                                        <th>授權書</th>
                                        <th>Description</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order_info['img_download']as $key => $value) {?>
                                    <tr>
                                        <td><?=$value['single_id']?></td>
                                        <td><?=$value['size_type']?></td>
                                        <td><button class="btn btn-primary"><i class="fa fa-download"></i></button></td>
                                        <td><?=$value['description']?></td>
                                        <td><?=$value['cost']?></td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-xs-6">
                            <p class="lead">Payment Methods:</p>
                            <div class="radio">
                                <?php foreach ($order_info['pay_type'] as $key => $value) {?>
                                    <label>
                                        <input type="radio" class=" pay_type" <?=$order_info['order_data']['pay_type'] ==$key ?'checked':''?>  name="pay_type" value="<?=$key?>"> <?=$value?>
                                    </label>
                                <?php }?>
                            </div>
                            <?php foreach ($order_info['order_message_data'] as $key => $value) {?>
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                <?=$value?>
                                </p>
                            <?php }?>
                            <textarea id="order_message" class="form-control"></textarea>
                            <button class="btn btn-primary pull-left" id="reply_order_message"  style="margin-top: 25px;">回覆</button>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-6">
                            <p class="lead">Amount Due <?= $order_info['order_data']['order_datetime'] ?></p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td><?= $order_info['order_data']['no_tax_cost_total'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tax (0.05%)</th>
                                            <td><?= $order_info['order_data']['tax'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td><?= $order_info['order_data']['cost_total'] ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-xs-12" style="text-align: center">
                            <button class="btn btn-primary"><i class="fa fa-download"></i> Generate PDF</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#reply_order_message").click(function(){
            if($('#order_message').val()!=''){
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('order/reply_order_message'); ?>',
                    type: 'POST',
                    data: {
                      order_message: $('#order_message').val(),
                      order_id: '<?=$order_info['order_data']['order_id']?>'
                    },
                    error: function(xhr) {
                      alert('Ajax request 發生錯誤');
                    },
                    success: function(response) {
                        window.location.reload();
                    }
                });
            }           
        });
    })
</script>