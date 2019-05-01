<div role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>員工座位</h3>
            </div>
            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <a href="<?= Yii::app()->createUrl('/employee/seats/new');?>">
                            <button class="btn btn-primary" type="button">新增座位</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>座位號碼</th>
                            <th>座位名稱</th>
                            <th>建立時間</th>
                            <th>更新時間</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($seats)):?>
                            <?php foreach($seats as $seat):?>
                            <tr>
                                <td>
                                    <a href="<?= Yii::app()->createUrl('/employee/seats/edit?id='.$seat->id);?>"><?=$seat->seat_number?></a>
                                </td>
                                <td><?=$seat->seat_name?></td>
                                <td><?=$seat->create_at?></td>
                                <td><?=$seat->update_at?></td>
                            </tr>
                            <?php endforeach;?>
                        <?php else:?>
                        <tr><td colspan="4">查無資料, 快去<a href="<?= Yii::app()->createUrl('/employee/seats/new');?>">新增資料</a>吧</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>