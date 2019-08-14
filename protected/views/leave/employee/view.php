<div role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>申請單檢視</h3>
        </div>
    </div>
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <button class="btn btn-default pull-right" onclick="history.back();">返回</button>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <form data-parsley-validate class="form-horizontal form-label-left">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_name">員工帳號</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="user_name" name="user_name" class="form-control col-md-7 col-xs-12" value="<?= $account ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">員工姓名</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="name" class="form-control col-md-7 col-xs-12" value="<?= $name ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="create_date">申請日期</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="create_date" class="form-control col-md-7 col-xs-12" value="<?= substr($record->create_at, 0, 10) ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="reason">事由</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="reason" name="reason" class="form-control col-md-7 col-xs-12" value="<?= $record->reason ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">類別</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="take" name="take" class="form-control col-md-7 col-xs-12" value="<?= $record->take ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">日期</label>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <input type="text" id="leave_time" name="leave_time" class="form-control col-md-7 col-xs-12" value="<?= $record->day ?>" readonly>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <input type="text" id="time" name="time" class="form-control col-md-7 col-xs-12" value="<?= substr($record->start_time, 11, 5) . ' - ' . substr($record->end_time, 11, 5) ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">時數</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="leave_minutes" name="leave_minutes" class="form-control col-md-7 col-xs-12" value="<?= $record->leave_minutes / 60 ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">工作交辦</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="remark" name="remark" class="form-control col-md-7 col-xs-12" value="<?= $record->remark ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agent">代理人</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="agent" name="agent" class="form-control col-md-7 col-xs-12" value="<?= $record->agent ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="manager">主管</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="manager" name="manager" class="form-control col-md-7 col-xs-12" value="<?= $record->manager ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">狀態</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="manager" name="manager" class="form-control col-md-7 col-xs-12" value="<?php if ($record->status == 0) : ?>未審核<?php elseif ($record->status == 1) : ?>已審核<?php endif; ?>" readonly>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
