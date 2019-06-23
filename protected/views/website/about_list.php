<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">關於我們內容管理</h3>
    </div>
</div>
<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
    <div class="panel-body">
        <table id="table" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
                <tr role="row">
                    <th>編號</th>
                    <th>項目</th>
                    <th>說明</th>
                    <th>圖片</th>
                    <th>內文</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($about as $key => $value) { ?>
                    <tr class="gradeC" role="row">
                        <td><?= $value['id'] ?></td>
                        <td><?= $value['title'] ?></td>
                        <td><?= $value['description'] ?></td>
                        <td>
                            <?php if ($value['image'] != '') : ?>
                                <img src="<?= Yii::app()->createUrl('/') . $value['image'] ?>" width="100%"></td>
                        <?php endif; ?>
                        <td><?= $value['paragraph'] ?></td>
                        <td>
                            <a class="oprate-right" href="<?php echo Yii::app()->createUrl('website/about_update/') ?>/<?= $value['id'] ?>">
                                <i class="fa fa-pencil-square-o fa-lg"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table').DataTable({
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {
                    "sFirst": "第一頁",
                    "sPrevious": "上一頁",
                    "sNext": "下一頁",
                    "sLast": "最後一頁"
                },
                "sEmptyTable": "無任何資料"
            }
        });
    });
</script>
