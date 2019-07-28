<div role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3>休假檢視(<?=$year?>)</h3>
            </div>
            <form id="form" method="get" action="<?php echo Yii::app()->createUrl('/leave/employee/index'); ?>">
                <div class="title_right">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-2">
                        <a href="<?php echo Yii::app()->createUrl('/leave/manager/new'); ?>" class="btn btn-primary">新增假單</a>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <input type="text" class="form-control" id="year" name="year">
                            <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">查詢年份</button>
                        </span>
                        </div>

                    </div>
                </div>
            </form>
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
                        <?php foreach($sum as $row):?>
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

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>假別</th>
                            <th>申請日期</th>
                            <th>申請時數(小時)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($list as $row):?>
                            <tr role="row">
                                <td><?=$row['take']?></td>
                                <td><?= $row['leave_time']?></td>
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

