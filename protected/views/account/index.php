<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">帳號列表</h3>
        <a href="<?php echo Yii::app()->createUrl('account/create'); ?>" class="btn btn-default btn-right">新增帳號</a>
    </div>
</div>
<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>帳號</th>
                <th>姓名</th>
                <th>角色</th>
                <th>建立時間</th>
                <th>狀態</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($accounts as $account):?>
                <tr class="gradeC" role="row">
                    <td><?= $account->user_account ?></td>
                    <td><?= $account->account_name ?></td>
                    <td>
                        <?php foreach ($groups as $group):?>
                            <?php if($account->account_group == $group->group_number):?>
                                <?= $group->group_name ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>

                    <td class="sort"><?= $account->make_time ?></td>
                    <td><?php echo ($account->account_type==0) ? "啟用" : "停權" ?></td>
                    <td>
                        <a class="oprate-right" href="<?php echo Yii::app()->createUrl('account/update') ?>/<?= $account->id ?>"><i class="fa fa-pencil-square-o fa-lg"></i></a>
                        <a class="oprate-right oprate-del" data-acc-id="<?=$account->id?>" data-acc-user="<?=$account->user_account?>"><i class="fa fa-times fa-lg"></i></a>
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
    
    $(document).ready(function(){
        var accountTable = $('#accountTable').DataTable({
            "lengthChange": false,
            "paging": true,
            "responsive": true,
            "info": false,
            "order": [[ 3, "desc" ]],
            "columnDefs": [ { "targets": 5, "orderable": false } ],
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁","sNext": "下一頁","sLast": "最後一頁"},
                "sEmptyTable": "無任何帳戶資料"
            }
        });
    });

    $(".oprate-del").on('click', function(){
        var accId = $(this).data("acc-id");
        var accUser = $(this).data("acc-user");
        var answer = confirm("確定要刪除 (" + accUser + ") ?");

        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method',"post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('account/delete'); ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");

            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name' , 'acc_id');
            idInput.setAttribute('value', accId);

            form.appendChild(input);
            form.appendChild(idInput);

            document.body.appendChild(form);

            form.submit();
        }
    });
</script>
