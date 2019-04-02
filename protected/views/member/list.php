<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">使用者帳號管理</h3>

        <?php foreach ($session_jsons as $jsons): ?>

            <?php if ($jsons["power_controller"] == 'member/create'): ?>

                <a href="<?php echo Yii::app()->createUrl('member/create'); ?>" class="btn btn-default btn-right">新增使用者帳號</a>
                <p class='btn btn-default btn-right' id='downloadToCa'> 下載至卡機 </p>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">

        <table id="specialcaseTable" width=100% class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">

            <thead>
            <tr role="row">
                <th>帳號</th>
                <th>姓名</th>
                <th>角色</th>
                <th>性別</th>
                <th>電話</th>
                <th>是否停權</th>
                <th>卡號</th>
                <th>註冊時間</th>
                <th>異動時間</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($datas as $value): ?>
                <tr class="gradeC" role="row">
                    <td><?= $value->account ?></td>
                    <td><?= $value->name ?></td>
                    <td>
                        <?php foreach ($groups as $group):?>
                            <?php if($value->user_group == $group->group_number):?>
                                <?= $group->group_name ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php if ($value->sex == 0): ?>
                            <?= '女生' ?>
                        <?php elseif ($value->sex == 1): ?>
                            <?= '男生' ?>
                        <?php elseif ($value->sex == 2): ?>
                            <?= '未設定' ?>
                        <?php endif ?>
                    </td>
                    <td><?= $value->tel_no1 ?></td>
                    <td><?= ($value->status == 0) ? '否' : '是' ?></td>
                    <td><?= $value->card_number ?></td>
                    <td><?= $value->create_date ?></td>
                    <td><?= $value->edit_date ?></td>

                    <td>
                        <?php foreach ($session_jsons as $jsons):?>

                            <?php if ($jsons["power_controller"] == 'member/update'):?>
                                <a class="oprate-right" href="<?php echo Yii::app()->createUrl('member/update') ?>/<?= $value->id ?>"><i class="fa fa-pencil-square-o fa-lg"></i></a>
                            <?php endif; ?>

                            <?php if ($jsons["power_controller"] == 'member/delete'):?>
                                <a class="oprate-right oprate-del" data-mem-id="<?=$value->id?>" data-mem-name="<?=$value->name?>"><i class="fa fa-times fa-lg"></i></a>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
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
    /*$(document).ready(function () {
        $('#newsTable').DataTable({
            "scrollX": true,
            "overflow": true,
            "lengthChange": false,
            "paging": true,
            "responsive": true,
            "info": false,
            "order": [[7, "desc"]],
            "columnDefs": [{"targets": 4, "orderable": false}],
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何客戶資料"
            }
        });
    });*/
    $(".oprate-del").on('click', function () {
        var id = $(this).data("mem-id");
        var memName = $(this).data("mem-name");
        var answer = confirm("確定要刪除 (" + memName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method', "POST");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('member/delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name', 'id');
            idInput.setAttribute('value', id);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });

    $("#downloadToCa").click(function(){

        var request = $.ajax({
        url: "<?=Yii::app()->createUrl('member/downloadtoca'); ?>",
        method: "POST",
        data: { },
        dataType: "json"
        });
 
        request.done(function( msg ) {
          if( msg == true){
            alert('已執行下載程序');
          }
        });
 
        request.fail(function( jqXHR, textStatus ) {

        });
    })
</script>