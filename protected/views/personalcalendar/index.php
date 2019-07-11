<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div id="error_msg">
    <?php if (isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
        <div class="alert alert-danger">
            <ul>
                <li><?= Yii::app()->session['error_msg'] ?></li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<div id="success_msg">
    <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
        <p class="alert alert-success">
            <?php echo Yii::app()->session['success_msg']; ?>
        </p>
    <?php endif; ?>
</div>

<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/css/fullcalendar.min.css" rel='stylesheet'/>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/css/fullcalendar.print.min.css"
      rel='stylesheet' media='print'/>

<!-- Modal -->
<div id="createCal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">建立行事曆</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('personalcalendar/create'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="token" name="_token" value="">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否開放:</label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="public">
                                        <option value="PRIVATE">個人計畫</option>
                                        <option value="OPEN">公開至共用行事曆</option>
                                        <?php if($wenhsunActivity) : ?>
                                        <option value="ADMIN">文訊活動</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">開始日期:</label>
                                <div class="col-sm-3">
                                    <input type="date" id="startDate" class="form-control" name="start_date" value="" readonly>
                                    <input type="hidden" name="employee_id" value="<?= $employee_id ?>">
                                </div>
                                <label class="col-sm-1 control-label">小時:</label>
                                <div class="col-sm-1">
                                    <select class="form-control" name="start_hour">
                                        <?php foreach (Common::hours() as $key => $value) : ?>
                                            <option value="<?= $key; ?>"><?= $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">分鐘:</label>
                                <div class="col-sm-1">
                                    <select class="form-control" name="start_minute">
                                        <?php foreach (Common::minutes() as $key => $value) : ?>
                                            <option value="<?= $key; ?>"><?= $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="adv_id" class="col-sm-2 control-label">結束日期:</label>
                                <div class="col-sm-3">
                                    <input type="date" id="endDate" class="form-control" name="end_date" value="" readonly>
                                </div>
                                <label class="col-sm-1 control-label">小時:</label>
                                <div class="col-sm-1">
                                    <select class="form-control" name="end_hour">
                                        <?php foreach (Common::hours() as $key => $value) : ?>
                                            <option value="<?= $key; ?>"><?= $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">分鐘:</label>
                                <div class="col-sm-1">
                                    <select class="form-control" name="end_minute">
                                        <?php foreach (Common::minutes() as $key => $value) : ?>
                                            <option value="<?= $key; ?>"><?= $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">計畫內容:</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="" name="content" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">送出</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div id="showEvent" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="eventTime" class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p id="content"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a href="" id="delete" class="btn btn-danger" role="button">Delete</a>
      </div>
    </div>

  </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/lib/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/fullcalendar.min.js"></script>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/gcal.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/gcal.min.js"></script>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/moment.min.js"></script>

<script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            buttonText: {
                today: '今天',
                month: '月',
                week: '週',
                day: '日',
                list: '列表',
            },
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
                //right: 'month,agendaDay,listWeek'
            },
            handleWindowResize:true,
            defaultDate: '<?php echo date('Y-m-d');?>',
            navLinks: true, // can click day/week names to navigate views
            weekNumbers: true,
            weekends: true,
            weekNumbersWithinDays: true,
            weekNumberCalculation: 'ISO',
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            timeFormat: 'HH:mm',
            axisFormat: 'HH:mm',
            displayEventEnd: true,
            events: {
                url: '<?php echo Yii::app()->createUrl('personalcalendar/getevents',['employee_id' => $employee_id, 'public' => $public]);?>',
                error: function() {
                    $('#script-warning').show();
                }
            },
            eventClick: function(event) {
                if (event.title != '可計畫日期') {
                    showEvent(event);
                }
            },

            loading: function(bool) {
                $('#loading').toggle(bool);
            }
        });
    });

</script>

<style>
    #script-warning {
        display: none;
        background: #eee;
        border-bottom: 1px solid #ddd;
        padding: 0 10px;
        line-height: 40px;
        text-align: center;
        font-weight: bold;
        font-size: 12px;
        color: red;
    }

    #loading {
        display: none;
        position: absolute;
        top: 10px;
        right: 10px;
    }

    a.active {
        color: #090b0e
    }
</style>


<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">個人行事曆</h3>
        <h4 class="text-right">
<a href="<?= Yii::app()->createUrl('personalcalendar/index'); ?>" <?php if ($public == 'N') : ?>class="active"<?php endif; ?>>私人</a> |
            <a href="<?= Yii::app()->createUrl('personalcalendar/index', ['public' => 'Y']); ?>" <?php if ($public == 'Y') : ?>class="active"<?php endif; ?>>公開</a>
        </h4>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">

        <div id='script-warning'>
            <code>沒有個人行事曆API權限</code> must be running.
        </div>

        <div id='loading'>loading...

        </div>

        <div id='calendar'>

        </div>

    </div>
</div>

<script>
    $(function () {
        if ($('#success_msg').html() != '') {
            $('#success_msg').show().fadeOut(2000)
        }
    })

    $(function () {
        if ($('#error_msg').html() != '') {
            $('#error_msg').show().fadeOut(2000)
        }
    })
</script>
<script>
    function createCalendar(start, end) {
        var csrfToken = "<?=CsrfProtector::genUserToken()?>";
        $('#token').val(csrfToken);
        $('#startDate').val(start);
        $('#endDate').val(end);
        $('#createCal').modal('show');
    }

    function showEvent(event) {
        $('#eventTime').text(moment(event.start).format('YYYY-MM-DD HH:mm') + '-' + moment(event.end).format('HH:mm'));
        $('#content').text(event.title);
        $('#delete').attr('href', event.delete);
        $('#showEvent').modal('show');
    }
</script>

<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>