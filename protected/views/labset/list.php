<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">單位分類及名稱設定</h3>
       <!-- --><?php /*if ($canCreate === true): */?>
            <a href="<?php echo Yii::app()->createUrl('labset/new'); ?>" class="btn btn-success btn-right">新增單位分類</a>
       <!-- --><?php /*endif;*/?>
    </div>
</div>

<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <!--<th>ID</th>-->
                <th>單位名稱</th>
                <th>建立人員</th>
                <th>建立時間</th>
                <th>層別</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($datas as $value): ?>
                <tr class="gradeC" role="row">
                    <!--<td><?=$value->id?></td>-->
                    <td><?=$value->name?></td>
                    <td><?=$value->builder?></td>
                    <td><?=$value->create_date?></td>
                    <td><?=$value->layer?></td>
                    <td>
                        <a class="oprate-right" href="<?php echo Yii::app()->createUrl('labset/update/id/') ?>/<?= $value->id ?>">
                            <i class="fa fa-pencil-square-o fa-lg"></i>
                        </a>
                        <a
                            class="oprate-right oprate-del" data-mem-id="<?= $value->id ?>" data-mem-name="<?= $value->name ?>"><i class="fa fa-times fa-lg"></i>
                        </a>
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
        var id = $(this).data("mem-id");
        var memName = $(this).data("mem-name");
        var answer = confirm("確定要刪除 (" + memName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method',"POST");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('labset/delete') ?>");
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