<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">角色權限列表</h3>
        <a href="<?php echo Yii::app()->createUrl('group/create'); ?>" class="btn btn-default btn-right">新增角色</a>
    </div>
</div>

<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']);?>
<div class="panel panel-default" style="width=100%; overflow-y:scroll;"> 
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>角色權限名稱</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <?php if($groups !== null && !empty($groups)): ?>
                    <?php foreach ($groups as $group): ?>
                        <tr>
                            <td><?= $group->group_name ?></td>
                            <td>
                                <?php foreach ($session_jsons as $jsons):?>

                                    <?php if ($jsons["power_controller"] == 'group/update'):?>
                                        <a class="oprate-right" href="<?php echo Yii::app()->createUrl('group/update') ?>/<?= $group->id ?>"><i class="fa fa-pencil-square-o fa-lg"></i></a>
                                    <?php endif; ?>

                                    <?php if ($jsons["power_controller"] == 'group/delete'):?>
                                        <a class="oprate-right oprate-del" data-grp-id="<?=$group->id?>" data-grp-name="<?=$group->group_name?>"><i class="fa fa-times fa-lg"></i></a>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
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
    
    $(document).ready(function(){
        var groupTable = $('#groupTable').DataTable({
            "lengthChange": false,
            "paging": true,
            "responsive": true,
            "info": false,
            "order": [[ 1, "asc" ]],
            "columnDefs": [ { "targets": 2, "orderable": false } ],
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁","sNext": "下一頁","sLast": "最後一頁"},
                "sEmptyTable": "無任何群組資料"
            }
        });
    });

    $(".oprate-del").on('click', function(){
        var grpId = $(this).data("grp-id");
        var grpName = $(this).data("grp-name");
        var answer = confirm("確定要刪除 (" + grpName + ") ?");

        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method',"post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('group/delete') ?>/" + grpId);
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


