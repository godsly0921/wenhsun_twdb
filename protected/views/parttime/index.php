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
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/lib/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/fullcalendar.min.js"></script>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/gcal.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/gcal.min.js"></script>

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
               // right: 'month,agendaWeek,agendaDay,listWeek'
                right: 'month,agendaDay,listWeek'
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
                url: '<?php echo Yii::app()->createUrl('parttime/getevents',['part_time_empolyee_id'=>$part_time_empolyee_id]);?>',
                error: function() {
                    $('#script-warning').show();
                }
            },
            eventClick: function(event) {
                if (event.title.substr(0,4) === '儀器關閉') {
                    alert(event.title);
                } else if (event.title !== '開放排班' && event.title.substr(0,4) !== '儀器關閉') {
                    var answer = confirm("確定刪除排班?");
                    if (!answer) {
                        return false;
                    }
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

</style>


<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">工讀生排班表</h3>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">

        <div id='script-warning'>
            <code>getevents.php</code> must be running.
        </div>

        <div id='loading'>loading...

        </div>

        <div id='calendar'>

        </div>

    </div>
</div>


<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script
    src="https://blackrockdigital.github.io/startbootstrap-sb-admin-2/vendor/datatables-responsive/dataTables.responsive.js"></script>
<script>
    $(document).ready(function () {
        $('#parttimeTable').DataTable({
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            }
        });
    });
    $(".oprate-del").on('click', function () {
        var parttimeId = $(this).data("parttime-id");
        var parttimeName = $(this).data("parttime-name");
        var answer = confirm("確定要刪除 (" + parttimeName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method', "post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('parttime/delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name', 'id');
            idInput.setAttribute('value', parttimeId);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });</script>
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

<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>