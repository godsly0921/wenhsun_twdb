<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">儀器資料設定</h3>

        <?php foreach ($session_jsons as $jsons): ?>
            <?php if ($jsons["power_controller"] == 'device/new'): ?>
                <a href="<?php echo Yii::app()->createUrl('device/new'); ?>" class="btn btn-success btn-right">新增儀器</a>
            <?php endif; ?>

            <?php if ($jsons["power_controller"] == 'device/positionnew'): ?>
                <a href="<?php echo Yii::app()->createUrl('device/positionnew'); ?>" class="btn btn-success btn-right">新增放置地點</a>
            <?php endif; ?>
        <?php endforeach; ?>

    </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <!--<th>項目編號</th>-->
                <th>儀器編號</th>
                <th>儀器名稱</th>
                <th>放置地點</th>
                <th>目前狀態</th>
                <th>建立時間</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($datas as $value): ?>
                <tr class="gradeC" role="row">
                    <!--<td><?=$value['id']?></td>-->
                    <td><?=$value['codenum']?></td>
                    <td><?=$value['name']?></td>
                    <td><?=$value['pname']?></td>
                    <td><?php foreach ($device_status as $v):?>
                            <?php if($value['status'] == $v->id):?>
                                <?= $v->name ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>
                    <td><?=$value['create_date']?></td>

                    <td>
                        <?php foreach ($session_jsons as $jsons):?>

                            <?php if ($jsons["power_controller"] == 'device/update'):?>
                                <a class="oprate-right" href="<?php echo Yii::app()->createUrl('device/update/id/') ?>/<?= $value['id'] ?>"><i class="fa fa-pencil-square-o fa-lg"></i></a>
                            <?php endif; ?>

                            <?php if ($jsons["power_controller"] == 'device/delete'):?>
                                <a class="oprate-right oprate-del" data-device-id="<?=$value['id']?>" data-device-name="<?=$value['name']?>"><i class="fa fa-times fa-lg"></i></a>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </td>

               </tr>
                <?php endforeach; ?>
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
    /*$(document).ready(function() {
        $('#newsTable').DataTable({
            "lengthChange": false,
            "paging": true,
            "responsive": true,
            "info": false,
            "order": [[ 1, "desc" ]],
            //"columnDefs": [ { "targets": 0, "orderable": false } ],
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁","sNext": "下一頁","sLast": "最後一頁"},
                "sEmptyTable": "無任何資料"
            }
        });
    });*/
    $(".oprate-del").on('click', function(){
        var id = $(this).data("device-id");
        var deviceName = $(this).data("device-name");
        var answer = confirm("確定要刪除 (" + deviceName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method',"POST");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('device/delete') ?>");
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