<div role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3>員工休假歷程</h3>
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
                                <td><?=$row['category']?></td>
                                <td><?=$row['leave_applied']?></td>
                                <td><?=$row['leave_available']?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

