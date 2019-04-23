<div role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>員工列表</h3>
            </div>
            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <a href="/employee/management/new">
                            <button class="btn btn-primary" type="button">新增員工</button>
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
                            <th>帳號</th>
                            <th>姓名</th>
                            <th>分機</th>
                            <th>座位</th>
                            <th>修改時間</th>
                            <th>建立時間</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($list)):?>
                            <?php foreach($list as $data):?>
                                <tr>
                                    <td>
                                        <a href="/employee/management/edit?id=<?=$data->id?>"><?=$data->user_name?></a>
                                    </td>
                                    <td><?=$data->name?></td>
                                    <td><?=$data->ext->ext_number?></td>
                                    <td><?=$data->seat->seat_number?></td>
                                    <td><?=$data->update_at?></td>
                                    <td><?=$data->create_at?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php else:?>
                            <tr><td colspan="6">查無資料, 快去<a href="/employee/management/new">新增資料</a>吧</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>