<div role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3><?= $employeeName ?>(<?= $employeeUserName ?>) - 員工休假紀錄(<?= $year ?>年)</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <p>注意: 年假計算為當年度可請時數</p>
                    <table id="table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>假別</th>
                                <th>已請時數(小時)</th>
                                <th>可請時數(小時)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sum as $row) : ?>
                                <tr role="row">
                                    <td><?= $row['category'] ?></td>
                                    <td><?= $row['leave_applied'] ?></td>
                                    <td><?= $row['leave_available'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <br>
        <div class="tab">
            <button type="button" class="btn btn-default" onClick="tab('holiday');">請假記錄</button>
            <button type="button" class="btn btn-default" onClick="tab('overtime');">加班記錄</button>
        </div>
        <br>

        <div id="holiday" class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <table id="datatable1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>請假日期</th>
                                <th>假別</th>
                                <th>事由</th>
                                <th>申請日期</th>
                                <th>請假時間</th>
                                <th>申請時數(小時)</th>
                                <th>審核狀態</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($holidayList as $row) : ?>
                                <tr role="row" id="<?= $row['id'] ?>">
                                    <td><?= date('Y-m-d', strtotime($row['leave_time'])) ?></td>
                                    
                                    <td><?= $row['take'] ?></td>
                                    <td><?= $row['reason'] ?></td>
                                    <td><?= substr($row['create_at'], 0, 10) ?></td>
                                    <td><?= substr($row['start_time'], 11, 8) . ' - ' . substr($row['end_time'], 11, 8) ?></td>
                                    <td><?= $row['leave_minutes'] / 60 ?></td>
                                    <td>
                                        <?php if ($row['status'] == 0) : ?>
                                            未審核
                                        <?php elseif ($row['status'] == 1) : ?>
                                            已審核
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == 0){ ?>
                                            <button type="button" class="btn btn-link" onclick="approve('<?=$row['id']?>')" style="margin:0;padding:0">
                                                <i class="fa fa-check-square-o" style="font-size:18px"></i>
                                                核準
                                            </button>
                                            <br/>
                                        <?php }?>
                                        <a href="<?= Yii::app()->createUrl('/leave/manager/edit?id=' . $row['id']); ?>"><i class="fa fa-edit" style="font-size:18px"></i>
                                        </a>
                                        &nbsp;
                                        <a href="<?= Yii::app()->createUrl('leave/employee/view?id=' . $row['id']) ?>">
                                            <i class="fa fa-newspaper-o" style="font-size:18px"></i>
                                        </a>
                                        &nbsp;
                                        <a href="javascript: void(0);" onclick="del(<?= $row['id'] ?>);">
                                            <i class="fa fa-times" style="font-size:18px"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="overtime" class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <table id="datatable2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>申請日期</th>
                                <th>項目</th>
                                <th>事由</th>
                                <th>加班日期</th>
                                <th>加班時間</th>
                                <th>申請時數(小時)</th>
                                <th>審核狀態</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($overtimeList as $row) : ?>
                                <tr role="row" id="<?= $row['id'] ?>">
                                    <td><?= substr($row['create_at'], 0, 10) ?></td>
                                    <td><?= $row['take'] ?></td>
                                    <td><?= $row['reason'] ?></td>
                                    <td><?= date('Y-m-d', strtotime($row['leave_time'])) ?></td>
                                    <td><?= substr($row['start_time'], 11, 8) . ' - ' . substr($row['end_time'], 11, 8) ?></td>
                                    <td><?= $row['leave_minutes'] / 60 ?></td>
                                    <td>
                                        <?php if ($row['status'] == 0) : ?>
                                            未審核
                                        <?php elseif ($row['status'] == 1) : ?>
                                            已審核
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= Yii::app()->createUrl('/leave/manager/edit?id=' . $row['id']); ?>">
                                            <i class="fa fa-edit" style="font-size:18px"></i>
                                        </a>
                                        &nbsp;
                                        <a href="<?= Yii::app()->createUrl('leave/employee/view?id=' . $row['id']) ?>">
                                            <i class="fa fa-newspaper-o" style="font-size:18px"></i>
                                        </a>
                                        &nbsp;
                                        <a href="javascript: return false;" onclick="del(<?= $row['id'] ?>);">
                                            <i class="fa fa-times" style="font-size:18px"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    function tab(tab) {
        if (tab === "holiday") {
            $("#holiday").show();
            $("#overtime").hide();
            $("#datatable1").dataTable().fnDestroy();
            $("#datatable1").DataTable({
                "scrollX": true,
                "lengthChange": false,
                "oLanguage": {
                    "oPaginate": {
                        "sFirst": "第一頁",
                        "sPrevious": "上一頁",
                        "sNext": "下一頁",
                        "sLast": "最後一頁"
                    },
                    "sEmptyTable": "無任何請假資料"
                },
                "order": [
                    [0, 'desc']
                ]
            });
        } else if (tab === "overtime") {
            $("#holiday").hide();
            $("#overtime").show();
            $("#datatable2").dataTable().fnDestroy();
            $("#datatable2").DataTable({
                "scrollX": true,
                "lengthChange": false,
                "oLanguage": {
                    "oPaginate": {
                        "sFirst": "第一頁",
                        "sPrevious": "上一頁",
                        "sNext": "下一頁",
                        "sLast": "最後一頁"
                    },
                    "sEmptyTable": "無任何請假資料"
                },
                "order": [
                    [0, 'desc']
                ]
            });
        }
    }

    function del($id) {
        var result =confirm("你確定要刪除嗎");
        if (result==true){
            $.ajax({
                url: "<?= Yii::app()->createUrl('/leave/manager/delete') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    id: $id
                },
                success: function(response) {
                    if (response) {
                        alert("刪除成功");
                        $("#" + $id).remove();
                    } else {
                        alert("刪除失敗");
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("刪除失敗");
                }
            });
        }
        else {
            return;
        }
    }

    function approve(id) {
        var values = [];
        values.push(id);
        $.ajax({
            url: "<?= Yii::app()->createUrl('/leave/manager/AjaxUpdate') ?>",
            type: "POST",
            dataType: "json",
            data: {
                id: id
            },
            success: function(response) {
                if (response.status) {
                    alert(response.msg);
                    window.location.reload();
                } else {
                    alert(response.msg);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert(response.msg);
            }
        });
    }
    
    $(document).ready(function() {
        $("#datatable1").DataTable({
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {
                    "sFirst": "第一頁",
                    "sPrevious": "上一頁",
                    "sNext": "下一頁",
                    "sLast": "最後一頁"
                },
                "sEmptyTable": "無任何請假資料"
            },
            "order": [
                [0, 'desc']
            ]
        });

        $("#datatable2").DataTable({
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {
                    "sFirst": "第一頁",
                    "sPrevious": "上一頁",
                    "sNext": "下一頁",
                    "sLast": "最後一頁"
                },
                "sEmptyTable": "無任何加班資料"
            },
            "order": [
                [0, 'desc']
            ]
        });

        $("#overtime").hide();
    });
</script>