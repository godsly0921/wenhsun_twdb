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
                    <p>注意: 年假計算為到職至今可請時數</p>
                    <table id="datatable" class="table table-striped table-bordered">
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
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>申請日期</th>
                                <th>假別</th>
                                <th>事由</th>
                                <th>請假日期</th>
                                <th>請假時間</th>
                                <th>申請時數(小時)</th>
                                <th>審核狀態</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($holidayList as $row) : ?>
                                <tr role="row">
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
                                    <td><a href="<?= Yii::app()->createUrl('/leave/manager/edit?id=' . $row['id']); ?>"><i class="fa fa-edit" style="font-size:18px"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="overtime" class="row" style="display:none">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <table id="datatable" class="table table-striped table-bordered">
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
                                <tr role="row">
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
                                    <td><a href="<?= Yii::app()->createUrl('/leave/manager/edit?id=' . $row['id']); ?>"><i class="fa fa-edit" style="font-size:18px"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function tab(tab) {
        if (tab === "holiday") {
            $("#holiday").show();
            $("#overtime").hide();
        } else if (tab === "overtime") {
            $("#holiday").hide();
            $("#overtime").show();
        }
    }
</script>