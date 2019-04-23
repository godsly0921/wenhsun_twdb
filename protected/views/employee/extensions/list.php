<div role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>員工分機</h3>
            </div>
            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <a href="/employee/extensions/new">
                            <button class="btn btn-primary" type="button">新增分機號碼</button>
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
                            <th>分機號碼</th>
                            <th>建立時間</th>
                            <th>修改時間</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($exts)):?>
                            <?php foreach($exts as $ext):?>
                            <tr>
                                <td>
                                    <a href="/employee/extensions/edit?id=<?=$ext->id?>"><?=$ext->ext_number?></a>
                                </td>
                                <td><?=$ext->create_at?></td>
                                <td><?=$ext->update_at?></td>
                            </tr>
                            <?php endforeach;?>
                        <?php else:?>
                        <tr><td colspan="3">查無資料, 快去<a href="/employee/extensions/new">新增資料</a>吧</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>