<div role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3><?=$employeeName?>(<?=$employeeId?>) - 員工休假歷程(<?=$year?>年)</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>假別</th>
                            <th>申請時間(小時)</th>
                            <th>申請時數(小時)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($list as $row):?>
                            <tr role="row">
                                <td><?=$row['take']?></td>
                                <td><?=$row['leave_time']?></td>
                                <td><?=$row['leave_minutes'] / 60?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

