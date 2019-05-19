<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">訂單列表</h3>
       <!-- --><?php /*if ($canCreate === true): */?>
            <!-- <a href="<?php #echo Yii::app()->createUrl('product/new'); ?>" class="btn btn-success btn-right">新增產品</a> -->
       <!-- --><?php /*endif;*/?>
    </div>
</div>

<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>訂單編號</th>
                <th>訂購產品</th>
                <th>購買金額</th>
                <th>訂購狀態</th>
                <th>建立時間</th>               
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($order_data as $key => $value){ ?>
                <tr class="gradeC" role="row">
                    <td><?=$value['order_id']?></td>
                    <td><?=$value['product_name']?></td>
                    <td><?=$value['cost_total']?></td>
                    <td><?=$value['order_status']?></td>
                    <td><?=$value['creatorder_datetimee_time']?></td>                    
                    <td>
                        <a class="oprate-right" href="<?php echo Yii::app()->createUrl('order/detail/') ?>/<?= $value['order_id'] ?>">
                            <i class="fa fa-pencil-square-o fa-lg"></i>
                        </a>
                        <a
                            class="oprate-right oprate-del" data-mem-id="<?= $value['order_id'] ?>" data-mem-name="<?= $value['order_id'] ?>"><i class="fa fa-times fa-lg"></i>
                        </a>
                    </td>
               </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#specialcaseTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            }
        } );
    } );
</script>
<script>
    $(".oprate-del").on('click', function(){
        var id = $(this).data("mem-id");
        var memName = $(this).data("mem-name");
        var answer = confirm("確定要刪除 (" + memName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method',"POST");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('product/delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name' , 'id');
            idInput.setAttribute('value', id);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>