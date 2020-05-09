<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">API 管理列表</h3>
        <a href="<?php echo Yii::app()->createUrl('apimanage/apimanage_create'); ?>" class="btn btn-default btn-right">新增 API</a>
    </div>
</div>
<?php if(isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
    <div class="alert alert-danger">
        <?=Yii::app()->session['error_msg']?>
    </div>
<?php endif; ?>
<?php if(isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
    <div class="alert alert-success">
        <strong><?=Yii::app()->session['success_msg'];?></strong>
    </div>
<?php endif; ?>
<?php
unset( Yii::app()->session['error_msg'] );
unset( Yii::app()->session['success_msg'] );
?>
<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']);?>
<div class="panel panel-default" style="width=100%; overflow-y:scroll;"> 
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>API KEY</th>
                <th>API 密碼</th>
                <th>建立時間</th>
                <th>狀態</th>
                <th>備註</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($data)){?>
                <?php foreach ($data as $key => $value) {?>
                    <tr>
                        <td><?=$value['api_key']?></td>
                        <td><?=$value['api_password']?></td>
                        <td><?=$value['createtime']?></td>
                        <td><?=$value['status']==0?"停用":"啟用"?></td>
                        <td><?=$value['remark']?></td>
                        <td>
                            <?php foreach ($session_jsons as $jsons) : ?>
                                <?php if ($jsons["power_controller"] == 'apimanage/apimanage_update') : ?>
                                <a class="oprate-right" href="<?php echo Yii::app()->createUrl('apimanage/apimanage_update/') ?>/<?= $value['id'] ?>">
                                    <i class="fa fa-pencil-square-o fa-lg" style="cursor: pointer"><span style="font-size:15px;">修改</span></i>
                                </a>
                                <?php endif; ?>
                                <?php if ($jsons["power_controller"] == 'apimanage/apimanage_delete') : ?>
                                <a class="oprate-right oprate-del" data-mem-id="<?= $value['id'] ?>" data-api_key="<?= $value['api_key'] ?>">
                                    <i class="fa fa-times fa-lg" style="cursor: pointer"><span style="font-size:15px;">刪除</span></i>
                                </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php }?> 
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $(function() {
        if ($('#hide_message').html() != '') {
            $('#hide_message').show().fadeOut(3500)
        }
    })
</script>
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

    $(".oprate-del").on('click', function(){
        var id = $(this).data("mem-id");
        var apikey = $(this).data("api_key");
        var answer = confirm("確定要刪除 (" + apikey + ") ?");

        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method',"post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('apimanage/apimanage_delete') ?>/" + id);
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");

            form.appendChild(input);

            document.body.appendChild(form);

            form.submit();
        }
    });
</script>


